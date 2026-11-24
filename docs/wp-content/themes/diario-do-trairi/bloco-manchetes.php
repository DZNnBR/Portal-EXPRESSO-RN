<?php
$lead = dt_query_posts( array( 'posts_per_page' => 5 ) );
if ( $lead->have_posts() ) :
?>
<section class="dt-section" aria-labelledby="destaques"><div class="dt-wrap">
<h2 class="screen-reader-text" id="destaques">Manchetes do Rio Grande do Norte</h2>
<div class="dt-hero">
<?php $lead->the_post(); get_template_part( 'template-parts-card', null, array( 'modifier' => 'dt-card--lead', 'size' => 'dt-lead' ) ); ?>
<div class="dt-hero__side">
<?php while ( $lead->have_posts() ) : $lead->the_post(); get_template_part( 'template-parts-card', null, array( 'modifier' => 'dt-card--compact' ) ); endwhile; ?>
</div></div></div></section>
<?php endif; wp_reset_postdata(); ?>
