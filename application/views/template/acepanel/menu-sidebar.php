<style>
    .menu-text {
        font-size:11px;
    }
</style>
<?php
class MenuBuilder {

      /**
       * Menu items
       */
      var $items = array();

      /**
       * HTML contents
       */
      var $html  = array();

      function MenuBuilder() {
          //do nothing
      }

      /**
       * Get all menu items from database
       */
      function get_menu_items() {
            $ci = & get_instance();
            $ci->load->model('adm_sistem/p_menu');
            $tmenu = $ci->p_menu;

            $isadmin = false;

            if ( $ci->session->userdata('p_user_id') == 1 ) $isadmin = true;
            $sql = "SELECT COUNT(1) jml FROM p_user_role WHERE p_role_id = 1 AND p_user_id =" .$ci->session->userdata('p_user_id');
            $query = $tmenu->db->query($sql);
		    $row = array_change_key_case($query->row_array(), CASE_LOWER);

		    if($row['jml'] > 0) {
		        $isadmin = true;
		    }

            return $tmenu->getMenuItems($isadmin, $ci->session->userdata('module_id'), $ci->session->userdata('p_user_id'));
      }

      function beauty_menu($text) {
        if(strlen($text) > 16) {
            return substr($text,0,14)."..";
        }
        return $text;
      }

      /**
       * Build the HTML for the menu
       */
      function get_menu_html( $root_id = 0 )
      {
              $this->html  = array();
              $this->items = $this->get_menu_items();

              foreach ( $this->items as $item ) {
                    $item = array_change_key_case($item, CASE_LOWER);
                    $children[$item['parent_id']][] = $item;
              }

              // loop will be false if the root has no children (i.e., an empty menu!)
              $loop = !empty( $children[$root_id] );

              // initializing $parent as the root
              $parent = $root_id;
              $parent_stack = array();

              // HTML wrapper for the menu (open)
              $this->html[] = '<ul class="nav nav-list">';
              $this->html[] = '<li class="nav-menu-content active" title ="DASHBOARD" data-source="dashboard.php">
                                   <a href="#">
                                       <i class="menu-icon fa fa-tachometer"></i>
                                   	<span class="menu-text"> <i> DASHBOARD </i></span>
                                   </a>
                               </li>';

              while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) )
              {
                      if ( $option === false )
                      {
                              $parent = array_pop( $parent_stack );

                              // HTML for menu item containing childrens (close)
                              $this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 ) . '</ul>';
                              $this->html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ) . '</li>';
                      }
                      elseif ( !empty( $children[$option['value']['p_menu_id']] ) )
                      {
                              $tab = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 );

                              $icon_parent = 'menu-icon fa fa-folder-open';
                              if(!empty($option['value']['menu_icon'])) {
                                    $icon_parent = str_replace("ace-icon","menu-icon",$option['value']['menu_icon']);
                              }
                              // HTML for menu item containing childrens (open)
                              $this->html[] = sprintf(
                                      '%1$s<li class="" title="%2$s">
                                      <a href="#" class="dropdown-toggle">
                                      	<i class="%3$s"></i>
                                      	<span class="menu-text">
                                      	    %4$s
                                      	</span>
                                      	<b class="arrow fa fa-angle-down"></b>
                                      </a>
                                      <b class="arrow"></b>
                                      ',
                                      $tab,                                          // %1$s = tabulation
                                      $option['value']['title'],                     // %2$s = title
                                      $icon_parent,                                  // %3$s = icon
                                      $this->beauty_menu($option['value']['menu'])   // %4$s = menu
                              );
                              $this->html[] = $tab . "\t" . '<ul class="submenu">';

                              array_push( $parent_stack, $option['value']['parent_id'] );
                              $parent = $option['value']['p_menu_id'];
                      }
                      else {

                              $icon_leaf = 'menu-icon glyphicon glyphicon-file';
                              if(!empty($option['value']['menu_icon'])) {
                                    $icon_leaf = str_replace("ace-icon","menu-icon",$option['value']['menu_icon']);
                              }

                              // HTML for menu item with no children (aka "leaf")
                              $this->html[] = sprintf(
                                      '%1$s<li class="nav-menu-content" data-source="%2$s" title="%3$s">
                                                <a href="#">
                                                    <i class="%4$s"></i>
                                                	<span class="menu-text"> <i>%5$s</i> </span>
                                                </a>
                                                <b class="arrow"></b>
                                            </li>',
                                      str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ),   // %1$s = tabulation
                                      str_replace("/","-",$option['value']['file_name']),   // %2$s = file_name (URL)
                                      $option['value']['title'],                            // %3$s = title,
                                      $icon_leaf,                                           // %4$s = icon,
                                      $this->beauty_menu($option['value']['menu'])          // %5$s = menu
                              );
                      }
              }

              // HTML wrapper for the menu (close)
              $this->html[] = '</ul>';

              return implode( "\r\n", $this->html );
      }

}

$menu = new MenuBuilder();
echo $menu->get_menu_html();

?>