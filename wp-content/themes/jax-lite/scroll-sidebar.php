<div id="sidebar-wrapper">
	
	<div id="scroll-sidebar" class="clearfix">
    
		<div class="navigation"><i class="fa fa-times open"></i></div>	

		<div class="post-article">

            <div class="title-container"><h3 class="title">Menu</h3></div>
    
            <nav id="widget-menu" class="custommenu">
            
                <?php wp_nav_menu( array('menu' => 'main-menu', 'container' => 'false','depth' => 3, 'menu_id' => 'widgetmenus' )); ?>
            
            </nav>                
		
        </div>

		<?php 

			if ( is_active_sidebar( jaxlite_sidebar_name('scroll')) ) : 
	        
				dynamic_sidebar( jaxlite_sidebar_name('scroll') );	
			
			endif;
			
		?>
	    
		<div class="post-article">

			<div class="copyright">
	                
				<p>
				
					<?php 
					
						if (jaxlite_setting('jaxlite_copyright_text')): 
							
							echo stripslashes(jaxlite_setting('jaxlite_copyright_text'));
							
						else:
						
							echo __('Copyright ','jaxlite') . get_bloginfo("name") . " " . date("Y");
						
						endif; 
							
						echo " | " . __('Theme by','jaxlite'); 
					?> 
                    
                    	<a href="<?php echo esc_url('https://www.themeinprogress.com/'); ?>" target="_blank">Theme in Progress</a> |
                        
                        <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'jaxlite' ) ); ?>" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'jaxlite' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'jaxlite' ), 'WordPress' ); ?>
                        </a>
                    
				</p>
	                    
			</div>

			<?php do_action('jaxlite_socials'); ?>

		</div>
	    
	</div>

</div>