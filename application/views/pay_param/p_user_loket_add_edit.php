<div class="row" style="display:none;" id="user_loket_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="user_loket_form_title"> Add/Edit Counter User</div>
		</div>
        <form class="form-horizontal" role="form" id="user_loket_form">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> User Name *</label>
                <div class="col-sm-9">
                    <input id="form_p_user_loket_id" type="text" style="display:none;" placeholder="ID User Loket">
                    <input id="form_user_name" class="col-xs-10 col-sm-5 required" type="text">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Password *</label>
                <div class="col-sm-9">
                    <input id="form_user_pwd" class="col-xs-10 col-sm-5 required" type="password">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Full Name </label>
                <div class="col-sm-9">
                    <input id="form_full_name" class="col-xs-10 col-sm-5" type="text">
                </div>
            </div>
            
             <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Description </label>
                <div class="col-sm-9">
                    <textarea id="form_description" class="col-xs-10 col-sm-5" type="text"></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Status *</label>
                <div class="col-sm-9">
                	<select id="form_status" class="col-xs-10 col-sm-5 required">
                		<option value="1">ACTIVE</option>
                		<option value="0">NOT ACTIVE</option>
                	</select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> User Level </label>
                <div class="col-sm-9">
                	<select id="form_user_level" class="col-xs-10 col-sm-5">
                		<option value="A">COUNTER ADMIN</option>
                		<option value="U">COUNTER USER</option>
                	</select>
                </div>
            </div>
                       
                       
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Expired Password</label>
                <div class="col-sm-8">
                    <div class="input-group col-xs-8 col-sm-5">
                        <input type="text" data-date-format="yyyy-mm-dd" id="form_exp_pass" class="form-control date-picker">
                        <span class="input-group-addon">
        					<i class="fa fa-calendar bigger-110"></i>
    					</span>
					</div>
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
			      	<button type="button" class="btn btn-primary btn-round" id="user_loket_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>
                    
                    <button type="reset" class="btn btn-danger btn-round" id="user_loket_form_btn_cancel">
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
        
        $("#form_exp_pass").datepicker({ autoclose: true, todayHighlight: true });
        
        $("#user_loket_form_btn_cancel").on(ace.click_event, function() {
            user_loket_toggle_main_content();
        });
    
        $("#user_loket_form_btn_save").on(ace.click_event, function() {
            user_loket_save();
        });
    });

    function user_loket_toggle_main_content() {

        $("#user_loket_form")[0].reset();
                
        //reset date
        $("#form_exp_pass").datepicker("update", "");
        
        $("#user_loket_form_add_edit").hide();
        $("#user_loket_row_content").toggle("slow");
        
    }

    function user_loket_show_form_add() {
        user_loket_toggle_main_content();
        $("#user_loket_form_add_edit").show("slow");
        $("#user_loket_form_title").html("Add Counter User" + " : " + $("#form_p_bank_branch_code").val());
    }

    function user_loket_show_form_edit(theID) {
        user_loket_toggle_main_content();
        $("#user_loket_form_add_edit").show("slow");
        $("#user_loket_form_title").html("Edit Counter User" + " : " + $("#form_p_bank_branch_code").val());
        
        $("#form_p_user_loket_id").val(theID);
        $.post( "<?php echo WS_URL.'pay_param.p_user_loket_controller/read'; ?>",
            {
                p_user_loket_id : $("#form_p_user_loket_id").val(),
                rowCount : -1
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        var obj = response.items[0];
        	        
        	        $("#form_p_user_loket_id").val(obj.p_user_loket_id);
        	        $("#form_p_bank_branch_id").val(obj.p_bank_branch_id);
        	        $("#form_user_name").val(obj.user_name);
        	        
        	        $("#form_full_name").val(obj.full_name);
        	        $("#form_description").val(obj.description);        	        
        	        $("#form_status").val(obj.user_loket_status);
        	        $("#form_user_level").val(obj.user_level);
        	        $("#form_exp_pass").datepicker("update", obj.exp_pass);
        	                	        
        	        $("#form_create_by").val(obj.create_by);
        	        $("#form_create_date").val(obj.create_date);
        	        $("#form_update_by").val(obj.update_by);
        	        $("#form_update_date").val(obj.update_date);        	        
                }
            }
        );
        
    }

    function user_loket_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_user_loket_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'pay_param.p_user_loket_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_user_loket_id     : $("#form_p_user_loket_id").val(),
                    p_bank_branch_id    : $("#form_p_bank_branch_id").val(),
                    user_name           : $("#form_user_name").val(),
        	        user_pwd            : $("#form_user_pwd").val(),
        	        full_name           : $("#form_full_name").val(),
        	        description         : $("#form_description").val(),   	        
        	        status              : $("#form_status").val(),
        	        user_level          : $("#form_user_level").val(),
        	        exp_pass            : $("#form_exp_pass").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        loadContentWithParams('pay_param-p_user_loket.php', 
                        {
                         p_bank_branch_id : $("#form_p_bank_branch_id").val(),
                         p_bank_branch_code : $("#form_p_bank_branch_code").val(),   
                         p_bank_id: $("#form_p_bank_id").val(), 
                         p_bank_code: $("#form_p_bank_code").val()
                        }
                    );
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );

    }
</script>