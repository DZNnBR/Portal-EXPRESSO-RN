<?php
/** Matéria individual. */
get_header();
while ( have_posts() ) : the_post();
?>
<article <?php post_class(); ?>>
	<header class="dt-article">
		<?php dt_post_category(); ?>
		<h1><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) : ?><p class="dt-article__dek"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		<?php dt_post_meta(); ?>
	</header>
	<?php if ( has_post_thumbnail() ) : ?><figure class="dt-article__image"><?php the_post_thumbnail( 'full' ); ?><?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?><figcaption><?php echo wp_kses_post( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption><?php endif; ?></figure><?php endif; ?>
	<div class="dt-article dt-article__content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
		<?php the_tags( '<p><strong>Assuntos:</strong> ', ', ', '</p>' ); ?>
	</div>
</article>
<?php endwhile; get_footer(); ?>
