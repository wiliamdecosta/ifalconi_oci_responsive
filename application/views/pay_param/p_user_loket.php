<!-- Bootgrid Dialog -->
<link rel="stylesheet" href="<?php echo BS_PATH; ?>bootgrid/jquery.bootgrid.css" />
<script src="<?php echo BS_PATH; ?>bootgrid/jquery.bootgrid.min.js"></script>
<link rel="stylesheet" href="<?php echo BS_CSS_PATH; ?>datepicker.css" />
<script src="<?php echo BS_JS_PATH; ?>date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo BS_PATH; ?>bootgrid/modification.css" />
<script src="<?php echo BS_PATH; ?>bootgrid/properties.js"></script>

<div class="page-header">
	<h1>
		Parameter
		<small>
		    <i class="ace-icon fa fa-angle-double-right"></i>
			Counter Group
			
			<i class="ace-icon fa fa-angle-double-right"></i>
			Counter
			
			<i class="ace-icon fa fa-angle-double-right"></i>
			Counter User
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="user_loket_row_content" style="display:none;">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div class="col-xs-12">
		        <p>
		          <button type="button" class="btn btn-pink btn-xs" id="backButtonGroupCounter">
      	            <span> &larr; Counter Group </span> 
                  </button>
                  
                  <button type="button" class="btn btn-pink btn-xs" id="backButtonCounter">
      	            <span> &larr; Counter </span> 
                  </button>
                </p>
                
		        <div class="well well-sm">
		            <div class="inline middle pink2 bigger-150"> Counter User List : <span class="label label-xlg label-yellow label-white"> <?php echo getVarClean('p_bank_branch_code','str',''); ?> </span></div>
		        </div>
		        
		        <p>
					<button class="btn btn-white btn-success btn-round" id="user_loket_btn_add">
						<i class="ace-icon glyphicon glyphicon-plus bigger-120 green"></i>
					    Add
					</button>

					<button class="btn btn-white btn-danger btn-round" id="user_loket_btn_delete">
						<i class="ace-icon glyphicon glyphicon-trash bigger-120 red"></i>
						Delete
					</button>
					
					<input id="form_p_bank_branch_id" type="hidden" placeholder="ID Bank Branch" value="<?php echo getVarClean('p_bank_branch_id','int',0); ?>">
					<input id="form_p_bank_branch_code" type="hidden" placeholder="Code Bank Branch" value="<?php echo getVarClean('p_bank_branch_code','str',''); ?>">
					
					<input id="form_p_bank_id" type="hidden" placeholder="ID Bank" value="<?php echo getVarClean('p_bank_id','int',0); ?>">
					<input id="form_p_bank_code" type="hidden" placeholder="Code Bank" value="<?php echo getVarClean('p_bank_code','str',''); ?>">
				</p>
                
		        <table id="user_loket_grid_selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th data-identifier="true" data-visible="false" data-header-align="center" data-align="center" data-column-id="p_user_loket_id"> ID Counter User</th>
                     <th data-header-align="center" data-align="center" data-formatter="opt-edit" data-sortable="false" data-width="100">Options</th>
                     <th data-column-id="user_name" data-width="190"> Username </th>
                     <th data-column-id="full_name"> Full Name </th>
                     <th data-column-id="user_loket_status" data-formatter="user_loket_status"> Status </th>
                     <th data-column-id="user_level" data-formatter="user_level"> Level </th>
                  </tr>
                </thead>
              </table>
		    </div>
	    </div>
        <!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->

<?php $this->load->view('pay_param/p_user_loket_add_edit.php'); ?>

<script>
    jQuery(function($) {
        user_loket_prepare_table();

        /* show content */
        $("#user_loket_grid_selection").bootgrid().on("loaded.rs.jquery.bootgrid", function (e){
           $("#user_loket_row_content").slideDown("fast", function(){});
        });

        $("#user_loket_btn_add").on(ace.click_event, function() {
            user_loket_show_form_add();
        });

        $("#user_loket_btn_delete").on(ace.click_event, function(){
            if($("#user_loket_grid_selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', properties.bootgridinfo.no_delete_records);
            }else {
                user_loket_delete_records( $("#user_loket_grid_selection").bootgrid("getSelectedRows") );
            }
        });
        
        $("#backButtonCounter").on(ace.click_event, function () {
            loadContentWithParams('pay_param-p_loket.php',
            {
                p_bank_id : $("#form_p_bank_id").val(),
                p_bank_code : $("#form_p_bank_code").val()
            });
        });
        
        $("#backButtonGroupCounter").on(ace.click_event, function () {
            loadContent('pay_param-p_group_loket.php');
        });

    });

    function user_loket_prepare_table() {
        $("#user_loket_grid_selection").bootgrid({
    	     formatters: {
                "user_loket_status" : function (col, row) {
                    var dataarr = {"0":"NOT ACTIVE", "1":"ACTIVE"};
                    return dataarr[row.user_loket_status];
                },
                "user_level" : function (col, row) {
                    var dataarr = {"A":"COUNTER ADMIN", "U":"COUNTER USER"};
                    return dataarr[row.user_level];
                },
                "opt-edit" : function(col, row) {
                    return '<a href="#" title="Edit" onclick="user_loket_show_form_edit(\''+ row.p_user_loket_id +'\')" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp; <a href="#" title="Delete" onclick="user_loket_delete_records(\''+ row.p_user_loket_id +'\')" class="red"><i class="ace-icon glyphicon glyphicon-trash bigger-130"></i></a>';
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
       	     url: '<?php echo WS_URL2."pay_param.p_user_loket_controller/read"; ?>',
       	     post: function () {
    	         return { p_bank_branch_id : $("#form_p_bank_branch_id").val() };
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

    function user_loket_reload_table() {
        $("#user_loket_grid_selection").bootgrid("reload");
    }

    function user_loket_delete_records(theID) {
        BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_WARNING,
		    title:'Delete Confirmation',
		    message: properties.bootgridinfo.delete_confirmation_question,
		    btnCancelLabel: 'Cancel',
            btnOKLabel: 'Yes, Delete',
		    callback: function(result) {
    	        if(result) {
    	            $.post( "<?php echo WS_URL.'pay_param.p_user_loket_controller/destroy'; ?>",
            		    { items: JSON.stringify(theID) },
                        function( response ) {
                            if(response.success == false) {
                	            showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
                	        }else {
                    	        loadContentWithParams('pay_param-p_user_loket.php', 
                                    {
                                     p_bank_branch_id : $("#form_p_bank_branch_id").val(),
                                     p_bank_branch_code : $("#form_p_bank_branch_code").val(),   
                                     p_bank_id : $("#form_p_bank_id").val(), 
                                     p_bank_code : $("#form_p_bank_code").val()
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