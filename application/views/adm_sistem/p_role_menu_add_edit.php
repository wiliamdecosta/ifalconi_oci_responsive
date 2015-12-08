<div class="row" style="display:none;" id="role_menu_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="role_menu_form_title"> Add/Edit Menu Role </div>
		</div>
        <form class="form-horizontal" role_menu="form" id="role_menu_form">

            <?php
                $ci =& get_instance();
                $sql = "SELECT * FROM v_p_menu_tree WHERE p_application_id = ".getVarClean('p_application_id','int',0)."
                        AND p_menu_id NOT IN (SELECT p_menu_id FROM p_role_menu WHERE p_role_id = ".getVarClean('p_role_id','int',0).")";
                $query = $ci->db->query($sql);
                $itemsMenu = $query->result_array();
            ?>
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Menu *</label>
                <input id="form_p_role_menu_id" type="text" style="display:none;">
                <div class="col-sm-9">
                    <select id="form_p_menu_id" class="col-xs-10 col-sm-5 required">
                        <option value=""> -- Please Select Menu -- </option>
            		    <?php foreach($itemsMenu as $item): ?>
            		    <option value="<?php echo $item['p_menu_id']; ?>"> <?php echo $item['code']; ?> </option>
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
			      	<button type="button" class="btn btn-primary btn-round" id="role_menu_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>

                    <button type="reset" class="btn btn-danger btn-round" id="role_menu_form_btn_cancel">
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
        $("#role_menu_form_btn_cancel").on(ace.click_event, function() {
            role_menu_toggle_main_content();
        });

        $("#role_menu_form_btn_save").on(ace.click_event, function() {
            role_menu_save();
        });
    });

    function role_menu_toggle_main_content() {
        $("#role_menu_form")[0].reset();
        $("#role_menu_form_add_edit").hide();
        $("#role_menu_row_content").toggle("slow");
    }

    function role_menu_show_form_add() {
        role_menu_toggle_main_content();
        $("#role_menu_form_add_edit").show("slow");
        $("#role_menu_form_title").html("Add Menu" + " : " + $("#form_role_code").val());
    }

    function role_menu_show_form_edit(theID) {
        //no edit
        return;
    }

    function role_menu_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_role_menu_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'adm_sistem.p_role_menu_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_role_menu_id      : $("#form_p_role_menu_id").val(),
                    p_menu_id           : $("#form_p_menu_id").val(),
                    p_role_id           : $("#form_p_role_id").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        loadContentWithParams('adm_sistem-p_role_menu',{
        	            p_role_id           : $("#form_p_role_id").val(),
        	            role_code           : $("#form_role_code").val(),
        	            p_application_id    : $("#form_p_application_id").val(),
        	            application_code    : $("#form_application_code").val()
        	        });
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );
    }
</script>