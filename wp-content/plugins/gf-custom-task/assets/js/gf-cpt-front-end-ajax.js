( function( $ ) {
    $( document ).bind( 'gform_post_render', function() {

        var self = [{ response: {}}];
        var removed_assignee = '';
        var email_valid_pattern = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;

        $("#input_5_2").chosen().on('change',function( clickedProject ){

            if ( $(clickedProject.target).val() == '-- select a Project --'){
                return false;
            }

            var id = self.getProjectID( clickedProject );
            self.requestProjectData( id );

            $.when( self.response ).done(function( response ){
                self.response = JSON.parse(response);
                self.updateAssignees(self.response.clients);
                self.updateCCusers(self.response.clients);
                self.updateTasks( self.response.tasks );
                self.updateEstimates( self.response.estimates )
                self.updateInvoices( self.response.invoices )
            });
            $.when( self.response ).fail( function( response ){
                    console.log('failed: ' + response);
            });
        });

        $("#input_5_8").chosen().on('change',function( clickedAssignee ){
            var assigneedID = self.assigneeID( clickedAssignee );
            self.removeAssignee( assigneedID );
        });

        $("#input_5_9_chosen input").on('click',function(){
            self.addCCuser = function( event, input ){

                var newOption = $(input).val(),
                    origInput = $(input).parents("#input_5_9_chosen").prev('select'),
                    origInputVal = $(origInput).val(),
                    origInputOpts = $(origInput).find('option'),
                    origInputOptsVals = $(origInputOpts).map(function(){
                        return $(this).val();
                    }).get();

                if (email_valid_pattern.test(newOption)){
                    event.preventDefault();
                    if ( $.inArray( newOption, origInputOptsVals ) !== -1 ){
                        newOption = '';
                    }
                    if (origInputVal){
                        origInputVal.push(newOption);
                    } else {
                        origInputVal = [newOption];
                    }
                    $(origInput)
                        .append('<option value="'+newOption+'">'+newOption+'</option>')
                        .val(origInputVal)
                        .trigger("chosen:updated");
                    newOption = '';
                }
                return false;
            };
            $(this).keydown( function( event ){
                var input = this;
                if (event.keyCode == 9 || event.keyCode == 188){
                    self.addCCuser( event, input );
                }
            });
        });

        $('#input_5_3').on('change', function(){
            var issue_type = $(this).val();
            self.updateAdjustment( issue_type );
        });

        self.getProjectID = function( clickedProject ){
            return $(clickedProject.target).val();
        };

        self.assigneeID = function( clickedAssignee ){
            return $(clickedAssignee.target).val();
        };

        self.requestProjectData = function( id ){

            var data = {
                action: 'mg_process_ajax_on_project_change',
                nonce: gfCPTask.nonce,
                post_id: id
            };

            self.response = $.post( gfCPTask.ajaxurl, data );
        };

        self.updateAssignees = function( assignees ){
            var assignee_select = $('select#input_5_8');
            assignee_select.empty();
            $.each( assignees, function(){
                $("<option value='"+this.user_id+"'>"+this.display_name+"</option>").appendTo(assignee_select);
            });
            $("#input_5_8").trigger("chosen:updated")
        };

        self.updateCCusers = function( assignees ){
            var assignee_select = $('select#input_5_9');
            assignee_select.empty();
            $.each( assignees, function(){
                $("<option value='"+this.user_id+"'>"+this.display_name+"</option>").appendTo(assignee_select);
            });
            $("#input_5_8").trigger("chosen:updated")
        };

        self.removeAssignee = function( assigneedID ){
            var assignee_select = $('select#input_5_9');

            if (removed_assignee !== '' || removed_assignee !== 'undefined'){
                $(assignee_select).append(removed_assignee);
            }
            removed_assignee = $( assignee_select ).find("option[value='"+assigneedID+"']").remove();
            $("#input_5_9").trigger("chosen:updated")
        };

        self.updateTasks = function( tasks ){
            var tasks_select = $('select#input_5_11');
            tasks_select.empty();
            $.each( tasks, function(){
                $( '<option value="'+this.task_id+'">'+this.task_title+'</option>').appendTo(tasks_select);
            });
            $("#input_5_11").trigger("chosen:updated")
        };

        self.updateEstimates = function( estimates ){
            var estimates_select = $('select#input_5_14');
            estimates_select.empty();
            $.each( estimates, function(){
                $( '<option value="'+this.est_id+'">'+this.est_title+'</option>').appendTo(estimates_select);
            });
            $("#input_5_14").trigger("chosen:updated")
        };

        self.updateInvoices = function( invoices ){
            var invoices_select = $('select#input_5_13');
            invoices_select.empty();
            $.each( invoices, function(){
                $( '<option value="'+this.inv_id+'">'+this.inv_title+'</option>').appendTo(invoices_select);
            });
            $("#input_5_13").trigger("chosen:updated")
        };

        self.updateAdjustment = function( issue_type ){
            if (issue_type === 'Bug'){
                $('#input_5_17').val('100');
            }
        };

    });

} )( jQuery );

