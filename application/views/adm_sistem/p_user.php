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
			Users Administration
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="user_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> User List </div>
		        </div>
		        <p>
					<button class="btn btn-white btn-success btn-round" id="user_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="user_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
				</p>
				
		        <table id="user_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible="false" data-header-align="center" data-align="center" data-column-id="p_user_id"> ID User</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="user_name" data-width="190">User Name</th>
                     <th data-column-id="full_name"> Full Name </th>
                     <th data-column-id="user_status" data-formatter="user_status"> Status </th>
                     <th data-column-id="email_address"> Email </th>
                  </tr>
                </thead>
              </table>
              <?php /*$this->load->view('adm_sistem/p_user_search_criteria.php');*/ ?>
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->
<?php $this->load->view('adm_sistem/p_user_add_edit.php'); ?>

<script>
    jQuery(function($) {
        user_prepare_table();

        /* show content */
        $("#user_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#user_row_content").slideDown("fast", function(){});
        });

        $("#user_btn_add").on(ace.click_event, function() {
            user_show_form_add();
        });

        $("#user_btn_delete").on(ace.click_event, function(){
            if($("#user_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                user_delete_records( $("#user_grid_selection").bootgrid("getSelectedRows") );
            }
        });

    });

    function user_prepare_table() {
        $("#user_grid_selection").bootgrid({
    	     formatters: {
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Edit" onclick="user_show_form_edit(\''+ row.p_user_id +'\')" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp; <a href="#" title="Delete" onclick="user_delete_records(\''+ row.p_user_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a> &nbsp; <a href="#" title="User Role" onclick="user_show_user_role(\''+ row.p_user_id +'\', \''+ row.user_name +'\')" class="purple"><i class="ace-icon fa fa-users bigger-130"></i></a>';
                },
                "user_status" : function (col, row) {
                    var dataarr = {"1":"ACTIVE", "0":"NEW USER", "2":"INACTIVE", "3":"BLOCKED"};
                    return dataarr[row.user_status];
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
       	     url: '<?php echo WS_URL2."adm_sistem.p_user_controller/read"; ?>',
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

    function user_reload_table() {
        $("#user_grid_selection").bootgrid("reload");
    }

    function user_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title: 'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'adm_sistem.p_user_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
                        function( response ) {
                            if(response.success == false) {
                	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                	        }else {
                    	        loadContent('adm_sistem-p_user');
                                showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                            }
                        }
                	);
    	        }
		    }
		});
    }
    
    function user_show_user_role(theID, theCode) {
        loadContentWithParams("adm_sistem-p_user_role.php", {p_user_id: theID, user_name: theCode});   
    }

</script>