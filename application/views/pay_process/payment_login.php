<script src="<?php echo BS_PATH; ?>bootgrid/properties.js"></script>
<script src="<?php echo BS_PATH; ?>encrypt/jquery.md5.js"></script>

<div class="row">
    <div class="col-xs-12">
        <div class="well well-sm">
		    <div class="inline middle pink2 bigger-150"> Payment Login </div>
		</div>
		<span class="brown center bigger-110"> <p> You need to login first to access this menu. Please logging in by using your counter user information. </p></span>

		<div class="login-container">
		    <div class="widget-box">
		        <div class="widget-header widget-header-flat">
					<h4 class="smaller">
    				    <i class="ace-icon fa fa-credit-card green"></i>
    				    Please Enter Your Information
					</h4>
				</div>
    		    <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" role="form">
                			<div class="form-group">
                			    <div class="col-sm-12">
                                    <span class="block input-icon input-icon-right">
                                        <input type="hidden" id="form_url_redirect" value="<?php echo getVarClean("url_redirect","str",""); ?>">
                    					<input type="text" id="form_user_name" placeholder="Username" class="form-control">
                    					<i class="ace-icon fa fa-user"></i>
                    				</span>
                			    </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <span class="block input-icon input-icon-right">
                    					<input type="password" id="form_password" placeholder="Password" class="form-control">
                    					<i class="ace-icon fa fa-lock"></i>
                    				</span>
                			    </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn width-35 pull-right btn-sm btn-pink" type="button" id="btn-login">
                    					<i class="ace-icon fa fa-key"></i>
                    					<span class="bigger-110">Login</span>
                    				</button>
                				</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function check_login() {

    if($.trim($("#form_user_name").val()) == "" ||
        $.trim($("#form_password").val()) == "" ) {

        showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', 'Username and Password must be filled');
        return;
    }

    var progressBarDialog = BootstrapDialog.show({
	    closable: false,
        type: BootstrapDialog.TYPE_PRIMARY,
    	title: 'Processing Your Request',
    	message: properties.bootgridinfo.progressbar
	});

    $.post( "<?php echo WS_URL.'pay_param.p_user_loket_controller/login_payment'; ?>",
        {
            user_name : $("#form_user_name").val(),
            password : $("#form_password").val()
        },
        function( response ) {
            progressBarDialog.close();
            if(response.success == false) {
                showBootDialog(true, BootstrapDialog.TYPE_WARNING, 'Attention', response.message);
            }else {
    	        loadContentWithParams($("#form_url_redirect").val(), {
                    p_user_loket_id : response.items,
                    user_name : $("#form_user_name").val(),
                    password : $("#form_password").val()
                });
            }
        }, "json"
   );
}

jQuery(function($) {

     $("#form_user_name").keyup(function(e){
	 	 if(e.keyCode == 13) { /* on enter */
			check_login();
		 }
	 });

	 $("#form_password").keyup(function(e){
	 	 if(e.keyCode == 13) { /* on enter */
			check_login();
		 }
	 });

     $("#btn-login").on(ace.click_event, function() {
        check_login();
     });

});


</script>