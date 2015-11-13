<div id="si_dashboard" class="wrap about-wrap">

	<h1><?php printf( self::__( 'Create Leads, <a href="%s">Sprout Clients</a>!' ), self::PLUGIN_URL, self::SC_VERSION ); ?></h1>

	<div class="about-text"><?php self::_e( 'Thank you for using Sprout Clients at such an early stage of the development process &mdash; your feedback during this time is critical to it\'s success.' ) ?></div>


	<h2 class="nav-tab-wrapper">
		<?php do_action( 'sprout_settings_tabs' ); ?>
	</h2>

	<div class="welcome_content clearfix">
		<div class="license-overview">
			<div class="activate_message clearfix">
				<div class="activation_msg clearfix">
					 <p>
						<h4><?php self::_e( 'First Things First...' ) ?></h4>
						<?php self::_e( 'An active license for Sprout Clients provides support and updates. By activating your license, you can get automatic plugin updates from the WordPress dashboard. Updates provide you with the latest bug fixes and the new features each major release brings.' ) ?></p>
				</div>
				<div class="activation_inputs clearfix">
					<input type="text" name="<?php echo SC_Updates::LICENSE_KEY_OPTION ?>" id="<?php echo SC_Updates::LICENSE_KEY_OPTION ?>" value="<?php echo SC_Updates::license_key() ?>" class="fat-input <?php echo 'license_'.SC_Updates::license_status() ?>" size="40" class="text-input">
					<?php if ( SC_Updates::license_status() != false && SC_Updates::license_status() == 'valid' ) : ?>
						<button id="sc_activate_license" class="button button-large" disabled="disabled"><?php self::_e( 'Activate License' ) ?></button> 
						<button id="sc_deactivate_license" class="button button-large"><?php self::_e( 'Deactivate License' ) ?></button>
					<?php else : ?>
						<button id="sc_activate_license" class="button button-primary button-large"><?php self::_e( 'Activate License' ) ?></button>
					<?php endif ?>
					<div id="license_message" class="clearfix"></div>
				</div>
			</div>


			<h2 class="headline_callout"><?php self::_e( 'Sprout Clients Future' ) ?></h2>

			<div class="feature-section col three-col clearfix">
				<div class="col-1">
					<span class="flow_icon icon-notebook"></span>
					<h4><?php self::_e( 'Contact Management but MORE' ); ?></h4>
					<p><?php self::_e( "Manage your contacts in a way that gains more clients and customers. In a future release Sprout Clients will integrate with the best WordPress e-commerce plugins (i.e. WooCommerce &amp; Easy Digital Downloads) bringing a full featured CRM to administrators, e.g. full customer purchase history, event tracking, e-commerce customer management, messaging, and much more." ); ?></p>
				</div>
				<div class="col-2">
					<span class="flow_icon icon-lightsaber"></span>
					<h4><?php self::_e( 'Jedi Automation Tricks' ); ?></h4>
					<p><?php self::_e( 'Use the force of Sprout Clients to automatically follow-up with your contacts. A prime example would be an e-mail that is sent to someone you recently met at a conference, an e-mail that can be dynamically created for you or a message that was written just after meeting him/her and scheduled to send later. How about a notification reminding you that you have a few contacts that need attention.' ); ?></p>
				</div>
				<div class="col-3 last-feature">
					<span class="flow_icon icon-sproutapps-invoices"></span>
					<h4><?php self::_e( 'Build Relationships that Convert with Time' ); ?></h4>
					<p><?php self::_e( 'The premise of "managing" your contacts is to build relationships. Properly leveraging your contact lists isn\'t sending out a single email to the entire list asking for work &mdash; instead the process should be relational (get it?). Sprout Clients wants to make the process of building those relationships easier and less time consuming.' ); ?></p>
				</div>
			</div>

		</div>
	</div>

	<hr />

	<div class="welcome_content">
		<h3><?php self::_e( 'FAQs' ); ?></h3>

		<div class="feature-section col three-col clearfix">
			<div>
				<h4><?php self::_e( 'Where do I start?' ); ?></h4>
				<p>
					<?php printf( self::__( "You can jump right in and start <a href='%s'>creating</a> your first lead but here are some important things to know first:" ), admin_url( 'post-new.php?post_type=sa_client' ) ); ?>
				</p>
				<p>
					<ol>
						<li><?php self::_e( 'Each Lead should have a type set that signifies how it associates with you.' ) ?></li>
						<li><?php self::_e( 'Even though a lead can have many contacts associated I understand most of our "leads" only have a single point of contact. Leads:Contacts is setup as one:many intentionally, trust us.' ) ?></li>
						<li><?php self::_e( 'Statuses are set manually at the moment but in the future count on them being dynamically set by conditions. For now use them to organize your leads and better your workflows for follow-ups.' ) ?></li>
						<li><?php self::_e( 'If you\'re a Sprout Invoices user than "Clients" are now "Leads".' ) ?></li>

					</ol>
				</p>
			</div>
			<div>
				<h4><?php self::_e( 'Leads &amp; WordPress Users?' ); ?></h4>
				<p><?php printf( self::__( '<a href="%s">Leads</a> have WordPress users associated with them and leads are not limited to a single user either. This allows for you to have multiple points of contact for a single lead. Leads can share contacts too...consider a use case where a "Lead" is a conference that you ended up meeting a lot of new contacts but you still have a "Lead" for each of those contacts.' ), admin_url( 'edit.php?post_type=sa_client' ) ); ?></p>

				<h4><?php self::_e( 'What is next for Sprout Clients?' ); ?></h4>
				<p><?php printf( self::__( "A whole boat load of new features. Consider this current version of Sprout Clients as a beta release. We wanted to get it out there a bit early to get more user feedback before we steer this ship into an untenable product that doesn't help business process." ) ); ?></p>
			</div>
			<div class="last-feature">
				<h4><?php self::_e( 'Need help? Or an important feature?' ); ?></h4>
				<p><?php printf( self::__( "We want to make sure using Sprout Invoices is enjoyable and not a hassle. Sprout Apps has some pretty awesome <a href='%s'>support</a> and a budding <a href='%s'>knowledgebase</a> that will help you get anything resolved." ), self::PLUGIN_URL.'/support/', self::PLUGIN_URL.'/support/knowledgebase/' ); ?></p>

				<p><?php printf( "<a href='https://sproutapps.co/support/' target='_blank' class='button'>%s</a>", self::__( 'Support' ) ); ?>&nbsp;<?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-clients/' target='_blank' class='button'>%s</a>", self::__( 'Documentation' ) ); ?></p>

				<p><img class="footer_sa_logo" src="<?php echo SC_RESOURCES . 'admin/icons/sproutapps.png' ?>" /></p>

			</div>
		</div>

	</div>

</div>

