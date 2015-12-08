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
			Stamp Group
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="stamp_group_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> Stamp Group List </div>
		        </div>
		        <p>
					<button class="btn btn-white btn-success btn-round" id="stamp_group_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="stamp_group_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
				</p>

		        <table id="stamp_group_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible = "false" data-header-align="center" data-align="center" data-column-id="p_stamp_group_id"> ID Stamp Group</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="code" data-width="190">Stamp Group Code</th>
                     <th data-column-id="description"> Description </th>
                  </tr>
                </thead>
              </table>
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<?php $this->load->view('pay_param/p_stamp_group_add_edit.php'); ?>

<script>
    jQuery(function($) {
        stamp_group_prepare_table();

        /* show content */
        $("#stamp_group_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#stamp_group_row_content").slideDown("fast", function(){});
        });

        $("#stamp_group_btn_add").on(ace.click_event, function() {
            stamp_group_show_form_add();
        });

        $("#stamp_group_btn_delete").on(ace.click_event, function(){
            if($("#stamp_group_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                stamp_group_delete_records( $("#stamp_group_grid_selection").bootgrid("getSelectedRows") );
            }
        });

    });

    function stamp_group_prepare_table() {
        $("#stamp_group_grid_selection").bootgrid({
    	     formatters: {
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Edit" onclick="stamp_group_show_form_edit(\''+ row.p_stamp_group_id +'\')" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp; <a href="#" title="Delete" onclick="stamp_group_delete_records(\''+ row.p_stamp_group_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a> &nbsp; <a href="#" title="Stamp" onclick="stamp_group_show_stamp(\''+ row.p_stamp_group_id +'\',\''+ row.code +'\')" class="purple"><i class="ace-icon fa fa-bookmark bigger-130"></i></a>';
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
       	     url: '<?php echo WS_URL2."pay_param.p_stamp_group_controller/read"; ?>',
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

    function stamp_group_reload_table() {
        $("#stamp_group_grid_selection").bootgrid("reload");
    }

    function stamp_group_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'pay_param.p_stamp_group_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
                        function( response ) {
                            if(response.success == false) {
                	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                	        }else {
                    	        loadContent('pay_param-p_stamp_group');
                                showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                            }
                        }
                	);
    	        }
		    }
		});
    }
    
    function stamp_group_show_stamp(theID, theCode) {
        loadContentWithParams("pay_param-p_stamp.php", {p_stamp_group_id: theID, p_stamp_group_code: theCode});   
    }

</script>