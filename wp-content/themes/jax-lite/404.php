<?php 

	get_header(); 

	do_action('jaxlite_header_sidebar', 'header-sidebar-area');

	if ( ( jaxlite_get_header_layout() == "header_one" ) || ( jaxlite_get_header_layout() == "header_two" ) ) :
	
		echo '<h1 class="title headtitle ' . jaxlite_get_header_layout() . '">' . __( 'Content not found',"jaxlite") . '</h1>';
	
	endif;

?>

<div class="container">

	<div class="row" id="blog" >
		
        <article class="post-container col-md-12">

			<div class="post-article">

                <h1> <?php _e( 'Oops, it is a little bit embarassing...',"jaxlite" ) ?> </h1>           
			
				<?php _e( 'The page that you requested, was not found.',"jaxlite"); ?> 

                <h2> <?php _e( 'What can i do?',"jaxlite" ) ?> </h2>           

                <p> <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name') ?>"> <?php _e( 'Back to the homepage',"jaxlite"); ?> </a> </p>
              
                <p> <?php _e( 'Check the typed URL',"jaxlite"); ?> </p>

                <p> <?php _e( 'Make a search, from the below form:',"jaxlite"); ?> </p>
                
                <section class="contact-form">
                
                    <form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
                         <input type="text" value="<?php _e( 'Search', "jaxlite" ) ?>" name="s" id="s" onblur="if (this.value == '') {this.value = '<?php _e( 'Search', "jaxlite" ) ?>';}" onfocus="if (this.value == '<?php _e( 'Search', "jaxlite" ) ?>') {this.value = '';}" class="input-search"/>
                         <input type="submit" id="searchsubmit" class="button-search" value="<?php _e( 'Search', "jaxlite" ) ?>" />
                    </form>
                    
                    <div class="clear"></div>
                    
                </section>

			</div>

    	</article>
           
    </div>
    
</div>

<?php 
	
	do_action('jaxlite_bottom_sidebar', 'bottom-sidebar-area' );
	
	get_footer(); 

?>