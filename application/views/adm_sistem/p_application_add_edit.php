<script src="<?php echo BS_JS_PATH; ?>fuelux/fuelux.spinner.js"></script>
<div class="row" style="display:none;" id="application_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="application_form_title"> Add/Edit Application </div>
		</div>
        <form class="form-horizontal" application="form" id="application_form">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Code *</label>
                <div class="col-sm-9">
                    <input id="form_p_application_id" type="text" style="display:none;" placeholder="Role ID">
                    <input id="form_code" class="col-xs-10 col-sm-5 required" type="text">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Listing Number </label>
                <div class="col-sm-9">
                    <input id="form_listing_no" class="col-xs-10 col-sm-3" type="text">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Description </label>
                <div class="col-sm-9">
                    <textarea id="form_description" class="col-xs-10 col-sm-5" type="text"> </textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Status *</label>
                <div class="col-sm-9">
                    <select id="form_is_active" class="col-xs-10 col-sm-5 required">
            		    <option value="Y">ENABLE</option>
                        <option value="N">DISABLE</option>
            	    </select>
                </div>
            </div>
                
           
            
            <?php
			    $ci =& get_instance();
	            $user_name = $ci->session->userdata('user_name');
			?>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Created By </label>
                <div class="col-sm-9">
                    <input id="form_created_by" disabled type="text" value="<?php echo $user_name; ?>">
                    &nbsp;  <input id="form_creation_date" disabled type="text" value="<?php echo date("Y-m-d"); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Updated By </label>
                <div class="col-sm-9">
                    <input id="form_updated_by" disabled type="text" value="<?php echo $user_name; ?>">
                    &nbsp; <input id="form_updated_date" disabled type="text" value="<?php echo date("Y-m-d"); ?>">
                </div>
            </div>

           <div class="space-4"></div>

           <div class="clearfix form-actions">
		        <div class="center col-md-9">
			      	<button type="button" class="btn btn-primary btn-round" id="application_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>
                    
                    <button type="reset" class="btn btn-danger btn-round" id="application_form_btn_cancel">
                        <i class="glyphicon glyphicon-circle-arrow-left bigger-120"></i>
                        Cancel
                    </button>
			      	
			    </div>
		   </div>
       </form>
    </div>
</div>


<script>
    jQuery(function($) {
        $("#application_form_btn_cancel").on(ace.click_event, function() {
            application_toggle_main_content();
        });
    
        $("#application_form_btn_save").on(ace.click_event, function() {
            application_save();
        });
        
        
        $("#form_listing_no").ace_spinner({
                    min: 0,
                    max: 20,
                   step: 1,
                btn_up_class:'btn-success' , 
                btn_down_class:'btn-success'
        });
        
    });

    function application_toggle_main_content() {
        $("#application_form")[0].reset();
        
        $("#application_form_add_edit").hide();
        $("#application_row_content").toggle("slow");
    }

    function application_show_form_add() {
        application_toggle_main_content();
        $("#application_form_add_edit").show("slow");
        $("#application_form_title").html("Add Module");
    }

    function application_show_form_edit(theID) {
        application_toggle_main_content();
        $("#application_form_add_edit").show("slow");
        $("#application_form_title").html("Edit Module");
        
        $("#form_p_application_id").val(theID);
        $.post( "<?php echo WS_URL.'adm_sistem.p_application_controller/read'; ?>",
            {
                p_application_id : $("#form_p_application_id").val(),
                rowCount : -1
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        var obj = response.items[0];
        	        
        	        $("#form_p_application_id").val(obj.p_application_id);
        	        $("#form_code").val(obj.code);
        	        $("#form_listing_no").ace_spinner('value', obj.listing_no);
        	        $("#form_is_active").val(obj.is_active);
        	        $("#form_description").val(obj.description);
        	       
        	        
        	        $("#form_created_by").val(obj.created_by);
        	        $("#form_creation_date").val(obj.creation_date);
        	        $("#form_updated_by").val(obj.updated_by);
        	        $("#form_updated_date").val(obj.updated_date);
        	        
                }
            }
        );
        
    }

    function application_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_application_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'adm_sistem.p_application_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_application_id    : $("#form_p_application_id").val(),
                    code                : $("#form_code").val(),
                    listing_no          : $("#form_listing_no").val(),
                    is_active           : $("#form_is_active").val(),
                    description         : $("#form_description").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        loadContent('adm_sistem-p_application');
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );
    }
</script>