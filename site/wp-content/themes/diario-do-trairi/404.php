<?php
/** Página não encontrada. */
get_header();
?>
<section class="dt-article"><span class="dt-kicker">Erro 404</span><h1>Página não encontrada</h1><p>A notícia pode ter mudado de endereço. Use a busca ou volte para a página inicial.</p><?php get_search_form(); ?><p><a class="dt-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">Voltar ao início</a></p></section>
<?php get_footer(); ?>
