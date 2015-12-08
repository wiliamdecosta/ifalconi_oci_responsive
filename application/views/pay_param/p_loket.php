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
			Counter Group
			
			<i class="ace-icon fa fa-angle-double-right"></i>
			Counter
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="loket_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <p>
                  <button type="button" class="btn btn-pink btn-xs" id="backButton">
      	            <span> &larr; Counter Group </span>
                  </button>
                </p>
                
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> Counter List : <span class="label label-xlg label-yellow label-white"><?php echo getVarClean('p_bank_code','str',''); ?></span></div>
		        </div>
		        
		        <p>
					<button class="btn btn-white btn-success btn-round" id="loket_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="loket_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
					
					<input id="form_p_bank_id" type="hidden" placeholder="ID Bank" value="<?php echo getVarClean('p_bank_id','int',0); ?>">
					<input id="form_p_bank_code" type="hidden" placeholder="Code Bank" value="<?php echo getVarClean('p_bank_code','str',''); ?>">
				</p>
                
		        <table id="loket_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible="false" data-header-align="center" data-align="center" data-column-id="p_bank_branch_id"> ID Counter</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="code" data-width="190">Counter Code</th>
                     <th data-column-id="bank_code"> Group Counter</th>
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

<?php $this->load->view('pay_param/p_loket_add_edit.php'); ?>

<script>
    jQuery(function($) {
        loket_prepare_table();

        /* show content */
        $("#loket_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#loket_row_content").slideDown("fast", function(){});
        });

        $("#loket_btn_add").on(ace.click_event, function() {
            loket_show_form_add();
        });

        $("#loket_btn_delete").on(ace.click_event, function(){
            if($("#loket_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                loket_delete_records( $("#loket_grid_selection").bootgrid("getSelectedRows") );
            }
        });
        
        $("#backButton").on(ace.click_event, function () {
            loadContent('pay_param-p_group_loket.php');
        });

    });

    function loket_prepare_table() {
        $("#loket_grid_selection").bootgrid({
    	     formatters: {
                "loket_type" : function (col, row) {
                    var dataarr = {"":"", "1":"H2H", "2":"P2H", "3":"WEB"};
                    return dataarr[row.loket_type];
                },
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Edit" onclick="loket_show_form_edit(\''+ row.p_bank_branch_id +'\')" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp; <a href="#" title="Delete" onclick="loket_delete_records(\''+ row.p_bank_branch_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a> &nbsp; <a href="#" title="Counter User" onclick="loket_show_user_loket(\''+ row.p_bank_branch_id +'\',\''+ row.code +'\')" class="purple"><i class="ace-icon glyphicon glyphicon-user bigger-130"></i></a>';
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
       	     url: '<?php echo WS_URL2."pay_param.p_bank_branch_controller/read"; ?>',
       	     post: function () {
    	         return { p_bank_id : $("#form_p_bank_id").val() };
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

    function loket_reload_table() {
        $("#loket_grid_selection").bootgrid("reload");
    }

    function loket_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'pay_param.p_bank_branch_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
                        function( response ) {
                            if(response.success == false) {
                	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                	        }else {
                    	        loadContentWithParams('pay_param-p_loket.php', 
                    	           {
                                    p_bank_id: $("#form_p_bank_id").val(), 
                    	            p_bank_code: $("#form_p_bank_code").val()
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
    
    function loket_show_user_loket(theID, theCode) {
        loadContentWithParams("pay_param-p_user_loket.php", 
            {
                p_bank_id: $("#form_p_bank_id").val(), 
                p_bank_code: $("#form_p_bank_code").val(),
                p_bank_branch_id : theID,
                p_bank_branch_code : theCode
            }
        );  
    }
    
</script>