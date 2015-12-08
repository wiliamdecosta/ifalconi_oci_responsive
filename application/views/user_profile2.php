<div class="col-sm-offset-1 col-sm-10">

    <form class="form-horizontal">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-16">
    			<li class="active">
    				<a href="#edit-basic" data-toggle="tab">
    					<i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
    					Basic Info
    				</a>
    			</li>
    			<li>
    				<a href="#edit-password" data-toggle="tab">
    					<i class="blue ace-icon fa fa-key bigger-125"></i>
    					Password
    				</a>
    			</li>
    		</ul>
    		<div class="tab-content profile-edit-tab-content">
    		    <div class="tab-pane in active" id="edit-basic">
    				<h4 class="header blue bolder smaller">General</h4>
    				
    				<!-- user id -->
    				<div class="form-group">
    					<div class="col-sm-9">
    						<input type="hidden" id="form-field-userid">
    					</div>
    				</div>
    				
    				<!-- username -->
    				<div class="form-group">
    					<label for="form-field-username" class="col-sm-3 control-label no-padding-right">Username</label>
    
    					<div class="col-sm-9">
    					    <div class="input-medium">
    					        <div class="input-group">
    						        <input type="text" value="" placeholder="Username" readonly id="form-field-username" class="col-xs-12 col-sm-10">
    					        </div>
    					    </div>
    					</div>
    				</div>
    				<div class="space-4"></div>
    				
    				
    				<!-- nama lengkap -->
    				<div class="form-group">
    					<label for="form-field-fullname" class="col-sm-3 control-label no-padding-right">Full Name</label>
    					<div class="col-sm-9">
    						<input type="text" class="col-xs-10 col-sm-5" placeholder="Fullname" id="form-field-fullname">
    					</div>
    				</div>
    					
    				<div class="space-4"></div>
    				
    				<div class="space"></div>
    				<h4 class="header blue bolder smaller">Contact</h4>
    
    				<div class="form-group">
    					<label for="form-field-email" class="col-sm-3 control-label no-padding-right">Email</label>
    					<div class="col-sm-9">
    						<span class="input-icon input-icon-right">
    							<input type="email" value="" id="form-field-email">
    							<i class="ace-icon fa fa-envelope"></i>
    						</span>
    					</div>
    				</div>
    
    				<div class="space"></div>
    				
    			</div>
    		    <div class="tab-pane" id="edit-password">
    				<div class="space-10"></div>
    
    				<div class="form-group">
    					<label for="form-field-pass1" class="col-sm-3 control-label no-padding-right">New Password</label>
    
    					<div class="col-sm-9">
    						<input type="password" id="form-field-pass1">
    					</div>
    				</div>
    
    				<div class="space-4"></div>
    
    				<div class="form-group">
    					<label for="form-field-pass2" class="col-sm-3 control-label no-padding-right">Confirm Password</label>
    
    					<div class="col-sm-9">
    						<input type="password" id="form-field-pass2">
    					</div>
    				</div>
    			</div>
    		</div> <!-- /.tab-content -->						
        </div> <!-- /.tabbabel -->
        
        <div class="clearfix form-actions">
    		<div class="col-md-offset-3 col-md-9">
    			<button type="button" class="btn btn-info" id="btn-update">
    				<i class=""></i>
    				Update
    			</button>
    		</div>
    	</div>
    														
    </form> <!-- /.form-horizontal -->		            
</div>
	    
<script>
    jQuery(function($) {
        $.post( "<?php echo WS_URL.'adm_sistem.p_user_controller/getInfo'; ?>", function( response ) {
            var item = response.data;
            
            $("#form-field-userid").val( item.p_user_id );
            $("#form-field-username").val( item.user_name );
            $("#form-field-fullname").val( item.full_name );
            $("#form-field-email").val( item.email_address );
            
        });
        
        $("#btn-update").on(ace.click_event,function(){
            if(validForm()) {
                
                $.post( "<?php echo WS_URL.'adm_sistem.p_user_controller/UpdateInfo'; ?>", 
                {
                    user_id: $("#form-field-userid").val(),
                    user_name: $("#form-field-username").val(),
                    user_realname : $("#form-field-fullname").val(),
                    user_email: $("#form-field-email").val(),
                    user_password1: $("#form-field-pass1").val(), 
                    user_password2: $("#form-field-pass2").val()
                },
                function( response ) {
                    if(response.success) {
                        showBootDialog(true,
                                        BootstrapDialog.TYPE_SUCCESS,
                                        'Information',
                                        response.message);
                        loadContent('user_profile2.php');                                        
                    }else {
                        showBootDialog(true,
                                        BootstrapDialog.TYPE_WARNING,
                                        'Attention',
                                        response.message);
                    }
                });
                
            }else {
                
            }
        });
    });
    
    function validForm() {
        return true;
    }
    
    
</script>