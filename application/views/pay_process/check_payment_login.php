<?php
function check_payment_login($url_redirect) {

    /* ----------------------- check login first ------------------------ */
    if( getVarClean("p_user_loket_id","str","") == "" or
            getVarClean("user_name","str","") == "" or 
            getVarClean("password","str","") == "") :
        echo '<script>
                loadContentWithParams("pay_process-payment_login.php",{
                    url_redirect : "'.$url_redirect.'"
                });
            </script>';
            exit;
    else :

         /* re-check login */
        $user_name = getVarClean("user_name","str","");
        $password = getVarClean("password","str","");
        
    	$data = file_get_contents(PAYMENT_WS_URL.'ws.php?type=json&module=paymentccbs&class=p_user_loket&method=valid_login&user_name='.$user_name.'&password='.$password);
        $data = json_decode($data, true);
        
        $p_user_loket_id = $data['rows'];
                
    	if($p_user_loket_id < 0) :
    	    echo '<script>
                loadContentWithParams("pay_process-payment_login.php",{
                    url_redirect : "'.$url_redirect.'"
                });
            </script>';
            exit;
    	endif;
    endif;
    /* ----------------------- end check login ------------------------ */
}
?>
