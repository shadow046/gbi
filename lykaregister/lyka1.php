/* Decoded by unphp.net */

<?php @system("clear");
$b = "[1;35m";
echo "$b
'##:::::::'   ##::' '##:::'##::::'###::::
 ##:::::::.   ##::   ##::'##::::'## ##:::
 ##::::::::.  ##::   ##:'##::::'##:. ##::
 ##:::::::::. ##::   #####::::'##:::. ##:
 ##:::::::::: ##::   ##. ##::: #########:
 ##:::::::::: ##::   ##:. ##:: ##.... ##:
 ########:::: ##::   ##::. ##: ##:::: ##:
........:::::...::::..::::..::..:::::..::
";
echo "
";
$puti = "[1;37m";
echo "$puti
";
$count = count(file('dummy.txt'));
$countmain = count(file('account.txt'));
$dummyfile = "dummy.txt";
$filename = "account.txt";
$mainpass = readline('Enter main pasword: ');
$dummypass = readline('Enter dummy pasword: ');
$dummy = file($dummyfile);
$lines = file($filename);
for ($i = 0; $i < $count; $i++){
  echo $dummy[$i];
  for ($f = 0; $f < 4; $f++){
    $user2 = $lines[$f];
    $pass2 = $mainpass;
    $user = $dummy[$i];
    $pass = $dummypass;
    $urll2 = "https://identity.mylykaapps.com/useraccounts/login";
    $headerss2 = array("Content-Type: application/json", "user-agent:Lyka/3.4.321 (com.thingsilikeapq; build:637 Android O_MR1 28)");
    $dataa2 = <<<DATA
    {
      "countryCode": "US",curl 
      "device": {
        "deviceId": "eea39e5d303ac0xx",
        "deviceImei": "eea39e5d303ac0xx",
        "deviceModel": "Xiaomi Redmi Note 7",
        "deviceName": "android",
        "deviceOs": "Android O_MR1 ",
        "isEmulator": false,
        "notificationToken": "eEBjxYrDSJyFw7N-DpEGNG:APA91bEZnWo-TDdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VODiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-gz",
        "osVersion": "28"
      },
      "password": "$pass2",
      "username": "$user2"
    }
    DATA;
    $curll2 = curl_init();
    curl_setopt_array($curll2, array(CURLOPT_PORT => "443", CURLOPT_URL => "$urll2", CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_TIMEOUT => 30, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => $dataa2, CURLOPT_HTTPHEADER => $headerss2));
    $respp2 = curl_exec($curll2);
    curl_close($curll2);
    //var_dump($resp);
    $jsonn2 = json_decode($respp2);
    $bearer2 = $jsonn2->data->token->accessToken;
    //echo $bearer2;
    //////
    $urlg = "https://profiles.mylykaapps.com/api/v3/profiles/GetUser";
    $curlg = curl_init($urlg);
    curl_setopt($curlg, CURLOPT_URL, $urlg);
    curl_setopt($curlg, CURLOPT_POST, true);
    curl_setopt($curlg, CURLOPT_RETURNTRANSFER, true);
    $headersg = array("authorization: Bearer $bearer2", "Content-Type: application/json",);
    curl_setopt($curlg, CURLOPT_HTTPHEADER, $headersg);
    $datag = <<<DATA
    {
      "type": "username",
      "username": "$user2"
    }
    DATA;
    curl_setopt($curlg, CURLOPT_POSTFIELDS, $datag);
    //for debug only!
    //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $respg = curl_exec($curlg);
    curl_close($curlg);
    //var_dump($respg);
    $jsong = json_decode($respg);
    $uid = $jsong->data->id;
    //echo $uid;
    $uname = $jsong->data->userName;
    $green = "[1;32m";
    // if (!$uname) {
    //   $red = "[1;312m";
    //   echo "$red$user2 is error";
    //   exit;
    // }
    echo "$green$uname is RATED
    ";
    $urld = "https://profiles.mylykaapps.com/api/v3/profiles/GetProfilePosts?os=android&pageIndex=1&pageSize=42&id=$uid&category=ALL";
    $curld = curl_init($urld);
    curl_setopt($curld, CURLOPT_URL, $urld);
    curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
    $headersd = array("authorization: Bearer $bearer2", "user-agent: Lyka/3.4.321 (com.thingsilikeapp; build:637 Android",);
    curl_setopt($curld, CURLOPT_HTTPHEADER, $headersd);
    $respd = curl_exec($curld);
    curl_close($curld);
    $jsond = json_decode($respd, true);
    $arr = json_decode($respd, true);
    //main 1
    $p0 = $arr["data"]["0"]["id"];
    $p1 = $arr["data"]["1"]["id"];
    $p2 = $arr["data"]["2"]["id"];
    $p3 = $arr["data"]["3"]["id"];
    $p4 = $arr["data"]["4"]["id"];
    $p5 = $arr["data"]["5"]["id"];
    $p6 = $arr["data"]["6"]["id"];
    $p7 = $arr["data"]["7"]["id"];
    $p8 = $arr["data"]["8"]["id"];
    $p9 = $arr["data"]["9"]["id"];
  }
}
?>