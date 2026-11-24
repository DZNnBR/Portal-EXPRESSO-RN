<section class="dt-section" id="ultimas" aria-labelledby="ultimas-titulo"><div class="dt-wrap">
	<div class="dt-section__head"><div><span class="dt-kicker">Em ordem de publicação</span><h2 id="ultimas-titulo">Últimas notícias</h2></div><a href="<?php echo esc_url( home_url( '/ultimas-noticias/' ) ); ?>">Abrir feed completo</a></div>
	<div class="dt-feed-layout">
		<div class="dt-feed">
		<?php
		$feed = dt_query_posts( array( 'posts_per_page' => 8 ) );
		if ( $feed->have_posts() ) : while ( $feed->have_posts() ) : $feed->the_post(); get_template_part( 'template-parts-feed-card' ); endwhile; else :
		?>
		<div class="dt-empty">Nenhuma notícia publicada ainda.</div>
		<?php endif; wp_reset_postdata(); ?>
		</div>
		<aside class="dt-sidebar" aria-label="Conteúdo complementar">
			<section class="dt-widget"><h2>Em foco</h2><ol class="dt-ranked">
			<?php
			$popular = dt_query_posts( array( 'posts_per_page' => 5, 'orderby' => 'comment_count date' ) );
			while ( $popular->have_posts() ) : $popular->the_post(); ?><li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li><?php endwhile; wp_reset_postdata();
			?></ol></section>
			<section class="dt-widget dt-widget--sand"><h2>Fale com a redação</h2><p>Envie notícias e sugestões de pauta de sua cidade.</p><a class="dt-button" href="<?php echo esc_url( home_url( '/contato/' ) ); ?>">Contato</a></section>
		</aside>
	</div>
</div></section>
