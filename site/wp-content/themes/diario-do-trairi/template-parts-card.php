<?php
/** Card editorial reutilizável. */
$modifier = isset( $args['modifier'] ) ? $args['modifier'] : '';
$size     = isset( $args['size'] ) ? $args['size'] : 'dt-card';
?>
<article <?php post_class( 'dt-card ' . $modifier ); ?>>
	<a class="dt-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php dt_card_image( $size ); ?></a>
	<div class="dt-card__body">
		<?php dt_post_category(); ?>
		<h3 class="dt-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="dt-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
		<?php dt_post_meta(); ?>
	</div>
</article>
