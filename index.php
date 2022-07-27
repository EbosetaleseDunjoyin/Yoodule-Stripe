<?php

/**
 * Plugin Name: Yoodule Stripe
 * Description: Thss plugin is used to help with your Stripe Api integration
 * Author: Ebose
 * Version: 1.0.0
 */
require_once('vendor/autoload.php');


//MENU LOGO
add_action( 'admin_menu', 'yac_stripe_page' );
function yac_stripe_page() {
    add_menu_page(
        'Yoodule Stripe',
        'Yoodule Stripe',
        'manage_options',
        plugin_dir_path(__FILE__) . 'view.php',
        null,
        'dashicons-buddicons-forums',
        20
    );
};

//CREATE DB

register_activation_hook(__FILE__, 'my_plugin_create_db');

function my_plugin_create_db()
{

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'stripe_api';

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		publishable_key varchar(255),
        secret_key varchar(255),
		UNIQUE KEY id (id)
	) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


//SHORTCODE INTEGRATION
function yoodule_stripe_shortcode()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'stripe_api';
    $wpdb->show_errors();

    $posts = $wpdb->get_results("SELECT id,publishable_key,secret_key FROM $table_name ORDER BY id DESC LIMIT 1");

    if (count($posts) > 0) {
        foreach ($posts as $key => $value) {
            # code...
            $publish =  $value->publishable_key;
            $secret__key =  $value->secret_key;
           
        }
    }
    $stripe = new \Stripe\StripeClient(
        $secret__key
    );
   
    $list = $stripe->prices->all();

    
    // Things that you want to do.
    $list_time = json_encode($list);
    
    $message =
    "<!doctype html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Bootstrap demo</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor' crossorigin='anonymous'>
    <link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css'>
  


</head>

<body>
    <div class='container'>
       
       
       

        <div class='row my-5'>
           <table id='table_id' class='display'>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Unit Amount</th>
                        <th>Currency</th>
                        <th>Product</th>
                        
                    </tr>
                </thead>
                <tbody>
                   ";

    foreach ($list as $key => $value) {
        # code...
        $message.= '<tr>';
       $message.=  '<td>'. $value->id . '</td>';
       $message.=  '<td>'. $value->unit_amount / 100 . '</td>';
       $message.=  '<td>'. $value->currency . '</td>';
       $message.=  '<td>'. $value->product . '</td>';
        // $price_amount = $value->unit_amount / 100;
        // $price_currency = $value->currency;
        // $price_product = $value->product;
        $message .= '</tr>';


        // $list_id = json_encode($price_id);
    }
    $message.=  " 
                </tbody>
            </table>
        </div>
    </div>
    
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js' integrity='sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==' crossorigin='anonymous' referrerpolicy='no-referrer'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js' integrity='sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2' crossorigin='anonymous'></script>
    <script type='text/javascript' charset='utf8' src='https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js'></script><script type='text/javascript' charset='utf8' src='https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js'></script>
    <script>
        $(document).ready( function () {
            $('#table_id').DataTable();
        } );
        var data = $list->data
    </script>
    </body>

</html>

";
    $render = "Hello world";
    // Output needs to be return
   
    return $message;
}
// register shortcode
add_shortcode('yoodule_stripe', 'yoodule_stripe_shortcode');
