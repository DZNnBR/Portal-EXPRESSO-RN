<?php /** Rodapé do tema. */ ?>
</main>
<footer class="dt-footer">
	<div class="dt-wrap dt-footer__grid">
		<section>
			<h2>Portal Expresso RN</h2>
			<p>Notícias, serviços e histórias do litoral ao sertão do Rio Grande do Norte.</p>
		</section>
		<section>
			<h3>Editorias</h3>
			<ul><li><a href="<?php echo esc_url( dt_category_link_by_slug( 'politica-e-cidadania' ) ); ?>">Política &amp; Cidadania</a></li><li><a href="<?php echo esc_url( dt_category_link_by_slug( 'economia-e-agro' ) ); ?>">Economia &amp; Agro</a></li><li><a href="<?php echo esc_url( dt_category_link_by_slug( 'turismo-e-gastronomia' ) ); ?>">Turismo &amp; Gastronomia</a></li><li><a href="<?php echo esc_url( dt_category_link_by_slug( 'esportes' ) ); ?>">Esportes</a></li></ul>
		</section>
		<section>
			<h3>Institucional</h3>
			<ul><li><a href="<?php echo esc_url( home_url( '/sobre/' ) ); ?>">Sobre</a></li><li><a href="<?php echo esc_url( home_url( '/politica-editorial/' ) ); ?>">Política editorial</a></li><li><a href="<?php echo esc_url( home_url( '/correcoes/' ) ); ?>">Correções</a></li><li><a href="<?php echo esc_url( home_url( '/contato/' ) ); ?>">Contato</a></li></ul>
		</section>
		<section>
			<h3>Participe</h3>
			<ul><li><a href="<?php echo esc_url( home_url( '/envie-sua-pauta/' ) ); ?>">Envie sua pauta</a></li><li><a href="<?php echo esc_url( home_url( '/anuncie/' ) ); ?>">Anuncie</a></li><li><a href="<?php echo esc_url( home_url( '/politica-de-privacidade/' ) ); ?>">Privacidade</a></li></ul>
		</section>
	</div>
	<div class="dt-footer__bottom"><div class="dt-wrap">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> Portal Expresso RN.</div></div>
</footer>
<nav class="rn-dock" aria-label="Atalhos do portal">
 <a href="<?php echo esc_url(home_url('/')); ?>"><span aria-hidden="true">⌂</span>Início</a>
 <a href="<?php echo esc_url(home_url('/ultimas-noticias/')); ?>"><span aria-hidden="true">◷</span>Últimas</a>
 <a href="<?php echo esc_url(home_url('/?s=')); ?>"><span aria-hidden="true">⌕</span>Busca</a>
 <button type="button" class="rn-menu" aria-expanded="false" aria-controls="menu-principal"><span aria-hidden="true">☰</span>Menu</button>
</nav>
<?php wp_footer(); ?>
</body>
</html>
