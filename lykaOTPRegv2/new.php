<?php
error_reporting(0);
$b="\033[1;35m";
$green="\033[1;32m";
$yllw="\033[1;33m";
$wh="\033[1;94m";
$cyn="\033[1;96m";
$lred="\033[1;91m";
$wht="\033[1;97m";
$blhigh = "\033[44m";
$defhigh = "\033[49m";
$lcyan = "\033[34m";

$lykM = "
:::        :::   ::: :::    :::     :::     
:+:        :+:   :+: :+:   :+:    :+: :+:   
+:+         +:+ +:+  +:+  +:+    +:+   +:+  
+#+          +#++:   +#++:++    +#++:++#++: 
+#+           +#+    +#+  +#+   +#+     +#+ 
#+#           #+#    #+#   #+#  #+#     #+# 
##########    ###    ###    ### ###     ### ";

function chkJ($storeName){
    if(!file_exists($storeName)){
        $fp = fopen($storeName, 'w');
        if($storeName=="profile.json"){
            //{ "accountStorage" : "lyaccnts.json"}
            fwrite($fp, '{"accountStorage" : "lyaccnts.json"}');
            fclose($fp);
        } else {
        fwrite($fp, "{}");
        fclose($fp);
        }
    }
};
chkJ("lyaccnts.json");chkJ("lyfiles.json");chkJ("lykaregister.json");chkJ("profile.json");

$accntSetting = file_get_contents('profile.json');
$accntX = json_decode($accntSetting, true);

function slp($sec){
    for ($x = 0; $x < $sec; $x++) {
        echo "."; sleep(1);
    }
}

$cookyStr = file_get_contents('lyaccnts.json');
$accntdata = json_decode($cookyStr, true);
$filesTr = file_get_contents('lyfiles.json');
$filesdata = json_decode($filesTr, true);
$totalaccnt = count($accntdata);

//Set the sleep of entire file

if(!$accntX["sleep"]??null){
    $accntX["sleep"] = 1;
    $newaccntx = json_encode($accntX);
    file_put_contents('profile.json', $newaccntx);
}
if(!$accntX["sleep"]??null){
    $accntX["sleep"] = 1;
    $newaccntx = json_encode($accntX);
    file_put_contents('profile.json', $newaccntx);
}
if(!$accntX["mainAccounts"]??null){
    $accntX["mainAccounts"] = [];
    foreach($accntdata as $rateAcc => $vals){
       if($vals["cookie"]!=""){
        if(count($accntX["mainAccounts"])==4){
            break;
        }
        $accntX["mainAccounts"][]=$rateAcc;
       }
    }
    $newaccntx = json_encode($accntX);
    file_put_contents('profile.json', $newaccntx);
}

$sleepSec = intval($accntX["sleep"]);
$notActivWall = 0;
$totalGms = 0;
$rcountr=1;
$walletErr="";
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
};
function postX($urlx, $payloader, $cooks = ""){
    global $uAgent,$sleepSec;
    $postURL = $urlx;
    $postHeader = !$cooks 
        ? array(
        "Content-Type: application/json; charset=UTF-8",
        "user-agent: $uAgent") 
        : array(
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
};
function getX($urlx, $gdevID, $cooks = ""){
    global $uAgent,$sleepSec;
    $getURL = $urlx;
    $getHeader = array(
        "Content-Type: application/json; charset=UTF-8",
        "user-agent: $uAgent",
        "authorization: Bearer $cooks",
        "x-clientid: $gdevID",
        );
        $getCurl = curl_init($getURL);
        curl_setopt($getCurl, CURLOPT_URL, $getURL);
        curl_setopt($getCurl, CURLOPT_HTTPGET, true);
        curl_setopt($getCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($getCurl, CURLOPT_HTTPHEADER, $getHeader);
        sleep($sleepSec);
        $getResp = curl_exec($getCurl);
        curl_close($getCurl);
        $getjson = json_decode($getResp);
     //    echo $getResp;
        return $getjson;
};
function payload($devIDx, $xtraPay, $rTokenx = ""){
    $valdata = <<<DATA
            {"device": {
                "deviceId": "$devIDx",
                "deviceImei": "",
                "deviceModel": "Xiaomi Redmi Note 5",
                "deviceName": "android",
                "deviceOs": "Android R ",
                "isEmulator": false,
                "osVersion": "30",
                "notificationToken": "$rTokenx"
            },
            "countryCode": "US",
            $xtraPay
            }
            DATA; 
    return $valdata;
};
function getGems($devIDgem, $accCookie, $uname =""){
    global $totalGms,$notActivWall,$green;
    $getGemsURL1= "https://wallets.mylykaapps.com/api/v3/wallets/getgems?os=android";
    $gemJSON = getX($getGemsURL1, $devIDgem, $accCookie);
    $new= json_encode($gemJSON);
    file_put_contents('gems.json', $new);
    if(!$gemJSON->data->isWalletActivated){
        activateWallet($devIDgem,$accCookie,$uname);
        $notActivWall++;
    }
    echo $green.".";
    $totalGms+=$gemJSON->data->totalGem;
    return !$gemJSON->data ?: $gemJSON->data->totalGem;
};
function lykalogin($accntun,$accntpw){
    global $uAgent;
    global $accntdata;
    global $green;
    global $lred;
    $loginURL = "https://identity.mylykaapps.com/useraccounts/login";
    $loginHeader = array(
    "Content-Type: application/json; charset=UTF-8",
    "user-agent: $uAgent"
    );

        $devIDGen =  dechex(mt_rand()).dechex(mt_rand());
        $notiTokn =   notkens() ;


    $loginData =  payload($devIDGen,'"password": "'.$accntpw.'", "username": "'.$accntun.'"',$notiTokn);
    $cloginURL = curl_init();
    curl_setopt_array($cloginURL, array(
    CURLOPT_PORT => "443",
    CURLOPT_URL => "$loginURL",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $loginData,
    CURLOPT_HTTPHEADER => $loginHeader));

    $loginResp = curl_exec($cloginURL);
    curl_close($cloginURL);
    $loginJson = json_decode($loginResp);
     
    
    $accntdata[$accntun]["devId"] = "$devIDGen";
    $accntdata[$accntun]["noToken"] = "$notiTokn";
    $accntdata[$accntun]["password"] = "$accntpw";

    
    if($loginJson->status) {
            $loginBearer = $loginJson->data->token->accessToken;
            $accntdata[$accntun]["cookie"] = $loginBearer;
    } else if($loginJson->Data) {
        echo "\n\n$accntun → → →$lred  Failed!!"."\n\n";
    }  
    
    $newaccntdata = json_encode($accntdata);
    file_put_contents('lyaccnts.json', $newaccntdata);
    $cookyStr = file_get_contents('lyaccnts.json');
    $accntdata = json_decode($cookyStr, true);

    if($accntdata[$accntun]["cookie"] != null){
        echo "\n\n$accntun → → →$green  success!"."\n\n";
    } else {
        echo "\n\n$accntun → → →$lred  Failed!!"."\n\n";
    }


};
function lykalogout($accntun){
    global $uAgent;
    global $accntdata, $yllw,$green;
    
    if($accntdata[$accntun]??null ){
    $lout = "https://users.mylykaapps.com/api/v3/users/logoutuser";
    $fdevID = $accntdata[$accntun]["devId"];
    $fcookiex = $accntdata[$accntun]["cookie"];
    $fnoToken = $accntdata[$accntun]["noToken"];

     $delHeader = array(
        "authorization: Bearer $fcookiex",
        "Content-Type: application/json",
        "user-agent: $uAgent",
        "x-clientid: $fdevID",
        "deviceos: android",
        );
        $loutCurl = curl_init($lout);
        curl_setopt($loutCurl, CURLOPT_URL, $lout);
        curl_setopt($loutCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($loutCurl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($loutCurl, CURLOPT_HTTPHEADER, $delHeader);
        $deldata = payload($fdevID,"",$fnoToken);
        curl_setopt($loutCurl, CURLOPT_POSTFIELDS, $deldata);
        $deleteResp = curl_exec($loutCurl);
        curl_close($loutCurl);
        $deljson = json_decode($deleteResp);
         if($deljson->status){
                echo "\n\n"."$accntun$yllw logged out.\n\n";
                $accntdata[$accntun]["cookie"] = "";
                $accntdata[$accntun]["password"] = "";
            }  else {
                echo "\n\nAccount not logged in.\n\n";
            } 
     } else {
        echo "$green\n\n$accntun$yllw not in your list.\n\n";
     }
     
    $newaccntdata = json_encode($accntdata);
    file_put_contents('lyaccnts.json', $newaccntdata);
};
function getUid($rDevID,$mcCookie){
    $getUID = getX("https://profiles.mylykaapps.com/api/v3/profiles/GetUserProfileForEditing?os=android", $rDevID,$mcCookie);
    return $getUID->data->id;
};
function getUserPost($rDevID,$mcCookie,$guid){
    $gURL = "https://profiles.mylykaapps.com/api/v3/profiles/GetProfilePosts?os=android&pageIndex=1&pageSize=41&id=$guid&category=ALL";
    return getX($gURL,$rDevID,$mcCookie);
};
function getPost($rDevIDs,$mcCookies,$postId){
    $gURLs = "https://posts.mylykaapps.com/api/v3/posts/getpost?os=android&id=$postId";
    return getX($gURLs,$rDevIDs,$mcCookies);
};
function ratePost($rDevIDr,$postId,$userid,$notToken,$rcooks){
    $rURL = "https://posts.mylykaapps.com/api/v3/posts/ratepost";
    return postX($rURL,payload($rDevIDr,'"postId": '.$postId.',"rate": 5,"userid": "'.$userid.'"',$notToken),$rcooks);
};
function allRater($toRates, $mcounters = 0){
    global $accntdata;
    global $mrate;
    global $yllw;
    global $wht;
    global $lred;
    global $rcountr;
    global $green;
    $timesended = date("h:i:sa");
    $rCooki = $accntdata[$toRates]["cookie"];
    $rDevID = $accntdata[$toRates]["devId"];
    echo "$yllw$mrate\n$wht";
    echo $timestarted."\n";
    global $timestarted;
    echo "Time Started: ".$timestarted."\n";
    echo "\n$wht---------------------------------$wht\n";
    echo "You are rating → $toRates\n$lred";
    $gmny = getGems($rDevID,$rCooki);
    echo "gems:$green ⬘⬘ ".$wht.$gmny;
    echo "\n$lred---------------------------------$wht\n";

    $uid = GetUid($rDevID,$rCooki);
    $postArrs = getUserPost($rDevID,$rCooki,$uid);
    //ID of the account being rated
 
    $mcount = 1;
      //Dummy accounts
    if($postArrs){
        foreach($accntdata as $rateAcc => $vals){
            if($rateAcc!=$toRates){
                $raterCooks = $vals["cookie"];
                $rrDevID = $vals["devId"];
                $rrToken = $vals["noToken"];
                if ($mcounters == 10) {
                    $main = $vals["main10"];
                }else if ($mcounters == 20) {
                    $main = $vals["main20"];
                }else if ($mcounters == 30) {
                    $main = $vals["main30"];
                }else if ($mcounters == 40) {
                    $main = $vals["main40"];
                }
                $post = array();
                for ($i=1; $i < 41; $i++) { 
                    array_push($post, $vals["post".$i]);
                }
                $maxRateX = false;
                $attmpts = 0;
                $pcnt = 0;
                if ($main == 'no') {
                    if($postArrs->data??null && $raterCooks!=""){
                        $dummygems = getGems($rrDevID,$raterCooks);
                        $postkey = 0;
                        $postkey = $postkey+$mcounters;
                        $countpost = 0;
                        foreach ($postArrs->data as $arrb) {
                            $postId = $arrb->id;
                            $countpost++;
                            if ($countpost < 11) {
                                if (!in_array($postId, $post)) {
                                    if($postId && !$maxRateX){
                                        $postRep = getPost($rrDevID,$raterCooks,$postId);
                                                //  echo $postRep->message;
                                        if($postRep->data->count->rateSent==0){
                                            $ratedX = ratePost($rrDevID,$postId,$uid,$rrToken,$raterCooks);
                                            //  echo $ratedX->message;
                                            if($ratedX->status){
                                                $dummygem = getGems($rrDevID,$raterCooks);
                                                $dummygems = $dummygem;
                                                echo "\n$yllw--- $wht$rateAcc$yllw ---";
                                                echo "Total $green Gems: ⬘⬘ ".$wht.$dummygem."$green rated  →$yllw Main $toRates Total $green Gems: ⬘⬘ ".$wht.getGems($rDevID, $rCooki)."\n";
                                                $pcnt++;
                                                $postkey++;
                                                $accntdata[$rateAcc]["post".$postkey] = $postId;
                                                $newaccntdata = json_encode($accntdata);
                                                file_put_contents('lyaccnts.json', $newaccntdata);
                                                $cookyStr = file_get_contents('lyaccnts.json');
                                                $accntdata = json_decode($cookyStr, true);
                                            } else if($pcnt>=11) {
                                                $maxRateX = false;
                                                break;
                                            }
                                        }else if($postRep->data->count->rateSent==5){
                                            break;
                                        }
                                    }
                                }else{
                                    $postkey = array_search($postId, $post)+1;
                                }
                            }
                        }
                        echo "$wht$rateAcc$yllw maxed. Total $green Gems: ⬘⬘  ".$wht.$dummygems." $lred|$yllw Main $toRates Total $green Gems: ⬘⬘  ".$wht.getGems($rDevID, $rCooki)."\n";
                        $accntdata[$rateAcc]["main".$mcounters] = "yes";
                        $newaccntdata = json_encode($accntdata);
                        file_put_contents('lyaccnts.json', $newaccntdata);
                        $cookyStr = file_get_contents('lyaccnts.json');
                        $accntdata = json_decode($cookyStr, true);
                    }
                }
                $rcountr++;
            }
            $mcount++;
        }
    } else {
        echo "\nno posts to rate.\n";
    }


};
function delPost($fdevIdx,$fcookiex,$postIdx){
    global $uAgent,$wht, $yllw;
    $delURL = "https://posts.mylykaapps.com/api/v3/posts/deletepost";
    $delHeader = array(
    "authorization: Bearer $fcookiex",
    "Content-Type: application/json",
    "user-agent: $uAgent",
    "x-clientid: $fdevIdx",
    "deviceos: android",
    );
    $deleteCurl = curl_init($delURL);
    curl_setopt($deleteCurl, CURLOPT_URL, $delURL);
    curl_setopt($deleteCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($deleteCurl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($deleteCurl, CURLOPT_HTTPHEADER, $delHeader);
    $deldata = <<<DATA
    {"id": $postIdx
    }
    DATA;  
        curl_setopt($deleteCurl, CURLOPT_POSTFIELDS, $deldata);
        usleep(500000);
        $deleteResp = curl_exec($deleteCurl);
        curl_close($deleteCurl);
        $deljson = json_decode($deleteResp);
    return $deljson;
};
function delMoment($fdevIdx,$fcookiex,$postIdx){
    global $uAgent;
    $delURL = "https://posts.mylykaapps.com/api/v3/posts/deletepost";
    $delHeader = array(
    "authorization: Bearer $fcookiex",
    "Content-Type: application/json",
    "user-agent: $uAgent",
    "x-clientid: $fdevIdx",
    "deviceos: android",
    );
    $deleteCurl = curl_init($delURL);
    curl_setopt($deleteCurl, CURLOPT_URL, $delURL);
    curl_setopt($deleteCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($deleteCurl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($deleteCurl, CURLOPT_HTTPHEADER, $delHeader);
    $deldata = <<<DATA
    {"id": $postIdx
    }
    DATA;  
        curl_setopt($deleteCurl, CURLOPT_POSTFIELDS, $deldata);
        $deleteResp = curl_exec($deleteCurl);
        curl_close($deleteCurl);
        $deljson = json_decode($deleteResp);
    return $deljson;
};
function uploader($fdevIds,$fcookie, $fuid ,$fdataM, $unamer,$xmain = false){
    global $wht, $yllw, $accntdata,$accntX;
    $yr = date("Y");
    $dy = date("d");
    $mnt = intval(date("m"));
    $uploadCnt = 0;
    $uploadDone = false;
   
    while(!$uploadDone){
    foreach ($fdataM as $fdat){
        if($uploadCnt==11){
            $uploadDone = true;
            break;
        }
        $fname = $fdat["fname"];
        $fdat["file"][0]["key"] .= "$fuid/$yr$mnt$dy/$fname";
        $fdataUp = json_decode(join("",$fdat["file"][0]));
        $fdataTitle =  $fdat["title"];
        $fdataContent =  $fdat["content"];
        //Query upload
        $uploadUrlxx = "https://posting.mylykaapps.com/api/v3/posts/AddImagePost";
        $uploadRe = PostX($uploadUrlxx,payload($fdevIds,'"files": ['.$fdataUp.'],"isHighlight": true,"isSharedLink":false,"mediaTags": "[[]]","title": "'.$fdataTitle.'-'.$mnt.$dy.'","content": "'.$fdataContent.'"'),$fcookie);
        //Moments 
        $upresp = $uploadRe->message;
       if($upresp == "323 - Post saved."){
        echo "$yllw →$wht Photo uploaded →$yllw Gems: ".getGems($fdevIds, $fcookie)."\n";
        //data->id-- to rate

        if($xmain && in_array($unamer,$accntX["mainAccounts"]) ){
            echo "\nRating.\n";
            foreach($accntdata as $rateAcc => $vals){
                    $raterCooks = $vals["cookie"];
                    $rrDevID = $vals["devId"];
                    $rrToken = $vals["noToken"];
                    getGems($rrDevID, $raterCooks);
                        echo "\n$yllw--- $wht$rateAcc$yllw ---\n";
                        $postId = $uploadRe->data->id;
                        $userIDd = $uploadRe->data->owner->id;
                        if( $raterCooks!=""){
                            $ratedX = ratePost($rrDevID,$postId,$userIDd,$rrToken,$raterCooks);
                        //  echo $ratedX->message;
                            if($ratedX->status){
                            echo "rated ..  →$yllw Gems: ".getGems($fdevIds, $fcookie)."\n";
                        } 
                    }
                }
            }

        $uploadCnt++;
       }
    }
    }
    echo "\n";
    return $uploadCnt-1;
};
function uploaderMoment($fdevIds,$fcookie, $fuid, $xmain = false){
    global $yllw;
    global $wht;
    echo "$wht\n→ Uploading Moments\n\n";
        $uploadLegacy = "https://media.mylykaapps.com/api/v1/media/social/multi-upload-url";
        $uploadPay = <<<DATA
                  {"category":"moment","clientId":"$fuid","files":[{"fileName":"hakdog.jpeg",
                    "mediaType":"image"}]}
                  DATA; 
        $uploadLegPost = postX($uploadLegacy,$uploadPay,$fcookie);

        if($uploadLegPost->data){
            $mediaID = $uploadLegPost->data[0]->mediaId;

            $amznToken = "https://media.mylykaapps.com/api/v1/access/aws/media-token/$fuid";
            $firstGet = GetX($amznToken,$fdevIds,$fcookie);

            if($firstGet->data){
                $amzIDid = $firstGet->data->identityId;
                $amzIDtoken = $firstGet->data->token; 

                $postURL = "https://cognito-identity.ap-southeast-1.amazonaws.com/";
                $postHeader =  array(
                "Content-Type: application/x-amz-json-1.1",
                "Accept-encoding: indentity",
                "X-Amz-Target: AWSCognitoIdentityService.GetCredentialsForIdentity",
                "user-agent: aws-sdk-android/2.22.4 Linux/3.18.140-gb765813d2c04 Dalvik/2.1.0/0 en_US") ;
                $amndata = <<<DATA
                        {"Logins": {
                            "cognito-identity.amazonaws.com": "$amzIDtoken"},
                        "IdentityId": "$amzIDid"}
                        DATA; 
                $postCurl = curl_init($postURL);
                curl_setopt($postCurl, CURLOPT_URL, $postURL);
                curl_setopt($postCurl, CURLOPT_POST, true);
                curl_setopt($postCurl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($postCurl, CURLOPT_HTTPHEADER, $postHeader);
                curl_setopt($postCurl, CURLOPT_POSTFIELDS, $amndata);
                $postResp = curl_exec($postCurl);
                curl_close($postCurl);
               // echo $postResp;
                $postjson = json_decode($postResp);
                if($postjson->Credentials){

                  $sessToken = $postjson->Credentials->SessionToken;
                  $aws_access_key_id = $postjson->Credentials->AccessKeyId;
                  $aws_secret_access_key = $postjson->Credentials->SecretKey;
                    
                  //AWS Process
                  $bucket_name = 'lyka-legacy-images-input';
                  $aws_region = 'ap-southeast-1';
                  $host_name = $bucket_name . '.s3.amazonaws.com';
                  $content = "0";
                  $content_title = $mediaID;
                  $aws_service_name = 's3';
                  $timestamp = gmdate('Ymd\THis\Z');
                  $date = gmdate('Ymd');
                  $request_headers = array();
                  $request_headers['x-amz-date'] = $timestamp;
                  $request_headers['Host'] = $host_name;
                  $request_headers['x-amz-security-token'] = $sessToken;
                  $request_headers['x-amz-content-sha256'] = hash('sha256', $content);
                  ksort($request_headers);

                  $canonical_headers = [];
                  foreach($request_headers as $key => $value) {
                      $canonical_headers[] = strtolower($key) . ":" . $value;
                  }
                  $canonical_headers = implode("\n", $canonical_headers);
                  
                  // Signed headers
                  $signed_headers = [];
                  foreach($request_headers as $key => $value) {
                      $signed_headers[] = strtolower($key);
                  }
                  $signed_headers = implode(";", $signed_headers);
                  
                  // Cannonical request 
                  $canonical_request = [];
                  $canonical_request[] = "PUT";
                  $canonical_request[] = "/" . $content_title;
                  $canonical_request[] = "";
                  $canonical_request[] = $canonical_headers;
                  $canonical_request[] = "";
                  $canonical_request[] = $signed_headers;
                  $canonical_request[] = hash('sha256', $content);
                  $canonical_request = implode("\n", $canonical_request);
                  $hashed_canonical_request = hash('sha256', $canonical_request);
                  
                  // AWS Scope
                  $scope = [];
                  $scope[] = $date;
                  $scope[] = $aws_region;
                  $scope[] = $aws_service_name;
                  $scope[] = "aws4_request";
                  
                  // String to sign
                  $string_to_sign = [];
                  $string_to_sign[] = "AWS4-HMAC-SHA256"; 
                  $string_to_sign[] = $timestamp; 
                  $string_to_sign[] = implode('/', $scope);
                  $string_to_sign[] = $hashed_canonical_request;
                  $string_to_sign = implode("\n", $string_to_sign);
                  
                  // Signing key
                  $kSecret = 'AWS4' . $aws_secret_access_key;
                  $kDate = hash_hmac('sha256', $date, $kSecret, true);
                  $kRegion = hash_hmac('sha256', $aws_region, $kDate, true);
                  $kService = hash_hmac('sha256', $aws_service_name, $kRegion, true);
                  $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
                  
                  // Signature
                  $signature = hash_hmac('sha256', $string_to_sign, $kSigning);
                  
                  // Authorization
                  $authorization = [
                      'Credential=' . $aws_access_key_id . '/' . implode('/', $scope),
                      'SignedHeaders=' . $signed_headers,
                      'Signature=' . $signature
                  ];
                  $authorization = 'AWS4-HMAC-SHA256' . ' ' . implode( ',', $authorization);
                  
                  // Curl headers
                  $curl_headers = [ 'Authorization: ' . $authorization ];
                  foreach($request_headers as $key => $value) {
                      $curl_headers[] = $key . ": " . $value;
                  }
                  
                  $url = 'https://' . $host_name . '/' . $content_title;
                  $ch = curl_init($url);
                  curl_setopt($ch, CURLOPT_HEADER, false);
                  curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
                  curl_exec($ch);
                  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                 // echo $http_code;
                  if($http_code != 200) 
                  exit('Error : Failed to upload');
                  



                  
                  $delURL = "https://lyka-legacy-images-input.s3.ap-southeast-1.amazonaws.com/".$mediaID;
                      $delHeader = array(
                        "x-clientid: $fdevIds",
                        "x-amz-date: $timestamp",
                        "x-amz-content-sha256: STREAMING-AWS4-HMAC-SHA256-PAYLOAD",
                        "Authorization: $authorization"
                        );
                       $deleteCurlx = curl_init($delURL);
                          curl_setopt($deleteCurlx, CURLOPT_URL, $delURL);
                          curl_setopt($deleteCurlx, CURLOPT_RETURNTRANSFER, true);
                          curl_setopt($deleteCurlx, CURLOPT_CUSTOMREQUEST, "PUT");
                          curl_setopt($deleteCurlx, CURLOPT_HTTPHEADER, $delHeader);
                          $deleteResp = curl_exec($deleteCurlx);
                          curl_close($deleteCurlx);

                //Run the upload 10x
                  $cmoments = 0;
                  for ($x = 0; $x < 25; $x++) {
                    $uploadMoment = "https://momenting.mylykaapps.com/api/v3/moments/AddImageMoment";
                    sleep(1);
                    $momnts = PostX($uploadMoment,payload($fdevIds,'"files":[{"height":2081,"key":"'.$mediaID.'", "RemoteStorage":"lyka-legacy-images-input" ,"type":"image","width":1079}]'),$fcookie);
                    $momntsss = $momnts->message;
                   // echo $momnts->message;
                    if( $momntsss == "Moment retrieved."){
                        echo "$yllw →$wht Moment posted →$yllw Gems: ".getGems($fdevIds, $fcookie)."\n";
                        $cmoments++;
                        if($cmoments==10){
                            $x=25;
                        }
                    }
                    
                }
                    
                   
                }
        }}



    return null;
};

function activateWallet($devId, $cookw, $uname){
    global $walletErr;
    $url = "https://wallets.mylykaapps.com/api/v3/wallets/activatewallet";
    $jsonreps =  postX($url,payload($devId,""),$cookw);
    echo "\n$jsonreps->message\n";
    $walletErr .= $uname."--".$jsonreps->message."\n";
    $wal = file_get_contents('foractivation.json');
    $wall = json_decode($wal, true);
    $wall[$uname] = "$walletErr";
    $walldata = json_encode($wall);
    file_put_contents('foractivation.json', $walldata);
}

function iniStorage($storageName){
    $accntCnt = 0;
    foreach($storageName as $acct=>$vals){
        if( $vals["cookie"] ?? null){
            getGems($vals["devId"],$vals["cookie"],$acct);
            $accntCnt++;
        }
    }
    return $accntCnt;
};




 $conPro = "";
 $x44 = true;
 while($x44){
    @system("clear");
echo "$green$lykM";
echo "\n\n$lred\n ©e$wht"."LYK$lred"."tr$wht"."A$wh v1.3";
echo "\n$b----------------------------------------------\n\n\n"."$green 1. Upload Photo & Moment"."\n";
echo "$yllw 2. Max Rate"."\n";
echo "$lred 3. Transfer Gems"."\n";
echo "$wh 4. Reset cookies"."\n";
echo "$wht 5. Check Gems $cyn.$blhigh ⬘⬘ $defhigh$cyn."."\n";
echo "$yllw 6. Login - Logout $cyn\n";
echo "$green 7. Accounts $cyn\n";
echo "$wht 8. Settings $cyn\n";
echo "$lred 9. Reset Upload count"."\n\n\n\n";

$postMe = readline("What to do? \n → ");
$uAgent = "Lyka/3.6.36 (com.thingsilikeapp; build:836 Android R 30)";


$lastUpload = "";
$lastRate = "";

if($postMe == 9){
    $accntgetupload['upload'] = "1";
    $newupload = json_encode($accntgetupload);
    file_put_contents('lyupload.json', $newupload);
}else if($postMe == 1){
    @system("clear");
    $upldr =  "    | | ._  |  _   _.  _|  _  ._
    |_| |_) | (_) (_| (_| (/_ | 
        |    ";
        echo "\n$green$upldr";
        echo "\n\n";
        echo "\n$yllw";
        echo "\n$b----------------------------------------------\n\n$wht";
        echo "$yllw 1.$wht Update all accounts\n";
        echo "$yllw 2.$wht Upload wall photo\n";
        echo "$yllw 3.$wht Upload moments\n";
        echo "$yllw 4.$wht Rate main accnts\n";
        echo "$yllw 5.$wht Delete all posts\n\n\n\n$yllw";
        echo "Use comma i.e. 1,2,5\n\n$wht";
        $uploadAll = readline('→ ');
        echo "\n$wht";
        $tcountr = 1;
        $getupload = file_get_contents('lyupload.json');
        $accntgetupload= json_decode($getupload, true);
        if($uploadAll!=""){
            $sopt = explode(",",$uploadAll);
            if(in_array("1",$sopt)){ //update all
                foreach($accntdata as $accnts => $vals) {
                    if ($tcountr >= $accntgetupload['upload'] ) {
                        if($vals["cookie"]??null){
                            $mcCookie =$vals["cookie"];
                            $mcDev = $vals["devId"];
                            //Uploader 
                            @system("clear");
                            echo "\n$green$upldr";
                            echo "\n";
                            echo "\n\n$wht------- $cyn$accnts$wht -------- .. $tcountr \n\n";
                            $getUID = getX("https://profiles.mylykaapps.com/api/v3/profiles/GetUserProfileForEditing?os=android", $mcDev,$mcCookie);
                            if($getUID->data??null){
                                $fuid = $getUID->data->id;
                                if(in_array("5",$sopt)){ //Delete photos
                                    if(in_array("2",$sopt)){
                                        $postArr2 = getX("https://profiles.mylykaapps.com/api/v3/profiles/GetProfilePosts?os=android&pageIndex=1&pageSize=200&id=$fuid&category=ALL",$ $mcDev, $mcCookie);
                                        echo "→ Deleting posts";
                                        if($postArr2->data??null){
                                            foreach ($postArr2->data as $arrb) {
                                                $postIdx = $arrb->id;
                                                $deljson = delPost($mcDev,$mcCookie,$postIdx);
                                                    if($deljson->message){
                                                        echo ".";
                                                    }
                                            } //forEach IDs
                                        }
                                        echo "\n\n";
                                    }

                                    if(in_array("3",$sopt)){
                                        $postArrMom = getX("https://moments.mylykaapps.com/api/v3/moments/gethomemoments?os=android&pageIndex=1&pageSize=200",$mcDev, $mcCookie);
                                        $arrb2 = $postArrMom->data[0];
                                        echo "→ Deleting moments";
                                        if($arrb2->mediaContents??null){
                                        foreach($arrb2->mediaContents as $medme){
                                            $postIdx2 = $medme->id;
                                            $del2json = delMoment($mcDev,$mcCookie,$postIdx2);
                                            if($del2json->message){
                                                echo ".";
                                            }
                                        }
                                    }
                                    echo "\n\n";
                                    }
                                }
                                $uploadjson = 0;
                                if(in_array("2",$sopt)){
                                    $uploadjson = uploader($mcDev,$mcCookie, $fuid ,$filesdata,$accnts,in_array("4",$sopt));
                                    echo "\n";
                                    if($uploadjson > 0){ 
                                        echo "\nUploaded $uploadjson Photos.. ";
                                        echo "\n";
                                    }
                                }
                                if(in_array("3",$sopt)){
                                    uploaderMoment($mcDev,$mcCookie ,$fuid);
                                    echo "\nUploaded $uploadjson Moments.. ";
                                    echo "\n";
                                }
                                echo "\n";
                            }
                            echo "\n";
                            $tcountr++;
                            $accntgetupload['upload'] = "$tcountr";
                            $newupload = json_encode($accntgetupload);
                            file_put_contents('lyupload.json', $newupload);
                        }
                    }else{
                        $tcountr++;
                    }
                }
            } else {
                @system("clear");
                echo "\n$green$upldr";
                echo "\n\n";
                echo "\n$yllw";
                echo "\n$b----------------------------------------------\n\n$wht";
                $selUpl = readline("Upload to username: ");
                
                if(!$accntdata[$selUpl]??null){
                    echo "\n$lred$selUpl$wht not found in your accounts. ";
                    echo "\nLogin the username then try again.\n\n\n";
                    exit();
                } else {

                    $mcCookie =$accntdata[$selUpl]["cookie"];
                    $mcDev = $accntdata[$selUpl]["devId"];
                        //Uploader 
                    @system("clear");
                    echo "\n$green$upldr";
                    echo "\n";
                    echo "\n\n$wht------- $cyn$selUpl$wht -------- ..  $tcountr \n\n";
                    $getUID = getX("https://profiles.mylykaapps.com/api/v3/profiles/GetUserProfileForEditing?os=android", $mcDev,$mcCookie);

                    if($getUID->data??null){
                        $fuid = $getUID->data->id;
                        if(in_array("5",$sopt)){ //Delete photos
                            if(in_array("2",$sopt)){
                                $postArr2 = getX("https://profiles.mylykaapps.com/api/v3/profiles/GetProfilePosts?os=android&pageIndex=1&pageSize=200&id=$fuid&category=ALL",$ $mcDev, $mcCookie);
                                echo "→ Deleting posts";
                                if($postArr2->data??null){
                                    foreach ($postArr2->data as $arrb) {
                                        $postIdx = $arrb->id;
                                            $deljson = delPost($mcDev,$mcCookie,$postIdx);
                                            if($deljson->message){
                                                echo ".";
                                            }
                                        } //forEach IDs
                                }
                                echo "\n\n";
                            }
                            if(in_array("3",$sopt)){
                                $postArrMom = getX("https://moments.mylykaapps.com/api/v3/moments/gethomemoments?os=android&pageIndex=1&pageSize=200",$mcDev, $mcCookie);
                                echo "→ Deleting moments";
                                $arrb2 = $postArrMom->data[0];
                                if($arrb2->mediaContents??null){
                                foreach($arrb2->mediaContents as $medme){
                                    $postIdx2 = $medme->id;
                                    $del2json = delMoment($mcDev,$mcCookie,$postIdx2);
                                    if($del2json->message){
                                        echo ".";
                                    }
                                }
                            }
                                echo "\n\n";
                            }

                        }
                        $uploadjson = 0;
                        if(in_array("2",$sopt)){
                        $uploadjson = uploader($mcDev,$mcCookie, $fuid ,$filesdata,$selUpl,in_array("4",$sopt));
                        echo "\n";
                        if($uploadjson > 0){ 
                            echo "\nUploaded $uploadjson Photos.. ";
                            echo "\n";
                                }
                        }
                        if(in_array("3",$sopt)){
                            uploaderMoment($mcDev,$mcCookie ,$fuid);
                            echo "\n";
                            
                        }
                        echo "\n";
                    }
                    echo "\n\n";
                    $tcountr++;
                }
            } 

                $uploadAlls = readline('→ ');

        } else {
            
            $conPro = "";
        }
} 
else if($postMe == 2){
    //Max rater. 
    $mrate = "                               __     
    __ _  ___ ___ __ _______ _/ /____ 
   /  ' \/ _ `/\ \ // __/ _ `/ __/ -_)
  /_/_/_/\_,_//_\_\/_/  \_,_/\__/\__/ 
                                      ";

    @system("clear");
    echo "$yllw";
    echo $mrate;                                        
    echo "\n$b----------------------------------------------\n\n$wht";
    echo "$yllw  1.$wht Max rate main\n";
    echo "$yllw  2.$wht Set main accounts\n";
    echo "$yllw  3.$wht Enter username\n";
    echo "$yllw  4.$wht Reset Dummy\n";
    echo "\n";

    echo "\n$green----------------------------------------------\n$wht";
    echo " main accounts:\n\n ",join(",",$accntX["mainAccounts"]);
    echo "\n$green----------------------------------------------\n\n$wht";
    echo "\n";
    $rateL = readline(' → ');

    if($rateL==2){
        @system("clear");
        echo "$yllw";
        echo $mrate;                                        
        echo "\n$b----------------------------------------------\n\n$wht";
        echo "$yllw  2.$wht Set main: use comma (,)\n\n";
        $changeMain = readline(' → ');
        if($changeMain!=""){
            $accntX["mainAccounts"]=[];
        foreach(explode(",",$changeMain) as $nacnt){
            $accntX["mainAccounts"][]=$nacnt;
        }
        $newaccntx = json_encode($accntX);
        file_put_contents('profile.json', $newaccntx);
    }
    } 
        //All accounts
    else if($rateL==1){
        @system("clear");
        $mainCnt = 10;
        $timestarted = date("h:i:sa");
        // foreach($accntdata as $rateAcc => $vals){
        //     allRater($rateAcc,$mainCnt); 
        //     $mainCnt++;
        // }
        foreach( $accntX["mainAccounts"] as $rme){
            allRater($rme,$mainCnt); 
            $mainCnt+=10;
        }
        $timesended = date("h:i:sa");
        echo "\n$wht---------------------------------$wht\n";
        echo "Time Started: ".$timestarted."\n";
        echo "\n$wht---------------------------------$wht\n";
        echo "Time Ended: ".$timesended."\n";

    } 
    else if($rateL==3){
        @system("clear");
        echo "$yllw";
        echo $mrate;                                        
        echo "\n$b----------------------------------------------\n\n$wht";
        // foreach($accntdata as $accnts => $vals) {
        //     $accntdata[$accnts]["main1"] = "no";
        //     $accntdata[$accnts]["main2"] = "no";
        //     $accntdata[$accnts]["main3"] = "no";
        //     $accntdata[$accnts]["main4"] = "no";
        // }
        // $newaccntdata = json_encode($accntdata);
        // file_put_contents('lyaccnts.json', $newaccntdata);
        // $cookyStr = file_get_contents('lyaccnts.json');
        // $accntdata = json_decode($cookyStr, true);

        echo "Rate username:\n";
        $rateMee = readline(' → ');
        if( $rateMee!="" && $accntdata[$rateMee]??null){
  
             @system("clear");
            allRater($rateMee); 
        } else{
            echo "Not found in your accounts.\nCant check wallet.\n\n\n";
            $conPro = readline("→  ");
        }
    }else if($rateL==4){
        @system("clear");
        echo "$yllw";
        echo $mrate;                                        
        echo "\n$b----------------------------------------------\n\n$wht";
        foreach($accntdata as $accnts => $vals) {
            $accntdata[$accnts]["main10"] = "no";
            $accntdata[$accnts]["main20"] = "no";
            $accntdata[$accnts]["main30"] = "no";
            $accntdata[$accnts]["main40"] = "no";
            for ($i=1; $i < 41; $i++) { 
                $accntdata[$accnts]["post".$i] = "";
            }
        }
        $newaccntdata = json_encode($accntdata);
        file_put_contents('lyaccnts.json', $newaccntdata);
        $cookyStr = file_get_contents('lyaccnts.json');
        $accntdata = json_decode($cookyStr, true);

        // echo "Rate username:\n";
        // $rateMee = readline(' → ');
        // if( $rateMee!="" && $accntdata[$rateMee]??null){
  
        //      @system("clear");
        //     allRater($rateMee); 
        // } else{
        //     echo "Not found in your accounts.\nCant check wallet.\n\n\n";
        //     $conPro = readline("→  ");
        // }
    }else if($rateL==5){
        @system("clear");
        echo "$yllw";
        echo $mrate;                                        
        echo "\n$b----------------------------------------------\n\n$wht";
        foreach($accntdata as $accnts => $vals) {
            for ($i=11; $i < 51; $i++) { 
                $accntdata[$accnts]["post".$i] = "";
            }
        }
        $newaccntdata = json_encode($accntdata);
        file_put_contents('lyaccnts.json', $newaccntdata);
        $cookyStr = file_get_contents('lyaccnts.json');
        $accntdata = json_decode($cookyStr, true);

        // echo "Rate username:\n";
        // $rateMee = readline(' → ');
        // if( $rateMee!="" && $accntdata[$rateMee]??null){
  
        //      @system("clear");
        //     allRater($rateMee); 
        // } else{
        //     echo "Not found in your accounts.\nCant check wallet.\n\n\n";
        //     $conPro = readline("→  ");
        // }
    }
    else {
        $conPro = "";
    }

   echo "\n";

}
else if($postMe == 4){
    @system("clear");
    echo "$wh";
    echo "     _                             
    /   _   _  |  o  _     / \ \ \ 
    \_ (_) (_) |< | (/_   |   \ \ |
                           \     / ";
    echo "\n\n";
    echo "$wht";

    echo "\n\nRefresh all?\n\n\n";
    echo "y $yllw=$wht All accounts will be relogged in.\n";
    echo "n $yllw=$wht only missing cookies..\n\n\n";
    $cookieAll = readline("→ ");


        if($cookieAll=="y"){
            foreach($accntdata as $accnt => $val) {
                $cpw = $val["password"];
                if($cpw !="") {
                    echo "Getting fresh cookies.. "."\n\n";
                    lykalogin($accnt,$val["password"]);
                    }
            }
        } else if($cookieAll=="n") {
                foreach($accntdata as $accnt => $val) {
                    $cooks = $val["cookie"];
                    $cpw = $val["password"];
                    if(!$cooks && $cpw !="") {
                    echo "\n\nRefreshing null cookies.. "."\n\n";
                    lykalogin($accnt,$val["password"]);
                    }
            } //end of cookie check
        }
        
        $newaccntdata = json_encode($accntdata);
        file_put_contents('lyaccnts.json', $newaccntdata);
        $conPro = readline(" →  ");
}
else if($postMe == 5 || $postMe == 3){
    @system("clear");
    echo "$lcyan";
    $gmss = "    ,---. .-,--. ,-,-,-.   .---.
    |  -'  `\__  `,| | |   \___ 
    |  ,-'  /      | ; | .     \
    `---|  '`--'   '   `-' `---'
    ,-.|                       
    `-+' ";
    echo $gmss;
    echo "\n\n---------------------------------$wht";

    if($postMe == 3){
        @system("clear");
        echo "$lcyan";
        echo $gmss;
        echo "$yllw\n\n---------------------------------$wht\n\n";
        $xDeal = readline("Transfer all Gems to username: ");

        echo "$lred\nExclude username(s) ";
        $excludeThh = readline("--→ ");

        $exc2 = explode(",", join(",",array($excludeThh,$xDeal)));
        
        if(!$accntdata[$xDeal]){
            echo "\n$lred$xDeal$wht not found in your accounts. ";
            echo "\nLogin the username then try again.\n\n\n";
            exit();
        } else {  
            $xdlDev = $accntdata[$xDeal]["devId"];
            $xdlCooky = $accntdata[$xDeal]["cookie"];
            $xgetUID = getX("https://profiles.mylykaapps.com/api/v3/profiles/GetUserProfileForEditing?os=android",$xdlDev,$xdlCooky);

            if($xgetUID->data){
                @system("clear");
                echo "$lcyan";
                echo $gmss;
                echo "$yllw\n\n---------------------------------\n$wht$xDeal\n\n";
                $fuidx = $xgetUID->data->id;
                echo "→ Transferring";
                foreach($accntdata as $accnts => $vals) {

                    $sdid = $vals["devId"];
                    $sntokn = $vals["noToken"];
                    $scooks = $vals["cookie"];
                   $notinArr = in_array($accnts,$exc2);
                   if(!$notinArr){
                        $tGem1 = getGems($sdid, $scooks);
                        if($tGem1>0){
                            $sendJsonF = postX("https://wallets.mylykaapps.com/api/v3/wallets/SendGemV2", payload($sdid,'"recipientId": '.$fuidx.',"amount": '.$tGem1, $sntokn),$scooks);
                            echo "$green.";
                           // echo $sendJsonF->message."\n";
                        } //if balance is more than 0
                   }
                }

                @system("clear");
                echo "$lcyan";
                echo $gmss;
                echo "$yllw\n\n---------------------------------\n$wht$xDeal\n\n";
                $itxGems = getGems($xdlDev, $xdlCooky); 
                echo "\nTransfer done!";
                echo "$wht\n\n\n$xDeal gems:$yllw ₱$itxGems";
                echo "\n\n$lred------------------------------\n\n";
          
            }
            }
        }



    //Check all account gems and only display those with Balance. 
    if($postMe != 3){
    echo "\nDisplay all balance? (y_n)$green\n";
    $showAll = readline(":: ");
    @system("clear");
    echo "$lcyan";
    echo $gmss;
    echo "$yllw\n\n---------------------------------$wht\n\n";
    echo $showAll!="y"? "Computing gems " : null;
    $gmmm = 0;
    
    foreach($accntdata as $accnts => $vals) {

        if($vals["cookie"]??null){
            $mcCookie = $vals["cookie"];
            $mcDev = $vals["devId"];
            $itGems = getGems($mcDev, $mcCookie); 
            if($showAll=="y"){
                echo $itGems ? "$wht\n"."$accnts --$lcyan ⬘ $itGems ": null;
            } else{
               echo ""; 
            }
            $gmmm += $itGems;
        } // End of gemChecker  
    }

    echo "$wht\n\n\nTotal gems:$yllw ₱$gmmm";
    echo "\n\n$lred----------------------\n\n";
    }
    $conPro = readline("→  ");
} 
else if($postMe == "x"){
    @system("clear");
    echo "$green\n\n\n$wht";
    echo "$green\n\n-------------------------------\n\n$wht";
    echo "   App Version$lred →$wht  3.6.36\n";
    echo "   User-agent$lred  →$wht  Lyka/3.6.36 (com.thingsilikeapp; build:836 Android R 30)\n";
    echo "$lcyan\n\n-------------------------------\n\n$wht";
    echo "   Author$lred →$wht  Elytra\n";
    echo "   Telegram$lred →$wht  https://t.me/Elytra06\n";
    echo "$yllw\n\n-------------------------------\n\n$wht";

    $conPro = readline("→  ");
} 
else if($postMe == 6){
    @system("clear");
    echo "$yllw$lykM";
    echo "\n\n$lred\n ©e$wht"."LYK$lred"."tr$wht"."A$wh v1.3";
    echo "\n$b----------------------------------------------\n";
    echo "$wh\n1.$wht Login";
    echo "$wh\n2.$wht Logout \n\n\n";

    echo "Whenever you're ready.\n";
    $nPosts = readline("→  ");

    if($nPosts==1){

    $conPros = "y";
    while($conPros=="y"){
    @system("clear");
    echo "$yllw$lykM";
    echo "\n\n\n$wht → Lyka Login";
    echo "\n$b----------------------------------------------\n\n\n$wht";
    $nPosts = readline("username →  ");
    $nPassw = readline("password →  ");

    if($nPosts!="" && $nPassw!=""){
        lykalogin($nPosts,$nPassw);
    } 
    echo "\n\n$yllw";
    $conPros = readline("y ? →  ");
    }
    } //end ofLogin 
    else if($nPosts==2){
    $conPros = "y";
    while($conPros=="y"){
        @system("clear");
        echo "$yllw$lykM";
        echo "\n\n\n\n$wht → Lyka Logout";
        echo "\n$b----------------------------------------------\n\n\n$wht";
        $nlogout = readline("username →  ");
        $nlogout? lykalogout($nlogout):null;
        echo "\n\n$yllw";
        $conPros = readline("y ? →  ");
    }
    }
} 
else if($postMe == 7){
    //Set storage
    //default is lyaccnt.json
    @system("clear");
    echo "$green$lykM";
    $totalGms = 0;
    echo "\n\n$lred\n ©e$wht"."LYK$lred"."tr$wht"."A$wh v1.3";
    echo "\n$b----------------------------------------------\n\n$wht";
    $iniStores = file_get_contents($accntX['accountStorage']);
    $iniStoresData = json_decode($cookyStr,true);
    echo "Storage: ".$green.$accntX['accountStorage']." ";
    echo "$wht\nActive accounts: $yllw".iniStorage($iniStoresData);
    echo "$wht\nTotal Gems: $green".$totalGms;
    echo "$wht\nWallet not active: $notActivWall";
    echo "\n\n$lcyan----------------------------------------------\n\n\n$wht";  

    if($walletErr>0){
        echo "Wallets are activated automatically,\nunless there's an error below..\n\n";
        echo "";
    } 

    if($walletErr!=""){
        echo $lred."wallet log: \n$wht".$walletErr."\n\n";
    }

    $notActivWall = 0;
    $totalGms = 0;
    $walletErr="";

    $conPro = readline("→  ");
} 
else if($postMe == 8){
    //Set storage
    //default is lyaccnt.json
    @system("clear");
    echo "$green$lykM";
    echo "\n\n$lred\n ©e$wht"."LYK$lred"."tr$wht"."A$wh v1.3";
    echo "\n$b----------------------------------------------\n\n$wht";

    echo "$wht 1. Set JSON account storage"."\n\n\n";

    $conPro = readline("→  ");
} 




if($conPro=="x"){
    exit();
} 
}


?>