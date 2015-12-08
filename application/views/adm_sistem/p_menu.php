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
			MODULE + MENU ADMINISTRATION
			<i class="ace-icon fa fa-angle-double-right"></i>
			MENU
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="menu_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <p>
                  <button type="button" class="btn btn-pink btn-xs" id="backButton">
      	            <span>&larr; Module + Menu Administration </span> 
                  </button>
                </p>
                
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> Menu List : <span class="label label-xlg label-yellow label-white"> <?php echo getVarClean('application_code','str',''); ?> </span> <span id="menu_code_selected" class="green"></span></div>
		        </div>
		        <p>
					<button class="btn btn-white btn-success btn-round" id="menu_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="menu_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
					
					<!--
					<button class="btn btn-sm btn-success btn-round" data-toggle="modal" data-target="#right-tree-menu">
						<i class="ace-icon glyphicon glyphicon-list-alt bigger-120"></i>
						<i class="ace-icon fa fa-exchange bigger-120"></i>
					</button>
					-->
					
					<input id="form_p_application_id" type="hidden" placeholder="ID Module" value="<?php echo getVarClean('p_application_id','int',0); ?>">
					<input id="form_application_code" type="hidden" placeholder="Module Name" value="<?php echo getVarClean('application_code','str',''); ?>">
					
					<input id="form_parent_id" type="hidden" placeholder="ID Parent" value="<?php echo getVarClean('parent_id','int',0); ?>">
					<input id="form_parent_code" type="hidden" placeholder="Code Parent">
				</p>
                
    		     <table id="menu_grid_selection" class="table table-striped table-bordered table-hover">
                 <thead>
                   <tr>
                     <th data-identifier="true" data-visible="false" data-header-align="center" data-align="center" data-column-id="p_menu_id"> ID Menu</th>
                      <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                      <th data-column-id="code" data-width="190">Menu Code</th>
                      <th data-column-id="file_name">File Name</th>
                      <th data-column-id="listing_no">Listing Number</th>
                      <th data-column-id="is_active" data-formatter="is_active">Active</th>
                   </tr>
                 </thead>
                </table>
               
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<?php $this->load->view('adm_sistem/p_menu_add_edit.php'); ?>
<?php $this->load->view('adm_sistem/right_menu_tree.php'); ?>

<script>
    jQuery(function($) {
        menu_prepare_table();

        /* show content */
        $("#menu_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#menu_row_content").slideDown("fast", function(){});
        });

        $("#menu_btn_add").on(ace.click_event, function() {
            menu_show_form_add();
        });

        $("#menu_btn_delete").on(ace.click_event, function(){
            if($("#menu_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                menu_delete_records( $("#menu_grid_selection").bootgrid("getSelectedRows") );
            }
        });
       
        $("#backButton").on(ace.click_event, function () {
            loadContent('adm_sistem-p_application.php');
        });
        
    });

    function menu_prepare_table() {
        $("#menu_grid_selection").bootgrid({
    	     formatters: {
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Edit" onclick="menu_show_form_edit(\''+ row.p_menu_id +'\')" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp;<a href="#" title="Delete" onclick="menu_delete_records(\''+ row.p_menu_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a>';
                },
                "is_active" : function (col, row) {
                    var dataarr = {"":"","Y":"YES", "N":"NO"};
                    return dataarr[row.is_active];
                }
             },
    	     rowCount:[5,10,25,50,100,-1],
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
       	     url: '<?php echo WS_URL2."adm_sistem.p_menu_controller/read"; ?>',
       	     post: function () {
    	         return { 
    	            p_application_id : $("#form_p_application_id").val(), 
    	            parent_id        : $("#form_parent_id").val()
    	         };
    	     },
    	     selection: true,
    	     multiSelect: true,
    	     sorting:true,
    	     rowSelect:true,
    	     labels: {
    	        loading     : properties.bootgridinfo.loading,
    	        noResults   : 'Menu empty. To add menu, click Add button'
	         }
    	});
    	resize_bootgrid();
    }

    function menu_reload_table() {
        $("#menu_grid_selection").bootgrid("reload");
    }

    function menu_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'adm_sistem.p_menu_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
                        function( response ) {
                            if(response.success == false) {
                	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                	        }else {
                    	        loadContentWithParams('adm_sistem-p_menu',{
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
		    }
		});
    }
    
</script>