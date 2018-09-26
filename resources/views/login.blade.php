
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="robots" content="noindex, nofollow">
    <title>Expert System on Agricultural Water Management</title>
     <link rel="shortcut icon" href="../images/icarlogo.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../assets/login/bootstrap.min.css">
  <!-- Font Awesome -->
 <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../assets/login/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/login/AdminLTE.min.css">
  <link rel="stylesheet" href="../assets/login/login.css">
  <!-- iCheck -->
  <!-- fontawesome -->
  <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="../assets/login/fontawesome-all.css">
  <link rel="stylesheet" href="../assets/login/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style media="screen">
            #background {
                /*min-height: 460px;*/
                background-image: url('../assets/login/background.jpg');
                /*background-repeat: no-repeat;*/
                /*background-size: cover;*/
            }

        </style>
  <!-- Google Font -->
  <script src="../assets/login/rainyday.min.js"></script>
        <script>
        
            function run() {
                var rainyDay = new RainyDay({
                    image: 'background' // Target p element with ID, RainyDay.js will use backgorund image to simulate rain effects.
                });
                window.setTimeout(function () {
                    // rainyDay.destroy()
                }, 1000)
            }

        </script>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" onload="run()">
<div class="auth-container" id="background">
 <div class="center-block">
            <div class="auth-module">
                <div class="toggle" style="background: transparent;">
                    <!-- <i class="fa fa-user-shield"></i> -->
                    <!--<i class="fas fa-user-lock"></i>
                    <!-- <div class="tip">Click here to register</div> -->
                </div>

                <!-- Login form -->
                <div class="form">

                    <h1 class="text-light">Login to your account</h1>
                    <br>
                   <h5 class="text-light"></h5>
                   <div class="alert alert-success" id="success" style="display: none;"></div>
                    <div class="alert alert-danger" id="danger" style="display: none;"></div>
                    <form class="form-horizontal" action="login" method="POST" id="LoginForm">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="form-group">
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="userid" class="form-control" placeholder="Username" required="" type="text">
                                <div class="form-control-feedback">
                                   
                                </div>
                            </div>
                            <div class="form-group has-feedback has-feedback-left">
                                <input name="password" class="form-control"  placeholder="Password" required="" type="password">
                                <div class="form-control-feedback">
                                   
                                </div>
                            </div>
                           <!--  <div class="login-options">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="styled" checked="checked">
                                        Remember me
                                    </label>
                                </div>
                            </div> -->
                            <button class="btn btn-success btn-block" id="signin">Sign In</button>
                        </div>
                    </form>
                </div>
            

               <div class="forgot">
                    <a href="#">Forgot your password?</a>
                </div>
            </div>
            <div class="footer">
                
            </div>
        </div>

</div>
<!-- jQuery 3 -->
<script src="../assets/js/vendor/jquery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../assets/login/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../assets/login/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>

<script>
$('#LoginForm').submit(function(e){
e.preventDefault();
$('#signin').html("Please wait......");
$.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:new FormData(this),
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                if(data=="Email does not exits" || data=="You have entered wrong password"){
                     $('#danger').show();
                     $("#danger").html(data); 
                     $('#success').hide();           
                }
                else{
                     $('#danger').hide();
                     $('#success').show();
                     $("#success").html(data);
                     window.location.href="dashboard";
                }
                    $('#signin').html("Sign In");

            },
            error: function(data){
                $('#signin').html("Sign In");
                 $('#danger').show();
                 $("#danger").html("Something went wrong. Please try again later"); 
                 $('#success').hide();
            }
        });
});
 

</script>

@if(session('response'))
<script>
    $('#danger').show();
    $("#danger").html("Successfully logged out");
</script>
@endif
</body>
</html>
