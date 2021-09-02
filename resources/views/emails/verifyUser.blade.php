<!DOCTYPE html>
<html>
  <head>
    <title>Welcome Email</title>
  </head>
  <body>
    <p>Hi {{auth()->user()->name}},</p>
    <p><img src="{{asset('apsoft.jpg')}}"></p>
    <p>Welcome to the Ticket Monitoring System! <br>
      To start using the system, click on the verification link below.<br>
      <br>
      <b><a href="{{url('user/verify', $user->verifyUser->token)}}" target="_blank">{{url('user/verify', $user->verifyUser->token)}}</a></b></p>
    <br/>
    <p>Thank you.<br>
    </p>
  </body>
</html>