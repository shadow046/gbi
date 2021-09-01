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
function random_username($string) {
    $pattern = " ";
    $firstPart = strstr(strtolower($string), $pattern, true);
    $secondPart = substr(strstr(strtolower($string), $pattern, false), 0,3);
    $nrRand = rand(0, 100);
    
    $username = trim($firstPart).trim($secondPart).trim($nrRand);
    return $username;
}
function randomName() {
    $firstname = array(
        'Johnathon',
        'Anthony',
        'Erasmo',
        'Raleigh',
        'Nancie',
        'Tama',
        'Camellia',
        'Augustine',
        'Christeen',
        'Luz',
        'Diego',
        'Lyndia',
        'Thomas',
        'Georgianna',
        'Leigha',
        'Alejandro',
        'Marquis',
        'Joan',
        'Stephania',
        'Elroy',
        'Zonia',
        'Buffy',
        'Sharie',
        'Blythe',
        'Gaylene',
        'Elida',
        'Randy',
        'Margarete',
        'Margarett',
        'Dion',
        'Tomi',
        'Arden',
        'Clora',
        'Laine',
        'Becki',
        'Margherita',
        'Bong',
        'Jeanice',
        'Qiana',
        'Lawanda',
        'Rebecka',
        'Maribel',
        'Tami',
        'Yuri',
        'Michele',
        'Rubi',
        'Larisa',
        'Lloyd',
        'Tyisha',
        'Samatha',
    );

    $lastname = array(
        'Mischke',
        'Serna',
        'Pingree',
        'Mcnaught',
        'Pepper',
        'Schildgen',
        'Mongold',
        'Wrona',
        'Geddes',
        'Lanz',
        'Fetzer',
        'Schroeder',
        'Block',
        'Mayoral',
        'Fleishman',
        'Roberie',
        'Latson',
        'Lupo',
        'Motsinger',
        'Drews',
        'Coby',
        'Redner',
        'Culton',
        'Howe',
        'Stoval',
        'Michaud',
        'Mote',
        'Menjivar',
        'Wiers',
        'Paris',
        'Grisby',
        'Noren',
        'Damron',
        'Kazmierczak',
        'Haslett',
        'Guillemette',
        'Buresh',
        'Center',
        'Kucera',
        'Catt',
        'Badon',
        'Grumbles',
        'Antes',
        'Byron',
        'Volkman',
        'Klemp',
        'Pekar',
        'Pecora',
        'Schewe',
        'Ramage',
    );
    $name = $firstname[rand ( 0 , count($firstname) -1)];
    $name .= ' ';
    $name .= $lastname[rand ( 0 , count($lastname) -1)];
    return $name;
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
    global $password;
    global $fulln;
    $number= readline("-->> Phone Number: +63");
    if($number == "x"){
        exit('cancelled.'."\n");
    }

    $fulln= readline("-->> Enter fullname: ");
    if ($fulln == "") {
        exit('cancelled.'."\n");
    }

    if($password == ""){
        $password= readline("-->> Password: ");
        if ($password == "") {
            exit('cancelled.'."\n");
        }
    }
    $min = strtotime("47 years ago");
    $max = strtotime("18 years ago");
    $rand_time = mt_rand($min, $max);
    $phoneNumberx = "+63".$number;
    $birthDatex = date('m/d/Y', $rand_time);
    $fullnamex = $fulln;
    $genderx = "Male";
    $usernamex = random_username($fullnamex);
    $passwordx = $password;
    $DevIdx = dechex(mt_rand()).dechex(mt_rand());
    $notificationTokenx = notkens();

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
                    $signedTokenx = $validateOTP->data->signedToken;
                    // echo $validateOTP->data->signedToken;
                    $codex = $xOTP;
                    $OTPsucc = true;
                    $otpMess = $validateOTP->message;
                    if($otpMess == "Invalid passcode."){
                        echo $otpMess."\n\n";
                    }else{
                        echo $otpMess."\n\n";
                        if($signedTokenx!=""){
                            $registerPass = postX("https://identity.mylykaapps.com/useraccounts/RegisterV3", payload($DevIdx,'"birthDate":"'.$birthDatex.'","code":"'.$codex.'","countryCode":"PH","fullname":"'.$fullnamex.'","gender":"'.$genderx.'","isMerchant":false,"password":"'.$passwordx.'","phoneNumber":"'.$phoneNumberx.'","signedToken":"'.$signedTokenx.'","type":"phone","username":"'.$usernamex.'"'),$notificationTokenx) ;
                            if($registerPass->data) {
                                $cookyStr = file_get_contents('lyaccnts.json');
                                $accntdata = json_decode($cookyStr, true);
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
    register();
}
register();
?>