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
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="application_role_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <p>
                  <button type="button" class="btn btn-pink btn-xs" id="backButton">
      	            <span>&larr; Role Administration </span> 
                  </button>
                </p>
                
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> Module Role List : <span class="label label-xlg label-yellow label-white"> <?php echo getVarClean('role_code','str',''); ?> </span> </div>
		        </div>
		        <p>
					<button class="btn btn-white btn-success btn-round" id="application_role_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="application_role_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
					
					<input id="form_p_role_id" type="hidden" placeholder="ID Role" value="<?php echo getVarClean('p_role_id','int',0); ?>">
					<input id="form_role_code" type="hidden" placeholder="Role Code" value="<?php echo getVarClean('role_code','str',''); ?>">
				</p>

		        <table id="application_role_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible="false" data-header-align="center" data-align="center" data-column-id="p_application_role_id"> ID Role</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="application_code" data-width="190">Module Code</th>
                  </tr>
                </thead>
              </table>
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<?php $this->load->view('adm_sistem/p_application_role_add_edit.php'); ?>

<script>
    jQuery(function($) {
        application_role_prepare_table();

        /* show content */
        $("#application_role_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#application_role_row_content").slideDown("fast", function(){});
        });

        $("#application_role_btn_add").on(ace.click_event, function() {
            application_role_show_form_add();
        });

        $("#application_role_btn_delete").on(ace.click_event, function(){
            if($("#application_role_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                application_role_delete_records( $("#application_role_grid_selection").bootgrid("getSelectedRows") );
            }
        });
        
        $("#backButton").on(ace.click_event, function () {
            loadContent('adm_sistem-p_role.php');
        });
    });

    function application_role_prepare_table() {
        $("#application_role_grid_selection").bootgrid({
    	     formatters: {
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Delete" onclick="application_role_delete_records(\''+ row.p_application_role_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a> &nbsp; <a href="#" title="Menu Role" onclick="application_role_show_role_menu(\''+ row.p_application_id +'\', \''+ row.code +'\')" class="purple"><i class="ace-icon glyphicon glyphicon-list bigger-130"></i></a>';
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
       	     url: '<?php echo WS_URL2."adm_sistem.p_application_role_controller/read"; ?>',
       	     post: function () {
    	         return { p_role_id : $("#form_p_role_id").val() };
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

    function application_role_reload_table() {
        $("#application_role_grid_selection").bootgrid("reload");
    }

    function application_role_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'adm_sistem.p_application_role_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
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
		    }
		});
    }
    
    function application_role_show_role_menu(theID, theCode) {
        loadContentWithParams("adm_sistem-p_role_menu.php", {
            p_application_id: theID, 
            application_code: theCode,
            p_role_id : $("#form_p_role_id").val(),
            role_code : $("#form_role_code").val()
        });
    }
</script>