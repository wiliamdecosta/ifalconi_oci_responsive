<?php
/**
 * payment
 * class controller for table p_bank
 *
 * @since 08/06/2015 13:25:03
 * @author wiliamdecosta@gmail.com
 */
class subscriber_deposit_controller extends wbController{  
  
    public static function get_deposit_amount($args = array()){
        
        $data = array('rows' => array(), 'total' => 0, 'success' => false, 'message' => '');

        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'paymentccbs','class' => 'subscriber_deposit', 'method' => 'get_deposit_amount', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '');
			
            $ws_data = self::getResultData($ws_client, $params);
            /*if($ws_data['data'] == null) {
                throw new Exception($ws_data['message']);
            }*/
            
            $data['items']       = $ws_data ['data'];
            $data['total']      = $ws_data ['total'];
            
            $data['message']    = $ws_data ['message'];
            $data['success']    = $ws_data ['success'];

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }
        echo json_encode($data);
        exit;
        
    }
}
?>