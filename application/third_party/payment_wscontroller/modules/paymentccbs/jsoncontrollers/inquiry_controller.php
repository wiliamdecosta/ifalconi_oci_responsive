<?php
/**
 * inquiry
 * class controller for table bds_inquiry
 *
 * @since 23-10-2012 12:07:20
 * @author wiliamdecosta@gmail.com
 */
class inquiry_controller extends wbController{
    /**
     * read
     * controler for get all items
     */
    public static function read($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);

        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');

        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'read', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
           
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = $ws_data ['success'];
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }
        return $data;

    }

    /**
     * create
     * controler for create new item
     */
    public static function create($args = array()){
        // Security check
        //if (!wbSecurity::check('DHotel')) return;

        // Get arguments from argument array
        $jsonItems = wbRequest::getVarClean('items', 'str', '');
        $items =& wbUtil::jsonDecode($jsonItems);

        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');

        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
		    			'getParams' => json_encode($_GET),
		    			'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'create', 'type' => 'json' )),
		    			'postParams' => json_encode($_POST),
		    			'jsonItems' => '',
		    			'start' => $start,
		    			'limit' => $limit);
            $ws_data = self::getResultData($ws_client, $params);
        
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = $ws_data ['success'];
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    /**
     * update
     * controler for update item
     */
    public static function update($args = array()){
        // Security check
        //if (!wbSecurity::check('DHotel')) return;

        // Get arguments from argument array
        $jsonItems = wbRequest::getVarClean('items', 'str', '');
        $items =& wbUtil::jsonDecode($jsonItems);
        
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
		    			'getParams' => json_encode($_GET),
		    			'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'update', 'type' => 'json' )),
		    			'postParams' => json_encode($_POST),
		    			'jsonItems' => '',
		    			'start' => $start,
		    			'limit' => $limit);
            $ws_data = self::getResultData($ws_client, $params);
                    
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = $ws_data ['success'];
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    /**
     * update
     * controler for remove item
     */
    public static function destroy($args = array()){
        // Security check
        //if (!wbSecurity::check('DHotel')) return;

        // Get arguments from argument array
        $jsonItems = wbRequest::getVarClean('items', 'str', '');
        $items =& wbUtil::jsonDecode($jsonItems);
        
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');

        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
		    			'getParams' => json_encode($_GET),
		    			'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'destroy', 'type' => 'json')),
		    			'postParams' => json_encode($_POST),
		    			'jsonItems' => '',
		    			'start' => $start,
		    			'limit' => $limit);
            $ws_data = self::getResultData($ws_client, $params);
        
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = $ws_data ['success'];
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }
        return $data;
    }
    
    /*
    * IMPORT WALKIN
    */
    public static function upload_excel($args = array()){
        
        include('lib/excel/reader.php');
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        
        global $_FILES;
        try {
            if(empty($_FILES['excel_file']['name'])){
                throw new Exception('File tidak boleh kosong');        
            }
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            echo json_encode($data);
            session_write_close();
            exit;
        }
        
        $jsonItems = wbRequest::getVarClean('items', 'str', '');
        $items =& wbUtil::jsonDecode($jsonItems);
        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }
        
        $file_name = $_FILES['excel_file']['name'];
        $file_location = 'var/uploadexcel/'.$file_name;
       
        if (!move_uploaded_file($_FILES['excel_file']['tmp_name'], $file_location)){
            throw new Exception("Upload file gagal");
        }
        
        $xl_reader =& new Spreadsheet_Excel_Reader();
		$res = $xl_reader->_ole->read($file_location);
        
        if($res === false) {
        	if($xl_reader->_ole->error == 1) {
        		$data['message'] = 'Harus File Excel';
                echo json_encode($data);
                session_write_close();
                exit;
        	}
        }
        
        try{
	         $xl_reader->read($file_location);
             $firstColumn = $xl_reader->sheets[0]['cells'][1][1];
             
             if($firstColumn != 'Kode Hotel') {
                 throw new Exception('Format Table Salah');
             }
                          
             /* pengecekkan semua data */
             for($i = 2; $i <= $xl_reader->sheets[0]['numRows']; $i++) {
                   
                   $kode_hotel = $xl_reader->sheets[0]['cells'][$i][1];
                   $nama_hotel =  $xl_reader->sheets[0]['cells'][$i][2];
         
                   if(empty($kode_hotel) or $kode_hotel == 'Keterangan') break;  
                   
                   if(empty($nama_hotel)) {
                        throw new Exception('Nama Hotel (Kolom 2) pada baris '.($i-1).' Tidak boleh kosong'); 
                   }
                    
             }
                
        } catch(Exception $e) {
                $data['message'] = $e->getMessage();
                echo json_encode($data);
                session_write_close();
                exit;
        }     
             
             /* insert data */
        $recInsert = array();
        
        $items = array();
        try {
           
           for($i = 2; $i <= $xl_reader->sheets[0]['numRows']; $i++) {
              
              $kode_hotel = $xl_reader->sheets[0]['cells'][$i][1];
              $nama_hotel =  $xl_reader->sheets[0]['cells'][$i][2];
              $kelas =  $xl_reader->sheets[0]['cells'][$i][3];
              $jumlah_kamar =  $xl_reader->sheets[0]['cells'][$i][4];
              $alamat =  $xl_reader->sheets[0]['cells'][$i][5];
              $kota =  $xl_reader->sheets[0]['cells'][$i][6];
              $kode_pos =  $xl_reader->sheets[0]['cells'][$i][7];
              $telepon =  $xl_reader->sheets[0]['cells'][$i][8];
              $website =  $xl_reader->sheets[0]['cells'][$i][9];
              
              if(empty($kode_hotel) or $kode_hotel == 'Keterangan') break;  
               
              
              $recInsert['code'] = $kode_hotel;
              $recInsert['hotel_name'] = $nama_hotel;
              $recInsert['kelas_id'] = $kelas;
              $recInsert['jml_kamar'] = $jumlah_kamar;
              $recInsert['address_1'] = $alamat;
              $recInsert['kota'] = $kota;
              $recInsert['kode_pos'] = $kode_pos;
              $recInsert['phone_no'] = $telepon;
              $recInsert['website'] = $website;
              
              //$table->setRecord($recInsert);
              //$table->create();
              $items[] = $recInsert;
           }
           
           $ws_client = self::getNusoap();
		   $params = array('search' => '',
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'upload_excel', 'type' => 'json' )),
					'jsonItems' => json_encode(array('items' => $items))
					);
					
           $ws_data = self::getResultData($ws_client, $params);
           
           
           $data['success'] = true;
    	   $data['message'] = 'Data berhasil disimpan';
           $data['items'] = $items;
           
        }catch(Exception $e) {
           $data['message'] = $e->getMessage();
           echo json_encode($data);
           session_write_close();
           exit;
        }
	   
	   echo json_encode($data);
       session_write_close();
       exit;
        
    } 
    public static function execPembayaran($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');

        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'execPembayaran', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['data'];
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
    public static function cancelPembayaran($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'cancelPembayaran', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = $ws_data ['success'];
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
     public static function execCancelPembayaran($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'execCancelPembayaran', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = $ws_data ['success'];
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
    public static function reqCancelInquiry($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'reqCancelInquiry', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = $ws_data ['success'];
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
    public static function reqCancelStatus($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'reqCancelStatus', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = $ws_data ['success'];
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
    public static function submitCancel($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'submitCancel', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $data['items']['os_cancel_info'];
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
     public static function report($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'report', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
    public static function getBankBranch($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'p_bank_branch', 'method' => 'read', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
    public static function duplicateInquery($args = array()){
        // Security check
        //if (!wbSecurity::check('Inquiry')) return;

        // Get arguments from argument array
        //extract($args);
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'duplicateInquery', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
    public static function sentAjax($args = array()){
        $data['items'] = 'tes';
        $data['total'] = '1';
        $data['message'] = 'message';
        $data['success'] = true;
        return $data;

    }
    public static function execReject($args = array()){
        $data = array('items' => array(), 'total' => 0, 'success' => false, 'message' => '');
        try{
            $ws_client = self::getNusoap();
		    $params = array('search' => '',
					'getParams' => json_encode($_GET),
					'controller' => json_encode(array('module' => 'bds','class' => 'inquiry', 'method' => 'execReject', 'type' => 'json' )),
					'postParams' => json_encode($_POST),
					'jsonItems' => '',
					'start' => $start,
					'limit' => $limit);
					
            $ws_data = self::getResultData($ws_client, $params);
            $data['items'] = $ws_data ['data'];
            $data['total'] = $ws_data ['total'];
            $data['message'] = $ws_data ['message'];
            $data['success'] = true;
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
            $data['success'] = false;
        }
        return $data;

    }
    
}
?>