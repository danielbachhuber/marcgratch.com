
/**
 * GP Reload Form Front-end JS
 */

window.GPReloadForm;

( function( $ ) {

    gwrf = GPReloadForm = function( args ) {

        var self = this;

        self.formId         = args.formId;
        self.spinnerUrl     = args.spinnerUrl;
        self.refreshTime    = args.refreshTime;
        self.refreshTimeout = null;

        // if we've already done the init for this form, don't do it again on subsequent gform_post_render calls
        if( window[ 'gwrf_' + args.formId ] ) {
            return window[ 'gwrf_' + args.formId ];
        }

        self.formWrapper = $( '#gform_wrapper_' + self.formId );
        self.staticElem  = self.formWrapper.parent();

        var clonedElem = $( '<div>' ).append( self.formWrapper.clone() );
        clonedElem.find( '.ginput_counter' ).remove();

        self.formHtml = clonedElem.html();//.replace( /gform_post_render/g, 'XYZ' ); //$( '<div />' ).append( self.formWrapper.clone() ).html();
        self.spinnerInitialized = false;

        if( self.staticElem.data( 'gwrf' ) ) {
            return self.staticElem.data( 'gwrf' );
        }

        self.init = function() {

            $( document ).bind( 'gform_confirmation_loaded', function( event, formId ) {

                if( formId != self.formId || self.refreshTime <= 0 || self.staticElem.find( '.form_saved_message' ).length > 0 ) {
                    return;
                }

                self.refreshTimeout = setTimeout( function() {
                    self.reloadForm();
                }, self.refreshTime * 1000 );

            } );

            self.staticElem.on( 'click', 'a.gws-reload-form', function( event ) {
                event.preventDefault();
                self.reloadForm();
            } );

            self.staticElem.data( 'gwrf', self );

        };

        self.reloadForm = function() {

            if( self.refreshTimeout ) {
                clearTimeout( self.refreshTimeout );
            }

            self.staticElem.find( '.gform_confirmation_message_' + self.formId ).replaceWith( self.formHtml );

            window[ 'gf_submitting_' + self.formId ] = false;

            $( document ).trigger( 'gform_post_render', [ self.formId, 0 ] );

            if( window['gformInitDatepicker'] ) {
                gformInitDatepicker();
            }

        };

        self.initSpinner = function(){

            //self.staticElem.on( 'submit', '#gform_' + self.formId, function() {
            //    $( '#gform_submit_button_' + self.formId ).attr( 'disabled', true ).after( '<' + 'img id=\"gform_ajax_spinner_' + self.formId + '\"  class=\"gform_ajax_spinner\" src=\"' + self.spinnerUrl + '\" />' );
            //    self.formWrapper.find( '.gform_previous_button' ).attr( 'disabled', true );
            //    self.formWrapper.find( '.gform_next_button' ).attr( 'disabled', true ).after( '<' + 'img id=\"gform_ajax_spinner_' + self.formId + '\"  class=\"gform_ajax_spinner\" src=\"' + self.spinnerUrl + '\" alt=\"\" />' );
            //});

        };

        self.init();

    };

} )( jQuery );