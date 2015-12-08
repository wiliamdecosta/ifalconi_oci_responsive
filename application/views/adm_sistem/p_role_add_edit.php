<div class="row" style="display:none;" id="role_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="role_form_title"> Add/Edit Role </div>
		</div>
        <form class="form-horizontal" role="form" id="role_form">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Code *</label>
                <div class="col-sm-9">
                    <input id="form_p_role_id" type="text" style="display:none;" placeholder="Role ID">
                    <input id="form_code" class="col-xs-10 col-sm-5 required" type="text">
                </div>
            </div>
            
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Status *</label>
                <div class="col-sm-9">
                    <select id="form_is_active" class="col-xs-10 col-sm-5 required">
            		    <option value="Y">ACTIVE</option>
                        <option value="N">NOT ACTIVE</option>
            	    </select>
                </div>
            </div>
                
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Description </label>
                <div class="col-sm-9">
                    <textarea id="form_description" class="col-xs-10 col-sm-5" type="text"> </textarea>
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
			      	<button type="button" class="btn btn-primary btn-round" id="role_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>
                    
                    <button type="reset" class="btn btn-danger btn-round" id="role_form_btn_cancel">
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
        $("#role_form_btn_cancel").on(ace.click_event, function() {
            role_toggle_main_content();
        });
    
        $("#role_form_btn_save").on(ace.click_event, function() {
            role_save();
        });
    });

    function role_toggle_main_content() {
        $("#role_form")[0].reset();
        
        $("#role_form_add_edit").hide();
        $("#role_row_content").toggle("slow");
    }

    function role_show_form_add() {
        role_toggle_main_content();
        $("#role_form_add_edit").show("slow");
        $("#role_form_title").html("Add Role");
    }

    function role_show_form_edit(theID) {
        role_toggle_main_content();
        $("#role_form_add_edit").show("slow");
        $("#role_form_title").html("Edit Role");
        
        $("#form_p_role_id").val(theID);
        $.post( "<?php echo WS_URL.'adm_sistem.p_role_controller/read'; ?>",
            {
                p_role_id : $("#form_p_role_id").val(),
                rowCount : -1
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        var obj = response.items[0];
        	        
        	        $("#form_p_role_id").val(obj.p_role_id);
        	        $("#form_code").val(obj.code);
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

    function role_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_role_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'adm_sistem.p_role_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_role_id           : $("#form_p_role_id").val(),
                    code                : $("#form_code").val(),
                    is_active           : $("#form_is_active").val(),
                    description         : $("#form_description").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        loadContent('adm_sistem-p_role');
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );
    }
</script>