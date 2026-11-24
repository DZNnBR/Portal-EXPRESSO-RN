<?php
/** Arquivos de categoria, data e autor. */
get_header();
?>
<header class="dt-page-header"><div class="dt-wrap"><span class="dt-kicker">Editoria</span><h1><?php the_archive_title(); ?></h1><?php the_archive_description( '<div>', '</div>' ); ?></div></header>
<section class="dt-section"><div class="dt-wrap"><div class="dt-grid-4">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); get_template_part( 'template-parts-card' ); endwhile; else : ?><div class="dt-empty">Nenhuma publicação encontrada nesta editoria.</div><?php endif; ?>
</div><nav class="dt-pagination" aria-label="Páginas"><?php the_posts_pagination(); ?></nav></div></section>
<?php get_footer(); ?>
