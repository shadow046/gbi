<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Sheets\DataExports;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\FormField;
use App\Models\Form;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Response;
use DB;
use DateTime;
use Illuminate\Support\Arr;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ExportData(Request $request, $year, $month, $monthname) 
    {
        return Excel::download(new DataExports($year,$month,$monthname), $monthname.' - '.$year.'.xlsx');
    }
    public function createuser(){
        
        $user = User::create([
            'name' => 'Jerome',
            'email'=> 'emorej046@gmail.com',
            'password'=> Hash::make('123456')
        ]);
        return $user;
    }
    public function createuserlyka()
    {
        $fullnamex = $this->randomName();
        $username = $this->random_username($fullnamex);
        $devid = dechex(mt_rand()).dechex(mt_rand());
        return view('createuser', compact('devid', 'username'));
    }
    public function randomName() {
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
    public function notkens(){
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
    public function random_username($string) {
        $pattern = " ";
        $firstPart = strstr(strtolower($string), $pattern, true);
        $secondPart = substr(strstr(strtolower($string), $pattern, false), 0,3);
        $nrRand = rand(0, 100);
        
        $username = trim($firstPart).trim($secondPart).trim($nrRand);
        return $username;
    }
        
    public function sendotp(Request $request)
    {
        $DevIdx = $request->devid;
        $phoneNumberx = $request->pn;
        $passwordx = 'salamat';
        $sendOTP =$request->otpid;
        $xOTP = $request->otp;
        $notificationTokenx = $this->notkens();
        $min = strtotime("47 years ago");
        $max = strtotime("18 years ago");
        $rand_time = mt_rand($min, $max);
        $fullnamex = $this->randomName();
        $usernamex = $request->un;
        $birthDatex = date('m/d/Y', $rand_time);
        $genderx ="male";
        $validateOTP = $this->postX("https://settings.mylykaapps.com/api/v3/otpservices/ValidateOTPV2", $this->payload($DevIdx,'"reference":"phone","requestId":"'.$sendOTP.'","type":"register","value":"'.$phoneNumberx.'",'."code".":".'"'.$xOTP.'"'));
        // return $xOTP.'---'.$sendOTP.'---'.$validateOTP->message.'--'.$phoneNumberx.'---'.$validateOTP->data->signedToken;
        if( $validateOTP->data) {
            $signedTokenx = $validateOTP->data->signedToken;
            $otpMess = $validateOTP->message;
            if($otpMess == "Invalid passcode."){
                return response($validateOTP->message);
            }else{
                $codex = $xOTP;
                if($signedTokenx!=""){
                    $registerPass = $this->postX("https://identity.mylykaapps.com/useraccounts/RegisterV3", $this->payload($DevIdx,'"birthDate":"'.$birthDatex.'","code":"'.$codex.'","countryCode":"PH","fullname":"'.$fullnamex.'","gender":"'.$genderx.'","isMerchant":false,"password":"'.$passwordx.'","phoneNumber":"'.$phoneNumberx.'","signedToken":"'.$signedTokenx.'","type":"phone","username":"'.$usernamex.'"')) ;
                    if ($registerPass->message == 'Too many requests, try again later') {
                        return response($registerPass->message);
                    }else{

                    }
                }
            }
        } else {
            $otpMess = "invalid OTP";
            return response($otpMess);
        }
        // $validURL = 'https://settings.mylykaapps.com/api/v3/otpservices/GenerateOTPV2';
        // $validHeader = array(
        //     "Content-Type: application/json; charset=UTF-8",
        //     "user-agent: Lyka/3.6.29 (com.thingsilikeapp; build:829 Android R 30)"
        // );
        // $payloader= array(
        //     "device"=>array(
        //         "deviceId"=>$request->devid,
        //         "deviceImei"=>"",
        //         "deviceModel"=>"unknown unknown",
        //         "deviceName"=>"android",
        //         "deviceOs"=>"Android R ",
        //         "isEmulator"=>false,
        //         "osVersion"=>"30",
        //         "notificationToken"=>$request->notifid
        //     ),

        //     "reference"=>"phone",
        //     "requestId"=>$request->otpid,
        //     "type"=>"register",
        //     "value"=>$request->pn,
        //     "code"=>$request->otp
        // );
        // $validCurl = curl_init($validURL);
        // curl_setopt($validCurl, CURLOPT_URL, $validURL);
        // curl_setopt($validCurl, CURLOPT_POST, true);
        // curl_setopt($validCurl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($validCurl, CURLOPT_HTTPHEADER, $validHeader);
        // curl_setopt($validCurl, CURLOPT_POSTFIELDS, json_encode($payloader));
        // $validResp = curl_exec($validCurl);
        // curl_close($validCurl);
        // $valjson = json_decode($validResp);
        // return response()->json($valjson->data);
    }

    function postX($urlx, $payloader){
        $uAgent = "Lyka/3.6.29 (com.thingsilikeapp; build:829 Android R 30)";
        $validURL = $urlx;
        $validHeader = 
        array(
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
    }

    public function payload($devIDx, $xtraPay, $rTokenx = ""){
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
    }
    public function check(Request $request)
    {
        $DevIdx = $request->devid;
        $usernamex = $request->un;
        $phoneNumberx = $request->pn;
        $notif = $request->notif;

        $vaidateUN = $this->postX("https://identity.mylykaapps.com/useraccounts/validateusername", $this->payload("$DevIdx",'"country": "PH","isMerchant": false,"username": "'.$usernamex.'"')) ->message;
        $validateNumber = $this->postX("https://identity.mylykaapps.com/useraccounts/validatephonenumber", $this->payload($DevIdx,'"country": "PH","isMerchant": false,"phoneNumber":"'.$phoneNumberx.'"')) ->message;
        
        if(strpos($vaidateUN,'does not') && strpos($validateNumber,'does not')){
            $sendOTP = $this->postX("https://settings.mylykaapps.com/api/v3/otpservices/GenerateOTPV2", $this->payload($DevIdx,'"reference":"phone","type":"register","value":"'.$phoneNumberx.'"')) ->data->requestId;
            return response($sendOTP);
        }else if (!strpos($vaidateUN,'does not')) {
            return response($vaidateUN);
        }else{
            return response($validateNumber);
        }
        // $validURL = 'https://identity.mylykaapps.com/useraccounts/validatephonenumber';
        // $validHeader = array(
        //     "Content-Type: application/json; charset=UTF-8",
        //     "user-agent: Lyka/3.6.29 (com.thingsilikeapp; build:829 Android R 30)"
        // );
        // $payloader= array(
        //     "device"=>array(
        //         "deviceId"=>$request->devid,
        //         "deviceImei"=>"",
        //         "deviceModel"=>"unknown unknown",
        //         "deviceName"=>"android",
        //         "deviceOs"=>"Android R ",
        //         "isEmulator"=>false,
        //         "osVersion"=>"30",
        //         "notificationToken"=>$request->notifid
        //     ),
        //     "country"=>"PH",
        //     "isMerchant"=>false,
        //     "phoneNumber"=>$request->pn
        // );
        // $validCurl = curl_init($validURL);
        // curl_setopt($validCurl, CURLOPT_URL, $validURL);
        // curl_setopt($validCurl, CURLOPT_POST, true);
        // curl_setopt($validCurl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($validCurl, CURLOPT_HTTPHEADER, $validHeader);
        // curl_setopt($validCurl, CURLOPT_POSTFIELDS, json_encode($payloader));
        // $validResp = curl_exec($validCurl);
        // curl_close($validCurl);
        // $valjson = json_decode($validResp);
        // if (!strpos($valjson->message,'does not')) {
        //     return response()->json($validResp);
        // }

        // $validURL = 'https://identity.mylykaapps.com/useraccounts/validateusername';
        // $validHeader = array(
        //     "Content-Type: application/json; charset=UTF-8",
        //     "user-agent: Lyka/3.6.29 (com.thingsilikeapp; build:829 Android R 30)"
        // );
        // $payloader= array(
        //     "device"=>array(
        //         "deviceId"=>$request->devid,
        //         "deviceImei"=>"",
        //         "deviceModel"=>"unknown unknown",
        //         "deviceName"=>"android",
        //         "deviceOs"=>"Android R ",
        //         "isEmulator"=>false,
        //         "osVersion"=>"30",
        //         "notificationToken"=>$request->notifid
        //     ),
        //     "country"=>"PH",
        //     "isMerchant"=>false,
        //     "username"=>$request->un
        // );
        // $validCurl = curl_init($validURL);
        // curl_setopt($validCurl, CURLOPT_URL, $validURL);
        // curl_setopt($validCurl, CURLOPT_POST, true);
        // curl_setopt($validCurl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($validCurl, CURLOPT_HTTPHEADER, $validHeader);
        // curl_setopt($validCurl, CURLOPT_POSTFIELDS, json_encode($payloader));
        // $validResp = curl_exec($validCurl);
        // curl_close($validCurl);
        // $valjson = json_decode($validResp);
        // if (!strpos($valjson->message,'does not')) {
        //     return response()->json($validResp);
        // }
        // $validURL = 'https://settings.mylykaapps.com/api/v3/otpservices/GenerateOTPV2';
        // $validHeader = array(
        //     "Content-Type: application/json; charset=UTF-8",
        //     "user-agent: Lyka/3.6.29 (com.thingsilikeapp; build:829 Android R 30)"
        // );
        // $payloader= array(
        //     "device"=>array(
        //         "deviceId"=>$request->devid,
        //         "deviceImei"=>"",
        //         "deviceModel"=>"unknown unknown",
        //         "deviceName"=>"android",
        //         "deviceOs"=>"Android R ",
        //         "isEmulator"=>false,
        //         "osVersion"=>"30",
        //         "notificationToken"=>$request->notifid
        //     ),
        //     "reference"=>"phone",
        //     "type"=>"register",
        //     "value"=>$request->pn
        // );
        // $validCurl = curl_init($validURL);
        // curl_setopt($validCurl, CURLOPT_URL, $validURL);
        // curl_setopt($validCurl, CURLOPT_POST, true);
        // curl_setopt($validCurl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($validCurl, CURLOPT_HTTPHEADER, $validHeader);
        // curl_setopt($validCurl, CURLOPT_POSTFIELDS, json_encode($payloader));
        // $validResp = curl_exec($validCurl);
        // curl_close($validCurl);
        // $valjson = json_decode($validResp);
        // return response()->json($valjson->data);
    }

    public function closed(Request $request)
        {$TopIssue = FormField::query()->select(
            DB::raw(
                'SUM(CASE WHEN value = \'avr\' THEN 1 ELSE 0 END) as avr'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'AX Issue\' THEN 1 ELSE 0 END) as AXIssue'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Back Office\' THEN 1 ELSE 0 END) as BackOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Biometrics\' THEN 1 ELSE 0 END) as Biometrics'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Browser\' THEN 1 ELSE 0 END) as Browser'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cabling\' THEN 1 ELSE 0 END) as Cabling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cash Drawer\' THEN 1 ELSE 0 END) as CashDrawer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CBB\' THEN 1 ELSE 0 END) as CBB'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CCTV\' THEN 1 ELSE 0 END) as CCTV'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Desktop\' THEN 1 ELSE 0 END) as Desktop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Dismantling / Re-Installation\' THEN 1 ELSE 0 END) as Dismantling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EIMS\' THEN 1 ELSE 0 END) as EIMS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Email\' THEN 1 ELSE 0 END) as Email'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EOD\' THEN 1 ELSE 0 END) as EOD'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'E-Sales\' THEN 1 ELSE 0 END) as ESales'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-MPC\' THEN 1 ELSE 0 END) as HWMPC'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-PC/POS\' THEN 1 ELSE 0 END) as HWPCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-POS\' THEN 1 ELSE 0 END) as HWPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Printer\' THEN 1 ELSE 0 END) as HWPrinter'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Server\' THEN 1 ELSE 0 END) as HWServer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Inquiry\' THEN 1 ELSE 0 END) as Inquiry'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Installation\' THEN 1 ELSE 0 END) as Installation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Internet\' THEN 1 ELSE 0 END) as Internet'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Laptop\' THEN 1 ELSE 0 END) as Laptop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Microsoft 365\' THEN 1 ELSE 0 END) as Microsoft365'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Modem\' THEN 1 ELSE 0 END) as Modem'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'MS Office\' THEN 1 ELSE 0 END) as MSOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'My HR\' THEN 1 ELSE 0 END) as MyHR'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Others\' THEN 1 ELSE 0 END) as Others'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'PC/POS\' THEN 1 ELSE 0 END) as PCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS\' THEN 1 ELSE 0 END) as POS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS Application\' THEN 1 ELSE 0 END) as POSApplication'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Price Change\' THEN 1 ELSE 0 END) as PriceChange'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Printer\' THEN 1 ELSE 0 END) as Printer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Relocation\' THEN 1 ELSE 0 END) as Relocation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Reset Password\' THEN 1 ELSE 0 END) as ResetPassword'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Router\' THEN 1 ELSE 0 END) as Router'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Sales Discrepancy\' THEN 1 ELSE 0 END) as SalesDiscrepancy'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'UPS\' THEN 1 ELSE 0 END) as UPS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'VPN\' THEN 1 ELSE 0 END) as VPN'
            )
        )
        ->where('FieldId', 'GBISubCategory')
        ->get();

        $top = [
            'AVR'=>$TopIssue[0]->avr,
            'AX Issue'=>$TopIssue[0]->Axissue,
            'Back Office'=>$TopIssue[0]->Backoffice,
            'Biometrics'=>$TopIssue[0]->Biometrics,
            'Browser'=>$TopIssue[0]->Browser,
            'Cabling'=>$TopIssue[0]->Cabling,
            'Cash Drawer'=>$TopIssue[0]->CashDrawer,
            'CBB'=>$TopIssue[0]->CBB,
            'Cctv'=>$TopIssue[0]->CCTV,
            'Desktop'=>$TopIssue[0]->Desktop,
            'Dismantling / Re-Installation'=>$TopIssue[0]->Dismantling,
            'EIMS'=>$TopIssue[0]->EIMS,
            'Email'=>$TopIssue[0]->Email,
            'EOD'=>$TopIssue[0]->EOD,
            'E-Sales'=>$TopIssue[0]->ESales,
            'HW-MPC'=>$TopIssue[0]->HWMPC,
            'HW-PC/POS'=>$TopIssue[0]->HWPCPOS,
            'HW-POS"'=>$TopIssue[0]->HWPOS,
            'HW-Printer'=>$TopIssue[0]->HWPrinter,
            'HW-Server'=>$TopIssue[0]->HWServer,
            'Inquiry'=>$TopIssue[0]->Inquiry,
            'Installation'=>$TopIssue[0]->Installation,
            'Internet'=>$TopIssue[0]->Internet,
            'Laptop'=>$TopIssue[0]->Laptop,
            'Microsoft 365'=>$TopIssue[0]->Microsoft365,
            'Modem'=>$TopIssue[0]->Modem,
            'MS Office'=>$TopIssue[0]->MSOffice,
            'My HR'=>$TopIssue[0]->MyHR,
            'Others'=>$TopIssue[0]->Others,
            'PC/POS'=>$TopIssue[0]->PCPOS,
            'POS'=>$TopIssue[0]->POS,
            'POS Application'=>$TopIssue[0]->POSApplication,
            'Price Change'=>$TopIssue[0]->PriceChange,
            'Printer'=>$TopIssue[0]->Printer,
            'Relocation'=>$TopIssue[0]->Relocation,
            'Reset Password'=>$TopIssue[0]->ResetPassword,
            'Router'=>$TopIssue[0]->Router,
            'Sales Discrepancy'=>$TopIssue[0]->SalesDiscrepancy,
            'UPS'=>$TopIssue[0]->UPS,
            'VPN'=>$TopIssue[0]->VPN
        ];

    $filtered = array_filter($top);
    arsort($filtered);
    //

    //resolver group

    $resolvergroup = FormField::query()->select(
        DB::raw(
            'SUM(CASE WHEN value = \'avr\' THEN 1 ELSE 0 END) as avr'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'AX Issue\' THEN 1 ELSE 0 END) as AXIssue'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Back Office\' THEN 1 ELSE 0 END) as BackOffice'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Biometrics\' THEN 1 ELSE 0 END) as Biometrics'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Browser\' THEN 1 ELSE 0 END) as Browser'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Cabling\' THEN 1 ELSE 0 END) as Cabling'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Cash Drawer\' THEN 1 ELSE 0 END) as CashDrawer'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'CBB\' THEN 1 ELSE 0 END) as CBB'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'CCTV\' THEN 1 ELSE 0 END) as CCTV'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Desktop\' THEN 1 ELSE 0 END) as Desktop'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Dismantling / Re-Installation\' THEN 1 ELSE 0 END) as Dismantling'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'EIMS\' THEN 1 ELSE 0 END) as EIMS'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Email\' THEN 1 ELSE 0 END) as Email'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'EOD\' THEN 1 ELSE 0 END) as EOD'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'E-Sales\' THEN 1 ELSE 0 END) as ESales'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'HW-MPC\' THEN 1 ELSE 0 END) as HWMPC'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'HW-PC/POS\' THEN 1 ELSE 0 END) as HWPCPOS'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'HW-POS\' THEN 1 ELSE 0 END) as HWPOS'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'HW-Printer\' THEN 1 ELSE 0 END) as HWPrinter'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'HW-Server\' THEN 1 ELSE 0 END) as HWServer'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Inquiry\' THEN 1 ELSE 0 END) as Inquiry'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Installation\' THEN 1 ELSE 0 END) as Installation'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Internet\' THEN 1 ELSE 0 END) as Internet'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Laptop\' THEN 1 ELSE 0 END) as Laptop'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Microsoft 365\' THEN 1 ELSE 0 END) as Microsoft365'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Modem\' THEN 1 ELSE 0 END) as Modem'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'MS Office\' THEN 1 ELSE 0 END) as MSOffice'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'My HR\' THEN 1 ELSE 0 END) as MyHR'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Others\' THEN 1 ELSE 0 END) as Others'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'PC/POS\' THEN 1 ELSE 0 END) as PCPOS'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'POS\' THEN 1 ELSE 0 END) as POS'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'POS Application\' THEN 1 ELSE 0 END) as POSApplication'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Price Change\' THEN 1 ELSE 0 END) as PriceChange'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Printer\' THEN 1 ELSE 0 END) as Printer'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Relocation\' THEN 1 ELSE 0 END) as Relocation'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Reset Password\' THEN 1 ELSE 0 END) as ResetPassword'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Router\' THEN 1 ELSE 0 END) as Router'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'Sales Discrepancy\' THEN 1 ELSE 0 END) as SalesDiscrepancy'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'UPS\' THEN 1 ELSE 0 END) as UPS'
        ),
        DB::raw(
            'SUM(CASE WHEN value = \'VPN\' THEN 1 ELSE 0 END) as VPN'
        )
    )
    ->where('FieldId', 'GBIResolverGroup')
    ->get();
    //

    //aging

    $lessthan5 = Task::query()->select('TaskNumber')
        ->where('TaskNumber', 'LIKE', 'GBI%')
        ->join('form', 'taskid', 'task.id')
        ->join('formfield', 'formid', 'form.id')
        // ->whereNotIN('value', ['Closed', 'Resolved'])
        ->whereNotIN('Value', ['Resolved','Closed'])
        ->where('FieldId', 'GBIIncidentStatus')
        ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
        ->whereDate('DateCreated', '<=', Carbon::now())
        ->count();
    $sixto10 = Task::query()->select('TaskNumber')
        ->where('TaskNumber', 'LIKE', 'GBI%')
        ->join('form', 'taskid', 'task.id')
        ->join('formfield', 'formid', 'form.id')
        // ->whereNotIN('value', ['Closed', 'Resolved'])
        ->whereNotIN('Value', ['Resolved','Closed'])
        // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
        ->where('FieldId', 'GBIIncidentStatus')
        ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
        ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
        ->count();
    $elevento15 = Task::query()->select('TaskNumber')
        ->where('TaskNumber', 'LIKE', 'GBI%')
        ->join('form', 'taskid', 'task.id')
        ->join('formfield', 'formid', 'form.id')
        // ->whereNotIN('value', ['Closed', 'Resolved'])
        ->whereNotIN('Value', ['Resolved','Closed'])
        // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
        ->where('FieldId', 'GBIIncidentStatus')
        ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
        ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
        ->count();
    $sixteento20 = Task::query()->select('TaskNumber')
        ->where('TaskNumber', 'LIKE', 'GBI%')
        ->join('form', 'taskid', 'task.id')
        ->join('formfield', 'formid', 'form.id')
        ->whereNotIN('Value', ['Resolved','Closed'])
        // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
        // ->whereNotIN('value', ['Closed', 'Resolved'])
        ->where('FieldId', 'GBIIncidentStatus')
        ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
        ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
        ->count();
    $greaterthan20 = Task::query()->select('TaskNumber')
        ->where('TaskNumber', 'LIKE', 'GBI%')
        ->join('form', 'taskid', 'task.id')
        ->join('formfield', 'formid', 'form.id')
        ->whereNotIN('Value', ['Resolved','Closed'])
        // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
        // ->whereNotIN('value', ['Closed', 'Resolved'])
        ->where('FieldId', 'GBIIncidentStatus')
        ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
        ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
        ->count();

    // $lessthanfive = collect($lessthanfive);
    // foreach ($lessthanfive as $key) {
    //     $date = explode(' ',$key->DateCreated);
    //     // return $date[0];
    //     return Carbon::parse($date[0]);
    //     return (Carbon::parse($date[0]) < Carbon::parse(Carbon::now()));
    // }

    //
    return view('closedtickets', compact('filtered', 'lessthan5', 'sixto10', 'elevento15', 'sixteento20','greaterthan20'));
    // 20210628-50585
    }

    public function index()
    {
        // return view('home');
        // return DB::table('TaskAuditLog')->select('*')
        //     ->where('taskid', '1187251')
        //     ->get();
            // return array_filter(FormField::query()->select('value')
            // ->where('FieldId', 'GBIResolverGroup')
            // ->groupby('value')
            // ->get()->all());
            // try {
            //     DB::connection()
            //         ->getPdo();
            // } catch (Exception $e) {
            //     // abort($e instanceof PDOException ? 503 : 500);
            //     // return "Connection to database failed"
            //     return abort(403, 'There was a problem connecting to the server. Please try again later.');
            // }
            // return view('gbi');
            $TopIssue = FormField::query()->select(
                DB::raw(
                    'SUM(CASE WHEN value = \'avr\' THEN 1 ELSE 0 END) as avr'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'AX Issue\' THEN 1 ELSE 0 END) as AXIssue'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Back Office\' THEN 1 ELSE 0 END) as BackOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Biometrics\' THEN 1 ELSE 0 END) as Biometrics'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Browser\' THEN 1 ELSE 0 END) as Browser'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Cabling\' THEN 1 ELSE 0 END) as Cabling'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Cash Drawer\' THEN 1 ELSE 0 END) as CashDrawer'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'CBB\' THEN 1 ELSE 0 END) as CBB'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'CCTV\' THEN 1 ELSE 0 END) as CCTV'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Desktop\' THEN 1 ELSE 0 END) as Desktop'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Dismantling / Re-Installation\' THEN 1 ELSE 0 END) as Dismantling'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'EIMS\' THEN 1 ELSE 0 END) as EIMS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Email\' THEN 1 ELSE 0 END) as Email'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'EOD\' THEN 1 ELSE 0 END) as EOD'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'E-Sales\' THEN 1 ELSE 0 END) as ESales'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-MPC\' THEN 1 ELSE 0 END) as HWMPC'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-PC/POS\' THEN 1 ELSE 0 END) as HWPCPOS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-POS\' THEN 1 ELSE 0 END) as HWPOS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-Printer\' THEN 1 ELSE 0 END) as HWPrinter'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'HW-Server\' THEN 1 ELSE 0 END) as HWServer'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Inquiry\' THEN 1 ELSE 0 END) as Inquiry'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Installation\' THEN 1 ELSE 0 END) as Installation'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Internet\' THEN 1 ELSE 0 END) as Internet'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Laptop\' THEN 1 ELSE 0 END) as Laptop'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Microsoft 365\' THEN 1 ELSE 0 END) as Microsoft365'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Modem\' THEN 1 ELSE 0 END) as Modem'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'MS Office\' THEN 1 ELSE 0 END) as MSOffice'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'My HR\' THEN 1 ELSE 0 END) as MyHR'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Others\' THEN 1 ELSE 0 END) as Others'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'PC/POS\' THEN 1 ELSE 0 END) as PCPOS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'POS\' THEN 1 ELSE 0 END) as POS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'POS Application\' THEN 1 ELSE 0 END) as POSApplication'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Price Change\' THEN 1 ELSE 0 END) as PriceChange'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Printer\' THEN 1 ELSE 0 END) as Printer'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Relocation\' THEN 1 ELSE 0 END) as Relocation'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Reset Password\' THEN 1 ELSE 0 END) as ResetPassword'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Router\' THEN 1 ELSE 0 END) as Router'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Sales Discrepancy\' THEN 1 ELSE 0 END) as SalesDiscrepancy'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'UPS\' THEN 1 ELSE 0 END) as UPS'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'VPN\' THEN 1 ELSE 0 END) as VPN'
                ),
                DB::raw(
                    'SUM(CASE WHEN value = \'Linksys\' THEN 1 ELSE 0 END) as Linksys'
                )
            )
            ->where('FieldId', 'GBISubCategory')
            ->get();
        
            $top = [
                'AVR'=>$TopIssue[0]->avr,
                'AX Issue'=>$TopIssue[0]->Axissue,
                'Back Office'=>$TopIssue[0]->Backoffice,
                'Biometrics'=>$TopIssue[0]->Biometrics,
                'Browser'=>$TopIssue[0]->Browser,
                'Cabling'=>$TopIssue[0]->Cabling,
                'Cash Drawer'=>$TopIssue[0]->CashDrawer,
                'CBB'=>$TopIssue[0]->CBB,
                'Cctv'=>$TopIssue[0]->CCTV,
                'Desktop'=>$TopIssue[0]->Desktop,
                'Dismantling / Re-Installation'=>$TopIssue[0]->Dismantling,
                'EIMS'=>$TopIssue[0]->EIMS,
                'Email'=>$TopIssue[0]->Email,
                'EOD'=>$TopIssue[0]->EOD,
                'E-Sales'=>$TopIssue[0]->ESales,
                'HW-MPC'=>$TopIssue[0]->HWMPC,
                'HW-PC/POS'=>$TopIssue[0]->HWPCPOS,
                'HW-POS"'=>$TopIssue[0]->HWPOS,
                'HW-Printer'=>$TopIssue[0]->HWPrinter,
                'HW-Server'=>$TopIssue[0]->HWServer,
                'Inquiry'=>$TopIssue[0]->Inquiry,
                'Installation'=>$TopIssue[0]->Installation,
                'Internet'=>$TopIssue[0]->Internet,
                'Laptop'=>$TopIssue[0]->Laptop,
                'Linksys'=>$TopIssue[0]->Linksys,
                'Microsoft 365'=>$TopIssue[0]->Microsoft365,
                'Modem'=>$TopIssue[0]->Modem,
                'MS Office'=>$TopIssue[0]->MSOffice,
                'My HR'=>$TopIssue[0]->MyHR,
                'Others'=>$TopIssue[0]->Others,
                'PC/POS'=>$TopIssue[0]->PCPOS,
                'POS'=>$TopIssue[0]->POS,
                'POS Application'=>$TopIssue[0]->POSApplication,
                'Price Change'=>$TopIssue[0]->PriceChange,
                'Printer'=>$TopIssue[0]->Printer,
                'Relocation'=>$TopIssue[0]->Relocation,
                'Reset Password'=>$TopIssue[0]->ResetPassword,
                'Router'=>$TopIssue[0]->Router,
                'Sales Discrepancy'=>$TopIssue[0]->SalesDiscrepancy,
                'UPS'=>$TopIssue[0]->UPS,
                'VPN'=>$TopIssue[0]->VPN
            ];
        
        $filtered = array_filter($top);
        arsort($filtered);
        //
        
        //resolver group

        $resolvergroup = FormField::query()->select(
            DB::raw(
                'SUM(CASE WHEN value = \'avr\' THEN 1 ELSE 0 END) as avr'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'AX Issue\' THEN 1 ELSE 0 END) as AXIssue'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Back Office\' THEN 1 ELSE 0 END) as BackOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Biometrics\' THEN 1 ELSE 0 END) as Biometrics'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Browser\' THEN 1 ELSE 0 END) as Browser'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cabling\' THEN 1 ELSE 0 END) as Cabling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cash Drawer\' THEN 1 ELSE 0 END) as CashDrawer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CBB\' THEN 1 ELSE 0 END) as CBB'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CCTV\' THEN 1 ELSE 0 END) as CCTV'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Desktop\' THEN 1 ELSE 0 END) as Desktop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Dismantling / Re-Installation\' THEN 1 ELSE 0 END) as Dismantling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EIMS\' THEN 1 ELSE 0 END) as EIMS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Email\' THEN 1 ELSE 0 END) as Email'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EOD\' THEN 1 ELSE 0 END) as EOD'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'E-Sales\' THEN 1 ELSE 0 END) as ESales'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-MPC\' THEN 1 ELSE 0 END) as HWMPC'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-PC/POS\' THEN 1 ELSE 0 END) as HWPCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-POS\' THEN 1 ELSE 0 END) as HWPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Printer\' THEN 1 ELSE 0 END) as HWPrinter'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Server\' THEN 1 ELSE 0 END) as HWServer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Inquiry\' THEN 1 ELSE 0 END) as Inquiry'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Installation\' THEN 1 ELSE 0 END) as Installation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Internet\' THEN 1 ELSE 0 END) as Internet'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Laptop\' THEN 1 ELSE 0 END) as Laptop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Microsoft 365\' THEN 1 ELSE 0 END) as Microsoft365'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Modem\' THEN 1 ELSE 0 END) as Modem'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'MS Office\' THEN 1 ELSE 0 END) as MSOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'My HR\' THEN 1 ELSE 0 END) as MyHR'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Others\' THEN 1 ELSE 0 END) as Others'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'PC/POS\' THEN 1 ELSE 0 END) as PCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS\' THEN 1 ELSE 0 END) as POS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS Application\' THEN 1 ELSE 0 END) as POSApplication'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Price Change\' THEN 1 ELSE 0 END) as PriceChange'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Printer\' THEN 1 ELSE 0 END) as Printer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Relocation\' THEN 1 ELSE 0 END) as Relocation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Reset Password\' THEN 1 ELSE 0 END) as ResetPassword'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Router\' THEN 1 ELSE 0 END) as Router'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Sales Discrepancy\' THEN 1 ELSE 0 END) as SalesDiscrepancy'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'UPS\' THEN 1 ELSE 0 END) as UPS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'VPN\' THEN 1 ELSE 0 END) as VPN'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Linksys\' THEN 1 ELSE 0 END) as Linksys'
            )
        )
        ->where('FieldId', 'GBIResolverGroup')
        ->get();
        //

        //aging

        $lessthan5 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->whereNotIN('Value', ['Resolved','Closed'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(5))
            ->whereDate('DateCreated', '<=', Carbon::now())
            ->count();
        $sixto10 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->whereNotIN('Value', ['Resolved','Closed'])
            // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(10))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(6))
            ->count();
        $elevento15 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->whereNotIN('Value', ['Resolved','Closed'])
            // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(15))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(11))
            ->count();
        $sixteento20 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->whereNotIN('Value', ['Resolved','Closed'])
            // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(20))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(16))
            ->count();
        $greaterthan20 = Task::query()->select('TaskNumber')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->whereNotIN('Value', ['Resolved','Closed'])
            // ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            // ->whereNotIN('value', ['Closed', 'Resolved'])
            ->where('FieldId', 'GBIIncidentStatus')
            ->whereDate('DateCreated', '>=', Carbon::now()->subDays(399))
            ->whereDate('DateCreated', '<=', Carbon::now()->subDays(21))
            ->count();
        
        // $lessthanfive = collect($lessthanfive);
        // foreach ($lessthanfive as $key) {
        //     $date = explode(' ',$key->DateCreated);
        //     // return $date[0];
        //     return Carbon::parse($date[0]);
        //     return (Carbon::parse($date[0]) < Carbon::parse(Carbon::now()));
        // }

        //
        return view('opentickets', compact('filtered', 'lessthan5', 'sixto10', 'elevento15', 'sixteento20','greaterthan20'));
        // 20210628-50585
        
    }

    public function closedtickets(){
        $openticket =  Task::query()->select(
            'DateCreated',
            'TaskNumber',
            'TaskStatus',
            'CreatedBy',
            DB::raw('(CASE
                WHEN FormField.FieldId = \'GBISubCategory\'
                THEN
                FormField.Value
                END) as Issue'
            )
        )
        ->join('form', 'taskid', 'task.id')
        ->join('formfield', 'formid', 'form.id')
        ->where('FieldId', 'GBISubCategory')
        ->whereIN('TaskStatus', ['Submitted','Closed'])
        ->get();
//
        // $subcategory =  Task::query()->select(
        //         'DateCreated',
        //         'TaskNumber',
        //         'CreatedBy',
        //         'TaskStatus',
        //         DB::raw('(CASE
        //             WHEN FormField.FieldId = \'GBISubCategory\'
        //         THEN
        //             FormField.Value
        //             END) as Issue'
        //         )
        //     )
        //     ->join('form', 'taskid', 'task.id')
        //     ->join('formfield', 'formid', 'form.id')
        //     ->where('FieldId', 'GBISubCategory')
        //     ->whereIN('TaskStatus', ['Submitted','Closed'])
        //     ->get();
        // $GBIStoreCode = Task::query()->select(
        //         'TaskNumber',
        //         DB::raw('(CASE
        //             WHEN FormField.FieldId = \'GBIStoreCode\'
        //             THEN
        //             FormField.Value
        //             END) as GBIStoreCode'
        //         )
        //     )
        //     ->join('form', 'taskid', 'task.id')
        //     ->join('formfield', 'formid', 'form.id')
        //     ->where('TaskNumber', 'LIKE', 'GBI%')
        //     ->where('FieldId', 'GBIStoreCode')
        //     ->whereIN('TaskStatus', ['Submitted','Closed'])
        //     ->get();
        // $GBIStoreName = Task::query()->select(
        //         'TaskNumber',
        //         DB::raw('(CASE
        //             WHEN FormField.FieldId = \'GBIStoreName\'
        //             THEN
        //             FormField.Value
        //             END) as GBIStoreName'
        //         )
        //     )
        //     ->join('form', 'taskid', 'task.id')
        //     ->join('formfield', 'formid', 'form.id')
        //     ->where('TaskNumber', 'LIKE', 'GBI%')
        //     ->where('FieldId', 'GBIStoreName')
        //     ->whereNotIN('TaskStatus', ['Submitted','Closed'])
        //     ->get();
            // 
        // foreach ($openticket as $keys){
        //     // return $keys;
        //     // $openticket = collect($openticket);
        //     // if ($openticket->where('TaskNumber', $keys->TaskNumber)) {
        //     //     $keys->IncidentStatus = $openticket->where('TaskNumber', $keys->TaskNumber)->pluck('IncidentStatus')->first();
        //     // }else{
        //     //     $keys->IncidentStatus = '';
        //     // }
        //     $subcategory = collect($subcategory);
        //     if ($subcategory->where('TaskNumber', $keys->TaskNumber)) {
        //         $keys->Issue = $subcategory->where('TaskNumber', $keys->TaskNumber)->pluck('Issue')->first();
        //     }else{
        //         $keys->Issue = '';
        //     }
        //     //
        //     // return $gbi;
        //     // $GBIStoreName = collect($GBIStoreName);
        //     // if ($GBIStoreName->where('TaskNumber', $keys->TaskNumber)) {
        //     //     $keys->GBIStoreName = $GBIStoreName->where('TaskNumber', $keys->TaskNumber)->pluck('GBIStoreName')->first();
        //     // }else{
        //     //     $keys->GBIStoreName = '';
        //     // }
        //     // $GBIStoreCode = collect($GBIStoreCode);
        //     // if ($GBIStoreCode->where('TaskNumber', $keys->TaskNumber)) {
        //     //     $keys->GBIStoreCode = $GBIStoreCode->where('TaskNumber', $keys->TaskNumber)->pluck('GBIStoreCode')->first();
        //     // }else{
        //     //     $keys->GBIStoreCode = '';
        //     // }
        // }
        return response()->json(['data' => $openticket]);
    }

    public function getticket()
    {
        $openticket =  Task::query()->select(
            'DateCreated',
            'TaskNumber',
            'TaskStatus',
            'CreatedBy',
            DB::raw('(CASE
                WHEN FormField.FieldId = \'GBIIncidentStatus\'
                THEN
                FormField.Value
                END) as IncidentStatus'
            )
        )
        ->join('form', 'taskid', 'task.id')
        ->join('formfield', 'formid', 'form.id')
        ->where('FieldId', 'GBIIncidentStatus')
        ->whereNotIN('Value', ['Resolved','Closed'])
        ->get();
//
        $subcategory =  Task::query()->select(
                'DateCreated',
                'TaskNumber',
                'CreatedBy',
                'TaskStatus',
                DB::raw('(CASE
                    WHEN FormField.FieldId = \'GBISubCategory\'
                THEN
                    FormField.Value
                    END) as Issue'
                )
            )
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('FieldId', 'GBISubCategory')
            ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->get();
        $GBIStoreCode = Task::query()->select(
                'TaskNumber',
                DB::raw('(CASE
                    WHEN FormField.FieldId = \'GBIStoreCode\'
                    THEN
                    FormField.Value
                    END) as GBIStoreCode'
                )
            )
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->where('FieldId', 'GBIStoreCode')
            ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->get();
        $GBIStoreName = Task::query()->select(
                'TaskNumber',
                DB::raw('(CASE
                    WHEN FormField.FieldId = \'GBIStoreName\'
                    THEN
                    FormField.Value
                    END) as GBIStoreName'
                )
            )
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->where('FieldId', 'GBIStoreName')
            ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->get();
        $GBILatestNotes = Task::query()->select(
                'TaskNumber',
                DB::raw('(CASE
                    WHEN FormField.FieldId = \'GBILatestNotes\'
                    THEN
                    FormField.Value
                    END) as GBILatestNotes'
                )
            )
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('TaskNumber', 'LIKE', 'GBI%')
            ->where('FieldId', 'GBILatestNotes')
            ->whereNotIN('TaskStatus', ['Submitted','Closed'])
            ->get();
            // 
        foreach ($openticket as $keys){
            // return $keys;
            // $openticket = collect($openticket);
            // if ($openticket->where('TaskNumber', $keys->TaskNumber)) {
            //     $keys->IncidentStatus = $openticket->where('TaskNumber', $keys->TaskNumber)->pluck('IncidentStatus')->first();
            // }else{
            //     $keys->IncidentStatus = '';
            // }
            $subcategory = collect($subcategory);
            if ($subcategory->where('TaskNumber', $keys->TaskNumber)) {
                $keys->Issue = $subcategory->where('TaskNumber', $keys->TaskNumber)->pluck('Issue')->first();
            }else{
                $keys->Issue = '';
            }
            //
            // return $gbi;
            $GBIStoreName = collect($GBIStoreName);
            if ($GBIStoreName->where('TaskNumber', $keys->TaskNumber)) {
                $keys->GBIStoreName = $GBIStoreName->where('TaskNumber', $keys->TaskNumber)->pluck('GBIStoreName')->first();
            }else{
                $keys->GBIStoreName = '';
            }
            $GBIStoreCode = collect($GBIStoreCode);
            if ($GBIStoreCode->where('TaskNumber', $keys->TaskNumber)) {
                $keys->GBIStoreCode = $GBIStoreCode->where('TaskNumber', $keys->TaskNumber)->pluck('GBIStoreCode')->first();
            }else{
                $keys->GBIStoreCode = '';
            }

            $GBILatestNotes = collect($GBILatestNotes);
            if ($GBILatestNotes->where('TaskNumber', $keys->TaskNumber)) {
                $keys->GBILatestNotes = $GBILatestNotes->where('TaskNumber', $keys->TaskNumber)->pluck('GBILatestNotes')->first();
            }else{
                $keys->GBILatestNotes = '';
            }
        }
        return response()->json(['data' => $openticket]);

        return DataTables::of($openticket)
        // ->addColumn('DateCreated', function ($task){
        //     return Carbon::parse($task->DateCreated)->isoFormat('lll');
        // })

        // ->addColumn('Resolver', function ($task){
        //     $Issue = FormField::query()->select('value')
        //         ->where('FormId', $task->formid)
        //         ->where('label', 'Resolver Group')
        //         ->first()->value;
        
        //     return $Issue;
        // })
        ->make(true);
        
    }
    public function monthlytickets()
    {
        return view('monthlychart');
    }
    public function weeklytickets()
    {
        return view('weeklychart');
    }

    public function dailytickets()
    {
        $Store = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Store')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $Plant = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Plant')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $Office = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Office')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $date = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $dates = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"),)
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->pluck('date');
        // return $dates;
        $ofc = [];
        $plnt = [];
        $str = [];
        // return $Store;
        foreach ($date as $key) {
            if ($Office->where('Date', $key->date)->first()) {
                array_push($ofc, $Office->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($ofc, 0);
            }
            if ($Plant->where('Date', $key->date)->first()) {
                array_push($plnt, $Plant->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($plnt, 0);
            }
            if ($Store->where('Date', $key->date)->first()) {
                array_push($str, $Store->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($str, '0');
            }
        }
        // $datas=array();
        // for ($i=0; $i < $date->count(); $i++) { 
        //     array_push($datas, 0);
        // }
        
        // foreach ($date as $index => $date) {
        //     $datas[$date] = $test[$index];
        // }
        
        // return $stor;
        // return $datas;
        $plnt = collect($plnt);
        $str = collect($str);
        $ofc = collect($ofc);
        $strtotal = $str->sum(function($item) {
            return $item;
        });
        $plnttotal = $plnt->sum(function($item) {
            return $item;
        });
        $ofctotal = $ofc->sum(function($item) {
            return $item;
        });
        $grandtotal = array();
        foreach ($str as $key => $value) {
            array_push($grandtotal, $str[$key]+$plnt[$key]+$ofc[$key]);
        }
        array_push($grandtotal, $strtotal+$plnttotal+$ofctotal);
        return view('dailychart', compact('dates', 'plnt', 'str', 'ofc', 'strtotal', 'plnttotal', 'ofctotal', 'grandtotal'));
    }

    public function monthlyticketsdata(Request $request)
    {
        // return Carbon::now()->weekOfYear.'/'.Carbon::now()->endOfweek();
        $Store = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Store')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $StoreW = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("DATENAME(week, DateCreated) as Date"))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Store')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
            ->get();
            // $year = $request->year;
            // $week = array();
            // foreach ($Store as $key) {
            //     array_push($week, $key->Date);
            // }
            // $weekcount = count($week);
            
            // $dateTime = Carbon::now();
            // $dateTime->setISODate($year, $week); 
            // $result['start_date'] = $dateTime->format('d-M-Y');
            // $dateTime->modify('+6 days'); 
            // // return $dateTime;
            // $result['end_date'] = $dateTime->format('d-M-Y'); 
            // return $result;
            // return $Store;
        $Plant = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            // ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Plant')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $PlantW = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("DATENAME(week, DateCreated) as Date"))
            // ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Plant')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
            ->get();
        $Office = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            // ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Office')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $OfficeW = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("DATENAME(week, DateCreated) as Date"))
            // ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Office')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
            ->get();
        $date = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            // ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $dateW = Task::query()
            ->select(DB::raw("DATENAME(week, DateCreated) as date"))
            // ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->whereNotNull('Value')
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
            ->get();
        $dates = Task::query()
            ->select(DB::raw("Format(DateCreated, 'dd', 'en-US') as date"))
            // ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'dd', 'en-US')"))
            ->pluck('date');
        $datesW = Task::query()
            ->select(DB::raw("DATENAME(week, DateCreated) as date"))
            // ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereYear('DateCreated', $request->year)
            ->whereMonth('DateCreated', $request->month)
            ->whereNotNull('Value')
            ->groupBy(DB::raw("DATENAME(week, DateCreated)"))
            ->pluck('date');
        $ofc = [];
        $plnt = [];
        $str = [];
        $ofcW = [];
        $plntW = [];
        $strW = [];
        foreach ($date as $key) {
            if ($Office->where('Date', $key->date)->first()) {
                array_push($ofc, $Office->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($ofc, 0);
            }
            if ($Plant->where('Date', $key->date)->first()) {
                array_push($plnt, $Plant->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($plnt, 0);
            }
            if ($Store->where('Date', $key->date)->first()) {
                array_push($str, $Store->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($str, 0);
            }
        }
        foreach ($dateW as $key) {
            if ($OfficeW->where('Date', $key->date)->first()) {
                array_push($ofcW, $OfficeW->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($ofcW, 0);
            }
            if ($PlantW->where('Date', $key->date)->first()) {
                array_push($plntW, $PlantW->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($plntW, 0);
            }
            if ($StoreW->where('Date', $key->date)->first()) {
                array_push($strW, $StoreW->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($strW, '0');
            }
        }

        // $datas=array();
        // for ($i=0; $i < $date->count(); $i++) { 
        //     array_push($datas, 0);
        // }
        
        // foreach ($date as $index => $date) {
        //     $datas[$date] = $test[$index];
        // }
        
        // return $stor;
        // return $datas;
        $plnt = collect($plnt);
        $str = collect($str);
        $ofc = collect($ofc);

        $strtotal = $str->sum(function($item) {
            return $item;
        });
        $plnttotal = $plnt->sum(function($item) {
            return $item;
        });
        $ofctotal = $ofc->sum(function($item) {
            return $item;
        });
        $grandtotal = array();
        foreach ($str as $key => $value) {
            array_push($grandtotal, $str[$key]+$plnt[$key]+$ofc[$key]);
        }
        array_push($grandtotal, $strtotal+$plnttotal+$ofctotal);

        $plntW = collect($plntW);
        $strW = collect($strW);
        $ofcW = collect($ofcW);

        $strtotalW = $strW->sum(function($item) {
            return $item;
        });
        $plnttotalW = $plntW->sum(function($item) {
            return $item;
        });
        $ofctotalW = $ofcW->sum(function($item) {
            return $item;
        });
        $grandtotalW = array();
        foreach ($strW as $key => $value) {
            array_push($grandtotalW, $strW[$key]+$plntW[$key]+$ofcW[$key]);
        }
        array_push($grandtotalW, $strtotalW+$plnttotalW+$ofctotalW);
        $weekcount = count($grandtotalW);

        // dd($grandtotalW);
        $percent = array();
        if (count($grandtotalW) < 6) {
            array_push($percent, round(($strtotalW/$grandtotalW[$weekcount-1])*100,2).'%');
            array_push($percent, round(($plnttotalW/$grandtotalW[$weekcount-1])*100,2).'%');
            array_push($percent, round(($ofctotalW/$grandtotalW[$weekcount-1])*100,2).'%');
        }else{
            array_push($percent, round(($strtotalW/$grandtotalW[$weekcount-1])*100,2).'%');
            array_push($percent, round(($plnttotalW/$grandtotalW[$weekcount-1])*100,2).'%');
            array_push($percent, round(($ofctotalW/$grandtotalW[$weekcount-1])*100,2).'%');
        }
        $weekslabel = array();
        for ($i=1; $i <= count($datesW); $i++) { 
           array_push($weekslabel, 'Week '.$i);
        }
        $weekslabel=collect($weekslabel);
        $data = [
            'plnt'=>$plnt,
            'str'=>$str,
            'ofc'=>$ofc,
            'plntW'=>$plntW,
            'strW'=>$strW,
            'ofcW'=>$ofcW,
            'dates'=>$dates,
            'datesW'=>$datesW,
            'strtotal'=>$strtotal,
            'plnttotal'=>$plnttotal,
            'ofctotal'=>$ofctotal,
            'grandtotal'=>$grandtotal,
            'strtotalW'=>$strtotalW,
            'plnttotalW'=>$plnttotalW,
            'ofctotalW'=>$ofctotalW,
            'grandtotalW'=>$grandtotalW,
            'percent'=>$percent,
            'weekcount'=>count($datesW),
            'weekslabel'=>$weekslabel
        ];

        return response()->json($data);
    }
    public function dailyticketsdata()
    {
        $Store = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Store')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $Plant = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Plant')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $Office = Task::query()
            ->select(DB::raw('COUNT(Task.Id) as count'), DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as Date"))
            ->whereDate('DateCreated', '>=', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->where('Value', 'Office')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $date = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->get();
        $dates = Task::query()
            ->select(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US') as date"))
            ->whereDate('DateCreated', '>', Carbon::now()->subMonths(1))
            ->join('Form', 'TaskId', 'Task.Id')
            ->join('FormField', 'FormId', 'Form.Id')
            // ->orderBy('DateCreated', 'Asc')
            ->where('FieldId', 'GBISBU')
            ->whereNotNull('Value')
            ->groupBy(DB::raw("Format(DateCreated, 'MM-dd-yyyy', 'en-US')"))
            ->pluck('date');
        // return $Office;
        $ofc = [];
        $plnt = [];
        $str = [];
        // return $Store;
        foreach ($date as $key) {
            if ($Office->where('Date', $key->date)->first()) {
                array_push($ofc, $Office->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($ofc, 0);
            }
            if ($Plant->where('Date', $key->date)->first()) {
                array_push($plnt, $Plant->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($plnt, 0);
            }
            if ($Store->where('Date', $key->date)->first()) {
                array_push($str, $Store->where('Date', $key->date)->pluck('count')->first());
            }else{
                array_push($str, '0');
            }
        }
        // $datas=array();
        // for ($i=0; $i < $date->count(); $i++) { 
        //     array_push($datas, 0);
        // }
        
        // foreach ($date as $index => $date) {
        //     $datas[$date] = $test[$index];
        // }
        
        // return $stor;
        // return $datas;
        $plnt = collect($plnt);
        $str = collect($str);
        $ofc = collect($ofc);
        $strtotal = $str->sum(function($item) {
            return $item;
        });
        $plnttotal = $plnt->sum(function($item) {
            return $item;
        });
        $ofctotal = $ofc->sum(function($item) {
            return $item;
        });
        $grandtotal = array();
        foreach ($str as $key => $value) {
            array_push($grandtotal, $str[$key]+$plnt[$key]+$ofc[$key]);
        }
        array_push($grandtotal, $strtotal+$plnttotal+$ofctotal);
        $data = [
            'plnt'=>$plnt,
            'str'=>$str,
            'ofc'=>$ofc,
            'dates'=>$dates,
            'strtotal'=>$strtotal,
            'plnttotal'=>$plnttotal,
            'ofctotal'=>$ofctotal,
            'grandtotal'=>$grandtotal
        ];
        return response()->json($data);
    }
    public function storetopissue()
    {

        // $test = FormField::query()->select('TaskNumber')
        //     ->join('Form', 'FormId', 'Form.Id')
        //     ->join('Task', 'TaskId', 'Task.Id')
        //     ->where('FieldId', 'GBISBU')
        //     ->whereNull('value')
        //     ->count();
        // return $test;

        $TopIssue = FormField::query()->select(
            DB::raw(
                'SUM(CASE WHEN value = \'avr\' THEN 1 ELSE 0 END) as avr'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'AX Issue\' THEN 1 ELSE 0 END) as AXIssue'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Back Office\' THEN 1 ELSE 0 END) as BackOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Biometrics\' THEN 1 ELSE 0 END) as Biometrics'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Browser\' THEN 1 ELSE 0 END) as Browser'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cabling\' THEN 1 ELSE 0 END) as Cabling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Cash Drawer\' THEN 1 ELSE 0 END) as CashDrawer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CBB\' THEN 1 ELSE 0 END) as CBB'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'CCTV\' THEN 1 ELSE 0 END) as CCTV'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Desktop\' THEN 1 ELSE 0 END) as Desktop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Dismantling / Re-Installation\' THEN 1 ELSE 0 END) as Dismantling'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EIMS\' THEN 1 ELSE 0 END) as EIMS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Email\' THEN 1 ELSE 0 END) as Email'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'EOD\' THEN 1 ELSE 0 END) as EOD'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'E-Sales\' THEN 1 ELSE 0 END) as ESales'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-MPC\' THEN 1 ELSE 0 END) as HWMPC'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-PC/POS\' THEN 1 ELSE 0 END) as HWPCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-POS\' THEN 1 ELSE 0 END) as HWPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Printer\' THEN 1 ELSE 0 END) as HWPrinter'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'HW-Server\' THEN 1 ELSE 0 END) as HWServer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Inquiry\' THEN 1 ELSE 0 END) as Inquiry'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Installation\' THEN 1 ELSE 0 END) as Installation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Internet\' THEN 1 ELSE 0 END) as Internet'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Laptop\' THEN 1 ELSE 0 END) as Laptop'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Microsoft 365\' THEN 1 ELSE 0 END) as Microsoft365'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Modem\' THEN 1 ELSE 0 END) as Modem'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'MS Office\' THEN 1 ELSE 0 END) as MSOffice'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'My HR\' THEN 1 ELSE 0 END) as MyHR'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Others\' THEN 1 ELSE 0 END) as Others'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'PC/POS\' THEN 1 ELSE 0 END) as PCPOS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS\' THEN 1 ELSE 0 END) as POS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'POS Application\' THEN 1 ELSE 0 END) as POSApplication'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Price Change\' THEN 1 ELSE 0 END) as PriceChange'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Printer\' THEN 1 ELSE 0 END) as Printer'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Relocation\' THEN 1 ELSE 0 END) as Relocation'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Reset Password\' THEN 1 ELSE 0 END) as ResetPassword'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Router\' THEN 1 ELSE 0 END) as Router'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Sales Discrepancy\' THEN 1 ELSE 0 END) as SalesDiscrepancy'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'UPS\' THEN 1 ELSE 0 END) as UPS'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'VPN\' THEN 1 ELSE 0 END) as VPN'
            ),
            DB::raw(
                'SUM(CASE WHEN value = \'Linksys\' THEN 1 ELSE 0 END) as Linksys'
            )
        )
        ->where('FieldId', 'GBISubCategory')
        ->join('Form', 'FormId', 'Form.Id')
        ->join('Task', 'TaskId', 'Task.Id')
        ->get();
        $top = [
            'AVR'=>$TopIssue[0]->avr,
            'AX Issue'=>$TopIssue[0]->Axissue,
            'Back Office'=>$TopIssue[0]->Backoffice,
            'Biometrics'=>$TopIssue[0]->Biometrics,
            'Browser'=>$TopIssue[0]->Browser,
            'Cabling'=>$TopIssue[0]->Cabling,
            'Cash Drawer'=>$TopIssue[0]->CashDrawer,
            'CBB'=>$TopIssue[0]->CBB,
            'CCTV'=>$TopIssue[0]->CCTV,
            'Desktop'=>$TopIssue[0]->Desktop,
            'Dismantling / Re-Installation'=>$TopIssue[0]->Dismantling,
            'EIMS'=>$TopIssue[0]->EIMS,
            'Email'=>$TopIssue[0]->Email,
            'EOD'=>$TopIssue[0]->EOD,
            'E-Sales'=>$TopIssue[0]->ESales,
            'HW-MPC'=>$TopIssue[0]->HWMPC,
            'HW-PC/POS'=>$TopIssue[0]->HWPCPOS,
            'HW-POS'=>$TopIssue[0]->HWPOS,
            'HW-Printer'=>$TopIssue[0]->HWPrinter,
            'HW-Server'=>$TopIssue[0]->HWServer,
            'Inquiry'=>$TopIssue[0]->Inquiry,
            'Installation'=>$TopIssue[0]->Installation,
            'Internet'=>$TopIssue[0]->Internet,
            'Laptop'=>$TopIssue[0]->Laptop,
            'Linksys'=>$TopIssue[0]->Linksys,
            'Microsoft 365'=>$TopIssue[0]->Microsoft365,
            'Modem'=>$TopIssue[0]->Modem,
            'MS Office'=>$TopIssue[0]->MSOffice,
            'My HR'=>$TopIssue[0]->MyHR,
            'Others'=>$TopIssue[0]->Others,
            'PC/POS'=>$TopIssue[0]->PCPOS,
            'POS'=>$TopIssue[0]->POS,
            'POS Application'=>$TopIssue[0]->POSApplication,
            'Price Change'=>$TopIssue[0]->PriceChange,
            'Printer'=>$TopIssue[0]->Printer,
            'Relocation'=>$TopIssue[0]->Relocation,
            'Reset Password'=>$TopIssue[0]->ResetPassword,
            'Router'=>$TopIssue[0]->Router,
            'Sales Discrepancy'=>$TopIssue[0]->SalesDiscrepancy,
            'UPS'=>$TopIssue[0]->UPS,
            'VPN'=>$TopIssue[0]->VPN
        ];
       
        // store open top issue
        $storeopensubcategory =Task::query()->select('value as issue',
                'TaskNumber'
            )
            ->join('form', 'taskid', 'task.id')
            ->join('formfield', 'formid', 'form.id')
            ->where('FieldId', 'GBISubCategory')
            ->whereNotIN('TaskStatus',['Closed', 'Submitted'])
            ->get();
        
        $storeopengbisbu = Task::query()->select(
                        'TaskNumber',
                        'value as gbisbu'
                    )
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->whereNotIN('TaskStatus',['Closed', 'Submitted'])
                ->where('FieldId', 'GBISBU')
                ->whereIN('value', ['Store','Plant','Office'])
                ->get();
        
        foreach ($storeopengbisbu as $keys) {
            $storeopensubcategory = collect($storeopensubcategory);
            if ($storeopensubcategory->where('TaskNumber', $keys->TaskNumber)) {
                $keys->subcategory = $storeopensubcategory->where('TaskNumber', $keys->TaskNumber)->pluck('issue')->first();
            }
        }
        $storeopen = $storeopengbisbu->countBy('subcategory')->all();
        arsort($storeopen);
        //

        $filtered = array_filter($top);
        arsort($filtered);
        
        $issue=[];
        foreach ($storeopen as $key => $value) {
            if (isset($filtered[$key])) {
                $closedvalue = $filtered[$key];
                if ($key) {
                    $issue[]=array('key'=>$key, 'open'=>$value, 'closed'=>$closedvalue, 'total'=>$value+$closedvalue);
                }else{
                    $issue[]=array('key'=>'Unknown', 'open'=>$value, 'closed'=>$closedvalue, 'total'=>$value+$closedvalue);
                }
            }else{
                if ($key) {
                    $issue[]=array('key'=>$key, 'open'=>$value, 'closed'=>'0', 'total'=>$value);
                }else{
                    $issue[]=array('key'=>'Unknown', 'open'=>$value, 'closed'=>'0', 'total'=>$value);
                }
            }
        }
        // data
        return response()->json(['data'=>$issue]);

        // return DataTables::of($issue)->make(true);
    }

    public function taskdata(Request $request)
    {
        if(DB::connection()->getDatabaseName()){
            $storecode = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIStoreCode')
                ->select('value')
                ->pluck('value')->first();
            $storename = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIStoreName')
                ->select('value')
                ->pluck('value')->first();
            $storeaddress = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIStoreAddress')
                ->select('value')
                ->pluck('value')->first();
            $ownership = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIOwnership')
                ->select('value')
                ->pluck('value')->first();

            $contactperson = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIContactPerson')
                ->select('value')
                ->pluck('value')->first();

            $contactnumber = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIContactNumber')
                ->select('value')
                ->pluck('value')->first();

            $email = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIEmailAddress')
                ->select('value')
                ->pluck('value')->first();

            $problemreported = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIProblemReported')
                ->select('value')
                ->pluck('value')->first();

            $location = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBILocation')
                ->select('value')
                ->pluck('value')->first();
            
            $rootcause = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIRootCause')
                ->select('value')
                ->pluck('value')->first();

            $latestnotes = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBILatestNotes')
                ->select('value')
                ->pluck('value');
            $SBU = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBISBU')
                ->select('value')
                ->pluck('value')->first();

            $IncidentStatus = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIIncidentStatus')
                ->select('value')
                ->pluck('value')->first();

            $GBIActionTaken = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIActionTaken')
                ->select('value')
                ->pluck('value')->first();

            $GBIResolverGroup = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIResolverGroup')
                ->select('value')
                ->pluck('value')->first();

            $GBIResolvedBy = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIResolvedBy')
                ->select('value')
                ->pluck('value')->first();
            $GBIStoreType = Task::query()
                ->join('form', 'taskid', 'task.id')
                ->join('formfield', 'formid', 'form.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->where('FieldId', 'GBIStoreType')
                ->select('value')
                ->pluck('value')->first();

            $Remarks = Task::query()
                ->select('Author', 'Message', 'Timestamp')
                ->join('Remark', 'taskid', 'task.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->get();
            $History = Task::query()
                ->select('Label as Action', 'Snapshotvalue as Original','Source','Timestamp','UpdatedValue as Updated', 'Message','AuditLevel')
                ->join('taskauditlog', 'taskid', 'task.id')
                ->where('TaskNumber', $request->TaskNumber)
                ->get();
            return response()->json(
                [
                    'Store_Code'=>$storecode,
                    'Store_Address'=>$storeaddress,
                    'Ownership'=>$ownership,
                    'Contact_Person'=>$contactperson,
                    'Contact_Number'=>$contactnumber,
                    'Store_Name'=>$storename,
                    'Email_Address'=>$email,
                    'Location'=>$location,
                    'Latest_Notes'=>$latestnotes,
                    'Sbu'=>$SBU,
                    'IncidentStatus'=>$IncidentStatus,
                    'Problem_Reported'=>$problemreported,
                    'GBIActionTaken'=>$GBIActionTaken,
                    'GBIResolverGroup'=>$GBIResolverGroup,
                    'GBIResolvedBy'=>$GBIResolvedBy,
                    'Root_Cause'=>$rootcause,
                    'Remarks'=>$Remarks,
                    'History'=>$History,
                    'GBIStoreType'=>$GBIStoreType
                    // 'Incident_Status'=>$incidentstatus
                ]
            );
        }
        
    }
}
// 
// $avr = FormField::query()
//             // ->select('FieldId')
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'avr')
//             ->count();
//             // return $avr;
//             $axissue = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'AX Issue')
//             ->groupBy('value')
//             ->count();
//             $Backoffice = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Back Office')
//             ->groupBy('value')
//             ->count();
//             $biometrics = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Biometrics')
//             ->groupBy('value')
//             ->count();
//             $Browser = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Browser')
//             ->groupBy('value')
//             ->count();
//             $Cabling = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Cabling')
//             ->groupBy('value')
//             ->count();
//             $Cashdrawer = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Cash Drawer')
//             ->groupBy('value')
//             ->count();
//             $CBB = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'CBB')
//             ->groupBy('value')
//             ->count();
//             $Cctv = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'CCTV')
//             ->groupBy('value')
//             ->count();
//             $Desktop = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Desktop')
//             ->groupBy('value')
//             ->count();
//             $Dismantling = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Dismantling / Re-Installation')
//             ->groupBy('value')
//             ->count();
//             $EIMS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'EIMS')
//             ->groupBy('value')
//             ->count();
//             $Email = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Email')
//             ->groupBy('value')
//             ->count();
//             $EOD = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'EOD')
//             ->groupBy('value')
//             ->count();
//             $ESales = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'E-Sales')
//             ->groupBy('value')
//             ->count();
//             $HWMPC = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HW-MPC')
//             ->groupBy('value')
//             ->count();
//             $HWPCPOS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HW-PC/POS')
//             ->groupBy('value')
//             ->count();
//             $HWPOS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HWPOS')
//             ->groupBy('value')
//             ->count();
//             $HWPrinter = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HW-Printer')
//             ->groupBy('value')
//             ->count();
//             $HWServer = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'HW-Server')
//             ->groupBy('value')
//             ->count();
//             $Inquiry = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Inquiry')
//             ->groupBy('value')
//             ->count();
//             $Installation = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Installation')
//             ->groupBy('value')
//             ->count();
//             $Internet = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Internet')
//             ->groupBy('value')
//             ->count();
//             $Laptop = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Laptop')
//             ->groupBy('value')
//             ->count();
//             $Microsoft365 = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Microsoft 365')
//             ->groupBy('value')
//             ->count();
//             $Modem = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Modem')
//             ->groupBy('value')
//             ->count();
//             $MSOffice = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'MS Office')
//             ->groupBy('value')
//             ->count();
//             $MyHr = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'My Hr')
//             ->groupBy('value')
//             ->count();
//             $Others = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Others')
//             ->groupBy('value')
//             ->count();
//             $PCPOS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'PC/POS')
//             ->groupBy('value')
//             ->count();
//             $POS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'POS')
//             ->groupBy('value')
//             ->count();
//             $POSApplication = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'POS Application')
//             ->groupBy('value')
//             ->count();
//             $pricechange = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Price Change')
//             ->groupBy('value')
//             ->count();
//             $Printer = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Printer')
//             ->groupBy('value')
//             ->count();
//             $Relocation = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Relocation')
//             ->groupBy('value')
//             ->count();
//             $ResetPassword = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Reset Password')
//             ->groupBy('value')
//             ->count();
//             $Router = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Router')
//             ->groupBy('value')
//             ->count();
//             $SalesDiscrepancy = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'Sales Discrepancy')
//             ->groupBy('value')
//             ->count();
//             $UPS = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'UPS')
//             ->groupBy('value')
//             ->count();
//             $VPN = FormField::query()
//             // ->where('TaskNumber', 'LIKE', 'GBI%')
//             // ->join('form', 'taskid', 'task.id')
//             // ->join('formfield', 'formid', 'form.id')
//             // ->where('TaskStatus', '!=', 'Submitted')
//             ->where('FieldId', 'GBISubCategory')
//             ->where('value', 'VPN')
//             ->groupBy('value')
//             ->count();
//             $top = [
//                 'AVR'=>$avr,
//                 'AX Issue'=>$axissue,
//                 'Back Office'=>$Backoffice,
//                 'Biometrics'=>$biometrics,
//                 'Browser'=>$Browser,
//                 'Cabling'=>$Cabling,
//                 'Cash Drawer'=>$Cashdrawer,
//                 'CBB'=>$CBB,
//                 'Cctv'=>$Cctv,
//                 'Desktop'=>$Desktop,
//                 'Dismantling / Re-Installation'=>$Dismantling,
//                 'EIMS'=>$EIMS,
//                 'Email'=>$Email,
//                 'EOD'=>$EOD,
//                 'E-Sales'=>$ESales,
//                 'HW-MPS'=>$HWMPC,
//                 'HW-PC/POS'=>$HWPCPOS,
//                 'HW-POS"'=>$HWPOS,
//                 'HW-Printer'=>$HWPrinter,
//                 'HW-Server'=>$HWServer,
//                 'Inquiry'=>$Inquiry,
//                 'Installation'=>$Installation,
//                 'Internet'=>$Internet,
//                 'Laptop'=>$Laptop,
//                 'Microsoft 365'=>$Microsoft365,
//                 'Modem'=>$Modem,
//                 'MS Office'=>$MSOffice,
//                 'My HR'=>$MyHr,
//                 'Others'=>$Others,
//                 'PC/POS'=>$PCPOS,
//                 'POS'=>$POS,
//                 'POS Application'=>$POSApplication,
//                 'Price Change'=>$pricechange,
//                 'Printer'=>$Printer,
//                 'Relocation'=>$Relocation,
//                 'Reset Password'=>$ResetPassword,
//                 'Router'=>$Router,
//                 'Sales Discrepancy'=>$SalesDiscrepancy,
//                 'UPS'=>$UPS,
//                 'VPN'=>$VPN
//             ];