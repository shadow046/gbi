<?php

$regFile = file_get_contents('lykaregister.json');
$filesReg = json_decode($regFile, true);
$cookyStr = file_get_contents('lyaccnts.json');
$accntdata = json_decode($cookyStr, true);
$uAgent = "Lyka/3.6.29 (com.thingsilikeapp; build:829 Android R 30)";
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
        file_put_contents('lyaccntvalidCurl.json', $validHeader);
        $validResp = curl_exec($validCurl);
        curl_close($validCurl);
        $valjson = json_decode($validResp);
        return $valjson;

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
    file_put_contents('lyaccntvaldata.json', $valdata);
   return $valdata;
};

foreach ($filesReg as $filreg) {

$phoneNumberx = $filreg["phoneNumber"];
$birthDatex = $filreg["birthDate"];
$fullnamex = $filreg["fullname"];
$genderx = $filreg["gender"];
$usernamex = $filreg["username"];
$passwordx = $filreg["password"];
$DevIdx = $filreg["DevId"];
$notificationTokenx = $filreg["notificationToken"];


$vaidateUN = postX("https://identity.mylykaapps.com/useraccounts/validateusername", payload("$DevIdx",'"country": "PH","isMerchant": false,"username": "'.$usernamex.'"')) ->message;
$validateNumber = postX("https://identity.mylykaapps.com/useraccounts/validatephonenumber", payload($DevIdx,'"country": "PH","isMerchant": false,"phoneNumber":"'.$phoneNumberx.'"')) ->message;

//Check username if available
   echo "\n";
   echo $vaidateUN."\n";
   echo $validateNumber."\n";
    echo $phoneNumberx."\n";
   if(strpos($vaidateUN,'does not') && strpos($validateNumber,'does not')){
       echo "\n";
       //GENERATE OTP and read line
       $xtext = readline("-->> Send OTP to $phoneNumberx? Y/n: ");
        if($xtext == "n"){
            exit('cancelled.'."\n");
        }
       $sendOTP = postX("https://settings.mylykaapps.com/api/v3/otpservices/GenerateOTPV2", payload($DevIdx,'"reference":"phone","type":"register","value":"'.$phoneNumberx.'"')) ->data->requestId;
       echo $sendOTP."\n";
       
       if($sendOTP){
        $OTPsucc = false;
        
        while (!$OTPsucc){
            $xOTP = readline("-->> OTP: ");
            if($xOTP == "x"){
                
                exit('cancelled.'."\n");
            }

            $validateOTP = postX("https://settings.mylykaapps.com/api/v3/otpservices/ValidateOTPV2", payload($DevIdx,'"reference":"phone","requestId":"'.$sendOTP.'","type":"register","value":"'.$phoneNumberx.'",'."code".":".'"'.$xOTP.'"'));

        
            if( $validateOTP->data) {
                $filreg["signedToken"] = $validateOTP->data->signedToken;
                echo $validateOTP->data->signedToken;
                $filreg["code"] = $xOTP;
                $newfilesReg = json_encode($filreg);
                file_put_contents('lykaregisters.json', $newfilesReg);
                $OTPsucc = true;
                $otpMess = $validateOTP->message;
                
                if($otpMess == "Invalid passcode."){
                    echo $otpMess."\n\n";
                } else{
                echo $otpMess."\n\n";

                // $xregiCode = readline("-->> Continue with registration?: ");
                $regFile = file_get_contents('lykaregisters.json');
                $filreg = json_decode($regFile, true);   
                $codex = $filreg["code"];
                $signedTokenx = $filreg["signedToken"];
                
                    if($signedTokenx!=""){
                        $registerPass = postX("https://identity.mylykaapps.com/useraccounts/RegisterV3", payload($DevIdx,'"birthDate":"'.$birthDatex.'","code":"'.$codex.'","countryCode":"PH","fullname":"'.$fullnamex.'","gender":"'.$genderx.'","isMerchant":false,"password":"'.$passwordx.'","phoneNumber":"'.$phoneNumberx.'","signedToken":"'.$signedTokenx.'","type":"phone","username":"'.$usernamex.'"'),$notificationTokenx) ;

                        if($registerPass->data) {
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
                
                echo "Invalid OTP."."\n";
            }
        }

         }//if OTP is successful
   }
}


?>