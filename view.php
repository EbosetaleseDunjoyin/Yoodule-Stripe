<?php

$nameddd = 'golddigger';

//<button type="submit"  class="btn btn-primary mb-3">Save</button>
$publishable_key = $_POST['publishable_key'];
$secret_key = $_POST['secret_key'];


 
global $wpdb;
$table_name = $wpdb->prefix . 'stripe_api';
$wpdb->show_errors();
if ( isset( $_POST['submit'] ) ){
    
    $result = $wpdb->insert($table_name, array("publishable_key" => $publishable_key, "secret_key" => $secret_key), array("%s", "%s"));
    if ($result == 1) {
        # code...
        echo '<script>alert("Saved")</script>';
    } else {
        echo '<script>alert("Not saved")</script>';
    }
}
 

$posts = $wpdb->get_results("SELECT id,publishable_key,secret_key FROM $table_name ORDER BY id DESC LIMIT 1");

if (count($posts)> 0) {
    foreach ($posts as $key => $value) {
        # code...
        $publish =  $value->publishable_key;
        $secret__key =  $value->secret_key;
        // echo "Publishable Key = ". $publish ."<br>";
        // echo "Secret Key = ". $secret__key . "<br>";
    }
    
}
echo $secret__key;
$render =
"
<!doctype html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Bootstrap demo</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor' crossorigin='anonymous'>
</head>

<body>
    <div class='container'>
        <form  id='submit' class='row g-3 my-5' action='' method='POST' >
            <div class='col-auto'>
                <label for='staticEmail2' class='fs-6 mt-2'>Api Key</label>
            
            </div>
            <div class='col-auto'>
                <label for='inputPassword2' class='visually-hidden'>Publishable</label>
                <input type='text' class='form-control' id='inputPassword2' name='publishable_key' placeholder='Publishable Key'>
            </div>
            <div class='col-auto'>
                <label for='inputPassword2' class='visually-hidden'>Secret</label>
                <input type='text' class='form-control' id='inputPassword2' name='secret_key' placeholder='Secret Key'>
            </div>
            <div class='col-auto'>
                <input type='submit' value='Save' name='submit' class='btn btn-primary mb-3' >
                
            </div>
        </form>

         <div class='row mb-5'> ";

         if (count($posts) > 0) {
            # code...
            
            $render.=  "<h6 class='text'> 
                Secret key = $secret__key<br>
                </h6>
            <h6 class='text'>
                    Publishable key = $publish <br>
                </h6>
            ";
        }else{
            $render .= " <div class=''>No Api key saved yet</div>";
        }
      $render.= " 
      </div>
      <div class='row mb-5'>
            <div class='col-12'>
                <h5 class='text'>
                 Shortcode [yoodule_stripe]
                </h5>
            </div>
        </div>
       
    </div>
    

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js' integrity='sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2' crossorigin='anonymous'></script>
</body>

</html>

";

echo $render;




?>

