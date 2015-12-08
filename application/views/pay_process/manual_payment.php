<?php
    /*$this->load->view('pay_process/check_payment_login.php');
    check_payment_login("pay_process-manual_payment.php");*/
?>

<!-- Bootgrid Dialog -->
<link rel="stylesheet" href="<?php echo BS_PATH; ?>bootgrid/jquery.bootgrid.css" />
<link rel="stylesheet" href="<?php echo BS_PATH; ?>bootgrid/modification.css" />
<script src="<?php echo BS_PATH; ?>bootgrid/jquery.bootgrid.min.js"></script>
<script src="<?php echo BS_PATH; ?>bootgrid/properties.js"></script>
<script src="<?php echo BS_PATH; ?>bootgrid/jquery.number.min.js"></script>

<div class="page-header">
	<h1>
		Transaction
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			Manual Payment
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row" id="row-content">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
		    <div id="filter-group" class="col-xs-12 col-lg-5">
              <div class="input-group">
                <input id="form_user_name" type="hidden" value="<?php echo getVarClean("user_name","str",""); ?>">
                <input id="form_service_no" class="form-control" placeholder="Input Your Service Number">
                <span class="input-group-btn">
                    <button id="btnProses" class="btn btn-info btn-sm">
                        <span class="ace-icon fa fa-search icon-on-right bigger-110"></span> Do Process
                    </button>
                </span>
              </div>
            </div>
		</div>
        <div class="space-4"> </div>
        <div class="row" id="table-group" style="display:none;">

            <div class="col-xs-12">
              <p>
                <button type="button" class="btn btn-pink btn-xs" id="backButton">
      	            <span> &larr; Input Service No </span>
                </button>
              </p>
              <table id="grid-selection" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                     <th data-identifier="true" data-visible = "false" data-type="string" data-header-align="center" data-align="center" data-column-id="id"> ID </th>
                     <th data-visible="false" data-column-id="subscriber_id"> subscriber id </th>

                     <th data-column-id="account_no" data-header-align="center" data-align="center" data-width="150">Account</th>
                     <th data-column-id="service_no" data-header-align="center" data-align="center" data-width="150">Service No</th>
                     <th data-column-id="finance_period_code" data-header-align="center" data-align="center">Period</th>
                     <th data-column-id="payment_charge_amt" data-align="right" data-formatter="payment_charge_amt">Invoice</th>
                     <th data-column-id="payment_vat_amt" data-align="right" data-formatter="payment_vat_amt">Vat</th>
                     <th data-column-id="stamp_duty_fee" data-align="right" data-formatter="stamp_duty_fee">Stamp Duty <br/> Fee</th>
                     <th data-column-id="penalty_amount" data-align="right" data-formatter="penalty_amount">Penalty</th>
                  </tr>
                </thead>
              </table>
            </div>

            <div class="col-xs-12 col-lg-4">
            	 <div class="panel panel-info">
            		   <!-- Default panel contents -->
            		  <div class="panel-heading"><h4>PAYMENT SUMMARY</h4></div>
            	      <div class="panel-body">
                	      <div class="col-xs-12">
                	       <h5><span class="label label-warning">Total Invoice (Rp) : </span></h5> <input type="text" class="form-control priceformat align-right" readonly id="form_summary_total_invoice" placeholder="0">
                		  </div>

                		  <div class="col-xs-12">
                		   <h5><span class="label label-warning">Stamp Duty Fee (Rp) :  </span></h5> <input type="text" class="form-control priceformat align-right" readonly id="form_summary_total_stamp_duty" placeholder="0">
                		  </div>

                		  <div class="col-xs-12">
                		   <h5><span class="label label-warning">Penalty (Rp) :  </span></h5> <input type="text" class="form-control priceformat align-right" readonly id="form_summary_total_penalty" placeholder="0">
                		  </div>

                		  <div class="col-xs-12">
                		   <h5><span class="label label-success">GRAND TOTAL (Rp) :  </span></h5> <input type="text" class="form-control priceformat align-right" readonly  id="form_summary_grand_total" placeholder="0">
                          </div>


                          <div class="col-xs-12">
                              <h5><span class="label label-default">Deposit Amount :  </span></h5>
                              <input id="form_summary_deposit_amount"  class="col-xs-12 priceformat align-right" readonly type="text">
                          </div>

                          <br/>
                          <div class="col-xs-12 align-right">
                              <label>
                                <small class="muted center orange"> <strong> Use your deposit amount ? : </strong></small>
                                <input type="checkbox" class="ace ace-switch ace-switch-6" id="form_summary_use_deposit">
                                <span class="lbl middle"></span>
                              </label>
                          </div>
                          
                          <div class="col-xs-12 align-right">
                              <button id="btnAddDeposit" class="btn btn-success btn-xs">Add Deposit</button>
                          </div>

                          <div class="col-xs-12">
                              <h5><span class="label label-primary">Choose Counter * :  </span></h5>
                              <input id="form_p_bank_branch_id" type="hidden" placeholder="Counter ID">
                              <input id="form_bank_branch_code" class="col-xs-10" type="text" placeholder="Choose Counter">
                              <span class="input-group-btn">
                					<button class="btn btn-success btn-sm" type="button" id="btn_lov_bank_branch">
                						<span class="ace-icon fa fa-pencil-square-o icon-on-right bigger-110"></span>
                					</button>
                			  </span>
                          </div>

                          <div class="col-xs-12">
                            </br>
                              <input type="hidden" class="form-control" id="form_summary_subscriber_id">
                              <input type="hidden" class="form-control" id="form_client_ip_address" value="<?php echo get_ip_address(); ?>">
                              <input type="hidden" class="form-control" id="form_p_user_loket_id" value="<?php echo getVarClean("p_user_loket_id","str",""); ?>">
                              <input type="hidden" class="form-control" id="form_user_name" value="<?php echo getVarClean("user_name","str",""); ?>">
                              <input type="hidden" class="form-control" id="form_password" value="<?php echo getVarClean("password","str",""); ?>">
                    		  <button id="btnPembayaran" class="btn btn-primary btn-sm">Do Payment</button>
                		  </div>

            		  </div>
            	 </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('pay_lov/lov_ws_p_bank_branch.php'); ?>
<?php $this->load->view('pay_lov/lov_add_deposit_amount.php'); ?>

<script>
var responseError = false;
/* jquery on load */
jQuery(function($) {

	  $(".priceformat").number( true, 2 , '.',','); /* price number format */
	  $(".priceformat").css("font-weight", "bold");

      $("#btn_lov_bank_branch").on(ace.click_event, function() {
            modal_lov_bank_branch_show("form_p_bank_branch_id", "form_bank_branch_code");
      });

      $("#btnProses").on(ace.click_event, function () {
          do_process();
      });

	  $("#form_service_no").keyup(function(e){
		 if(e.keyCode == 13) { /* on enter */
			do_process();
		 }
	  });

	  $("#backButton").on(ace.click_event, function () {
          loadContentWithParams('pay_process-manual_payment.php', 
          {
                user_name       : $("#form_user_name").val(),
                password        : $("#form_password").val(),
                p_user_loket_id : $("#form_p_user_loket_id").val()
          });
      });

      $("#btnPembayaran").on(ace.click_event, function () {

          if($("#grid-selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', 'No data payment selected on table. Please put a check <span class="glyphicon glyphicon-check" /> on your data payment table');
			      return;
          }

          if($("#form_p_bank_branch_id").val() == "") {
                showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', '<h5> Please  <span class="label label-primary">Choose Counter *</span> for the payment </h5>');
                return;
          }

          BootstrapDialog.show({
                type: BootstrapDialog.TYPE_INFO,
                title: 'Payment Confirmation',
                message: 'Your Total Payment : <b> Rp. ' + $.number($("#form_summary_grand_total").val(), 2, '.', ',') + '</b>. Are You sure to make a payment?',
                buttons: [{
                    cssClass: 'btn-primary btn-sm',
                    label: 'Yes, Do Payment',
                    action: function(dialogItself) {
                        /* show progress bar modal */
                        dialogItself.close();
                    	execute_payment();
                    }
                }, {
                    icon: 'glyphicon glyphicon-remove',
                    cssClass: 'btn-danger btn-sm',
                    label: 'Cancel',
                    action: function(dialogItself){
                         dialogItself.close();
                    }
                }]
          });
      });
      
      $("#btnAddDeposit").on(ace.click_event, function () {
        
            if($("#grid-selection").bootgrid("getSelectedRows") == "") {
                showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', 'No data payment selected on table. Please put a check <span class="glyphicon glyphicon-check" /> on your data payment table');
			    return;
            } 
          
            var account_no = $("#grid-selection").bootgrid("getSelectedRows")[0];
            account_no = account_no.substr(account_no.indexOf("_")+1);
            
            var service_no = $("#form_service_no").val();
            var subscriber_id = $("#form_summary_subscriber_id").val();
            
            modal_lov_deposit_show(service_no, account_no, subscriber_id);
      });
    
      $("#form_summary_grand_total").on("change",function(){
            if( $(this).val() == 0 ) {
                $("#btnPembayaran").addClass("disabled");
            }else {
                $("#btnPembayaran").removeClass("disabled");    
            }
      });
      
});

function do_process() {

	/* cek input */
	if( $("#form_service_no").val() == "" ) {
	   showBootDialog(true, BootstrapDialog.TYPE_INFO, 'Information', 'Please Input Your Service Number');
	   return;
	}

    responseError = false; /* global var */

	create_stp_pay_acc_table(true, false);
	on_load_data_stp_pay_acc_table(true);
	set_payment_summary();
}


function create_stp_pay_acc_table(show_progressbar, is_after_payment) {

    if(show_progressbar) {
        var progressBarDialog = BootstrapDialog.show({
		    closable: false,
            type: BootstrapDialog.TYPE_PRIMARY,
    		title: 'Processing Your Request',
    		message: properties.bootgridinfo.progressbar
		});
    }

    $("#grid-selection").bootgrid("destroy");

    /************************** Start Setting Bootgrid ******************/
	$("#grid-selection").bootgrid({
	     formatters: {
            "payment_charge_amt" : function (column, row) {
				return $.number(row.payment_charge_amt, 2, '.',',') + '<input id="' + row.id + '-payment_charge_amt" readonly  type="hidden" value="' + row.payment_charge_amt + '" />';
            },
            "payment_vat_amt" : function (column, row) {
				return $.number(row.payment_vat_amt, 2, '.',',') + '<input id="' + row.id + '-payment_vat_amt" readonly  type="hidden" value="' + row.payment_vat_amt + '" />';
            },
            "stamp_duty_fee" : function (column, row) {
                return $.number(row.stamp_duty_fee, 2, '.',',') + '<input id="' + row.id + '-stamp_duty_fee" readonly  type="hidden" value="' + row.stamp_duty_fee + '" />';
            },
            "penalty_amount" : function (column, row) {
				return $.number(row.penalty_amount, 2, '.',',') + '<input id="' + row.id + '-penalty_amount" readonly  type="hidden" value="' + row.penalty_amount + '" />';
            }
         },
	     labels: {
	        loading     : properties.bootgridinfo.loading
	     },
	     rowCount:[10,25,50,100,-1],
		 navigation: 0,
	     ajax: true,
	     post: function () {
	         /* To accumulate custom parameter with the request object */
	         return {
	             service_no : $("#form_service_no").val()
	         };
	     },
	     requestHandler:function(request) {
	        if(request.sort) {
	            request.sortby = Object.keys(request.sort)[0];
	            request.sortdir = request.sort[request.sortby];
	            delete request.sort;
	        }
	        return request;
	     },
	     responseHandler:function (response) {

	        /* cek response if needed */
	        if(response.success == false) {
                if(show_progressbar) {
	                progressBarDialog.close();
	            }

	            if(!is_after_payment) { //kalau bukan pembayaran, maka tampilkan pesan error
	                showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
	                responseError = true;
	            }
	        }
	        return response;
	     },
	     url: "<?php echo PAYMENT_WS_URL.'ws.php?type=json&module=paymentccbs&class=payment&method=normal_payment'; ?>",
	     searchSettings:{
	        delay:100,
	        characters: 3
	     },
	     selection: true,
	     multiSelect: true,
	     rowSelect: false,
	     keepSelection: false,
	     sorting:false
	});
	/************************** End Setting Bootgrid ******************/

	resize_bootgrid();
}


function on_load_data_stp_pay_acc_table(is_close_dialog) {

    /* bootgrid on leaded data . hide filter, close progress bar, and show table */
    $("#grid-selection").bootgrid().on("loaded.rs.jquery.bootgrid",function(e){

       if(!responseError) {
           setTimeout( function(){

    			/* as default , all rows are selected */
    			var arr = new Array();
                for (var i = 0; i < $("#grid-selection").bootgrid("getCurrentRows").length; i++) {
                	arr[i] = $("#grid-selection").bootgrid("getCurrentRows")[i].id;
                }
                $("#grid-selection").bootgrid("select", arr);
                get_deposit_amount();

                $("#filter-group").hide();
                $("#table-group").show();

                if(is_close_dialog) close_all_dialogbox();
           }, 1000 );
       }else {
            $("#grid-selection").bootgrid("destroy");
       }
	});
}

function get_deposit_amount() {

    $.post( "<?php echo PAYMENT_WS_URL.'ws.php?type=json&module=paymentccbs&class=subscriber_deposit&method=get_deposit_amount'; ?>",
        {
            subscriber_id : $("#form_summary_subscriber_id").val()
        },
        function( data ) {
            $("#form_summary_deposit_amount").val(data.items);
        }, "json"
    );
}


function set_payment_summary() {
    reset_payment_summary();

    var totalInvoice = 0;
	var totalStampDuty = 0;
	var totalPenalty = 0;
	var grandTotal = 0;

	/* ketika row selected */
	$("#grid-selection").bootgrid().on("selected.rs.jquery.bootgrid", function (e, selectedRows){
    	var row,
		payment_charge_amt = 0,
		payment_vat_amt = 0,
		stamp_duty_fee = 0,
		penalty_amount = 0;

        for (var i = 0; i < selectedRows.length; i++) {
        	row = selectedRows[i];
            payment_charge_amt = $("#grid-selection").find("#" + row.id + "-payment_charge_amt").val();
			payment_vat_amt = $("#grid-selection").find("#" + row.id + "-payment_vat_amt").val();
			stamp_duty_fee = $("#grid-selection").find("#" + row.id + "-stamp_duty_fee").val();
			penalty_amount = $("#grid-selection").find("#" + row.id + "-penalty_amount").val();

            totalInvoice += parseInt(payment_charge_amt) + parseInt(payment_vat_amt);
			totalStampDuty += parseInt(stamp_duty_fee);
			totalPenalty += parseInt(penalty_amount);

			/* set subscriber id*/
            $("#form_summary_subscriber_id").val(row.subscriber_id);
        }

		grandTotal = totalInvoice + totalStampDuty - totalPenalty;
		$("#form_summary_total_invoice").val( totalInvoice );
		$("#form_summary_total_stamp_duty").val( totalStampDuty );
		$("#form_summary_total_penalty").val( totalPenalty );
		$("#form_summary_grand_total").val( grandTotal );
        
        $("#form_summary_grand_total").trigger("change");
    });

	/* ketika row deselected */
	$("#grid-selection").bootgrid().on("deselected.rs.jquery.bootgrid", function (e, deselectedRows){
    	var row,
		payment_charge_amt = 0,
		payment_vat_amt = 0,
		stamp_duty_fee = 0,
		penalty_amount = 0;

        for (var i = 0; i < deselectedRows.length; i++) {
        	row = deselectedRows[i];
            payment_charge_amt = $("#grid-selection").find("#" + row.id + "-payment_charge_amt").val();
			payment_vat_amt = $("#grid-selection").find("#" + row.id + "-payment_vat_amt").val();
			stamp_duty_fee = $("#grid-selection").find("#" + row.id + "-stamp_duty_fee").val();
			penalty_amount = $("#grid-selection").find("#" + row.id + "-penalty_amount").val();

            totalInvoice -= parseInt(payment_charge_amt) + parseInt(payment_vat_amt);
			totalStampDuty -= parseInt(stamp_duty_fee);
			totalPenalty -= parseInt(penalty_amount);
        }

		grandTotal = totalInvoice + totalStampDuty - totalPenalty;
		$("#form_summary_total_invoice").val( totalInvoice);
		$("#form_summary_total_stamp_duty").val( totalStampDuty );
		$("#form_summary_total_penalty").val( totalPenalty );
		$("#form_summary_grand_total").val( grandTotal );
		
		$("#form_summary_grand_total").trigger("change");
    });
    
    $("#form_summary_grand_total").trigger("change");
}

function execute_payment() {

    /* show progress bar */
    var progressBarDialog = BootstrapDialog.show({
		    closable: false,
            type: BootstrapDialog.TYPE_PRIMARY,
    		title: 'Processing Your Request',
    		message: properties.bootgridinfo.progressbar
		});

    $.post( "<?php echo PAYMENT_WS_URL.'ws.php?type=json&module=paymentccbs&class=payment&method=normal_payment'; ?>",
        {
            action              : "pay",
            service_no          : $("#form_service_no").val(),
            p_bank_branch_id    : $("#form_p_bank_branch_id").val(),
            i_id                : $("#grid-selection").bootgrid("getSelectedRows"),
            i_subscriberid      : $("#form_summary_subscriber_id").val(),
            cboxdeposit         : $("#form_summary_use_deposit").is(":checked") ? 'Y' : 'N',
            client_ip_address   : $("#form_client_ip_address").val(),
            /*p_user_loket_id     : $("#form_p_user_loket_id").val(),*/
            p_user_loket_id     : 1,
            user_name           : $("#form_user_name").val()
        },
        function( data ) {
            progressBarDialog.close();

            if(data.success) {
                showBootDialog(true, BootstrapDialog.TYPE_SUCCESS, 'Information', data.message);

                responseError = false; /* global var */
                create_stp_pay_acc_table(false, true);
                on_load_data_stp_pay_acc_table(false);
                set_payment_summary();

            }else {
                showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', data.message);
            }
        }, "json"
    );
}

function reset_payment_summary() {
    $("#form_summary_total_invoice").val(0);
	$("#form_summary_total_stamp_duty").val(0);
	$("#form_summary_total_penalty").val(0);
	$("#form_summary_grand_total").val(0);

	$("#form_summary_deposit_amount").val(0);
	$("#form_summary_use_deposit").attr("checked", false);
    $("#form_p_bank_branch_id").val("");
    $("#form_bank_branch_code").val("");
}

function close_all_dialogbox() {
    $.each(BootstrapDialog.dialogs, function(id, dialog){
        dialog.close();
    });
}


</script>