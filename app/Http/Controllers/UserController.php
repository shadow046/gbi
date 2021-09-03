<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserLog;
use App\Models\VerifyUser;
use Validator;
use Config;
use Mail;

class UserController extends Controller
{
    public function verifyUser(Request $request, $id)
    {
        $verifyUser = VerifyUser::where('token', $id)->first();
        //dd(isset($verifyUser));
        if(isset($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                User::where('id', $verifyUser->user_id)->update(['email_verified_at'=> now()]);
                $status = "Your e-mail is verified. You can now login.";
            } else {
                $status = "Your e-mail is already verified. You can now login.";
            }
        } else {
            return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
        }
        return redirect('/login')->with('status', $status);
    }

    public function storepass(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return redirect()->to('/')->with('success', 'Password change successfully.');
    }

    public function changepass()
    {
        $title = 'Change password';
        return view('changePassword', compact('title'));
    }
    
    public function resend(Request $request)
    {
        $checkduplicate = User::where('email', $request->email)->first();
        if ($checkduplicate) {
            if ($checkduplicate->id != auth()->user()->id) {
                $data = false;
                return response()->json(false);
            }
        }
        $verifyUser = VerifyUser::create([
            'user_id' => auth()->user()->id,
            'token' => sha1(time())
        ]);

        $user = User::where('id', auth()->user()->id)->first();
        
        User::where('id', auth()->user()->id)->update(['email' => $request->email]);
        $email = $request->email;

        $data = Mail::send('emails.verifyUser', ['user'=>$user],function( $message) use($email){ 
            $message->to($email, auth()->user()->name)->subject('Email verification'); 
            $message->from('noreply@apsoft.com.ph', 'Ticket Monitoring Email Verification'); 
            $message->bcc('jerome.lopez.aks2018@gmail.com');
        });
        return response()->json(true);
    }

    public function getusers()
    {
        $users = User::where('status', '!=', 4);
        return DataTables::of($users)
        ->addColumn('Access_Level', function (User $users){
            return $users->roles->first()->name;
        })
        ->addColumn('status', function (User $users){
            if ($users->status == 1) {
                return 'Active';
            }else{
                return 'Inactive';
            }
        })
        ->make(true);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if ($user->email != $request->input('email')) {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'min:7', 'max:255'],
                'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:users',
                'role' => ['required', 'string']
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'min:7', 'max:255'],
                'role' => ['required', 'string']
            ]);
        }
        
        if ($validator->passes()) {
            $user = User::where('id', $id)->first();
            $log = new UserLog;
            $log->activity = 'UPDATE USER '.$user->name;
            $log->user_id = auth()->user()->id;
            $log->fullname = auth()->user()->name;
            $log->save();
            $user->name = ucwords(mb_strtolower($request->input('first_name')));
            $user->email = $request->input('email');
            $user->status = $request->input('status');
            $data = $user->save();
            $user->syncRoles($request->input('role'));
            return response()->json($data);
        }
        return response()->json(['error'=>$validator->errors()->first()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'min:7', 'max:255'],
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:users',
            'role' => ['required', 'string']
        ]);
        if ($validator->passes()) {
            $user = new User;
            $user->name = ucwords(mb_strtolower($request->input('first_name')));
            $user->email = $request->input('email');
            $user->password = Hash::make('uwuqQ6NP?3E4');
            $user->status = 1;
            $user->assignRole($request->input('role'));
            $log = new UserLog;
            $log->activity = 'ADD NEW USER '.$user->name;
            $log->user_id = auth()->user()->id;
            $log->fullname = auth()->user()->name;
            $log->save();
            $data = $user->save();
            $config = array(
                'driver'     => \config('mailconf.driver'),
                'host'       => \config('mailconf.host'),
                'port'       => \config('mailconf.port'),
                'from'       => \config('mailconf.from'),
                'encryption' => \config('mailconf.encryption'),
                'username'   => \config('mailconf.username'),
                'password'   => \config('mailconf.password')
            );

            Config::set('mail', $config);
            Mail::send('new-user', [
                'email'=>$request->input('email'),
                'name'=>ucwords(mb_strtolower($request->input('first_name')))
            ],
            function( $message) use ($user){ 
                $message->to($user->email, $user->name)->subject('Account Details'); 
                $message->from('noreply@apsoft.com.ph', 'Ticket Monitoring');
                $message->bcc('jolopez@ideaserv.com.ph','emorej046@gmail.com');
            });
            return response()->json($data);
        }
        return response()->json(['error'=>$validator->errors()->first()]);
    }

    public function userlogs()
    {   
        $logs = UserLog::all();
        return DataTables::of($logs)
        ->addColumn('Date', function (UserLog $tickets){
            return Carbon::parse($tickets->created_at->toFormattedDateString().' '.$tickets->created_at->toTimeString())->isoFormat('lll');
        })
        ->addColumn('Access_Level', function (UserLog $users){
            $user = User::where('id', $users->user_id)->first();
            return $user->roles->first()->name;
        })
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
