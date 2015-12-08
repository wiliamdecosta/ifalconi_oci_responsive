<!-- Bootgrid Dialog -->
<link rel="stylesheet" href="<?php echo BS_PATH; ?>bootgrid/jquery.bootgrid.css" />
<link rel="stylesheet" href="<?php echo BS_PATH; ?>bootgrid/modification.css" />
<script src="<?php echo BS_PATH; ?>bootgrid/jquery.bootgrid.min.js"></script>
<script src="<?php echo BS_PATH; ?>bootgrid/properties.js"></script>

<div class="page-header">
	<h1>
		Parameter
		<small>
		    <i class="ace-icon fa fa-angle-double-right"></i>
			Role Administration
			<i class="ace-icon fa fa-angle-double-right"></i>
			Module Role
			<i class="ace-icon fa fa-angle-double-right"></i>
			Menu Role
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="role_menu_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <p>
                  <button type="button" class="btn btn-pink btn-xs" id="backButton">
      	            <span>&larr; Role Administration </span> 
                  </button>
                  
                  <button type="button" class="btn btn-pink btn-xs" id="backButtonModule">
      	            <span>&larr; Module Role </span> 
                  </button>
                </p>
                
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> Menu List : <span class="label label-xlg label-yellow label-white"> <?php echo getVarClean('role_code','str',''); ?> </span> </div>
		        </div>
		        <p>
					<button class="btn btn-white btn-success btn-round" id="role_menu_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="role_menu_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
					
					<input id="form_p_role_id" type="hidden" placeholder="ID Role" value="<?php echo getVarClean('p_role_id','int',0); ?>">
					<input id="form_role_code" type="hidden" placeholder="Role Code" value="<?php echo getVarClean('role_code','str',''); ?>">
					
					<input id="form_p_application_id" type="hidden" placeholder="ID Application" value="<?php echo getVarClean('p_application_id','int',0); ?>">
					<input id="form_application_code" type="hidden" placeholder="Application Code" value="<?php echo getVarClean('application_code','str',''); ?>">
				</p>

		        <table id="role_menu_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible="false" data-header-align="center" data-align="center" data-column-id="p_role_menu_id"> ID Role Menu</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="menu_code" data-width="190">Menu Code</th>
                  </tr>
                </thead>
              </table>
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<?php $this->load->view('adm_sistem/p_role_menu_add_edit.php'); ?>

<script>
    jQuery(function($) {
        role_menu_prepare_table();

        /* show content */
        $("#role_menu_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#role_menu_row_content").slideDown("fast", function(){});
        });

        $("#role_menu_btn_add").on(ace.click_event, function() {
            role_menu_show_form_add();
        });

        $("#role_menu_btn_delete").on(ace.click_event, function(){
            if($("#role_menu_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                role_menu_delete_records( $("#role_menu_grid_selection").bootgrid("getSelectedRows") );
            }
        });
        
        $("#backButton").on(ace.click_event, function () {
            loadContent('adm_sistem-p_role.php');
        });
        
        $("#backButtonModule").on(ace.click_event, function () {
            loadContentWithParams('adm_sistem-p_application_role.php',{
                 p_role_id : $("#form_p_role_id").val(),
                role_code  : $("#form_role_code").val()    
            });
        });
    });

    function role_menu_prepare_table() {
        $("#role_menu_grid_selection").bootgrid({
    	     formatters: {
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Delete" onclick="role_menu_delete_records(\''+ row.p_role_menu_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a>';
                }
             },
    	     rowCount:[10,25,50,100,-1],
    		 ajax: true,
    	     requestHandler:function(request) {
    	        if(request.sort) {
    	            var sortby = Object.keys(request.sort)[0];
    	            request.dir = request.sort[sortby];

    	            delete request.sort;
    	            request.sort = sortby;
    	        }
    	        return request;
    	     },
    	     responseHandler:function (response) {
    	        if(response.success == false) {
    	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
    	        }
    	        return response;
    	     },
       	     url: '<?php echo WS_URL2."adm_sistem.p_role_menu_controller/read"; ?>',
       	     post: function () {
    	         return { 
                    p_role_id : $("#form_p_role_id").val(),
    	            p_application_id : $("#form_p_application_id").val()
    	         };
    	     },
    	     selection: true,
    	     multiSelect: true,
    	     sorting:true,
    	     rowSelect:true,
    	     labels: {
    	        loading     : properties.bootgridinfo.loading
	         }
    	});
    	resize_bootgrid();
    }

    function role_menu_reload_table() {
        $("#role_menu_grid_selection").bootgrid("reload");
    }

    function role_menu_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'adm_sistem.p_role_menu_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
                        function( response ) {
                            if(response.success == false) {
                	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                	        }else {
                    	        loadContentWithParams('adm_sistem-p_role_menu',{
                    	            
                    	            p_application_id    : $("#form_p_application_id").val(), 
                                    application_code    : $("#form_application_code").val(),
                    	            p_role_id           : $("#form_p_role_id").val(),
                    	            role_code           : $("#form_role_code").val()    
                    	        });
                                showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                            }
                        }
                	);
    	        }
		    }
		});
    }
    
</script>