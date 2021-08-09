/* Decoded by unphp.net */

<?php @system("clear");
$b = "[1;35m";
echo "$b
'##:::::::'##:::'##:'##:::'##::::'###::::
 ##:::::::. ##:'##:: ##::'##::::'## ##:::
 ##::::::::. ####::: ##:'##::::'##:. ##::
 ##:::::::::. ##:::: #####::::'##:::. ##:
 ##:::::::::: ##:::: ##. ##::: #########:
 ##:::::::::: ##:::: ##:. ##:: ##.... ##:
 ########:::: ##:::: ##::. ##: ##:::: ##:
........:::::..:::::..::::..::..:::::..::
";
echo "
";
$puti = "[1;37m";
echo "$puti
";
$user = readline('Enter dummy username: ');
$pass = readline('Enter dummy pasword: ');
$user2 = readline('Enter main username: ');
$pass2 = readline('Enter main pasword: ');
//$pass;
//$pass = ''b'
// For output
//echo $a;
$urll2 = "https://identity.mylykaapps.com/useraccounts/login";
$headerss2 = array("Content-Type: application/json", "user-agent:Lyka/3.4.321 (com.thingsilikeapq; build:637 Android O_MR1 28)");
$dataa2 = <<<DATA
{
  "countryCode": "US",
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
//var_dump($respd,true);
/*function printValues($arr) {
    global $count;
    global $values;
    
    // Check input is an array
    if(!is_array($arr)){
        die("ERROR: Input is not an array");
    }
    
    
    foreach($arr as $key=>$value){
        if(is_array($value)){
            printValues($value);
        } else{
            $values[] = $value;
            $count++;
        }
    }
    
    // Return total count and values found in array
    return array('total' => $count, 'values' => $values);
}*/
$arr = json_decode($respd, true);
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
$p10 = $arr["data"]["10"]["id"];
$p11 = $arr["data"]["11"]["id"];
$p12 = $arr["data"]["12"]["id"];
$p13 = $arr["data"]["13"]["id"];
$p14 = $arr["data"]["14"]["id"];
$p15 = $arr["data"]["15"]["id"];
$p16 = $arr["data"]["16"]["id"];
$p17 = $arr["data"]["17"]["id"];
$p18 = $arr["data"]["18"]["id"];
$p19 = $arr["data"]["19"]["id"];
$p20 = $arr["data"]["20"]["id"];
$p21 = $arr["data"]["21"]["id"];
$p22 = $arr["data"]["22"]["id"];
$p23 = $arr["data"]["23"]["id"];
$p24 = $arr["data"]["24"]["id"];
$p25 = $arr["data"]["25"]["id"];
$p26 = $arr["data"]["26"]["id"];
$p27 = $arr["data"]["27"]["id"];
$p28 = $arr["data"]["28"]["id"];
$p29 = $arr["data"]["29"]["id"];
$p30 = $arr["data"]["30"]["id"];
$p31 = $arr["data"]["31"]["id"];
$p32 = $arr["data"]["32"]["id"];
$p33 = $arr["data"]["33"]["id"];
$p34 = $arr["data"]["34"]["id"];
$p35 = $arr["data"]["35"]["id"];
$p36 = $arr["data"]["36"]["id"];
$p37 = $arr["data"]["37"]["id"];
$p38 = $arr["data"]["38"]["id"];
$p39 = $arr["data"]["39"]["id"];
$p40 = $arr["data"]["40"]["id"];
$p41 = $arr["data"]["41"]["id"];
//echo $p40;
//echo $p39;
$urll = "https://identity.mylykaapps.com/useraccounts/login";
$curll = curl_init($urll);
curl_setopt($curll, CURLOPT_URL, $urll);
curl_setopt($curll, CURLOPT_POST, true);
curl_setopt($curll, CURLOPT_RETURNTRANSFER, true);
$headerss = array("Content-Type: application/json", "user-agent:Lyka/3.4.321 (com.thingsilikeapq; build:637 Android O_MR1 28)");
curl_setopt($curll, CURLOPT_HTTPHEADER, $headerss);
$dataa = <<<DATA
{
  "countryCode": "US",
  "device": {
    "deviceId": "egg39e5d303ac0ef",
    "deviceImei": "egg39e5d303ac0ef",
    "deviceModel": "Xiaomi Redmi Note 10",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNG:APA91bEZnWo-TDdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VODiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-gz",
    "osVersion": "28"
  },
  "password": "$pass",
  "username": "$user"
}
DATA;
curl_setopt($curll, CURLOPT_POSTFIELDS, $dataa);
$respp = curl_exec($curll);
curl_close($curll);
//var_dump($respp);
$jsonn = json_decode($respp);
$bearer = $jsonn->data->token->accessToken;
//echo $bearer;
///////////
$url = "https://posts.mylykaapps.com/api/v3/posts/ratepost";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array("authorization:Bearer $bearer", "user-agent: Lyka/3.4.321 (com.thingsilikeapp; build:637 Android O_MR1 28)", "deviceos: android", "Content-Type: application/json",);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$data = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p0,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
$resp = curl_exec($curl);
curl_close($curl);
//var_dump($resp);
$json = json_decode($resp);
echo $json->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl2 = curl_init($url);
curl_setopt($curl2, CURLOPT_URL, $url);
curl_setopt($curl2, CURLOPT_POST, true);
curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl2, CURLOPT_HTTPHEADER, $headers);
$data2 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p1,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl2, CURLOPT_POSTFIELDS, $data2);
$resp2 = curl_exec($curl2);
curl_close($curl2);
//var_dump($resp2);
$json2 = json_decode($resp2);
echo $json2->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl3 = curl_init($url);
curl_setopt($curl3, CURLOPT_URL, $url);
curl_setopt($curl3, CURLOPT_POST, true);
curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl3, CURLOPT_HTTPHEADER, $headers);
$data3 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p2,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl3, CURLOPT_POSTFIELDS, $data3);
$resp3 = curl_exec($curl3);
curl_close($curl3);
//var_dump($resp2);
$json3 = json_decode($resp3);
echo $json3->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl4 = curl_init($url);
curl_setopt($curl4, CURLOPT_URL, $url);
curl_setopt($curl4, CURLOPT_POST, true);
curl_setopt($curl4, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl4, CURLOPT_HTTPHEADER, $headers);
$data4 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p3,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl4, CURLOPT_POSTFIELDS, $data4);
$resp4 = curl_exec($curl4);
curl_close($curl4);
//var_dump($resp2);
$json4 = json_decode($resp4);
echo $json4->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl5 = curl_init($url);
curl_setopt($curl5, CURLOPT_URL, $url);
curl_setopt($curl5, CURLOPT_POST, true);
curl_setopt($curl5, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl5, CURLOPT_HTTPHEADER, $headers);
$data5 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p4,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl5, CURLOPT_POSTFIELDS, $data5);
$resp5 = curl_exec($curl5);
curl_close($curl5);
//var_dump($resp2);
$json5 = json_decode($resp5);
echo $json5->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl6 = curl_init($url);
curl_setopt($curl6, CURLOPT_URL, $url);
curl_setopt($curl6, CURLOPT_POST, true);
curl_setopt($curl6, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl6, CURLOPT_HTTPHEADER, $headers);
$data6 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p5,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl6, CURLOPT_POSTFIELDS, $data6);
$resp6 = curl_exec($curl6);
curl_close($curl6);
//var_dump($resp2);
$json6 = json_decode($resp6);
echo $json6->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl7 = curl_init($url);
curl_setopt($curl7, CURLOPT_URL, $url);
curl_setopt($curl7, CURLOPT_POST, true);
curl_setopt($curl7, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl7, CURLOPT_HTTPHEADER, $headers);
$data7 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p6,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl7, CURLOPT_POSTFIELDS, $data7);
$resp7 = curl_exec($curl7);
curl_close($curl7);
//var_dump($resp2);
$json7 = json_decode($resp7);
echo $json7->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl8 = curl_init($url);
curl_setopt($curl8, CURLOPT_URL, $url);
curl_setopt($curl8, CURLOPT_POST, true);
curl_setopt($curl8, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl8, CURLOPT_HTTPHEADER, $headers);
$data8 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p7,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl8, CURLOPT_POSTFIELDS, $data8);
$resp8 = curl_exec($curl8);
curl_close($curl8);
//var_dump($resp2);
$json8 = json_decode($resp8);
echo $json8->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl9 = curl_init($url);
curl_setopt($curl9, CURLOPT_URL, $url);
curl_setopt($curl9, CURLOPT_POST, true);
curl_setopt($curl9, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl9, CURLOPT_HTTPHEADER, $headers);
$data9 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p8,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl9, CURLOPT_POSTFIELDS, $data9);
$resp9 = curl_exec($curl9);
curl_close($curl9);
//var_dump($resp2);
$json9 = json_decode($resp9);
echo $json9->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl10 = curl_init($url);
curl_setopt($curl10, CURLOPT_URL, $url);
curl_setopt($curl10, CURLOPT_POST, true);
curl_setopt($curl10, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl10, CURLOPT_HTTPHEADER, $headers);
$data10 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p9,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl10, CURLOPT_POSTFIELDS, $data10);
$resp10 = curl_exec($curl10);
curl_close($curl10);
//var_dump($resp2);
$json10 = json_decode($resp10);
echo $json10->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl11 = curl_init($url);
curl_setopt($curl11, CURLOPT_URL, $url);
curl_setopt($curl11, CURLOPT_POST, true);
curl_setopt($curl11, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl11, CURLOPT_HTTPHEADER, $headers);
$data11 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p10,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl11, CURLOPT_POSTFIELDS, $data11);
$resp11 = curl_exec($curl11);
curl_close($curl11);
//var_dump($resp2);
$json11 = json_decode($resp11);
echo $json11->message;
echo "
";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl12 = curl_init($url);
curl_setopt($curl12, CURLOPT_URL, $url);
curl_setopt($curl12, CURLOPT_POST, true);
curl_setopt($curl12, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl12, CURLOPT_HTTPHEADER, $headers);
$data12 = <<<DATA
{
 "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p11,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl12, CURLOPT_POSTFIELDS, $data12);
$resp12 = curl_exec($curl12);
curl_close($curl12);
//var_dump($resp2);
$json12 = json_decode($resp12);
echo $json12->message;
echo "
";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl13 = curl_init($url);
curl_setopt($curl13, CURLOPT_URL, $url);
curl_setopt($curl13, CURLOPT_POST, true);
curl_setopt($curl13, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl13, CURLOPT_HTTPHEADER, $headers);
$data13 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p12,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl13, CURLOPT_POSTFIELDS, $data13);
$resp13 = curl_exec($curl13);
curl_close($curl13);
$json13 = json_decode($resp13);
echo $json13->message;
echo "
";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl14 = curl_init($url);
curl_setopt($curl14, CURLOPT_URL, $url);
curl_setopt($curl14, CURLOPT_POST, true);
curl_setopt($curl14, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl14, CURLOPT_HTTPHEADER, $headers);
$data14 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p13,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl14, CURLOPT_POSTFIELDS, $data14);
$resp14 = curl_exec($curl14);
curl_close($curl14);
$json14 = json_decode($resp14);
echo $json14->message;
echo "
";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl15 = curl_init($url);
curl_setopt($curl15, CURLOPT_URL, $url);
curl_setopt($curl15, CURLOPT_POST, true);
curl_setopt($curl15, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl15, CURLOPT_HTTPHEADER, $headers);
$data15 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p14,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl15, CURLOPT_POSTFIELDS, $data15);
$resp15 = curl_exec($curl15);
curl_close($curl15);
$json15 = json_decode($resp15);
echo $json15->message;
echo "
";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl17 = curl_init($url);
curl_setopt($curl17, CURLOPT_URL, $url);
curl_setopt($curl17, CURLOPT_POST, true);
curl_setopt($curl17, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl17, CURLOPT_HTTPHEADER, $headers);
$data17 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p16,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl17, CURLOPT_POSTFIELDS, $data17);
$resp17 = curl_exec($curl17);
curl_close($curl17);
$json17 = json_decode($resp17);
echo $json17->message;
echo "
";
////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl16 = curl_init($url);
curl_setopt($curl16, CURLOPT_URL, $url);
curl_setopt($curl16, CURLOPT_POST, true);
curl_setopt($curl16, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl16, CURLOPT_HTTPHEADER, $headers);
$data16 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p15,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl16, CURLOPT_POSTFIELDS, $data16);
$resp16 = curl_exec($curl16);
curl_close($curl16);
$json16 = json_decode($resp16);
echo $json16->message;
echo "
";
////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl18 = curl_init($url);
curl_setopt($curl18, CURLOPT_URL, $url);
curl_setopt($curl18, CURLOPT_POST, true);
curl_setopt($curl18, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl18, CURLOPT_HTTPHEADER, $headers);
$data18 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p17,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl18, CURLOPT_POSTFIELDS, $data18);
$resp18 = curl_exec($curl18);
curl_close($curl18);
$json18 = json_decode($resp18);
echo $json18->message;
echo "
";
////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl19 = curl_init($url);
curl_setopt($curl19, CURLOPT_URL, $url);
curl_setopt($curl19, CURLOPT_POST, true);
curl_setopt($curl19, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl19, CURLOPT_HTTPHEADER, $headers);
$data19 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p18,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl19, CURLOPT_POSTFIELDS, $data19);
$resp19 = curl_exec($curl19);
curl_close($curl19);
$json19 = json_decode($resp19);
echo $json19->message;
echo "
";
////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl20 = curl_init($url);
curl_setopt($curl20, CURLOPT_URL, $url);
curl_setopt($curl20, CURLOPT_POST, true);
curl_setopt($curl20, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl20, CURLOPT_HTTPHEADER, $headers);
$data20 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p19,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl20, CURLOPT_POSTFIELDS, $data20);
$resp20 = curl_exec($curl20);
curl_close($curl20);
$json20 = json_decode($resp20);
echo $json20->message;
echo "
";
////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl21 = curl_init($url);
curl_setopt($curl21, CURLOPT_URL, $url);
curl_setopt($curl21, CURLOPT_POST, true);
curl_setopt($curl21, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl21, CURLOPT_HTTPHEADER, $headers);
$data21 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p20,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl21, CURLOPT_POSTFIELDS, $data21);
$resp21 = curl_exec($curl21);
curl_close($curl21);
$json21 = json_decode($resp21);
echo $json21->message;
echo "
";
////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl22 = curl_init($url);
curl_setopt($curl22, CURLOPT_URL, $url);
curl_setopt($curl22, CURLOPT_POST, true);
curl_setopt($curl22, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl22, CURLOPT_HTTPHEADER, $headers);
$data22 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p21,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl22, CURLOPT_POSTFIELDS, $data22);
$resp22 = curl_exec($curl22);
curl_close($curl22);
$json22 = json_decode($resp22);
echo $json22->message;
echo "
";
///////////////////////////////////////////////
$curl23 = curl_init($url);
curl_setopt($curl23, CURLOPT_URL, $url);
curl_setopt($curl23, CURLOPT_POST, true);
curl_setopt($curl23, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl23, CURLOPT_HTTPHEADER, $headers);
$data23 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p22,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl23, CURLOPT_POSTFIELDS, $data23);
$resp23 = curl_exec($curl23);
curl_close($curl23);
$json23 = json_decode($resp23);
echo $json23->message;
echo "
";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl24 = curl_init($url);
curl_setopt($curl24, CURLOPT_URL, $url);
curl_setopt($curl24, CURLOPT_POST, true);
curl_setopt($curl24, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl24, CURLOPT_HTTPHEADER, $headers);
$data24 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p23,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl24, CURLOPT_POSTFIELDS, $data24);
$resp24 = curl_exec($curl24);
curl_close($curl24);
$json24 = json_decode($resp24);
echo $json24->message;
echo "
";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl25 = curl_init($url);
curl_setopt($curl25, CURLOPT_URL, $url);
curl_setopt($curl25, CURLOPT_POST, true);
curl_setopt($curl25, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl25, CURLOPT_HTTPHEADER, $headers);
$data25 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p24,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl25, CURLOPT_POSTFIELDS, $data25);
$resp25 = curl_exec($curl25);
curl_close($curl25);
$json25 = json_decode($resp25);
echo $json25->message;
echo "
";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl26 = curl_init($url);
curl_setopt($curl26, CURLOPT_URL, $url);
curl_setopt($curl26, CURLOPT_POST, true);
curl_setopt($curl26, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl26, CURLOPT_HTTPHEADER, $headers);
$data26 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p25,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl26, CURLOPT_POSTFIELDS, $data26);
$resp26 = curl_exec($curl26);
curl_close($curl26);
$json26 = json_decode($resp26);
echo $json26->message;
echo "
";
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl27 = curl_init($url);
curl_setopt($curl27, CURLOPT_URL, $url);
curl_setopt($curl27, CURLOPT_POST, true);
curl_setopt($curl27, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl27, CURLOPT_HTTPHEADER, $headers);
$data27 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p26,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl27, CURLOPT_POSTFIELDS, $data27);
$resp27 = curl_exec($curl27);
curl_close($curl27);
$json27 = json_decode($resp27);
echo $json27->message;
echo "
";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl28 = curl_init($url);
curl_setopt($curl28, CURLOPT_URL, $url);
curl_setopt($curl28, CURLOPT_POST, true);
curl_setopt($curl28, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl28, CURLOPT_HTTPHEADER, $headers);
$data28 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p27,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl28, CURLOPT_POSTFIELDS, $data28);
$resp28 = curl_exec($curl28);
curl_close($curl28);
$json28 = json_decode($resp28);
echo $json28->message;
echo "
";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl29 = curl_init($url);
curl_setopt($curl29, CURLOPT_URL, $url);
curl_setopt($curl29, CURLOPT_POST, true);
curl_setopt($curl29, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl29, CURLOPT_HTTPHEADER, $headers);
$data29 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p28,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl29, CURLOPT_POSTFIELDS, $data29);
$resp29 = curl_exec($curl29);
curl_close($curl29);
$json29 = json_decode($resp29);
echo $json29->message;
echo "
";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl30 = curl_init($url);
curl_setopt($curl30, CURLOPT_URL, $url);
curl_setopt($curl30, CURLOPT_POST, true);
curl_setopt($curl30, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl30, CURLOPT_HTTPHEADER, $headers);
$data30 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p29,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl30, CURLOPT_POSTFIELDS, $data30);
$resp30 = curl_exec($curl30);
curl_close($curl30);
$json30 = json_decode($resp30);
echo $json30->message;
echo "
";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl31 = curl_init($url);
curl_setopt($curl31, CURLOPT_URL, $url);
curl_setopt($curl31, CURLOPT_POST, true);
curl_setopt($curl31, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl31, CURLOPT_HTTPHEADER, $headers);
$data31 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p30,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl31, CURLOPT_POSTFIELDS, $data31);
$resp31 = curl_exec($curl31);
curl_close($curl31);
$json31 = json_decode($resp31);
echo $json31->message;
echo "
";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl32 = curl_init($url);
curl_setopt($curl32, CURLOPT_URL, $url);
curl_setopt($curl32, CURLOPT_POST, true);
curl_setopt($curl32, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl32, CURLOPT_HTTPHEADER, $headers);
$data32 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p31,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl32, CURLOPT_POSTFIELDS, $data32);
$resp32 = curl_exec($curl32);
curl_close($curl32);
$json32 = json_decode($resp32);
echo $json32->message;
echo "
";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl33 = curl_init($url);
curl_setopt($curl33, CURLOPT_URL, $url);
curl_setopt($curl33, CURLOPT_POST, true);
curl_setopt($curl33, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl33, CURLOPT_HTTPHEADER, $headers);
$data33 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p32,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl33, CURLOPT_POSTFIELDS, $data33);
$resp33 = curl_exec($curl33);
curl_close($curl33);
$json33 = json_decode($resp33);
echo $json33->message;
echo "
";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$curl34 = curl_init($url);
curl_setopt($curl34, CURLOPT_URL, $url);
curl_setopt($curl34, CURLOPT_POST, true);
curl_setopt($curl34, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl34, CURLOPT_HTTPHEADER, $headers);
$data34 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p33,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl34, CURLOPT_POSTFIELDS, $data34);
$resp34 = curl_exec($curl34);
curl_close($curl34);
$json34 = json_decode($resp34);
echo $json34->message;
echo "
";
////////
$curl35 = curl_init($url);
curl_setopt($curl35, CURLOPT_URL, $url);
curl_setopt($curl35, CURLOPT_POST, true);
curl_setopt($curl35, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl35, CURLOPT_HTTPHEADER, $headers);
$data35 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p34,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl35, CURLOPT_POSTFIELDS, $data35);
$resp35 = curl_exec($curl35);
curl_close($curl35);
$json35 = json_decode($resp35);
echo $json35->message;
echo "
";
///////
$curl36 = curl_init($url);
curl_setopt($curl36, CURLOPT_URL, $url);
curl_setopt($curl36, CURLOPT_POST, true);
curl_setopt($curl36, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl36, CURLOPT_HTTPHEADER, $headers);
$data36 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p35,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl36, CURLOPT_POSTFIELDS, $data36);
$resp36 = curl_exec($curl36);
curl_close($curl36);
$json36 = json_decode($resp36);
echo $json36->message;
echo "
";
///////
$curl37 = curl_init($url);
curl_setopt($curl37, CURLOPT_URL, $url);
curl_setopt($curl37, CURLOPT_POST, true);
curl_setopt($curl37, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl37, CURLOPT_HTTPHEADER, $headers);
$data37 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p36,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl37, CURLOPT_POSTFIELDS, $data37);
$resp37 = curl_exec($curl37);
curl_close($curl37);
$json37 = json_decode($resp37);
echo $json37->message;
echo "
";
///////
$curl38 = curl_init($url);
curl_setopt($curl38, CURLOPT_URL, $url);
curl_setopt($curl38, CURLOPT_POST, true);
curl_setopt($curl38, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl38, CURLOPT_HTTPHEADER, $headers);
$data38 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p37,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl38, CURLOPT_POSTFIELDS, $data38);
$resp38 = curl_exec($curl38);
curl_close($curl38);
$json38 = json_decode($resp38);
echo $json38->message;
echo "
";
///////
$curl39 = curl_init($url);
curl_setopt($curl39, CURLOPT_URL, $url);
curl_setopt($curl39, CURLOPT_POST, true);
curl_setopt($curl39, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl39, CURLOPT_HTTPHEADER, $headers);
$data39 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p38,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl39, CURLOPT_POSTFIELDS, $data39);
$resp39 = curl_exec($curl39);
curl_close($curl39);
$json39 = json_decode($resp39);
echo $json39->message;
echo "
";
//////
$curl40 = curl_init($url);
curl_setopt($curl40, CURLOPT_URL, $url);
curl_setopt($curl40, CURLOPT_POST, true);
curl_setopt($curl40, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl40, CURLOPT_HTTPHEADER, $headers);
$data40 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p39,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl40, CURLOPT_POSTFIELDS, $data40);
$resp40 = curl_exec($curl40);
curl_close($curl40);
$json40 = json_decode($resp40);
echo $json40->message;
//var_dump($resp40);
echo "
";
////
$curl41 = curl_init($url);
curl_setopt($curl41, CURLOPT_URL, $url);
curl_setopt($curl41, CURLOPT_POST, true);
curl_setopt($curl41, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl41, CURLOPT_HTTPHEADER, $headers);
$data41 = <<<DATA
{
  "device": {
    "deviceId": "eea39e5d303ac0cc",
    "deviceImei": "eea39e5d303ac0cc",
    "deviceModel": "Xiaomi Redmi Note 5",
    "deviceName": "android",
    "deviceOs": "Android O_MR1 ",
    "isEmulator": false,
    "notificationToken": "eEBjxYrDSJyFw7N-DpEGNB:APA91bEZnWo-TRdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VDiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-pg",
    "osVersion": "28"
  },
  "postId": $p40,
  "rate": 5,
  "userid": $uid
}
DATA;
curl_setopt($curl41, CURLOPT_POSTFIELDS, $data41);
$resp41 = curl_exec($curl41);
curl_close($curl41);
$json41 = json_decode($resp41);
echo $json41->message;
//var_dump($resp40);
echo "
";
