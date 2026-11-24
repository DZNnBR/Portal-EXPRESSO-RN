<?php
/** Página institucional. */
get_header();
while ( have_posts() ) : the_post();
?>
<header class="dt-page-header"><div class="dt-wrap"><span class="dt-kicker">Portal Expresso RN</span><h1><?php the_title(); ?></h1></div></header>
<article class="dt-article dt-article__content"><?php the_content(); wp_link_pages(); ?></article>
<?php endwhile; get_footer(); ?>
