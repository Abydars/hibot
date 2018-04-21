<?php
require_once( 'config.php' );

if ( ! isset( $_SESSION['user_id'] ) ) {
	header( "location: page-login.php" );
}

$user_id = $_SESSION['user_id'];
$error   = $success = false;
$user    = getUser( $user_id );

if ( isset( $_POST['is_submitted'] ) ) {
	$feeling         = $_POST['feeling'];
	$is_weird_health = $_POST['is_weird_health'];
	$medicines       = implode( ',', $_POST['medicines'] );
	$has_medicines   = $_POST['has_medicines'];
	$submitted_date  = date( "Y-m-d" );

	if ( $has_medicines == "0" ) {
		$medicines = "";
	}

	$start_date = date( "Y-m-d" );
	$end_date   = date( "Y-m-d", strtotime( "+7 days" ) );
	$query      = "SELECT * FROM weekly_form WHERE user_id = '{$user_id}' AND submitted_date >= '{$start_date}' AND submitted_date <= '{$end_date}'";
	$result     = $con->query( $query );

	if ( $result->num_rows <= 0 ) {

		$query   = "INSERT INTO weekly_form(feeling, is_weird_health, medicines, user_id, submitted_date) VALUES('{$feeling}', '{$is_weird_health}', '{$medicines}', '{$user_id}', '{$submitted_date}');";
		$created = $con->query( $query );
		$form_id = mysqli_insert_id( $con );

		$values = [];
		foreach ( $_POST['symptoms'] as $symptom ) {
			$values[] = "({$form_id}, {$symptom})";
		}
		$values  = implode( ",", $values );
		$query   = "INSERT INTO user_symptoms (form_id, symptom_id) VALUES {$values}";
		$created &= $con->query( $query );

		if ( $created ) {
			$success = "Thank you for submitting your report!";

			if ( ! empty( $user['username'] ) ) {
				notification( $user['username'], 'Thank you!', 'Thanks for submitting your weekly survey.' );
			}

			$url = "form-viewweeklyform.php";
			header( "location: {$url}" );
		} else {
			$error = "Failed to submit your report";
		}
	} else {
		$error = "You've already filled this form in this week";
	}
}
$symptoms = getSymptoms();
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Open Graph Meta-->
    <meta property="og:type" content="website">


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Hi Bot</title>
</head>
<body class="app sidebar-mini">
<!-- Navbar-->
<header class="app-header"><a class="app-header__logo" href="index.php">Hi Bot</a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
        <li class="app-search">
            <input class="app-search__input" type="search" placeholder="Search">
            <button class="app-search__button"><i class="fa fa-search"></i></button>
        </li>
        <!--Notification Menu-->
        <!-- <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown"><i class="fa fa-bell-o fa-lg"></i></a>
          <ul class="app-notification dropdown-menu dropdown-menu-right">
            <li class="app-notification__title">You have 4 new notifications.</li>
            <div class="app-notification__content">
              <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                  <div>
                    <p class="app-notification__message">Lisa sent you a mail</p>
                    <p class="app-notification__meta">2 min ago</p>
                  </div></a></li>
              <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-hdd-o fa-stack-1x fa-inverse"></i></span></span>
                  <div>
                    <p class="app-notification__message">Mail server not working</p>
                    <p class="app-notification__meta">5 min ago</p>
                  </div></a></li>
              <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-success"></i><i class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>
                  <div>
                    <p class="app-notification__message">Transaction complete</p>
                    <p class="app-notification__meta">2 days ago</p>
                  </div></a></li>
              <div class="app-notification__content">
                <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></span>
                    <div>
                      <p class="app-notification__message">Lisa sent you a mail</p>
                      <p class="app-notification__meta">2 min ago</p>
                    </div></a></li>
                <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-hdd-o fa-stack-1x fa-inverse"></i></span></span>
                    <div>
                      <p class="app-notification__message">Mail server not working</p>
                      <p class="app-notification__meta">5 min ago</p>
                    </div></a></li>
                <li><a class="app-notification__item" href="javascript:;"><span class="app-notification__icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-success"></i><i class="fa fa-money fa-stack-1x fa-inverse"></i></span></span>
                    <div>
                      <p class="app-notification__message">Transaction complete</p>
                      <p class="app-notification__meta">2 days ago</p>
                    </div></a></li>
              </div>
            </div>
            <li class="app-notification__footer"><a href="#">See all notifications.</a></li>
          </ul>
        </li> -->
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown"><i
                        class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-cog fa-lg"></i> Settings</a></li>
                <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-user fa-lg"></i> Profile</a></li>
                <li><a class="dropdown-item" href="page-login.html"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</header>
<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar"
                                        src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg"
                                        alt="User Image">
        <div>
            <p class="app-sidebar__user-name"><?= ucwords( $user['username'] ) ?></p>
            <p class="app-sidebar__user-designation">Software Engineer</p>
        </div>
    </div>
    <ul class="app-menu">
        <li><a class="app-menu__item active" href="index.php"><i class="app-menu__icon fa fa-dashboard"></i><span
                        class="app-menu__label">Dashboard</span></a></li>


        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                        class="app-menu__icon fa fa-pencil"></i><span class="app-menu__label">Fill Forms</span><i
                        class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="form-weeklyform.php"><i class="icon fa fa-circle-o"></i> Fill Weekly
                        Form</a></li>
                <li><a class="treeview-item" href="page-userhistory.php"><i class="icon fa fa-circle-o"></i> Fill Your
                        History</a></li>

            </ul>
        </li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i
                        class="app-menu__icon fa fa-television"></i><span class="app-menu__label">View Forms</span><i
                        class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="form-viewweeklyform.php"><i class="icon fa fa-circle-o"></i> View
                        Weekly Form</a></li>
                <li><a class="treeview-item" href="page-viewuserhistory.php"><i class="icon fa fa-circle-o"></i> View
                        Your History</a></li>

            </ul>
        </li>
        <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon fa fa-pie-chart"></i><span
                        class="app-menu__label">Charts</span></a></li>


    </ul>
</aside>
<main class="app-content">
    <!-- <div class="app-title">
	  <div>
		<h1><i class="fa fa-edit"></i> Form Samples</h1>
		<p>Sample forms</p>
	  </div>
	  <ul class="app-breadcrumb breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
		<li class="breadcrumb-item">Forms</li>
		<li class="breadcrumb-item"><a href="#">Sample Forms</a></li>
	  </ul>
	</div> -->
    <div class="row">

        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Your Medical History</h3>
				<?php if ( $error ) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div><?php } ?>
				<?php if ( $success ) { ?>
                    <div class="alert alert-success"><?php echo $success; ?></div><?php } ?>
                <form class="form-horizontal" method="POST">
                    <div class="tile-body">
                        <div class="form-group row">
                            <label class="control-label col-md-3">Hey Dude How Are You Feeling ?</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="feeling" rows="4"
                                          placeholder="Please Feel Free To Share"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3">Any Thing You Felt Weird About Your Health?</label>
                            <div class="col-md-8">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="1" type="radio" name="is_weird_health"
                                               required>Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="0" type="radio" name="is_weird_health"
                                               required>No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3">Check Symptoms That Have Occurred</label>
                            <div class="col-md-8">
                                <div class="animated-checkbox">
									<?php foreach ( $symptoms as $symptom ) { ?>
                                        <label>
                                            <input type="checkbox" name="symptoms[]" value="<?= $symptom['id'] ?>"
                                                   required><span
                                                    class="label-text"><?= $symptom['name'] ?></span> &nbsp;&nbsp;
                                        </label>
									<?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3">Did You Used Any Medicine ?</label>
                            <div class="col-md-8">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="1" type="radio" name="has_medicines"
                                               required>Yes
                                    </label>
                                </div>
                                <input class="form-control col-md-4" name="medicines[]" type="text"
                                       placeholder="Enter medicine name"> </br>
                                <input class="form-control col-md-4" name="medicines[]" type="text"
                                       placeholder="Enter medicine name">  </br>
                                <input class="form-control col-md-4" name="medicines[]" type="text"
                                       placeholder="Enter medicine name">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" value="0" type="radio" name="has_medicines"
                                               required>No
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="control-label col-md-3">Upload Image</label>
                            <div class="col-md-8">
                                <input class="form-control" type="file">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8 col-md-offset-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" required>I accept the terms and
                                        conditions
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-3">
                                <input type="hidden" name="is_submitted" value="1"/>
                                <button class="btn btn-primary" type="submit"><i
                                            class="fa fa-fw fa-lg fa-check-circle"></i>Submit
                                </button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#"><i
                                            class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>
<!-- Essential javascripts for application to work-->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<!-- Google analytics script-->
<script type="text/javascript">
    if (document.location.hostname == 'pratikborsadiya.in') {
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
        ga('create', 'UA-72504830-1', 'auto');
        ga('send', 'pageview');
    }
</script>
</body>
</html>
