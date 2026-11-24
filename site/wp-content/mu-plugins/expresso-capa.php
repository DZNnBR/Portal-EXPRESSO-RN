<?php
/** Plugin Name: Portal Expresso RN — foto de capa */
defined( 'ABSPATH' ) || exit;

function expresso_tem_foto( $id ) {
	return $id > 0 && wp_attachment_is_image( $id ) && wp_get_attachment_image_src( $id, 'full' );
}

function expresso_exige_foto( $status ) {
	return in_array( $status, array( 'publish', 'future', 'private' ), true );
}

add_filter( 'rest_pre_insert_post', function ( $post, $request ) {
	if ( is_wp_error( $post ) ) {
		return $post;
	}
	$id = (int) $request['id'];
	$status = $post->post_status ?? ( $id ? get_post_status( $id ) : 'draft' );
	if ( expresso_exige_foto( $status ) ) {
		$foto = (int) ( $request['featured_media'] ?? get_post_thumbnail_id( $id ) );
		if ( ! expresso_tem_foto( $foto ) ) {
			return new WP_Error( 'expresso_sem_foto', 'Escolha uma foto em Imagem destacada para publicar ou agendar a matéria.', array( 'status' => 400 ) );
		}
		$post->meta_input['_thumbnail_id'] = $foto;
	}
	return $post;
}, 20, 2 );

add_filter( 'wp_insert_post_data', function ( $data, $args ) {
	if ( 'post' === $data['post_type'] && expresso_exige_foto( $data['post_status'] ) ) {
		$foto = $args['meta_input']['_thumbnail_id'] ?? $args['_thumbnail_id'] ?? get_post_thumbnail_id( $args['ID'] ?? 0 );
		if ( ! expresso_tem_foto( (int) $foto ) ) {
			$data['post_status'] = 'draft';
		}
	}
	return $data;
}, 99, 2 );

add_filter( 'admin_post_thumbnail_html', function ( $html, $id ) {
	return 'post' === get_post_type( $id ) ? '<p>A foto de capa é obrigatória para publicar. Sem foto, a matéria fica em rascunho.</p>' . $html : $html;
}, 10, 2 );
