<?php
//https://profiles.mylykaapps.com/api/v3/profiles/GetProfilePosts?os=android&pageIndex=1&pageSize=41&id={$fuid}&category=ALL
$regFile = file_get_contents('username.json');
$filesReg = json_decode($regFile, true);
// $cookyStr = file_get_contents('lyaccnts.json');
// $accntdata = json_decode($cookyStr, true);
$uAgent = "Lyka/3.6.29 (com.thingsilikeapp; build:829 Android R 30)";

// $phoneNumberx = $filesReg["phoneNumber"];
// $birthDatex = $filesReg["birthDate"];
// $fullnamex = $filesReg["fullname"];
// $genderx = $filesReg["gender"];
$usernamex = $filesReg["username"];
$passwordx = $filesReg["password"];
$DevIdx = $filesReg["DevId"];
$notificationTokenx = $filesReg["notificationToken"];
// $codex = $filesReg["code"];
$signedTokenx = $filesReg["signedToken"];
function postX($urlx, $payloader){
    global $uAgent;
    $validURL = $urlx;
    $validHeader = array(
        "Content-Type: application/json; charset=UTF-8",
        "user-agent: $uAgent"
        );
        $validCurl = curl_init($validURL);
        curl_setopt($validCurl, CURLOPT_URL, $validURL);
        curl_setopt($validCurl, CURLOPT_POST, true);
        curl_setopt($validCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($validCurl, CURLOPT_HTTPHEADER, $validHeader);
        curl_setopt($validCurl, CURLOPT_POSTFIELDS, $payloader);
        $validResp = curl_exec($validCurl);
        curl_close($validCurl);
        $valjson = json_decode($validResp);
        return $valjson;

    curl_setopt_array(
        $curll2, array(
            CURLOPT_PORT => "443", 
            CURLOPT_URL => "$urll2", 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_SSL_VERIFYPEER => false, 
            CURLOPT_TIMEOUT => 30, 
            CURLOPT_CUSTOMREQUEST => "POST", 
            CURLOPT_POSTFIELDS => $dataa2, 
            CURLOPT_HTTPHEADER => $headerss2
        )
    );

};
function payload($devIDx, $xtraPay, $rTokenx = ""){
    if($rTokenx!=""){
        $wtoken = ",notificationToken: $rTokenx";
    } else {
        $wtoken=$rTokenx;
    }
    $valdata = <<<DATA
            {"device": {
                "deviceId": "$devIDx",
                "deviceImei": "",
                "deviceModel": "unknown unknown",
                "deviceName": "android",
                "deviceOs": "Android R ",
                "isEmulator": false,
                "osVersion": "30"
                $wtoken
            },
            $xtraPay
            }
            DATA; 
    return $valdata;
};
$LoginUrl = "https://identity.mylykaapps.com/useraccounts/login";
$headers = array(
    "Content-Type: application/json",
    "user-agent: $uAgent"
);
$data = <<<DATA
    {
      "countryCode": "US",curl 
      "device": {
        "deviceId": "eea39e5d303ac0xx",
        "deviceImei": "",
        "deviceModel": "unknown unknown",
        "deviceName": "android",
        "deviceOs": "Android R ",
        "isEmulator": false,
        "notificationToken": "eEBjxYrDSJyFw7N-DpEGNG:APA91bEZnWo-TDdSgVCzQcJq3gHioJtFThNyxw6PsgOCI1JHDzd55yqG-QZwAZRj4pwICrXo5VODiUYom7Fsf4Ql66-CWHFumNA2ynrKEP21bstPBMgwsN-3G_Ek0ZLcoKtVMg5oN6-gz",
        "osVersion": "28"
      },
      "password": "$usernamex",
      "username": "$passwordx"
    }
    DATA;
$CurlInit = curl_init();
curl_setopt_array(
    $CurlInit, array(
        CURLOPT_PORT => "443", 
        CURLOPT_URL => "$LoginUrl", 
        CURLOPT_RETURNTRANSFER => true, 
        CURLOPT_SSL_VERIFYPEER => false, 
        CURLOPT_TIMEOUT => 30, 
        CURLOPT_CUSTOMREQUEST => "POST", 
        CURLOPT_POSTFIELDS => $data, 
        CURLOPT_HTTPHEADER => $headers,
    )
);
$curl_exe = curl_exec($CurlInit);
curl_close($CurlInit);
$decode = json_decode($curl_exe);
file_put_contents('lyaccnts.json', $decode); //check mo if maguupdate.
?>