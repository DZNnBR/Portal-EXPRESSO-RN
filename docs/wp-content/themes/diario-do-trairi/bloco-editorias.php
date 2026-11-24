<section class="dt-section dt-section--tint" aria-label="Editorias"><div class="dt-wrap dt-grid-2">
	<?php
	$topics = array(
		array( 'cidades-e-cotidiano', 'Cidades e cotidiano' ),
		array( 'politica-e-cidadania', 'Política e serviços' ),
		array( 'economia-e-agro', 'Economia e campo' ),
		array( 'cultura-e-fe', 'Cultura, turismo e fé' ),
	);
	foreach ( $topics as $topic ) :
		$q = dt_query_posts( array( 'posts_per_page' => 4, 'category_name' => $topic[0] ) );
		?>
		<section class="dt-topic">
			<div class="dt-section__head"><h2><?php echo esc_html( $topic[1] ); ?></h2><a href="<?php echo esc_url( dt_category_link_by_slug( $topic[0] ) ); ?>">Ver editoria</a></div>
			<?php if ( $q->have_posts() ) : $q->the_post(); ?>
			<div class="dt-topic__lead"><a href="<?php the_permalink(); ?>"><?php dt_card_image( 'dt-card' ); ?></a><div><span class="dt-kicker"><?php echo esc_html( $topic[1] ); ?></span><h3 class="dt-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3></div></div>
			<ul class="dt-topic__list"><?php while ( $q->have_posts() ) : $q->the_post(); ?><li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li><?php endwhile; ?></ul>
			<?php else : ?><p>As novas publicações desta editoria aparecerão aqui.</p><?php endif; wp_reset_postdata(); ?>
		</section>
	<?php endforeach; ?>
</div></section>

