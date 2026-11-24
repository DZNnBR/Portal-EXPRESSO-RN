<?php
/** Resultados de busca. */
get_header();
?>
<header class="dt-page-header"><div class="dt-wrap"><span class="dt-kicker">Busca</span><h1><?php echo esc_html( sprintf( 'Resultados para: %s', get_search_query() ) ); ?></h1><div style="margin-top:20px;max-width:720px"><?php get_search_form(); ?></div></div></header>
<section class="dt-section"><div class="dt-wrap"><div class="dt-feed">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); get_template_part( 'template-parts-feed-card' ); endwhile; else : ?><div class="dt-empty"><h2>Nada encontrado</h2><p>Tente outro termo ou navegue pelas editorias.</p></div><?php endif; ?>
</div><nav class="dt-pagination" aria-label="Páginas"><?php the_posts_pagination(); ?></nav></div></section>
<?php get_footer(); ?>
