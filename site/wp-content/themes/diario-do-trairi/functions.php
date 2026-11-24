<?php
/**
 * Funções do tema Portal Expresso RN.
 *
 * @package DiarioDoTrairi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DT_THEME_VERSION', '2.1.0' );

function dt_setup() {
	load_theme_textdomain( 'diario-do-trairi', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 520,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'diario-do-trairi' ),
			'footer'  => __( 'Menu do rodapé', 'diario-do-trairi' ),
		)
	);
	add_image_size( 'dt-lead', 980, 600, true );
	add_image_size( 'dt-card', 640, 400, true );
}
add_action( 'after_setup_theme', 'dt_setup' );

function dt_assets() {
	wp_enqueue_style( 'dt-style', get_stylesheet_uri(), array(), DT_THEME_VERSION );
	wp_enqueue_script( 'dt-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), DT_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'dt_assets' );

function dt_widgets() {
	register_sidebar(
		array(
			'name'          => __( 'Lateral do feed', 'diario-do-trairi' ),
			'id'            => 'feed-sidebar',
			'before_widget' => '<section class="dt-widget">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'dt_widgets' );

function dt_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'dt_excerpt_length' );

function dt_post_category() {
	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		echo '<span class="dt-kicker">' . esc_html( $categories[0]->name ) . '</span>';
	}
}

function dt_post_meta() {
	echo '<div class="dt-meta">';
	echo '<span>' . esc_html( get_the_date() ) . '</span>';
	echo '<span>' . esc_html( sprintf( __( 'Por %s', 'diario-do-trairi' ), get_the_author() ) ) . '</span>';
	echo '</div>';
}

function dt_card_image( $size = 'dt-card' ) {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( $size, array( 'loading' => 'lazy' ) );
		return;
	}
	echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/placeholder-trairi.svg' ) . '" alt="" loading="lazy" width="640" height="400">';
}

function dt_category_link_by_slug( $slug ) {
	$category = get_category_by_slug( $slug );
	return $category ? get_category_link( $category ) : home_url( '/ultimas-noticias/' );
}

function dt_query_posts( $args = array() ) {
	$defaults = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
	);
	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

function dt_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'dt-home';
	}
	return $classes;
}
add_filter( 'body_class', 'dt_body_classes' );

function dt_elementor_support() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}
	update_option( 'elementor_cpt_support', array( 'post', 'page' ) );
}
add_action( 'admin_init', 'dt_elementor_support' );

function dt_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'dt_elementor_locations' );

function dt_search_form( $form ) {
	return '<form role="search" method="get" class="dt-search-form" action="' . esc_url( home_url( '/' ) ) . '">
		<label><span class="screen-reader-text">' . esc_html__( 'Buscar por:', 'diario-do-trairi' ) . '</span>
		<input type="search" placeholder="Buscar no Portal Expresso RN" value="' . esc_attr( get_search_query() ) . '" name="s"></label>
		<button class="dt-button" type="submit">' . esc_html__( 'Buscar', 'diario-do-trairi' ) . '</button>
	</form>';
}
add_filter( 'get_search_form', 'dt_search_form' );

function expresso_blocos_capa() {
    return array( 'manchetes' => 'Manchetes', 'giro' => 'Giro pelo RN', 'editorias' => 'Editorias', 'feed' => 'Últimas notícias' );
}
foreach ( expresso_blocos_capa() as $slug => $label ) {
    add_shortcode( 'expresso_' . $slug, static function () use ( $slug ) {
        ob_start();
        get_template_part( 'bloco-' . $slug );
        return ob_get_clean();
    } );
}

function expresso_preparar_capa_elementor() {
    $id = (int) get_option( 'page_on_front' );
    if ( ! $id || ! current_user_can( 'edit_post', $id ) || ! did_action( 'elementor/loaded' ) || get_post_meta( $id, '_expresso_capa_editavel', true ) || get_post_meta( $id, '_elementor_data', true ) || get_post_field( 'post_content', $id ) ) {
        return;
    }
    $layout = array();
    foreach ( expresso_blocos_capa() as $slug => $label ) {
        $layout[] = array(
            'id' => substr( md5( $slug ), 0, 7 ), 'elType' => 'container',
            'settings' => array( '_title' => $label, 'content_width' => 'full', 'padding' => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ) ),
            'elements' => array( array(
                'id' => substr( md5( 'widget-' . $slug ), 0, 7 ), 'elType' => 'widget', 'widgetType' => 'shortcode',
                'settings' => array( 'shortcode' => '[expresso_' . $slug . ']', '_title' => $label ), 'elements' => array(),
            ) ),
        );
    }
    $doc = \Elementor\Plugin::$instance->documents->get( $id );
    if ( $doc && $doc->save( array( 'elements' => $layout ) ) ) {
        update_post_meta( $id, '_elementor_edit_mode', 'builder' );
        update_post_meta( $id, '_expresso_capa_editavel', 1 );
    }
}
add_action( 'admin_init', 'expresso_preparar_capa_elementor', 30 );
