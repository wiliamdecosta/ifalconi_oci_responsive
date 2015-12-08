<div class="row" style="display:none;" id="area_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="area_form_title"> Add/Edit Area </div>
		</div>
        <form class="form-horizontal" role="form" id="area_form">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Code *</label>
                <div class="col-sm-9">
                    <input id="form_p_area_id" type="text" style="display:none;" placeholder="Area ID">
                    <input id="form_code" class="col-xs-10 col-sm-5 required" type="text">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Description </label>
                <div class="col-sm-9">
                    <textarea id="form_description" class="col-xs-10 col-sm-5" type="text"></textarea>
                </div>
            </div>

            <?php
			    $ci =& get_instance();
	            $user_name = $ci->session->userdata('user_name');
			?>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Created By </label>
                <div class="col-sm-9">
                    <input id="form_create_by" disabled type="text" value="<?php echo $user_name; ?>">
                    &nbsp;  <input id="form_create_date" disabled type="text" value="<?php echo date("Y-m-d"); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Updated By </label>
                <div class="col-sm-9">
                    <input id="form_update_by" disabled type="text" value="<?php echo $user_name; ?>">
                    &nbsp; <input id="form_update_date" disabled type="text" value="<?php echo date("Y-m-d"); ?>">
                </div>
            </div>

           <div class="space-4"></div>

           <div class="clearfix form-actions">
		        <div class="center col-md-9">
			      	<button type="button" class="btn btn-primary btn-round" id="area_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>
                    
                    <button type="reset" class="btn btn-danger btn-round" id="area_form_btn_cancel">
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
        $("#area_form_btn_cancel").on(ace.click_event, function() {
            area_toggle_main_content();
        });
    
        $("#area_form_btn_save").on(ace.click_event, function() {
            area_save();
        });
    });

    function area_toggle_main_content() {
        $("#area_form")[0].reset();
        
        $("#area_form_add_edit").hide();
        $("#area_row_content").toggle("slow");
    }

    function area_show_form_add() {
        area_toggle_main_content();
        $("#area_form_add_edit").show("slow");
        $("#area_form_title").html("Add Area");
    }

    function area_show_form_edit(theID) {
        area_toggle_main_content();
        $("#area_form_add_edit").show("slow");
        $("#area_form_title").html("Edit Area");
        
        $("#form_p_area_id").val(theID);
        $.post( "<?php echo WS_URL.'pay_param.p_area_controller/read'; ?>",
            {
                p_area_id : $("#form_p_area_id").val(),
                rowCount : -1
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        var obj = response.items[0];
        	        
        	        $("#form_p_area_id").val(obj.p_area_id);
        	        $("#form_code").val(obj.code);
        	        $("#form_description").val(obj.description);
        	        
        	        $("#form_create_by").val(obj.create_by);
        	        $("#form_create_date").val(obj.create_date);
        	        $("#form_update_by").val(obj.update_by);
        	        $("#form_update_date").val(obj.update_date);
        	        
                }
            }
        );
        
    }

    function area_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_area_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'pay_param.p_area_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_area_id           : $("#form_p_area_id").val(),
                    code                : $("#form_code").val(),
                    description         : $("#form_description").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        loadContent('pay_param-p_area');
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );
    }
</script>