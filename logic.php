<?php
/***
* Direct Drive
* @auth: Monzurul Hasan
* @file: logic.php
* @date: 30/10/2020
* @type: class
*/

class logic{
  public $id;
  
  public function __construct($id){
    $this->id = $id;
  }
  
  public function dLogic(){
    $id = $this->id;
    
    $ch = curl_init("https://drive.google.com/uc?id=$id&authuser=0&export=download");
    curl_setopt_array($ch, array(
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_POSTFIELDS => [],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => 'gzip,deflate',
      CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
      CURLOPT_HTTPHEADER => [
        'accept-encoding: gzip, deflate, br',
        'content-length: 0',
        'content-type: application/x-www-form-urlencoded;charset=UTF-8',
        'origin: https://drive.google.com',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
        'x-client-data: CKG1yQEIkbbJAQiitskBCMS2yQEIqZ3KAQioo8oBGLeYygE=',
        'x-drive-first-party: DriveWebUi',
        'x-json-requested: true'
      ]
    ));
    $response = curl_exec($ch);
    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $array_data = json_decode(str_replace(')]}\'', '', $response), true);
    $array_data['response_code'] = $response_code;
    
    return $array_data;
  }
  
  public function formatBytes($size, $precision = 2){
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
  }
}
