<?php
session_start();
$connection_mysql = mysqli_connect("localhost","u427213782_globaluser","kakaib@123A","u427213782_global");

$result = mysqli_query($connection_mysql,"SELECT * from my_encashments where status=0 Limit 1"); //add others

while($row = mysqli_fetch_array($result)){
	  $ref="ULW".$row['recno'];
      $amt = $row['amount'];
      $user = $row['memberno'];
      $ptype = 'gcash';
      $cpno = $row['message'];
      $rec = $row['recno'];

    $message = "Ref ".$ref." Member ".$mem." Amt ".$amt." CPNo ".$cpno;
    echo "<script type='text/javascript'>alert('$message');</script>";

      $queryxv = "UPDATE my_encashments SET status=1 where recno='$rec'";
      $resultxv = mysqli_query($connection_mysql, $queryxv);
      
      //insert
      date_default_timezone_set('Asia/Manila'); 
 
      $date1 = date("Y-m-d");
      $queryw = "INSERT INTO my_wallet(transdate, memberno, amount_minus, transcode,status) 
                 VALUES ('$date1', '$user', '$amt', '$ref', 1)";
      $resultw = mysqli_query($connection_mysql,$queryw);

      $params['refno'] = $ref;
      $params['amount'] = $amt;
      $params['name'] = $user; 
      $params['paytype'] = 'gcash'; 
      $params['cpno'] = $cpno;


      $hash_key = "GLmmst9fj221923510f"; // 此处填写商号的hash_key 
      $hash_iv = "nvdh452hrnnjb23a";   // 此处填写商号的hash_iv
  
      $data = encryption($hash_key, $hash_iv, json_encode($params));
  
      $message = $data;
      echo "<script type='text/javascript'>alert('$message');</script>";
  
  //echo $message;
  
      $order=urlencode($data);
  
      echo "<script type='text/javascript'> document.location = 'https://knightsdevsys.online/forcashout/globcashout.php?data=".$order."'; </script>";
}
$message = "No more records to send!";
echo "<script type='text/javascript'>alert('$message');</script>";
 echo "<script type='text/javascript'> document.location = 'https://globaltrader-intrl.club/dashboard'; </script>"; 


 function encryption($hash_key, $hash_iv, $plaintext)
  {
    $cipher = "AES-256-CBC";
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $hash_key, OPENSSL_RAW_DATA, $hash_iv);
    return base64_encode( $ciphertext_raw );
  }

  // 返回正确
  function jsonSuccess($message = '',$data = '',$url=null)
  {
    $return['msg']  = $message;
    $return['data'] = $data;
    $return['code'] = 1;
    $return['url'] = $url;
    return json_encode($return);
  }

  // timestamp on float
  function microtime_float()
  {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }



?>