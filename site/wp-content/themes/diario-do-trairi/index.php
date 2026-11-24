<?php
/** Lista genérica de publicações. */
get_header();
?>
<header class="dt-page-header"><div class="dt-wrap"><span class="dt-kicker">Portal Expresso RN</span><h1><?php echo esc_html( is_home() ? 'Últimas notícias' : get_the_archive_title() ); ?></h1></div></header>
<section class="dt-section"><div class="dt-wrap dt-feed-layout">
	<div class="dt-feed">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); get_template_part( 'template-parts-feed-card' ); endwhile; else : ?><div class="dt-empty">Nenhuma publicação encontrada.</div><?php endif; ?>
	<?php if ( get_the_posts_pagination() ) : ?><nav class="dt-pagination" aria-label="Páginas"><?php the_posts_pagination(); ?></nav><?php endif; ?>
	</div>
	<aside class="dt-sidebar"><section class="dt-widget dt-widget--sand"><h2>Uma região, 15 cidades</h2><p>Acompanhe notícias e serviços do Território do Trairi.</p><a class="dt-button" href="<?php echo esc_url( home_url( '/municipios/' ) ); ?>">Conheça os municípios</a></section></aside>
</div></section>
<?php get_footer(); ?>
