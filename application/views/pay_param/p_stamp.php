<!-- Bootgrid Dialog -->
<link rel="stylesheet" href="<?php echo BS_PATH; ?>bootgrid/jquery.bootgrid.css" />
<link rel="stylesheet" href="<?php echo BS_PATH; ?>bootgrid/modification.css" />
<script src="<?php echo BS_PATH; ?>bootgrid/jquery.bootgrid.min.js"></script>
<script src="<?php echo BS_PATH; ?>bootgrid/properties.js"></script>
<script src="<?php echo BS_PATH; ?>bootgrid/jquery.number.min.js"></script>

<link rel="stylesheet" href="<?php echo BS_CSS_PATH; ?>datepicker.css" />
<script src="<?php echo BS_JS_PATH; ?>date-time/bootstrap-datepicker.js"></script>

<div class="page-header">
	<h1>
		Parameter
		<small>
		    <i class="ace-icon fa fa-angle-double-right"></i>
			Stamp Group
			
			<i class="ace-icon fa fa-angle-double-right"></i>
			Stamp
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="stamp_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <p>
                  <button type="button" class="btn btn-pink btn-xs" id="backButton">
      	            <span> &larr; Stamp Group </span>
                  </button>
                </p>
                
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> Stamp List : <span class="label label-xlg label-yellow label-white"><?php echo getVarClean('p_stamp_group_code','str',''); ?></span></div>
		        </div>
		        
		        <p>
					<button class="btn btn-white btn-success btn-round" id="stamp_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="stamp_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
					
					<input id="form_p_stamp_group_id" type="hidden" placeholder="ID Stamp Group" value="<?php echo getVarClean('p_stamp_group_id','int',0); ?>">
					<input id="form_p_stamp_group_code" type="hidden" placeholder="Code Stamp Group" value="<?php echo getVarClean('p_stamp_group_code','str',''); ?>">
				</p>
                
		        <table id="stamp_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible="false" data-header-align="center" data-align="center" data-column-id="p_stamp_id"> ID Stamp</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="amt_low_limit" data-formatter="amt_low_limit" data-width="190" data-header-align="center" data-align="right">Low Limit Amount</th>
                     <th data-column-id="amt_up_limit" data-formatter="amt_up_limit" data-header-align="center" data-align="right"> Up Limit Amount</th>
                     <th data-column-id="stamp_amount" data-formatter="stamp_amount" data-header-align="center" data-align="right"> Stamp Amount </th>
                  </tr>
                </thead>
              </table>
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<?php $this->load->view('pay_param/p_stamp_add_edit.php'); ?>

<script>
    jQuery(function($) {
        stamp_prepare_table();

        /* show content */
        $("#stamp_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#stamp_row_content").slideDown("fast", function(){});
        });

        $("#stamp_btn_add").on(ace.click_event, function() {
            stamp_show_form_add();
        });

        $("#stamp_btn_delete").on(ace.click_event, function(){
            if($("#stamp_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                stamp_delete_records( $("#stamp_grid_selection").bootgrid("getSelectedRows") );
            }
        });
        
        $("#backButton").on(ace.click_event, function () {
            loadContent('pay_param-p_stamp_group.php');
        });

    });

    function stamp_prepare_table() {
        $("#stamp_grid_selection").bootgrid({
    	     formatters: {
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Edit" onclick="stamp_show_form_edit(\''+ row.p_stamp_id +'\')" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp; <a href="#" title="Delete" onclick="stamp_delete_records(\''+ row.p_stamp_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a>';
                },
                "amt_low_limit" : function (col, row) {
				    return $.number(row.amt_low_limit, 2, '.', ',');
                },
                "amt_up_limit" : function (col, row) {
				    return $.number(row.amt_up_limit, 2, '.', ',');
                },
                "stamp_amount" : function (col, row) {
				    return $.number(row.stamp_amount, 2, '.', ',');
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
       	     url: '<?php echo WS_URL2."pay_param.p_stamp_controller/read"; ?>',
       	     post: function () {
    	         return { p_stamp_group_id : $("#form_p_stamp_group_id").val() };
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

    function stamp_reload_table() {
        $("#stamp_grid_selection").bootgrid("reload");
    }

    function stamp_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'pay_param.p_stamp_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
                        function( response ) {
                            if(response.success == false) {
                	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                	        }else {
                    	        loadContentWithParams('pay_param-p_stamp.php', 
                    	           {
                                    p_stamp_group_id: $("#form_p_stamp_group_id").val(), 
                    	            p_stamp_group_code: $("#form_p_stamp_group_code").val()
                    	           }
                    	        );
                                showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', response.message);
                            }
                        }
                	);
    	        }
		    }
		});

    }
    
</script>