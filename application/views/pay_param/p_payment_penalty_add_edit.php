<div class="row" style="display:none;" id="payment_penalty_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="payment_penalty_form_title"> Add/Edit Payment Penalty </div>
		</div>
        <form class="form-horizontal" role="form" id="payment_penalty_form">
            <input id="form_p_payment_penalty_id" type="text" style="display:none;" placeholder="Payment Penalty ID">
            
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
                <label class="col-sm-2 control-label no-padding-right"> Day Low Limit *</label>
                <div class="col-sm-2">
                    <input type="text" id="form_day_low_limit" class="col-xs-12 required numberformat align-right"> 					
                </div>

                <label class="col-sm-1 control-label no-padding-right"> Day Up Limit * </label>
                <div class="col-sm-2">
                    <input type="text" id="form_day_up_limit" class="col-xs-12 required numberformat align-right">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Penalty Amount *</label>
                <div class="col-sm-2">
                    <input type="text" id="form_penalty_amount" class="col-xs-12 required priceformat align-right"> 					
                </div>

                <label class="col-sm-1 control-label no-padding-right"> Penalty Pct * </label>
                <div class="col-sm-2">
                    <input type="text" id="form_penalty_pct" class="col-xs-12 required priceformat align-right">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Month Late *</label>
                <div class="col-sm-7">
                    <input type="text" id="form_month_late" class="col-sm-2 required numberformat align-right">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right"> Added Amount *</label>
                <div class="col-sm-7">
                    <input type="text" id="form_added_amount" class="col-sm-3 required priceformat align-right">
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
			      	<button type="button" class="btn btn-primary btn-round" id="payment_penalty_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>
                    
                    <button type="reset" class="btn btn-danger btn-round" id="payment_penalty_form_btn_cancel">
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
        $(".numberformat").number( true, 0 , '.',','); /* number format */
        
        $("#form_valid_from").datepicker({ autoclose: true, todayHighlight: true });
        $("#form_valid_to").datepicker({ autoclose: true, todayHighlight: true });
        
        $("#payment_penalty_form_btn_cancel").on(ace.click_event, function() {
            payment_penalty_toggle_main_content();
        });
    
        $("#payment_penalty_form_btn_save").on(ace.click_event, function() {
            payment_penalty_save();
        });
    });

    function payment_penalty_toggle_main_content() {
        
        $("#payment_penalty_form")[0].reset();
        //reset date
        $("#form_valid_from").datepicker("update", "");
        $("#form_valid_to").datepicker("update", "");
        
        $("#payment_penalty_form_add_edit").hide();
        $("#payment_penalty_row_content").toggle("slow");
        
    }

    function payment_penalty_show_form_add() {
        payment_penalty_toggle_main_content();
        $("#payment_penalty_form_add_edit").show("slow");
        $("#payment_penalty_form_title").html("Add Payment Penalty" + " : " + $("#form_p_penalty_group_code").val());
    }

    function payment_penalty_show_form_edit(theID) {
        payment_penalty_toggle_main_content();
        $("#payment_penalty_form_add_edit").show("slow");
        $("#payment_penalty_form_title").html("Edit Payment Penalty" + " : " + $("#form_p_penalty_group_code").val());
        
        $("#form_p_payment_penalty_id").val(theID);
        $.post( "<?php echo WS_URL.'pay_param.p_payment_penalty_controller/read'; ?>",
            {
                p_payment_penalty_id : $("#form_p_payment_penalty_id").val(),
                rowCount : -1
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        var obj = response.items[0];
        	        
        	        $("#form_p_payment_penalty_id").val(obj.p_payment_penalty_id);
        	                	        
        	        $("#form_month_late").val(obj.month_late);
        	        $("#form_day_low_limit").val(obj.day_low_limit);
        	        $("#form_day_up_limit").val(obj.day_up_limit);
        	        $("#form_penalty_amount").val(obj.penalty_amount);
        	        $("#form_penalty_pct").val(obj.penalty_pct);
        	        $("#form_added_amount").val(obj.added_amount);
        	        $("#form_valid_from").datepicker("update", obj.valid_from);
        	        $("#form_valid_to").datepicker("update", obj.valid_to);
        	        $("#form_description").val(obj.description);
        	        
        	        $("#form_create_by").val(obj.create_by);
        	        $("#form_create_date").val(obj.create_date);
        	        $("#form_update_by").val(obj.update_by);
        	        $("#form_update_date").val(obj.update_date);
        	        
                }
            }
        );
        
    }

    function payment_penalty_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_payment_penalty_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'pay_param.p_payment_penalty_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_payment_penalty_id    : $("#form_p_payment_penalty_id").val(),
                    p_penalty_group_id      : $("#form_p_penalty_group_id").val(),
                    
                    month_late              : $("#form_month_late").val(),
                    day_low_limit           : $("#form_day_low_limit").val(),
                    day_up_limit            : $("#form_day_up_limit").val(),
                    penalty_amount          : $("#form_penalty_amount").val(),
                    penalty_pct             : $("#form_penalty_pct").val(),
                    added_amount            : $("#form_added_amount").val(),
                    valid_from              : $("#form_valid_from").val(),
                    valid_to                : $("#form_valid_to").val(),
                    description             : $("#form_description").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        loadContentWithParams('pay_param-p_payment_penalty.php', 
                        {
                         p_penalty_group_id   : $("#form_p_penalty_group_id").val(), 
                         p_penalty_group_code : $("#form_p_penalty_group_code").val()
                        }
                    );
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );

    }
</script>