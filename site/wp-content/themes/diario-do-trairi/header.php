<?php
/** Cabeçalho do tema. */
?><!doctype html>
<html data-theme="light" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>try { if (localStorage.getItem('expresso-theme') === 'dark') document.documentElement.dataset.theme = 'dark'; } catch (_) {}</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="dt-skip" href="#conteudo"><?php esc_html_e( 'Pular para o conteúdo', 'diario-do-trairi' ); ?></a>
<div class="dt-topbar">
	<div class="dt-wrap dt-topbar__inner">
		<span><?php echo esc_html( wp_date( 'l, j \d\e F \d\e Y' ) ); ?></span>
		<span><a href="<?php echo esc_url( home_url( '/politica-editorial/' ) ); ?>">Política editorial</a> &nbsp;•&nbsp; <a href="<?php echo esc_url( home_url( '/anuncie/' ) ); ?>">Anuncie</a></span>
	</div>
</div>
<header class="dt-header">
	<div class="dt-wrap dt-header__brand">
		<a class="dt-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Portal Expresso RN — página inicial">
			<?php if ( is_front_page() ) : ?>
				<h1 class="dt-logo__name"><small>Portal</small> Expresso <b>RN</b></h1>
			<?php else : ?>
				<span class="dt-logo__name"><small>Portal</small> Expresso <b>RN</b></span>
			<?php endif; ?>
		</a>
		<div class="dt-header__actions">
			<button class="rn-mode" type="button" aria-label="Modo escuro" aria-pressed="false">◐</button>
			<a class="dt-search-link" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="Buscar">⌕</a>
			<button class="dt-menu-toggle" type="button" aria-expanded="false" aria-controls="menu-principal" aria-label="Abrir menu">☰</button>
		</div>
	</div>
	<nav class="dt-nav" id="menu-principal" aria-label="Navegação principal">
		<div class="dt-wrap">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'fallback_cb'    => 'dt_fallback_menu',
			)
		);
		?>
		</div>
	</nav>
</header>
<?php
$urgent = dt_query_posts(
	array(
		'posts_per_page' => 1,
		'tag'            => 'urgente',
	)
);
if ( $urgent->have_posts() ) :
	$urgent->the_post();
	?>
	<div class="dt-breaking" role="status">
		<div class="dt-wrap dt-breaking__inner"><strong>Urgente</strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
	</div>
	<?php
	wp_reset_postdata();
endif;
?>
<main class="dt-main" id="conteudo">
<?php
function dt_fallback_menu() {
	$items = array(
		'Início'           => home_url( '/' ),
		'Últimas notícias' => home_url( '/ultimas-noticias/' ),
		'Municípios'       => home_url( '/municipios/' ),
		'Política'         => dt_category_link_by_slug( 'politica-e-cidadania' ),
		'Economia'         => dt_category_link_by_slug( 'economia-e-agro' ),
		'Saúde'            => dt_category_link_by_slug( 'saude-e-bem-estar' ),
		'Cultura'          => dt_category_link_by_slug( 'cultura-e-fe' ),
		'Esportes'         => dt_category_link_by_slug( 'esportes' ),
		'Turismo'          => dt_category_link_by_slug( 'turismo-e-gastronomia' ),
	);
	echo '<ul>';
	foreach ( $items as $label => $url ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}
