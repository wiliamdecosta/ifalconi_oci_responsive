<div class="row" style="display:none;" id="application_role_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="application_role_form_title"> Add/Edit Module Role </div>
		</div>
        <form class="form-horizontal" application_role="form" id="application_role_form">

            <?php
                $ci =& get_instance();
                $ci->load->model('adm_sistem/p_application');
		        $table = $ci->p_application;
		        
		        $table->setCriteria(" p_application_id NOT IN (SELECT p_application_id FROM p_application_role WHERE p_role_id = ".getVarClean('p_role_id','int',0).") ");  
		        $itemsModule = $table->getAll(0, -1, "p_application_id", "ASC");
            ?>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Module *</label>
                <input id="form_p_application_role_id" type="text" style="display:none;">
                <div class="col-sm-9">
                    <select id="form_p_application_id" class="col-xs-10 col-sm-5 required">
                        <option value=""> -- Please Select Module -- </option>
            		    <?php foreach($itemsModule as $item): ?>
            		    <option value="<?php echo $item['p_application_id']; ?>"> <?php echo $item['code']; ?> </option>
            		    <?php endforeach; ?>
            	    </select>
                </div>
            </div>


            <?php
	            $user_name = $ci->session->userdata('user_name');
			?>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Created By </label>
                <div class="col-sm-9">
                    <input id="form_created_by" disabled type="text" value="<?php echo $user_name; ?>">
                    &nbsp;  <input id="form_creation_date" disabled type="text" value="<?php echo date("Y-m-d"); ?>">
                </div>
            </div>

           <div class="space-4"></div>

           <div class="clearfix form-actions">
		        <div class="center col-md-9">
			      	<button type="button" class="btn btn-primary btn-round" id="application_role_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>

                    <button type="reset" class="btn btn-danger btn-round" id="application_role_form_btn_cancel">
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
        $("#application_role_form_btn_cancel").on(ace.click_event, function() {
            application_role_toggle_main_content();
        });

        $("#application_role_form_btn_save").on(ace.click_event, function() {
            application_role_save();
        });
    });

    function application_role_toggle_main_content() {
        $("#application_role_form")[0].reset();
        $("#application_role_form_add_edit").hide();
        $("#application_role_row_content").toggle("slow");
    }

    function application_role_show_form_add() {
        application_role_toggle_main_content();
        $("#application_role_form_add_edit").show("slow");
        $("#application_role_form_title").html("Add Module" + " : " + $("#form_role_code").val());
    }

    function application_role_show_form_edit(theID) {
        //no edit
        return;
    }

    function application_role_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_application_role_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'adm_sistem.p_application_role_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_application_role_id      : $("#form_p_application_role_id").val(),
                    p_application_id           : $("#form_p_application_id").val(),
                    p_role_id                  : $("#form_p_role_id").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        loadContentWithParams('adm_sistem-p_application_role',{
        	            p_role_id : $("#form_p_role_id").val(),
        	            role_code : $("#form_role_code").val()
        	        });
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );
    }
</script>