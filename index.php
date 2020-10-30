<?php
/***
* Direct Drive
* @auth: Monzurul Hasan
* @file: index.php
* @date: 30/10/2020
*/

//error_reporting(E_ALL);
//ini_set("display_errors", 1);
require_once('logic.php');

$this_file = "/main/";
$dw_file = "/d/";

function load_url($lu){
  $ft = $_SERVER['SCRIPT_NAME'];
  $rui = $_SERVER['REQUEST_URI'];
  if($ft == $rui){
    header('location: '.$lu);
  }
}
load_url($this_file);

$error = $url = "";
$has_id = false;

if(isset($_POST['gdurl'])){
  $url = trim($_POST['gdurl']);
  if(filter_var($url, FILTER_VALIDATE_URL)){

    // Get id from url..
    $url_components = parse_url($url); 
    parse_str($url_components['query'], $url_params);

    if(isset($url_params['id'])){
      $id = $url_params['id'];
      $has_id = true;
    } else {
      $exu = explode('/', $url);
      $exi = array_search("d", $exu, true);
      if(!empty($exi)){
        $n_exi = $exi + 1;
        $id = $exu[$n_exi];
        $has_id = true;
      } else {
        $has_id = false;
      }
    }
    // ^^

    $logic = new logic($id);
    $dLogic = $logic->dLogic();
  
    if($dLogic['response_code'] == 200){
      header('location: '.$dw_file.$id.'/view');
    } elseif(!$has_id) {
      $error = '<div class="msg">Url is not valid!</div>';
    } elseif(!$dLogic['response_code'] or empty($dLogic['response_code']) or $dLogic['response_code'] == 404){
      $error = '<div class="msg"><b>Err:: 404_NOT_FOUND</b><br>Your file link maybe broken. Please enter correct link!</div>';
    } elseif(isset($dLogic['error'])) {
      $error = '<div class="msg"><b>Err:: '.$dLogic['response_code'].'_'.$dLogic['error'].'</b></div>';
    } else {
      $error = '<div class="msg"><b>An error happened while browsing the file link!</b></div>';
    }
  } else {
    $error = '<div class="msg">Url is not valid</div>';
  }
}


?>
<!DOCTYPE html>
<html>
  <head>
    <title>Direct Drive</title>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <style>
      *{
        font-family: sans-serif;
      }
      body{
        margin: auto;
        max-width: 600px;
        padding: 5px;
      }
      h2{
        text-align: center;
        border-top: 3px solid #BBDEFB;
        border-bottom: 3px solid #BBDEFB;
        color: #1870E8;
        padding: 5px;
        user-select: none;
        margin-top: 5px;
        margin-bottom: 20px;
        background-color: #E3F2FD;
      }
      form{
        text-align: center;
      }
      .hat{
        width: 240px;
        border: 2px solid #1870E8;
        border-right: 0;
        display: inline-block;
        resize: vertical;
        border-radius: 3px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        outline: none;
        font-size: 13px;
        padding: 5px;
      }
      .has{
        display: inline-block;
        outline: none;
        padding: 5px 10px;
        border: 2px solid #1565C0;
        border-left: 0;
        font-size: 13px;
        border-radius: 3px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        background-color: #1870E8;
        color: #fff;
        font-weight: bold;
      }
      .has:hover{
        background-color: #1565C0;
      }
      .has:focus{
        background-color: #1565C0;
      }
      .msg{
        text-align: center;
        margin-bottom: 20px;
        padding: 5px;
        background-color: #FFEBEE;
        color: #D32F2F;
      }
      .crd{
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #03a97f;
        padding: 4px;
        background-color: #eee;
      }
    </style>
  </head>
  <body>
    <h2>Direct Drive</h2>
    <?php echo $error; ?>
    <form method="post" action="<?php echo $this_file; ?>">
      <input type="url" class="hat" name="gdurl" placeholder="Drive file url.." value="<?php echo $url; ?>" required/><input class="has" type="submit" value="Download"/>
    </form>
    <span class="crd">&copy; Monzurul Hasan</span>
  </body>
</html>
