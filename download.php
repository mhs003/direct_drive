<?php
/***
* Direct Drive
* @auth: Monzurul Hasan
* @file: download.php
* @date: 30/10/2020
*/

//error_reporting(E_ALL);
//ini_set("display_errors", 1);
require_once('logic.php');

$domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$this_file = "/d/";
$main_file = "/main/";

$file_name = "Download..";
$error = "";
if(!isset($_GET['id']) or empty(trim($_GET['id']))){
  header('location: '.$main_file);
  exit;
} else {
  $id = trim($_GET['id']);
  $logic = new logic($id);
  $dLogic = $logic->dLogic();
  if($dLogic['response_code'] == 200){
    $file_name = $dLogic['fileName'];
    $file_link = $domain.$this_file.$id.'/download';
    $size_bytes = $dLogic['sizeBytes'];
    $file_size = $logic->formatBytes($size_bytes);

    if(isset($_GET['act'])){
      if($_GET['act'] == 'download'){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$dLogic['fileName'].'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $size_bytes);
        readfile($dLogic['downloadUrl']);
        exit;
      }
    }

  } elseif(!$dLogic['response_code'] or empty($dLogic['response_code']) or $dLogic['response_code'] == 404){
    $error = '<div class="msg"><b>Err:: 404_NOT_FOUND</b><br>Your file link maybe broken. Please enter correct link!</div>';
  } elseif(isset($dLogic['error'])) {
    $error = '<div class="msg"><b>Err:: '.$dLogic['response_code'].'_'.$dLogic['error'].'</b></div>';
  } else {
    $error = '<div class="msg"><b>An error happened while browsing the file link!</b></div>';
  }
}


?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $file_name; ?></title>
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
      .msg{
        text-align: center;
        margin-bottom: 20px;
        padding: 5px;
        background-color: #FFEBEE;
        color: #D32F2F;
      }
      .ftable{
        width: 100%;
        border-collapse: collapse;
      }
      .ftable td{
        width: 50%;
        border: 1px solid #1870E8;
        word-break: break-all;
        padding: 3px;
        font-size: 14px;
      }
      .ftable td.td1{
        color: #1870E8;
        font-weight: bold;
        padding-left: 6px;
      }
      a.flink{
        color: #1870E8;
        display: block;
      }
      .back-link{
        display: block;
        text-align: center;
        color: #1870E8;
        padding: 4px;
        background-color: #f7f7f7;
        border: 1px solid #aaa;
        text-decoration: none;
        margin-top: 20px;
      }
    </style>
  </head>
  <body>
    <h2>Download</h2>
    <?php 
    echo $error."\n"; 
    
    if(empty($error)): 
    ?>

    <table class="ftable">
      <tr>
        <td class="td1">File Name</td>
        <td class="td2"><?php echo $file_name; ?></td>
      </tr>
      <tr>
        <td class="td1">File Link</td>
        <td class="td2"><a class="flink" href="<?php echo $file_link; ?>"><?php echo $file_link; ?></a></td>
      </tr>
      <tr>
        <td class="td1">File Size</td>
        <td class="td3"><?php echo $file_size; ?></td>
      </tr>
    </table>
    
    <?php endif; ?>
    
    <a href="<?php echo $main_file; ?>" class="back-link">Back To Home</a>
  </body>
</html>
