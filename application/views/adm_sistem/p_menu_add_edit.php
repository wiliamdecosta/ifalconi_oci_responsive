<script src="<?php echo BS_JS_PATH; ?>fuelux/fuelux.spinner.js"></script>
<div class="row" style="display:none;" id="menu_form_add_edit">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle blue bigger-150" id="menu_form_title"> Add/Edit Menu </div>
		</div>
        <form class="form-horizontal" menu="form" id="menu_form">

            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Code *</label>
                <div class="col-sm-9">
                    <input id="form_p_menu_id" type="text" style="display:none;" placeholder="Menu ID">
                    <input id="form_code" class="col-xs-10 col-sm-5 required" type="text">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> File Name </label>
                <div class="col-sm-9">
                    <input id="form_file_name" class="col-xs-10 col-sm-5" type="text">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Listing Number </label>
                <div class="col-sm-9">
                    <input id="form_listing_no" class="col-xs-10 col-sm-3" type="text">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Active ? *</label>
                <div class="col-sm-9">
                    <select id="form_is_active" class="col-xs-10 col-sm-5 required">
            		    <option value="Y">YES</option>
                        <option value="N">NO</option>
            	    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right"> Icon</label>
                <div class="col-sm-5">
                    <input id="form_menu_icon" type="text" placeholder="Icon ID" style="display:none;">
                    <input id="form_menu_icon_name" class="col-xs-8 col-sm-5 required" type="text" placeholder="Choose Icon">
                    <span class="input-group-btn">
						<button class="btn btn-success btn-sm" type="button" id="btn_lov_icon">
							<span class="ace-icon fa fa-pencil-square-o icon-on-right bigger-110"></span>
						</button>
					</span>
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
			      	<button type="button" class="btn btn-primary btn-round" id="menu_form_btn_save">
			      		<i class="ace-icon fa fa-floppy-o bigger-120"></i>
			      		Save
			      	</button>

                    <button type="reset" class="btn btn-danger btn-round" id="menu_form_btn_cancel">
                        <i class="glyphicon glyphicon-circle-arrow-left bigger-120"></i>
                        Cancel
                    </button>

			    </div>
		   </div>
       </form>
    </div>
</div>

<?php $this->load->view('adm_lov/lov_p_icon.php'); ?>

<script>
    jQuery(function($) {
        $("#menu_form_btn_cancel").on(ace.click_event, function() {
            menu_toggle_main_content();
            menu_show_tree_menu();
        });

        $("#menu_form_btn_save").on(ace.click_event, function() {
            menu_save();
        });
        
        $("#form_listing_no").ace_spinner({
                    min: 0,
                    max: 100,
                   step: 1,
                btn_up_class:'btn-success' , 
                btn_down_class:'btn-success'
        });
        
        $("#btn_lov_icon").on(ace.click_event, function() {
            modal_lov_icon_show("form_menu_icon","form_menu_icon_name");
        });
    });

    function menu_toggle_main_content() {
        $("#menu_form")[0].reset();
        
        $("#menu_form_add_edit").hide();
        $("#menu_row_content").toggle("slow");
    }

    function menu_show_form_add() {
        menu_toggle_main_content();
        $("#menu_form_add_edit").show("slow");
        $("#menu_form_title").html("Add Menu" + " : " + $("#form_parent_code").val());
        
        menu_hide_tree_menu();
    }

    function menu_show_form_edit(theID) {
        menu_toggle_main_content();
        $("#menu_form_add_edit").show("slow");
        $("#menu_form_title").html("Edit Menu");
        
        $("#form_p_menu_id").val(theID);
        $.post( "<?php echo WS_URL.'adm_sistem.p_menu_controller/getMenu'; ?>",
            {
                p_menu_id : $("#form_p_menu_id").val(),
                rowCount : -1
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
        	        var obj = response.items[0];
        	        
        	        $("#form_p_menu_id").val(obj.p_menu_id);
        	        $("#form_p_application_id").val(obj.p_application_id);
        	        $("#form_code").val(obj.code);
        	        $("#form_parent_id").val(obj.parent_id);
        	        $("#form_file_name").val(obj.file_name);
        	        $("#form_listing_no").ace_spinner('value', obj.listing_no);
        	        $("#form_is_active").val(obj.is_active);
        	        $("#form_description").val(obj.description);        	       
        	        $("#form_menu_icon").val(obj.menu_icon); 
        	        $("#form_menu_icon_name").val(obj.icon_name); 
        	        
        	        $("#form_created_by").val(obj.created_by);
        	        $("#form_creation_date").val(obj.creation_date);
        	        $("#form_updated_by").val(obj.updated_by);
        	        $("#form_updated_date").val(obj.updated_date);
        	        
                }
            }
        );
        menu_hide_tree_menu();
    }
    
    function menu_save() {
        var action_execute = "";

        //jika ID kosong, panggil method create. Jika ID ada, maka panggil method update
        action_execute = ( $("#form_p_menu_id").val() == "") ? "create" : "update";
        $.post( "<?php echo WS_URL.'adm_sistem.p_menu_controller/'; ?>" + action_execute,
            {items: JSON.stringify({
                    p_menu_id        : $("#form_p_menu_id").val(),
                    p_application_id : $("#form_p_application_id").val(),
                    code             : $("#form_code").val(),
                    parent_id        : $("#form_parent_id").val(),
                    file_name        : $("#form_file_name").val(),
                    listing_no       : $("#form_listing_no").val(),
                    is_active        : $("#form_is_active").val(),
                    menu_icon        : $("#form_menu_icon").val(),
                    description      : $("#form_description").val()
                })
            },
            function( response ) {
                if(response.success == false) {
                    showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                }else {
                    loadContentWithParams('adm_sistem-p_menu.php',{
        	            p_application_id : $("#form_p_application_id").val(),
        	            application_code : $("#form_application_code").val(),
                        parent_id        : $("#form_parent_id").val(),
                        parent_code      : $("#form_parent_code").val()
        	        });
                    showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                }
            }
        );
    }
</script>