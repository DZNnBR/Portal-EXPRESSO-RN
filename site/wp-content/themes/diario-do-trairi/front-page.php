<?php
get_header();
while ( have_posts() ) : the_post();
    if ( get_post_meta( get_the_ID(), '_expresso_capa_editavel', true ) ) {
        the_content();
    } else {
        get_template_part( 'bloco-manchetes' );
        the_content();
        foreach ( array( 'giro', 'editorias', 'feed' ) as $bloco ) {
            get_template_part( 'bloco-' . $bloco );
        }
    }
endwhile;
get_footer();
