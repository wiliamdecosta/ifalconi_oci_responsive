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
			Penalty Group
			
			<i class="ace-icon fa fa-angle-double-right"></i>
			Payment Penalty
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="payment_penalty_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <p>
                  <button type="button" class="btn btn-pink btn-xs" id="backButton">
      	            <span> &larr; Penalty Group </span>
                  </button>
                </p>
                
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> Payment Penalty List : <span class="label label-xlg label-yellow label-white"><?php echo getVarClean('p_penalty_group_code','str',''); ?></span></div>
		        </div>
		        
		        <p>
					<button class="btn btn-white btn-success btn-round" id="payment_penalty_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="payment_penalty_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
					
					<input id="form_p_penalty_group_id" type="hidden" placeholder="ID Penalty Group" value="<?php echo getVarClean('p_penalty_group_id','int',0); ?>">
					<input id="form_p_penalty_group_code" type="hidden" placeholder="Code Penalty Group" value="<?php echo getVarClean('p_penalty_group_code','str',''); ?>">
				</p>
                
		        <table id="payment_penalty_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible="false" data-header-align="center" data-align="center" data-column-id="p_payment_penalty_id"> ID Payment Penalty</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="month_late" data-formatter="month_late" data-width="190" data-header-align="center" data-align="right">Month Late</th>
                     <th data-column-id="day_low_limit" data-formatter="day_low_limit" data-header-align="center" data-align="right"> Day Low Limit</th>
                     <th data-column-id="day_up_limit" data-formatter="day_up_limit" data-header-align="center" data-align="right"> Day Up Limit </th>
                     <th data-column-id="penalty_amount" data-formatter="penalty_amount" data-header-align="center" data-align="right"> Penalty Amount </th>
                     <th data-column-id="penalty_pct" data-formatter="penalty_pct" data-header-align="center" data-align="right"> Penalty Pct </th>
                     <th data-column-id="added_amount" data-formatter="added_amount" data-header-align="center" data-align="right"> Added Amount </th>
                  </tr>
                </thead>
              </table>
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<?php $this->load->view('pay_param/p_payment_penalty_add_edit.php'); ?>

<script>
    jQuery(function($) {
        payment_penalty_prepare_table();

        /* show content */
        $("#payment_penalty_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#payment_penalty_row_content").slideDown("fast", function(){});
        });

        $("#payment_penalty_btn_add").on(ace.click_event, function() {
            payment_penalty_show_form_add();
        });

        $("#payment_penalty_btn_delete").on(ace.click_event, function(){
            if($("#payment_penalty_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                payment_penalty_delete_records( $("#payment_penalty_grid_selection").bootgrid("getSelectedRows") );
            }
        });
        
        $("#backButton").on(ace.click_event, function () {
            loadContent('pay_param-p_penalty_group.php');
        });

    });

    function payment_penalty_prepare_table() {
        $("#payment_penalty_grid_selection").bootgrid({
    	     formatters: {
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Edit" onclick="payment_penalty_show_form_edit(\''+ row.p_payment_penalty_id +'\')" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp; <a href="#" title="Delete" onclick="payment_penalty_delete_records(\''+ row.p_payment_penalty_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a>';
                },
                "month_late" : function (col, row) {
				    return $.number(row.month_late, 0, '.', ',');
                },
                "day_low_limit" : function (col, row) {
				    return $.number(row.day_low_limit, 0, '.', ',');
                },
                "day_up_limit" : function (col, row) {
				    return $.number(row.day_up_limit, 0, '.', ',');
                },
                "penalty_amount" : function (col, row) {
				    return $.number(row.penalty_amount, 2, '.', ',');
                },
                "penalty_pct" : function (col, row) {
				    return $.number(row.penalty_pct, 2, '.', ',');
                },
                "added_amount" : function (col, row) {
				    return $.number(row.added_amount, 2, '.', ',');
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
       	     url: '<?php echo WS_URL2."pay_param.p_payment_penalty_controller/read"; ?>',
       	     post: function () {
    	         return { p_penalty_group_id : $("#form_p_penalty_group_id").val() };
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

    function payment_penalty_reload_table() {
        $("#payment_penalty_grid_selection").bootgrid("reload");
    }

    function payment_penalty_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'pay_param.p_payment_penalty_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
                        function( response ) {
                            if(response.success == false) {
                	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                	        }else {
                    	        loadContentWithParams('pay_param-p_payment_penalty.php', 
                    	           {
                                    p_penalty_group_id: $("#form_p_penalty_group_id").val(), 
                    	            p_penalty_group_code: $("#form_p_penalty_group_code").val()
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