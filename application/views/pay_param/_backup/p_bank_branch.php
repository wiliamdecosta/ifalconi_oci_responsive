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
			Bank Branch
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="bank_branch_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <div class="well well-sm">
		            <div class="inline middle blue bigger-150"> Bank Branch List </div>
		        </div>
		        <p>
					<button class="btn btn-white btn-success btn-round" id="bank_branch_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="bank_branch_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
				</p>

		        <table id="bank_branch_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible = "false" data-header-align="center" data-align="center" data-column-id="p_bank_branch_id"> ID Bank Branch</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="code" data-width="200">Branch Name</th>
                     <th data-column-id="bank_code"> Bank </th>
                     <th data-column-id="bank_area_code"> Area </th>
                     <th data-column-id="address"> Address </th>
                     <th data-column-id="loket_no"> No. Counter </th>
                     <th data-column-id="loket_type" data-header-align="center" data-align="center" data-formatter="loket_type">Counter Type</th>
                  </tr>
                </thead>
              </table>
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<?php $this->load->view('pay_param/p_bank_branch_add_edit.php'); ?>

<script>
    jQuery(function($) {
        bank_branch_prepare_table();

        /* show content */
        $("#bank_branch_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#bank_branch_row_content").slideDown("fast", function(){});
        });

        $("#bank_branch_btn_add").on(ace.click_event, function() {
            bank_branch_show_form_add();
        });

        $("#bank_branch_btn_delete").on(ace.click_event, function(){
            if($("#bank_branch_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', 'Plese select data on the table to execute delete operation');
            }else {
                bank_branch_delete_records( $("#bank_branch_grid_selection").bootgrid("getSelectedRows") );
            }
        });

    });

    function bank_branch_prepare_table() {
        $("#bank_branch_grid_selection").bootgrid({
    	     formatters: {
                "loket_type" : function (col, row) {
                    var dataarr = new Array('','H2H','P2H','WEB');
                    return dataarr[row.loket_type];
                },
                "opt-edit" : function(col, row) {
                    return '<a href="#" onclick="bank_branch_show_form_edit(\''+ row.p_bank_branch_id +'\')" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp; <a href="#" onclick="bank_branch_delete_records(\''+ row.p_bank_branch_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a>';
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
       	     url: '<?php echo WS_URL2."p_bank_branch_controller/read"; ?>',
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

    function bank_branch_reload_table() {
        $("#bank_branch_grid_selection").bootgrid("reload");
    }

    function bank_branch_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: 'Do you really want to delete the data(s)?',
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'p_bank_branch_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
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
		    }
		});

    }

</script>