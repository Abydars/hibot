<?php
require_once( 'config.php' );

if ( ! isset( $_SESSION['user_id'] ) ) {
	header( "location: page-login.php" );
}

$user_id  = $_SESSION['user_id'];
$user     = getUser( $user_id );

if ( $user['role'] != 'admin' ) {
	header( "location: page-login.php" );
}

if($_POST) {
	$new_symptom = $_POST['symptom'];
	$query = "INSERT INTO symptoms (name) VALUES('{$new_symptom}')";

	if($con->query($query)) {
		header("location: diseases.php");
		die();
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta property="og:image" content="http://pratikborsadiya.in/blog/vali-admin/hero-social.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>ED SYSTEM</title>
</head>
<body class="app sidebar-mini">
<!-- Navbar-->
<header class="app-header"><a class="app-header__logo" href="index.php">Ed System</a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">


        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown"><i
                        class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
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


            <p class="app-sidebar__user-name"><?= ucwords( $user['name'] ) ?></p>

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
        <li><a class="app-menu__item" href="charts.php"><i class="app-menu__icon fa fa-pie-chart"></i><span
                        class="app-menu__label">Charts</span></a></li>
        <li><a class="app-menu__item" href="users.php"><i class="app-menu__icon fa fa-users"></i><span
                        class="app-menu__label">Users</span></a></li>


    </ul>
</aside>
<main class="app-content">
	<div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">New Symptom</h3>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-md-12">
			<form method="POST">
			<div class="form-group">
				<label>New Symptom</label>
				<input type="text" class="form-control" name="symptom" />
			</div>
			<input type="submit" class="btn btn-success" />
			</form>
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
<script type="text/javascript" src="js/plugins/chart.js"></script>
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
