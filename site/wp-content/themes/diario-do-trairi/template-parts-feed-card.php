<?php /** Card do feed cronológico. */ ?>
<article <?php post_class( 'dt-feed-card' ); ?>>
	<a class="dt-feed-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php dt_card_image( 'dt-card' ); ?></a>
	<div>
		<?php dt_post_category(); ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25 ) ); ?></p>
		<?php dt_post_meta(); ?>
	</div>
</article>
