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
			Payment Type
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="payment_type_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> Payment Type List </div>
		        </div>
		        <p>
					<button class="btn btn-white btn-success btn-round" id="payment_type_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="payment_type_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
				</p>
                <div class="col-xs-12">
		        <table id="payment_type_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible="false" data-header-align="center" data-align="center" data-column-id="p_payment_type_id"> ID Payment Type</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="code" data-width="190">Payment Type Code</th>
                     <th data-column-id="description"> Description </th>
                  </tr>
                </thead>
              </table>
            </div>
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->
					
<?php $this->load->view('pay_param/p_payment_type_add_edit.php'); ?>

<script>
    jQuery(function($) {
        payment_type_prepare_table();

        /* show content */
        $("#payment_type_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#payment_type_row_content").slideDown("fast", function(){});
        });

        $("#payment_type_btn_add").on(ace.click_event, function() {
            payment_type_show_form_add();
        });

        $("#payment_type_btn_delete").on(ace.click_event, function(){
            if($("#payment_type_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                payment_type_delete_records( $("#payment_type_grid_selection").bootgrid("getSelectedRows") );
            }
        });

    });

    function payment_type_prepare_table() {
        $("#payment_type_grid_selection").bootgrid({
    	     formatters: {
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Edit" onclick="payment_type_show_form_edit(\''+ row.p_payment_type_id +'\')" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp; <a href="#" title="Delete" onclick="payment_type_delete_records(\''+ row.p_payment_type_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a>';
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
       	     url: '<?php echo WS_URL2."pay_param.p_payment_type_controller/read"; ?>',
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

    function payment_type_reload_table() {
        $("#payment_type_grid_selection").bootgrid("reload");
    }

    function payment_type_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'pay_param.p_payment_type_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
                        function( response ) {
                            if(response.success == false) {
                	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                	        }else {
                    	        loadContent('pay_param-p_payment_type');
                                showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                            }
                        }
                	);
    	        }
		    }
		});

    }

</script>