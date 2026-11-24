<?php
$featured = dt_query_posts( array( 'posts_per_page' => 4, 'offset' => 5 ) );
if ( $featured->have_posts() ) :
?>
<section class="dt-section" aria-labelledby="trairi-destaques"><div class="dt-wrap">
	<div class="dt-section__head"><h2 id="trairi-destaques">Giro pelo RN</h2><a href="<?php echo esc_url( home_url( '/ultimas-noticias/' ) ); ?>">Ver todas</a></div>
	<div class="dt-grid-4"><?php while ( $featured->have_posts() ) : $featured->the_post(); get_template_part( 'template-parts-card' ); endwhile; ?></div>
</div></section>
<?php endif; wp_reset_postdata(); ?>

