<div id="register">
    <input type="text" id="pn" placeholder="phone number"><br><br>
    <input type="text" id="un" placeholder="Username" value="{{$username}}" disabled><br><br>
    {{-- <input type="text" id="pw" placeholder="Password"><br><br> --}}
    <input type="text" id="devid" placeholder="Device ID" value="{{$devid}}" disabled><br><br>
    <div id="otpdiv" style="display:none">
        <input type="text" id="otp" placeholder="OTP"><br><br>
        <input type="text" id="otpid" placeholder="OTPid" disabled><br><br>
        <button id="submitBtn">Submit</button>
    </div>
    <button id="sendBtn">Enter</button>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

$(document).on('click', '#submitBtn', function(){
    if ($('#otp').val()) {
        $.ajax({
            url: "sendotp",
            data:{
                pn: $('#pn').val(),
                un: $('#un').val(),
                pw: $('#pw').val(),
                devid: $('#devid').val(),
                otp: $('#otp').val(),
                otpid: $('#otpid').val(),
            },
            success: function(result){
                if(result == 'Too many requests, try again later'){
                    alert(result);
                    alert('Failed');
                }else{
                    alert('Registration Completed!');
                }
                location.reload();
            },
            error: function (data) {
                alert(data.responseText);
            }
        });
    }else{
        alert('Please complete the details!!');
    }

});
$(document).on('click', '#sendBtn', function(){
    if ($('#pn').val() && $('#devid').val()) {
        $.ajax({
            url: "check",
            data:{
                un: $('#un').val(),
                pn: $('#pn').val(),
                devid: $('#devid').val(),
            },
            success: function(result){
                console.log(result);
                if(result.message == "The phone number is being used by an existing user. "){
                    alert(result.message);
                }else if(result.message == "The username is being used by an existing user already. " ){
                    alert(result.message);
                }else{
                    $("#otpdiv").show();
                    $("#sendBtn").hide();
                    $("#pn").prop('disabled', true);
                    $('#otpid').val(result);
                }
            },
            error: function (data) {
                alert(data.responseText);
            }
        });
    }else{
        alert('Please complete the details!!');
    }
    
});
</script>
</div>