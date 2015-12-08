<link rel="stylesheet" href="<?php echo BS_PATH; ?>jqwidgets/jqx.base.css" />
<script src="<?php echo BS_PATH; ?>jqwidgets/jqxcore.js"></script>
<script src="<?php echo BS_PATH; ?>jqwidgets/jqxdata.js"></script>
<script src="<?php echo BS_PATH; ?>jqwidgets/jqxtree.js"></script>

<div id="right-tree-menu" style="display:none;" class="modal aside" data-background="true" data-body-scroll="false" data-offset="true" data-placement="right" data-backdrop="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header-success">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					<strong>Tree Menu</strong>
				</div>
			</div>

			<div class="modal-body">
				<div id="tree-menu"></div>
			</div>
		</div><!-- /.modal-content -->

		<button type="button" data-toggle="modal" data-target="#right-tree-menu" class="aside-trigger btn btn-success btn-app btn-xs ace-settings-btn">
            <i class="ace-icon fa bigger-110 icon-only fa-plus" data-icon2="fa-minus" data-icon1="fa-plus"></i>
        </button>
	</div><!-- /.modal-dialog -->
</div>

<script>
function menu_hide_tree_menu() {
    if(($("#right-tree-menu").data('bs.modal') || {isShown: false}).isShown) {
        $("#right-tree-menu").modal("hide");        
    }
}

function menu_show_tree_menu() {
    if(!($("#right-tree-menu").data('bs.modal') || {isShown: false}).isShown) {
        $("#right-tree-menu").modal("show");     
    }        
}

jQuery(function($) {
    $("#right-tree-menu").ace_aside();
    
    var data = [
        <?php
            $ci =& get_instance();
            $p_application_id = getVarClean("p_application_id","int",0);
            /*$sql = "select p_menu_id, parent_id, menu, file_name as path_file_name,  description
                    from ifl.f_display_menu_tree ($p_application_id)";*/
            $sql = "SELECT p_menu_id p_menu_id, NVL (parent_id, 0) parent_id, menu, path_file_name, description "
            		. "FROM (SELECT   p_menu_id, parent_id, code menu, NVL (file_name, '0') path_file_name, description, "
            		. "listing_no FROM p_menu WHERE is_active = 'Y' AND p_application_id = ".$p_application_id." "
            		. "ORDER BY NVL (parent_id, 0), listing_no) x START WITH nvl(parent_id,0) = 0 "
            		. "CONNECT BY PRIOR x.p_menu_id = x.parent_id ORDER SIBLINGS BY NVL (listing_no, 9999)";
		        
            $query = $ci->db->query($sql);
            $items = $query->result_array();
            
            $ci->load->model('adm_sistem/p_application');
		    $itemApp = $ci->p_application->get($p_application_id);
            
            /*
                structure
                [{ "id": "2",
                  "parentid": "0",
                  "text": "Hot Chocolate"
                }]
            */
            
            $total = count($items);
            echo '{';
            echo '"icon" : "'.BS_PATH.'jqwidgets/images/home.png",';
            echo '"expanded" : true,';
            echo '"id" : 0,';
            echo '"parentid" : -1,';
            echo '"text" : "'.$itemApp['code'].'"';
            if($total > 0)
            echo '},';
            else
            echo '}';
                
            for($i = 0; $i < $total; $i++) {
                $items[$i] = array_change_key_case($items[$i], CASE_LOWER);
                $icon = '"icon" : "'.BS_PATH.'jqwidgets/images/folder-close.png",';
                $sql = "SELECT count(1) as total FROM p_menu WHERE parent_id = ".$items[$i]['p_menu_id'];
                $query = $ci->db->query($sql);
		        $row = array_change_key_case($query->row_array(), CASE_LOWER);
		        
		        if($row['total'] == 0) 
                    $icon = '"icon" : "'.BS_PATH.'jqwidgets/images/file-icon.png",';
                    
                echo '{';
                echo $icon;
                echo '"id" : "'.$items[$i]['p_menu_id'].'",';
                echo '"parentid" : "'.$items[$i]['parent_id'].'",';
                echo '"text" : "'.$items[$i]['menu'].'"';
                if($i != ($total-1))
                echo '},';
                else
                echo '}';
            }
        ?>
    ];

    // prepare the data
    var source =
    {
        datatype: "json",
        datafields: [
            { name: 'id' },
            { name: 'parentid' },
            { name: 'expanded' },
            { name: 'text' },
            { name: 'icon' },
        ],
        id: 'id',
        localdata: data
    };

    // create data adapter.
    var dataAdapter = new $.jqx.dataAdapter(source);
    dataAdapter.dataBind();
    var records = dataAdapter.getRecordsHierarchy('id', 'parentid', 'items', [{ name: 'text', map: 'label'}]);
    $('#tree-menu').jqxTree({source: records, toggleMode: 'click'});

    $('#tree-menu').on('select', function (event) {
        var item = $('#tree-menu').jqxTree('getItem', event.args.element);
        //do whatever with item
        $("#menu_form_add_edit").hide();
        $("#form_parent_id").val(item.id);
        $("#form_parent_code").val(item.label);
        $("#menu_code_selected").html('<i class="ace-icon fa fa-angle-double-right"></i> ' + item.label);
        menu_reload_table();
	});
	
    menu_show_tree_menu();
});
</script>