<?php

$regFile = file_get_contents('lykaregister.json');
$filesReg = json_decode($regFile, true);
$cookyStr = file_get_contents('lyaccnts.json');
$accntdata = json_decode($cookyStr, true);
$uAgent = "Lyka/3.6.29 (com.thingsilikeapp; build:829 Android R 30)";
// function postX($urlx, $payloader){
//     global $uAgent;
//     $validURL = $urlx;
//     $validHeader = array(
//         "Content-Type: application/json; charset=UTF-8",
//         "user-agent: $uAgent"
//         );
//         $validCurl = curl_init($validURL);
//         curl_setopt($validCurl, CURLOPT_URL, $validURL);
//         curl_setopt($validCurl, CURLOPT_POST, true);
//         curl_setopt($validCurl, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($validCurl, CURLOPT_HTTPHEADER, $validHeader);
//         curl_setopt($validCurl, CURLOPT_POSTFIELDS, $payloader);
//         file_put_contents('lyaccntvalidCurl.json', $validHeader);
//         $validResp = curl_exec($validCurl);
//         curl_close($validCurl);
//         $valjson = json_decode($validResp);
//         return $valjson;

// };
function postX($urlx, $payloader, $cooks){
    global $uAgent;
    $postURL = $urlx;
    $postHeader = array(
        "Content-Type: application/json; charset=UTF-8",
        "user-agent: $uAgent","authorization: Bearer $cooks") ;
        $postCurl = curl_init($postURL);
        curl_setopt($postCurl, CURLOPT_URL, $postURL);
        curl_setopt($postCurl, CURLOPT_POST, true);
        curl_setopt($postCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($postCurl, CURLOPT_HTTPHEADER, $postHeader);
        curl_setopt($postCurl, CURLOPT_POSTFIELDS, $payloader);
        curl_setopt($postCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($postCurl, CURLOPT_SSL_VERIFYPEER, false);
        $postResp = curl_exec($postCurl);
    //   echo "\n".$postResp;
        curl_close($postCurl);
        $postjson = json_decode($postResp);
        return $postjson;
}
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
    file_put_contents('lyaccntvaldata.json', $valdata);
   return $valdata;
}
function random_username($string) {
    $pattern = " ";
    $firstPart = strstr(strtolower($string), $pattern, true);
    $secondPart = substr(strstr(strtolower($string), $pattern, false), 0,3);
    $nrRand = rand(0, 100);
    
    $username = trim($firstPart).trim($secondPart).trim($nrRand);
    return $username;
}
function notkens(){
    $notiTokn = "";
    for ($x = 1; $x <= 163; $x++) {
        if($x==22){
            $notiTokn .= ":";
        } else if(random_int(1,163)>$x&& random_int(1,163)<$x-20){
            if(random_int(1,2)==2){
                $notiTokn .= "_";
            } else {
                $notiTokn .= "-";
            }
        } else {
        $sme = random_int(1,3);
        switch ($sme) {
            case 1:
                $notiTokn .= chr(random_int(48,57));
            case 2:
                $notiTokn .= chr(random_int(65,90));
            case 3:
                $notiTokn .= chr(random_int(97,122));
          }
        }
    }
    return $notiTokn;
}

$password ="";
$fulln="";

function register(){
    // global $password;
    // global $fulln;
    $number= readline("-->> Phone Number: 0");
    if($number == "x"){
        exit('cancelled.'."\n");
    }

    // $fulln= readline("-->> Enter fullname: ");
    // if ($fulln == "") {
    //     exit('cancelled.'."\n");
    // }

    // if($password == ""){
    //     $password= readline("-->> Password: ");
    //     if ($password == "") {
    //         exit('cancelled.'."\n");
    //     }
    // }
    $phoneNumberx = "+63".$number;
    $number = '0'.$number;
    $cookyStr = file_get_contents('lyaccnts.json');
    $accntdata = json_decode($cookyStr, true);
    $DevIdx = $accntdata[$number]["devId"];
    $cookies = $accntdata[$number]["cookie"];
    // $vaidateUN = postX("https://identity.mylykaapps.com/useraccounts/validateusername", payload("$DevIdx",'"country": "PH","isMerchant": false,"username": "'.$usernamex.'"')) ->message;
    $validateNumber = postX("https://identity.mylykaapps.com/useraccounts/validatephonenumber", payload($DevIdx,'"country": "PH","isMerchant": false,"phoneNumber":"'.$phoneNumberx.'"'),'') ->message;

//Check username if available
    echo "\n";
    echo $validateNumber."\n";
    echo $phoneNumberx."\n";
    if(!strpos($validateNumber,'does not')){
        echo "\n";
        //GENERATE OTP and read line
        $xtext = readline("-->> Send OTP to $phoneNumberx? Y/n: ");
            if($xtext == "n"){
                exit('cancelled.'."\n");
            }
        $sendOTP = postX("https://settings.mylykaapps.com/api/v3/otpservices/GenerateOTPV2", payload($DevIdx,'"reference":"phone","type":"edit","value":"'.$phoneNumberx.'"'),$cookies)->data->requestId;
        // $sendOTP = postX("https://settings.mylykaapps.com/api/v3/otpservices/GenerateOTPV2", payload($DevIdx,'"reference":"phone","type":"register","value":"'.$phoneNumberx.'"')) ->data->requestId;
        if($sendOTP){
            $OTPsucc = false;
            while (!$OTPsucc){
                $xOTP = readline("-->> OTP: ");
                if($xOTP == "x"){
                    exit('cancelled.'."\n");
                }
                // $validateOTP = postX("https://users.mylykaapps.com/api/v3/users/ChangePhoneNumberV2", payload($DevIdx,'"reference":"phone","requestId":"'.$sendOTP.'","type":"edit","value":"'.$phoneNumberx.'",'."code".":".'"'.$xOTP.'"'),$cookies);
                $validateOTP = postX("https://settings.mylykaapps.com/api/v3/otpservices/ValidateOTPV2", payload($DevIdx,'"reference":"phone","requestId":"'.$sendOTP.'","type":"edit","value":"'.$phoneNumberx.'",'."code".":".'"'.$xOTP.'"'),$cookies);
                if( $validateOTP->data) {
                    $signedTokenx = $validateOTP->data->signedToken;
                    echo $validateOTP->data->signedToken;
                    $codex = $xOTP;
                    $OTPsucc = true;
                    $otpMess = $validateOTP->message;
                    if($otpMess == "Invalid passcode."){
                        echo $otpMess."\n\n";
                    }else{
                        echo $otpMess."\n\n";
                        if($signedTokenx!=""){
                            // $registerPas = postX("https://users.mylykaapps.com/api/v3/users/ChangePhoneNumberV2", payload($DevIdx,'"birthDate":"'.$birthDatex.'","code":"'.$codex.'","countryCode":"PH","fullname":"'.$fullnamex.'","gender":"'.$genderx.'","isMerchant":false,"password":"'.$passwordx.'","phoneNumber":"'.$phoneNumberx.'","signedToken":"'.$signedTokenx.'","type":"phone","username":"'.$usernamex.'"'),$cookies) ;
                            $registerPass = postX("https://users.mylykaapps.com/api/v3/users/ChangePhoneNumberV2", payload($DevIdx,'"countryCode":"PH","country":"PH","phoneNumber":"'.$phoneNumberx.'","code":"'.$codex.'","signedToken":"'.$signedTokenx.'","type":"phone","isMerchant":false'),$cookies);
                            $activ = postX("https://wallets.mylykaapps.com/api/v3/wallets/activatewallet", payload($DevIdx,'"countryCode":"PH","country":"PH","phoneNumber":"'.$phoneNumberx.'","code":"'.$codex.'","signedToken":"'.$signedTokenx.'"'),$cookies) ;
                            $data = json_encode($registerPass);
                            // $datas = json_encode($registerPas);
                            $activs = json_encode($activ);
                            echo $data."\n";
                            echo $datas."\n";
                            echo $activs."\n";
                            
                            if($registerPass) {
                                $data = json_encode($registerPass);
                                echo $data;
                            }else{
                                echo "Invalid OTP2."."\n";
                            }
                        }
                    }
                } else {
                    echo "Invalid OTP1."."\n";
                }
            }
        }//if OTP is successful
    }
    register();
}
register();
?>