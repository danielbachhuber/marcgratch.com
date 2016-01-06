<div id="si_dashboard" class="wrap about-wrap">

	<h1><?php printf( __( 'Starts with <a href="%s">Sprout Invoices</a>!', 'sprout-invoices' ), self::PLUGIN_URL, self::SI_VERSION ); ?></h1>

	<div class="about-text"><?php _e( 'Sprout Invoices is the cornerstone for Sprout Apps. We want it to streamline what could be a very complex workflow of accepting estimates and getting invoices paid. Our mission is to make you a happy user; let us hear from you.', 'sprout-invoices' ) ?></div>


	<h2 class="nav-tab-wrapper">
		<?php do_action( 'sprout_settings_tabs' ); ?>
	</h2>

	<div class="welcome_content clearfix">
		<div class="license-overview">
			<div class="activate_message clearfix">
				<div class="activation_msg clearfix">
					 <p>
						<h4><?php _e( 'First Things First...', 'sprout-invoices' ) ?></h4>
						<?php _e( 'An active license for Sprout Invoices provides support and updates. By activating your license, you can get automatic plugin updates from the WordPress dashboard. Updates provide you with the latest bug fixes and the new features each major release brings.', 'sprout-invoices' ) ?></p>
				</div>
				<div class="activation_inputs clearfix">
					<input type="text" name="<?php echo SI_Updates::LICENSE_KEY_OPTION ?>" id="<?php echo SI_Updates::LICENSE_KEY_OPTION ?>" value="<?php echo SI_Updates::license_key() ?>" class="text-input fat-input <?php echo 'license_'.SI_Updates::license_status() ?>" size="40">
					<?php if ( SI_Updates::license_status() != false && SI_Updates::license_status() == 'valid' ) : ?>
						<button id="activate_license" class="button button-large" disabled="disabled"><?php _e( 'Activate License', 'sprout-invoices' ) ?></button> 
						<button id="deactivate_license" class="button button-large"><?php _e( 'Deactivate License', 'sprout-invoices' ) ?></button>
					<?php else : ?>
						<button id="activate_license" class="button button-primary button-large"><?php _e( 'Activate License', 'sprout-invoices' ) ?></button>
					<?php endif ?>
					<div id="license_message" class="clearfix"></div>
				</div>
			</div>


			<h2 class="headline_callout"><?php _e( 'The Sprout Invoices Flow', 'sprout-invoices' ) ?></h2>

			<div class="feature-section col three-col clearfix">
				<div class="col-1">
					<span class="flow_icon icon-handshake"></span>
					<h4><?php _e( 'Lead Generation', 'sprout-invoices' ); ?></h4>
					<p><?php printf( __( "Receiving estimate requests on your site is simplified with Sprout Invoices. <a href='%s'>General Settings</a> has more information on how to add a form to your site as well as settings to integrate with an advanced form plugin, e.g. Gravity Forms or Ninja Forms.", 'sprout-invoices' ), admin_url( 'admin.php?page=sprout-apps/settings' ) ); ?></p>
					<p><?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-invoices/advanced/customize-estimate-submission-form/' target='_blank' class='button'>%s</a>", __( 'Documentation', 'sprout-invoices' ) ); ?></p>
				</div>
				<div class="col-2">
					<span class="flow_icon icon-sproutapps-estimates"></span>
					<h4><?php _e( 'Estimating', 'sprout-invoices' ); ?></h4>
					<p><?php printf( __( "A new <a href='%s'>estimate</a> is automatically created and notifications are sent after every estimate request submission. The <a href='%s'>notification</a> to you will provide a link to this new estimate; allowing you to review, update, and send the estimate to your prospective client without having to communicate via email.", 'sprout-invoices' ), admin_url( 'post-new.php?post_type='.SI_Estimate::POST_TYPE ),  admin_url( 'admin.php?page=sprout-apps/settings&tab=notifications' ) ); ?></p>
					<p><?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-invoices/estimates/' target='_blank' class='button'>%s</a>", __( 'Documentation', 'sprout-invoices' ) ); ?></p>
				</div>
				<div class="col-3 last-feature">
					<span class="flow_icon icon-sproutapps-invoices"></span>
					<h4><?php _e( 'Invoicing', 'sprout-invoices' ); ?></h4>
					<p><?php printf( __( "An <a href='%s'>invoice</a> is automatically created from an accepted estimate. By default these newly created invoices are <em>not</em> sent to the client, instead you  will need to review them before sending. Your <a href='%s'>notifications</a> are meant to be setup to help review, mark, and send them out quickly.", 'sprout-invoices' ), admin_url( 'post-new.php?post_type='.SI_Invoice::POST_TYPE ),  admin_url( 'admin.php?page=sprout-apps/settings&tab=notifications' ) ); ?></p>
					<p><?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-invoices/invoices/' target='_blank' class='button'>%s</a>", __( 'Documentation', 'sprout-invoices' ) ); ?></p>
				</div>
			</div>

		</div>
	</div>

	<hr />

	<div class="welcome_content">
		<h3><?php _e( 'FAQs', 'sprout-invoices' ); ?></h3>

		<div class="feature-section col three-col clearfix">
			<div>
				<h4><?php _e( 'Where do I start?', 'sprout-invoices' ); ?></h4>
				<p>
					<ol>
						<li><?php printf( __( "Start <a href='%s'>importing</a> your data from other services (i.e. WP-Invoice, Freshbooks or Harvest)." ), admin_url( 'admin.php?page=sprout-apps/settings&tab=import' ) ); ?></li>
						<li><?php printf( __( "Even with the defaults set in <a href='%s'>General Settings</a> the 'Company Info' should be filled out first.", 'sprout-invoices' ), admin_url( 'admin.php?page=sprout-apps/settings' ) ); ?></li>
						<li><?php printf( __( "Review your <a href='%s'>Payment Settings</a> and provide different methods of payments for your clients. Don't let your client find out you've configured the payment processor incorrectly&mdash;make sure to test.", 'sprout-invoices' ), admin_url( 'admin.php?page=sprout-apps/settings&tab=payments' ) ); ?></li>
						<li><?php printf( __( "There are a lot of <a href='%s'>notifications</a> sent throughout the entire client acquisition process, make sure they have your personality and represent your brand well. You can send HTML emails, check your <a href='%s'>General Settings</a> for more information.", 'sprout-invoices' ), admin_url( 'admin.php?page=sprout-apps/settings&tab=notifications' ), admin_url( 'admin.php?page=sprout-apps/settings' ) ); ?></li>
						<li><?php _e( 'Grow your business while not forgetting about your loved ones...and the occasional round of golf.', 'sprout-invoices' ) ?></li>
					</ol>
					<p><?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-invoices/sprout-invoices-getting-started/' target='_blank' class='button'>%s</a>", __( 'Documentation', 'sprout-invoices' ) ); ?></p>
				</p>
			</div>
			<div>
				<h4><?php _e( 'How well am I doing?', 'sprout-invoices' ); ?></h4>				
				<p><?php printf( __( "The <a href='%s'>Reports Dashboard</a> should key you on how well your're growing your business. There are reports for the estimates, invoices, payments and clients available for filtering and exporting.", 'sprout-invoices' ), admin_url( 'admin.php?page=sprout-apps/settings&tab=reporting' ) ); ?></p>

				<p><?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-invoices/reports/' target='_blank' class='button'>%s</a>", __( 'Documentation', 'sprout-invoices' ) ); ?></p>


				<h4><?php _e( 'Clients &amp; WordPress Users?', 'sprout-invoices' ); ?></h4>
				<p><?php printf( __( "<a href='%s'>Clients</a> have WordPress users associated with them and clients are not limited to a single user either. This allows for you to have multiple points of contact for a company/client.", 'sprout-invoices' ), admin_url( 'edit.php?post_type=sa_client' ) ); ?></p>

				<p><?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-invoices/clients/' target='_blank' class='button'>%s</a>", __( 'Documentation', 'sprout-invoices' ) ); ?></p>

				<h4><?php _e( 'What are Predefined Line Items?', 'sprout-invoices' ); ?></h4>
				<p><?php printf( __( "Predefined line-tems help with the creation of your estimates and invoices by pre-filling line items. Create some tasks that matter to your business before creating your first estimate or invoice and you'll see how they can save you a lot of time.", 'sprout-invoices' ) ); ?></p>

				<p><?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-invoices/invoices/predefined-tasks-line-items/' target='_blank' class='button'>%s</a>", __( 'Documentation', 'sprout-invoices' ) ); ?></p>
			</div>
			<div class="last-feature">
				<h4><?php _e( 'Can I import from X service or WP-Invoice?', 'sprout-invoices' ); ?></h4>
				<p><?php printf( __( 'Yes! WP-Invoice, Harvest and Freshbooks importers are now available.', 'sprout-invoices' ) ); ?></p>
				<p><?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-invoices/sprout-invoices-getting-started/importing/' target='_blank' class='button'>%s</a>", __( 'Documentation', 'sprout-invoices' ) ); ?></p>

				<h4><?php _e( 'I need help! Where is the support?', 'sprout-invoices' ); ?></h4>
				<p><?php printf( __( "We want to make sure using Sprout Invoices is enjoyable and not a hassle. Sprout Apps has some pretty awesome <a href='%s'>support</a> and a budding <a href='%s'>knowledgebase</a> that will help you get anything resolved.", 'sprout-invoices' ), self::PLUGIN_URL.'/support/', self::PLUGIN_URL.'/support/knowledgebase/' ); ?></p>

				<p><?php printf( "<a href='https://sproutapps.co/support/' target='_blank' class='button'>%s</a>", __( 'Support', 'sprout-invoices' ) ); ?>&nbsp;<?php printf( "<a href='https://sproutapps.co/support/knowledgebase/sprout-invoices/' target='_blank' class='button'>%s</a>", __( 'Documentation', 'sprout-invoices' ) ); ?></p>

				<p><img class="footer_sa_logo" src="<?php echo SI_RESOURCES . 'admin/icons/sproutapps.png' ?>" /></p>

			</div>
		</div>

	</div>

</div>

