<?php
/*
Plugin Name: Ukrywanie zakładki w menu
Plugin URI: https://github.com/Vitusc/hide-wordpress-menu-tab-from-dashboard
Description: Wtyczka dodaje checkbox z zapytaniem "ukryć zakładkę?" dla każdego elementu menu w kokpicie. Po zaznaczeniu checkboxu ukrywa dany element menu na całej stronie.
Version: 1.0
Author: Vitusc
Author URL: https://github.com/Vitusc
License: GPL2
*/

function ukryj_zakladke_custom_fields( $item_id, $item, $depth, $args ) {
    $hide_menu_item = get_post_meta( $item_id, '_hide_menu_item', true );
    ?>
    <div class="field-hide">
        <label>
            <input type="checkbox" name="menu-item-hide-menu-item[<?php echo $item_id; ?>]" value="1" <?php checked( $hide_menu_item, '1' ); ?> />
            Ukryć zakładkę?
        </label>
    </div>
    <?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'ukryj_zakladke_custom_fields', 10, 4 );

function ukryj_zakladke_custom_fields_save( $menu_id, $menu_item_db_id, $args ) {
    $hide_menu_item = isset( $_POST['menu-item-hide-menu-item'][$menu_item_db_id] ) ? '1' : '';
    update_post_meta( $menu_item_db_id, '_hide_menu_item', $hide_menu_item );
}
add_action( 'wp_update_nav_menu_item', 'ukryj_zakladke_custom_fields_save', 10, 3 );

function ukryj_zakladki_w_menu( $items, $menu, $args ) {
    if ( ! is_admin() ) {
        foreach ( $items as $key => $item ) {
            $hide_menu_item = get_post_meta( $item->ID, '_hide_menu_item', true );
            if ( $hide_menu_item == '1' ) {
                unset( $items[$key] );
            }
        }
    }
    return $items;
}
add_filter( 'wp_get_nav_menu_items', 'ukryj_zakladki_w_menu', 10, 3 );
