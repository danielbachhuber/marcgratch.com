<?php

/*
 Template Name: Page
*/

	get_header(); ?>
<div class="ast-index-listing ast-width clearfix">

	<?php if ( have_posts() ) :

		while ( have_posts() ) : the_post();

			get_template_part('content/page');

		endwhile;

	else :

		get_template_part( 'content/none' );

	endif;
?>
</div>

<?php get_footer(); ?>