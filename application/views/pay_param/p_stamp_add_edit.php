<div class="row" style="display:none;" id="stamp_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="stamp_form_title"> Add/Edit Stamp </div>
		</div>
        <form class="form-horizontal" role="form" id="stamp_form">
            <input id="form_p_stamp_id" type="text" style="display:none;" placeholder="Stamp ID">
            
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Valid From *</label>
                <div class="col-sm-3">
                    <div class="input-group col-xs-12">
                        <input type="text" data-date-format="yyyy-mm-dd" id="form_valid_from" class="form-control required date-picker">
                        <span class="input-group-addon">
        					<i class="fa fa-calendar bigger-110"></i>
    					</span>
					</div>    					
                </div>

                <label class="col-sm-1 control-label no-padding-right"> To </label>
                <div class="col-sm-3">
                    <div class="input-group col-xs-12">
                        <input type="text" data-date-format="yyyy-mm-dd" id="form_valid_to" class="form-control date-picker">
                        <span class="input-group-addon">
        					<i class="fa fa-calendar bigger-110"></i>
    					</span>
					</div>   
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Low Limit Amount *</label>
                <div class="col-sm-7">
                    <input type="text" id="form_amt_low_limit" class="col-xs-10 col-sm-5 required priceformat align-right">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Up Limit Amount *</label>
                <div class="col-sm-7">
                    <input type="text" id="form_amt_up_limit" class="col-xs-10 col-sm-5 required priceformat align-right">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Stamp Amount *</label>
                <div class="col-sm-7">
                    <input type="text" id="form_stamp_amount" class="col-xs-10 col-sm-5 required priceformat align-right">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Description </label>
                <div class="col-sm-7">
                    <textarea id="form_description" class="col-xs-10 col-sm-5" type="text"></textarea>
                </div>
            </div>

            <?php
			    $ci =& get_instance();
	            $user_name = $ci->session->userdata('user_name');
			?>
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Created By </label>
                <div class="col-sm-9">
                    <input id="form_create_by" disabled type="text" value="<?php echo $user_name; ?>">
                    &nbsp;  <input id="form_create_date" disabled type="text" value="<?php echo date("Y-m-d"); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Updated By </label>
                <div class="col-sm-9">
                    <input id="form_update_by" disabled type="text" value="<?php echo $user_name; ?>">
                    &nbsp; <input id="form_update_date" disabled type="text" value="<?php echo date("Y-m-d"); ?>">
                </div>
            </div>

           <div class="space-4"></div>

           <div class="clearfix form-actions">
		        <div class="center col-md-9">
			      	<button type="button" class="btn btn-primary btn-round" id="stamp_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>
                    
                    <button type="reset" class="btn btn-danger btn-round" id="stamp_form_btn_cancel">
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
        $(".priceformat").number( true, 2 , '.',','); /* price number format */
        
        $("#form_valid_from").datepicker({ autoclose: true, todayHighlight: true });
        $("#form_valid_to").datepicker({ autoclose: true, todayHighlight: true });
        
        $("#stamp_form_btn_cancel").on(ace.click_event, function() {
            stamp_toggle_main_content();
        });
    
        $("#stamp_form_btn_save").on(ace.click_event, function() {
            stamp_save();
        });
    });

    function stamp_toggle_main_content() {
        
        $("#stamp_form")[0].reset();
        //reset date
        $("#form_valid_from").datepicker("update", "");
        $("#form_valid_to").datepicker("update", "");
        
        $("#stamp_form_add_edit").hide();
        $("#stamp_row_content").toggle("slow");
        
    }

    function stamp_show_form_add() {
        stamp_toggle_main_content();
        $("#stamp_form_add_edit").show("slow");
        $("#stamp_form_title").html("Add Stamp " + " : " + $("#form_p_stamp_group_code").val());
    }

    function stamp_show_form_edit(theID) {
        stamp_toggle_main_content();
        $("#stamp_form_add_edit").show("slow");
        $("#stamp_form_title").html("Edit Stamp" + " : " + $("#form_p_stamp_group_code").val());
        
        $("#form_p_stamp_id").val(theID);
        $.post( "<?php echo WS_URL.'pay_param.p_stamp_controller/read'; ?>",
            {
                p_stamp_id : $("#form_p_stamp_id").val(),
                rowCount : -1
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        var obj = response.items[0];
        	        
        	        $("#form_p_stamp_id").val(obj.p_stamp_id);
        	        
        	        $("#form_valid_from").datepicker("update", obj.valid_from);
        	        $("#form_valid_to").datepicker("update", obj.valid_to);
        	        $("#form_amt_low_limit").val(obj.amt_low_limit);
        	        $("#form_amt_up_limit").val(obj.amt_up_limit);
        	        $("#form_stamp_amount").val(obj.stamp_amount);
        	        
        	        $("#form_description").val(obj.description);
        	        
        	        $("#form_create_by").val(obj.create_by);
        	        $("#form_create_date").val(obj.create_date);
        	        $("#form_update_by").val(obj.update_by);
        	        $("#form_update_date").val(obj.update_date);
        	        
                }
            }
        );
        
    }

    function stamp_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_stamp_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'pay_param.p_stamp_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_stamp_id          : $("#form_p_stamp_id").val(),
                    p_stamp_group_id    : $("#form_p_stamp_group_id").val(),
                    valid_from          : $("#form_valid_from").val(),
                    valid_to            : $("#form_valid_to").val(),
                    amt_low_limit       : $("#form_amt_low_limit").val(),
                    amt_up_limit        : $("#form_amt_up_limit").val(),
                    stamp_amount        : $("#form_stamp_amount").val(),
                    description         : $("#form_description").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        loadContentWithParams('pay_param-p_stamp.php', 
                        {
                         p_stamp_group_id   : $("#form_p_stamp_group_id").val(), 
                         p_stamp_group_code : $("#form_p_stamp_group_code").val()
                        }
                    );
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );

    }
</script>