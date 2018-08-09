<?php
require_once('config.php');

if(isset($_SESSION['user_id']))
  header("location: index.php");

$error = false;

if(isset($_POST['email']) && isset($_POST['password'])) {
  $query = "SELECT * FROM users WHERE username = '{$_POST['email']}' AND password = '{$_POST['password']}';";
  $result = mysqli_query($con, $query);
  $user_exists = $result->num_rows > 0;

  if($user_exists) {
    $user_id = $result->fetch_assoc()['user_id'];
    $_SESSION['user_id'] = $user_id;

    $url = "index.php";
    header("location: {$url}");
  } else {
    $error = "Authentication failed";
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>ED SYSTEM</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1>ED SYSTEM</h1>
      </div>

        <div class="login-box">
          <form class="login-form" method="POST">
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
            <?php if($error) { ?><div class="alert alert-danger"><?php echo $error; ?></div><?php } ?>
            <div class="form-group">
              <label class="control-label">USERNAME</label>
              <input name="email" class="form-control" type="text" placeholder="Email" autofocus required>
            </div>
            <div class="form-group">
              <label class="control-label">PASSWORD</label>
              <input name="password" class="form-control" type="password" placeholder="Password" required>
            </div>
            <div class="form-group">
              <div class="utility">
                <div class="animated-checkbox">
                  <label>
                    <input type="checkbox"><span class="label-text">Stay Signed in</span>
                  </label>
                </div>
                <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p>
              </div>
            </div>
            <div class="form-group btn-container">
              <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
                <p class="semibold-text mb-2"><a href="page-register.php" ><center>OR REGISTER</center></a></p>
            </div>
          </form>
          <form class="forget-form" action="index.php">
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
            <div class="form-group">
              <label class="control-label">EMAIL</label>
              <input class="form-control" type="text" placeholder="Email">
            </div>
            <div class="form-group btn-container">
              <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>
            </div>
            <div class="form-group mt-3">
              <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
            </div>
          </form>
        </div>




    </section>
  </body>
  <!-- Essential javascripts for application to work-->
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
  <!-- The javascript plugin to display page loading on top-->
  <script src="js/plugins/pace.min.js"></script>
</html>
<script type="text/javascript">
  // Login Page Flipbox control
  $('.login-content [data-toggle="flip"]').click(function() {
  	$('.login-box').toggleClass('flipped');
  	return false;
  });
</script>
