<?php
// @system("clear");
$regFile = file_get_contents('lykaregister.json');
$filesReg = json_decode($regFile, true);
$cookyStr = file_get_contents('lyaccnts.json');
$accntdata = json_decode($cookyStr, true);
$uAgent = "Lyka/3.6.29 (com.thingsilikeapp; build:829 Android R 30)";

$phoneNumberx = $filesReg["phoneNumber"];
$birthDatex = $filesReg["birthDate"];
$fullnamex = $filesReg["fullname"];
$genderx = $filesReg["gender"];
$usernamex = $filesReg["username"];
$passwordx = $filesReg["password"];
$DevIdx = $filesReg["DevId"];
$notificationTokenx = $filesReg["notificationToken"];

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
};
function payload($devIDx, $xtraPay, $rTokenx = ""){
     if($rTokenx!=""){
    $wtoken = ",notificationToken: $rTokenx";} else {
    $wtoken=$rTokenx;}
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


$vaidateUN = postX("https://identity.mylykaapps.com/useraccounts/validateusername", payload("$DevIdx",'"country": "PH","isMerchant": false,"username": "'.$usernamex.'"')) ->message;
$validateNumber = postX("https://identity.mylykaapps.com/useraccounts/validatephonenumber", payload($DevIdx,'"country": "PH","isMerchant": false,"phoneNumber":"'.$phoneNumberx.'"')) ->message;

//Check username if available
   echo "\n";
   echo $vaidateUN."\n";
   echo $validateNumber."\n";

   if(strpos($vaidateUN,'does not') && strpos($validateNumber,'does not')){
       echo "\n";
       //GENERATE OTP and read line
       $sendOTP = postX("https://settings.mylykaapps.com/api/v3/otpservices/GenerateOTPV2", payload($DevIdx,'"reference":"phone","type":"register","value":"'.$phoneNumberx.'"')) ->data->requestId;
       echo $sendOTP."\n";
       
       if($sendOTP){

        $OTPsucc = false;
        while (!$OTPsucc){
            $xOTP = readline("-->> OTP: ");
            if($xOTP == "x"){
                // @system("clear");
                exit('cancelled.'."\n");
            }
            $validateOTP = postX("https://settings.mylykaapps.com/api/v3/otpservices/ValidateOTPV2", payload($DevIdx,'"reference":"phone","requestId":"'.$sendOTP.'","type":"register","value":"'.$phoneNumberx.'",'."code".":".'"'.$xOTP.'"'));
            echo $validateOTP->data->signedToken;
            // if(array_key_exists("message",  $validateOTP)) {
            if($validateOTP->message) {
                $filesReg["signedToken"] = $validateOTP->data->signedToken;
                $filesReg["code"] = $xOTP;
                $newfilesReg = json_encode($filesReg);
                file_put_contents('lykaregister.json', $newfilesReg);
                $OTPsucc = true;
                $otpMess = $validateOTP->message;
                $signedTokenx = $validateOTP->data->signedToken;
                $codex = $xOTP;
                if($otpMess == "Invalid passcode."){
                    echo $otpMess."\n\n";
                } else{
                echo $otpMess."\n\n";
                $xregiCode = readline("-->> Continue with registration?: ");
                    // if($xregiCode=="y" && $signedTokenx!=""){
                    if($xregiCode=="y"){
                        $registerPass = postX("https://identity.mylykaapps.com/useraccounts/RegisterV3", payload($DevIdx,'"birthDate":"'.$birthDatex.'","code":"'.$codex.'","countryCode":"PH","fullname":"'.$fullnamex.'","gender":"'.$genderx.'","isMerchant":false,"password":"'.$passwordx.'","phoneNumber":"'.$phoneNumberx.'","signedToken":"'.$signedTokenx.'","type":"phone","username":"'.$usernamex.'"'),$notificationTokenx) ;
                        // echo $registerPass;
                        // echo $registerPass->data->token->accessToken;
                        // if(array_key_exists("data",  $registerPass)) {
                        if($registerPass->data->token->accessToken) {
                            $sessCookie = $registerPass->data->token->accessToken;
                            $unNew = str_replace("+63", "0", $phoneNumberx);
                            $accntdata[$unNew]["devId"] = "$DevIdx";
                            $accntdata[$unNew]["noToken"] = "$notificationTokenx";
                            $accntdata[$unNew]["password"] = "$passwordx";
                            $accntdata[$unNew]["cookie"] = "$sessCookie";
                            $newaccntdata = json_encode($accntdata);
                            file_put_contents('lyaccnts.json', $newaccntdata);
                        }
                        echo "\n".$registerPass->message."\n";
                    }
                }

            } else {
                // @system("clear");
                echo "Invalid OTP."."\n";
            }
        }

         }//if OTP is successful
   }


?>