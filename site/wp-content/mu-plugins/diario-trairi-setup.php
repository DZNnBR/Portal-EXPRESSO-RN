<?php
/**
 * Plugin Name: Portal Expresso RN — Configuração inicial
 * Description: Cria a estrutura editorial, páginas, menu e conteúdo demonstrativo do portal.
 * Version: 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ativa os componentes do portal quando o pacote é colocado em um WordPress novo.
 * A rotina só é executada por um administrador e pode ser chamada várias vezes.
 */
function dt_activate_portal_components() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( 'diario-do-trairi' !== get_stylesheet() && wp_get_theme( 'diario-do-trairi' )->exists() ) {
		switch_theme( 'diario-do-trairi' );
	}

	if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) && ! is_plugin_active( 'elementor/elementor.php' ) ) {
		activate_plugin( 'elementor/elementor.php' );
	}
}
add_action( 'init', 'dt_activate_portal_components', 1 );

function dt_seed_page( $title, $slug, $content, $parent = 0 ) {
	$existing = get_page_by_path( $parent ? get_page_uri( $parent ) . '/' . $slug : $slug, OBJECT, 'page' );
	if ( $existing ) {
		return (int) $existing->ID;
	}
	return (int) wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_parent'  => $parent,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		)
	);
}

function dt_seed_category( $name, $slug, $description = '' ) {
	$term = get_term_by( 'slug', $slug, 'category' );
	if ( $term ) {
		return (int) $term->term_id;
	}
	$result = wp_insert_term( $name, 'category', array( 'slug' => $slug, 'description' => $description ) );
	return is_wp_error( $result ) ? 0 : (int) $result['term_id'];
}

function dt_importar_edicao( $category_ids ) {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$dir = __DIR__ . '/edicao/';
	$materias = json_decode( file_get_contents( $dir . 'materias.json' ), true );
	if ( ! $materias ) {
		return false;
	}
	foreach ( $materias as $materia ) {
		if ( get_page_by_path( $materia['post_name'], OBJECT, 'post' ) ) {
			continue;
		}
		$upload = wp_upload_bits( $materia['file'], null, file_get_contents( $dir . $materia['file'] ) );
		if ( $upload['error'] ) {
			return false;
		}
		$foto = wp_insert_attachment( array( 'post_title' => $materia['alt'], 'post_excerpt' => $materia['credit'], 'post_mime_type' => 'image/jpeg', 'post_status' => 'inherit' ), $upload['file'], 0, true );
		if ( is_wp_error( $foto ) ) {
			return false;
		}
		wp_update_attachment_metadata( $foto, wp_generate_attachment_metadata( $foto, $upload['file'] ) );
		update_post_meta( $foto, '_wp_attachment_image_alt', $materia['alt'] );
		$materia['meta_input'] = array( '_thumbnail_id' => $foto );
		$materia['post_category'] = array( $category_ids[ $materia['category'] ] );
		$materia['post_status'] = 'publish';
		unset( $materia['category'], $materia['alt'], $materia['credit'], $materia['file'] );
		if ( is_wp_error( wp_insert_post( wp_slash( $materia ), true ) ) ) {
			return false;
		}
	}
	return true;
}

function dt_run_initial_setup() {
	if ( '1.0.1' === get_option( 'dt_initial_setup_complete' ) || ! is_blog_installed() ) {
		return;
	}
	if ( ! ( defined( 'WP_CLI' ) && WP_CLI ) && ! current_user_can( 'manage_options' ) ) {
		return;
	}

	update_option( 'blogname', 'Portal Expresso RN' );
	update_option( 'blogdescription', 'Notícias e serviços de todo o Rio Grande do Norte' );
	update_option( 'timezone_string', 'America/Fortaleza' );
	update_option( 'date_format', 'j \d\e F \d\e Y' );
	update_option( 'permalink_structure', '/%category%/%postname%/' );
	update_option( 'elementor_cpt_support', array( 'post', 'page' ) );
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	update_option( 'sidebars_widgets', array( 'wp_inactive_widgets' => array(), 'feed-sidebar' => array(), 'array_version' => 3 ) );

	$default_post = get_page_by_path( 'hello-world', OBJECT, 'post' );
	if ( $default_post ) {
		wp_delete_post( $default_post->ID, true );
	}
	$default_page = get_page_by_path( 'sample-page', OBJECT, 'page' );
	if ( $default_page ) {
		wp_delete_post( $default_page->ID, true );
	}

	$categories = array(
		'cidades-e-cotidiano'     => array( 'Cidades e Cotidiano', 'Notícias e serviços dos municípios do Trairi.' ),
		'politica-e-cidadania'    => array( 'Política e Cidadania', 'Poder público, transparência, direitos e serviços.' ),
		'economia-e-agro'         => array( 'Economia e Agro', 'Comércio, emprego, agricultura familiar e empreendedorismo.' ),
		'educacao-e-oportunidades'=> array( 'Educação e Oportunidades', 'Cursos, bolsas, vagas e iniciativas educacionais.' ),
		'saude-e-bem-estar'       => array( 'Saúde e Bem-estar', 'Serviços de saúde e informação de utilidade pública.' ),
		'seguranca-e-mobilidade'  => array( 'Segurança e Mobilidade', 'Trânsito, rodovias, segurança e deslocamento regional.' ),
		'turismo-e-gastronomia'   => array( 'Turismo e Gastronomia', 'Destinos, experiências e sabores da região.' ),
		'cultura-e-fe'            => array( 'Cultura e Fé', 'Patrimônio, festas, memória e expressões religiosas.' ),
		'esportes'                => array( 'Esportes', 'Competições, atletas e projetos esportivos.' ),
		'clima-e-agua'            => array( 'Clima e Água', 'Chuvas, seca, reservatórios e convivência com o semiárido.' ),
		'opiniao'                 => array( 'Opinião', 'Artigos assinados, claramente separados do noticiário.' ),
	);
	$category_ids = array();
	foreach ( $categories as $slug => $data ) {
		$category_ids[ $slug ] = dt_seed_category( $data[0], $slug, $data[1] );
	}

	$home_id    = dt_seed_page( 'Início', 'inicio', '<p>A página inicial é renderizada automaticamente pelo tema Portal Expresso RN.</p>' );
	$latest_id  = dt_seed_page( 'Últimas Notícias', 'ultimas-noticias', '<p>Acompanhe as publicações mais recentes do Portal Expresso RN.</p>' );
	$cities_id  = dt_seed_page( 'Municípios', 'municipios', '<p>Explore a cobertura do Rio Grande do Norte por município.</p><ul><li><a href="/?s=Natal">Natal</a></li><li><a href="/?s=Mossoró">Mossoró</a></li><li><a href="/?s=Caicó">Caicó</a></li><li><a href="/?s=Extremoz">Extremoz</a></li><li><a href="/?s=Santa Cruz">Santa Cruz</a></li><li><a href="/?s=Macau">Macau</a></li><li><a href="/?s=Martins">Martins</a></li></ul>' );
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	update_option( 'page_for_posts', $latest_id );

	$cities = array( 'Campo Redondo', 'Coronel Ezequiel', 'Jaçanã', 'Boa Saúde', 'Japi', 'Lajes Pintadas', 'Monte das Gameleiras', 'Passa e Fica', 'Serra Caiada', 'Santa Cruz', 'São Bento do Trairi', 'São José do Campestre', 'Serra de São Bento', 'Sítio Novo', 'Tangará' );
	foreach ( $cities as $city ) {
		dt_seed_page( $city, sanitize_title( $city ), '<p>Página local de <strong>' . esc_html( $city ) . '</strong>. As notícias, serviços e agenda do município serão reunidos aqui.</p>', $cities_id );
	}

	$institutional_pages = array(
		'Sobre'                    => array( 'sobre', '<p>O Portal Expresso RN acompanha o Rio Grande do Norte, da capital ao interior. Nossa cobertura reúne cidades, economia, educação, cultura, esporte e serviços.</p>' ),
		'Contato'                  => array( 'contato', '<p>Adicione aqui os canais oficiais da redação.</p>' ),
		'Envie sua pauta'          => array( 'envie-sua-pauta', '<p>Configure um formulário para receber sugestões de pauta, fotos e informações, com consentimento para tratamento de dados.</p>' ),
		'Anuncie'                  => array( 'anuncie', '<p>Apresente os formatos comerciais e os canais de contato. Todo conteúdo patrocinado deve ser identificado com clareza.</p>' ),
		'Política Editorial'       => array( 'politica-editorial', '<h2>Compromissos</h2><p>Clareza entre notícia, opinião e publicidade; confirmação de fatos; transparência sobre fontes; direito de resposta e correções visíveis.</p>' ),
		'Correções'                => array( 'correcoes', '<p>As correções editoriais serão registradas com data, explicação objetiva e link para a publicação afetada.</p>' ),
		'Política de Privacidade' => array( 'politica-de-privacidade', '<p>Este texto deve ser revisado por responsável jurídico antes do lançamento. Descreva os dados coletados, bases legais, prazos de retenção e direitos previstos na LGPD.</p>' ),
		'Agenda do RN'         => array( 'agenda', '<p>Eventos culturais, esportivos, educacionais e comunitários confirmados por seus organizadores.</p>' ),
		'Guia de Serviços'        => array( 'guia-de-servicos', '<p>Telefones, endereços e horários devem sempre indicar a fonte e a data da última verificação.</p>' ),
	);
	foreach ( $institutional_pages as $title => $data ) {
		dt_seed_page( $title, $data[0], $data[1] );
	}

	if ( ! dt_importar_edicao( $category_ids ) ) {
		return;
	}

	$menu_name = 'Menu principal';
	$menu      = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu ? $menu->term_id : wp_create_nav_menu( $menu_name );
	if ( ! is_wp_error( $menu_id ) && ! get_option( 'dt_menu_seeded' ) ) {
		$menu_items = array(
			array( 'Início', $home_id, 'page' ),
			array( 'Últimas Notícias', $latest_id, 'page' ),
			array( 'Municípios', $cities_id, 'page' ),
		);
		foreach ( $menu_items as $item ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $item[0], 'menu-item-object-id' => $item[1], 'menu-item-object' => $item[2], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
		foreach ( array( 'politica-e-cidadania' => 'Política', 'economia-e-agro' => 'Economia', 'saude-e-bem-estar' => 'Saúde', 'cultura-e-fe' => 'Cultura', 'esportes' => 'Esportes', 'turismo-e-gastronomia' => 'Turismo' ) as $slug => $label ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $label, 'menu-item-object-id' => $category_ids[ $slug ], 'menu-item-object' => 'category', 'menu-item-type' => 'taxonomy', 'menu-item-status' => 'publish' ) );
		}
		set_theme_mod( 'nav_menu_locations', array( 'primary' => (int) $menu_id ) );
		update_option( 'dt_menu_seeded', 1 );
	}

	flush_rewrite_rules();
	update_option( 'dt_initial_setup_complete', '1.0.1' );
}
add_action( 'init', 'dt_run_initial_setup', 99 );

function dt_setup_admin_notice() {
	if ( get_option( 'dt_initial_setup_complete' ) && ! is_plugin_active( 'elementor/elementor.php' ) ) {
		echo '<div class="notice notice-warning"><p><strong>Portal Expresso RN:</strong> o Elementor está instalado, mas ainda precisa ser ativado em Plugins.</p></div>';
	}
}
add_action( 'admin_notices', 'dt_setup_admin_notice' );

/**
 * Cria o editor Major e transfere para ele as postagens originalmente atribuídas ao admin.
 */
function dt_create_major_editor_and_migrate_posts() {
	if ( '1.0.0' === get_option( 'dt_major_author_migration' ) ) {
		return;
	}
	if ( ! ( defined( 'WP_CLI' ) && WP_CLI ) && ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$major = get_user_by( 'login', 'major' );
	if ( ! $major ) {
		$major_id = wp_insert_user(
			array(
				'user_login'   => 'major',
				'user_pass'    => wp_generate_password( 32, true, true ),
				'user_email'   => 'major@diariodotrairi.local',
				'display_name' => 'Major',
				'nickname'     => 'Major',
				'first_name'   => 'Major',
				'role'         => 'editor',
			)
		);
		if ( is_wp_error( $major_id ) ) {
			return;
		}
		$major = get_user_by( 'id', $major_id );
	} else {
		$major->set_role( 'editor' );
	}

	$admin = get_user_by( 'login', 'admin' );
	if ( $admin && $major ) {
		$post_ids = get_posts(
			array(
				'author'         => $admin->ID,
				'post_type'      => 'post',
				'post_status'    => array( 'publish', 'future', 'draft', 'pending', 'private' ),
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);
		foreach ( $post_ids as $post_id ) {
			wp_update_post( array( 'ID' => $post_id, 'post_author' => $major->ID ) );
		}
	}

	update_option( 'dt_major_author_migration', '1.0.0' );
}
add_action( 'init', 'dt_create_major_editor_and_migrate_posts', 100 );

/** Remove apenas o texto técnico inicial da home, preservando edições reais. */
function dt_clear_home_placeholder_content() {
	if ( get_option( 'dt_home_placeholder_cleared' ) ) {
		return;
	}
	if ( ! ( defined( 'WP_CLI' ) && WP_CLI ) && ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$home = get_page_by_path( 'inicio', OBJECT, 'page' );
	if ( $home && 'A página inicial é renderizada automaticamente pelo tema Portal Expresso RN.' === trim( wp_strip_all_tags( $home->post_content ) ) ) {
		wp_update_post( array( 'ID' => $home->ID, 'post_content' => '' ) );
	}
	update_option( 'dt_home_placeholder_cleared', '1.0.0' );
}
add_action( 'init', 'dt_clear_home_placeholder_content', 101 );
