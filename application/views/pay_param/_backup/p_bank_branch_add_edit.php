<div class="row" style="display:none;" id="bank_branch_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="bank_branch_form_title"> Add/Edit Bank Branch </div>
		</div>
        <form class="form-horizontal" role="form" id="bank_branch_form">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Code *</label>
                <div class="col-sm-9">
                    <input id="form_p_bank_branch_id" type="hidden" placeholder="Bank Branch ID">
                    <input id="form_code" class="col-xs-10 col-sm-5 required" type="text">
                </div>
            </div>

           <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Bank *</label>
                <div class="col-sm-8">
                    <input id="form_p_bank_id" type="hidden" placeholder="Bank ID">
                    <input id="form_bank_code" class="col-xs-8 col-sm-5 required" type="text" placeholder="Choose Bank">
                    <span class="input-group-btn">
						<button class="btn btn-success btn-sm" type="button" id="btn_lov_bank">
							<span class="ace-icon fa fa-pencil-square-o icon-on-right bigger-110"></span>
						</button>
					</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Area *</label>
                <div class="col-sm-8">
                    <input id="form_p_area_id" type="hidden" placeholder="Area ID">
                    <input id="form_bank_area_code" class="col-xs-8 col-sm-5 required" type="text" placeholder="Choose Area">
                    <span class="input-group-btn">
						<button class="btn btn-success btn-sm" type="button" id="btn_lov_area">
							<span class="ace-icon fa fa-pencil-square-o icon-on-right bigger-110"></span>
						</button>
					</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Address </label>
                <div class="col-sm-9">
                    <textarea id="form_address" class="col-xs-10 col-sm-5" type="text"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Counter Number * </label>
                <div class="col-sm-9">
                    <input id="form_loket_no" class="col-xs-10 col-sm-5 required" type="text">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Counter Type </label>
                <div class="col-sm-9">
                	<select id="form_loket_type" class="col-xs-10 col-sm-5">
                		<option value="">-- Select Counter Type -- </option>
                		<option value="1">H2H</option>
                		<option value="2">P2H</option>
                		<option value="3">Web</option>
                	</select>
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
		        <div class="col-md-offset-3 col-md-9">
			      	<button type="button" class="btn btn-primary btn-round" id="bank_branch_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>
                    
                    <button type="reset" class="btn btn-danger btn-round" id="bank_branch_form_btn_cancel">
                        <i class="glyphicon glyphicon-circle-arrow-left bigger-120"></i>
                        Cancel
                    </button>
			      	
			    </div>
		   </div>
       </form>
    </div>
</div>

<?php $this->load->view('pay_lov/lov_p_bank.php'); ?>
<?php $this->load->view('pay_lov/lov_p_area.php'); ?>

<script>

    $("#bank_branch_form_btn_cancel").on(ace.click_event, function() {
        bank_branch_toggle_main_content();
    });

    $("#bank_branch_form_btn_save").on(ace.click_event, function() {
        bank_branch_save();
    });

    $("#btn_lov_bank").on(ace.click_event, function() {
        modal_lov_bank_show("form_p_bank_id","form_bank_code");
    });

    $("#btn_lov_area").on(ace.click_event, function() {
        modal_lov_area_show("form_p_area_id","form_bank_area_code");
    });

    function bank_branch_toggle_main_content() {

        $("#bank_branch_form")[0].reset();
        $("#bank_branch_form_add_edit").hide();
        $("#bank_branch_row_content").toggle("slow");
        
    }

    function bank_branch_show_form_add() {
        bank_branch_toggle_main_content();
        $("#bank_branch_form_add_edit").show("slow");
        $("#bank_branch_form_title").html("Add Bank Branch");
    }

    function bank_branch_show_form_edit(theID) {
        bank_branch_toggle_main_content();
        $("#bank_branch_form_add_edit").show("slow");
        $("#bank_branch_form_title").html("Edit Bank Branch");
        
        $("#form_p_bank_branch_id").val(theID);
        $.post( "<?php echo WS_URL.'p_bank_branch_controller/read'; ?>",
            {
                p_bank_branch_id : $("#form_p_bank_branch_id").val()
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        var obj = response.items[0];
        	        
        	        $("#form_code").val(obj.code);
        	        
        	        $("#form_p_bank_id").val(obj.p_bank_id);
        	        $("#form_bank_code").val(obj.bank_code);
        	        
        	        $("#form_p_area_id").val(obj.p_area_id);
        	        $("#form_bank_area_code").val(obj.bank_area_code);
        	        
        	        $("#form_address").val(obj.address);
        	        $("#form_loket_no").val(obj.loket_no);
        	        $("#form_loket_type").val(obj.loket_type);
        	        $("#form_description").val(obj.description);
        	        
        	        $("#form_create_by").val(obj.create_by);
        	        $("#form_create_date").val(obj.create_date);
        	        $("#form_update_by").val(obj.update_by);
        	        $("#form_update_date").val(obj.update_date);
        	        
                }
            }
        );
        
    }

    function bank_branch_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_bank_branch_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'p_bank_branch_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_bank_branch_id    : $("#form_p_bank_branch_id").val(),
                    code                : $("#form_code").val(),
                    p_bank_id           : $("#form_p_bank_id").val(),
                    p_area_id           : $("#form_p_area_id").val(),
                    address             : $("#form_address").val(),
                    description         : $("#form_description").val(),
                    loket_no            : $("#form_loket_no").val(),
                    loket_type          : $("#form_loket_type").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        loadContent('pay_param-p_bank_branch');
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );

    }
</script>