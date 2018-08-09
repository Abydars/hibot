<?php
require_once( 'config.php' );

if ( ! isset( $_SESSION['user_id'] ) ) {
	header( "location: page-login.php" );
}

$symptoms = [];
$user_id  = $_SESSION['user_id'];
$user     = getUser( $user_id );
$result   = $con->query( "SELECT * FROM weekly_form WHERE user_id = '{$user_id}'" );

while ( $row = mysqli_fetch_assoc( $result ) ) { //for overall predictions
	$result2 = $con->query( "SELECT s.* FROM user_symptoms AS us JOIN symptoms AS s ON us.symptom_id = s.id WHERE form_id = '{$row['id']}'" );

	while ( $row2 = mysqli_fetch_assoc( $result2 ) ) {
		$symptoms[] = $row2['id'];
	}
}

$pie_data    = $bar_chart_data = $line_chart_data = [];
$predictions = predict( $symptoms );
arsort( $predictions );

$i      = 0;
$colors = [ "orange", "black", "red", "green", "pink", "purple", "grey" ];

foreach ( $predictions as $disease => $prediction ) {
	$j          = ( $i + 1 ) > ( count( $colors ) - 1 ) ? ( $i + 1 ) % ( count( $colors ) - 1 ) : ( $i + 1 );
	$pie_data[] = [
		"value" => $prediction,
		"color" => $colors[ $j ],
		"label" => $disease
	];
	$i ++;
}

$bar_chart_data['labels']     = array_keys( $predictions );
$bar_chart_data['datasets'][] = [
	"data"                 => array_map( function ( $a ) {
		return number_format( $a * 100, 2 );
	}, array_values( $predictions ) ),
	"fillColor"            => "rgba(220,220,220,0.2)",
	"strokeColor"          => "rgba(220,220,220,1)",
	"pointColor"           => "rgba(220,220,220,1)",
	"pointStrokeColor"     => "#fff",
	"pointHighlightFill"   => "#fff",
	"pointHighlightStroke" => "rgba(220,220,220,1)",
];

$line_chart_data['labels'] = array_keys( $predictions );
$result                    = $con->query( "SELECT * FROM weekly_form WHERE user_id = '{$user_id}'" );
$user_symptoms             = [];

while ( $row = mysqli_fetch_assoc( $result ) ) {
	$result2  = $con->query( "SELECT s.* FROM user_symptoms AS us JOIN symptoms AS s ON us.symptom_id = s.id WHERE form_id = '{$row['id']}'" );
	$entry_id = $row['id'];

	while ( $row2 = mysqli_fetch_assoc( $result2 ) ) {
		$user_symptoms[] = $row2['id'];
	}
	$row['predictions'] = predict( $user_symptoms );
	arsort( $row['predictions'] );

	$line_chart_data['datasets'][] = [
		"data"                 => array_map( function ( $a ) {
			return number_format( $a * 100, 2 );
		}, array_values( $row['predictions'] ) ),
		"fillColor"            => "rgba(220,220,220,0.2)",
		"strokeColor"          => "rgba(220,220,220,1)",
		"pointColor"           => "rgba(220,220,220,1)",
		"pointStrokeColor"     => "#fff",
		"pointHighlightFill"   => "#fff",
		"pointHighlightStroke" => "rgba(220,220,220,1)",
	];
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
		<?php if($user['role'] == 'admin') { ?>
        <li><a class="app-menu__item" href="users.php"><i class="app-menu__icon fa fa-users"></i><span
                        class="app-menu__label">Users</span></a></li>
		<li><a class="app-menu__item" href="diseases.php"><i class="app-menu__icon fa fa-users"></i><span
                        class="app-menu__label">Diseases</span></a></li>
		<?php } ?>


    </ul>
</aside>
<main class="app-content">
    <!-- <div class="app-title">
	  <div>
		<h1><i class="fa fa-dashboard"></i> Dashboard</h1>
		<p>A free and modular admin template</p>
	  </div>
	  <ul class="app-breadcrumb breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
		<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
	  </ul>
	</div> -->

    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">Health Comparison</h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">Your Health Now</h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">Overall Predictions</h3>
                <div class="embed-responsive embed-responsive-16by9">
                   <canvas class="embed-responsive-item" id="prediction-bar-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="tile">
        <h3 class="tile-title">Chat</h3>
        <div class="messanger">
            <div class="messages">
                <div class="message"><img src="">
                    <p class="info">Hello there!<br>Good Morning</p>
                </div>
                <div class="message me"><img src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg">
                    <p class="info">Hi<br>Good Morning</p>
                </div>
                <div class="message"><img src="">
                    <p class="info">How are you?</p>
                </div>
                <div class="message"><img src="">
                    <p class="info">How are you feeling today?<br>Fill up your routine form<br>Stay Hydrated ;)</p>
                </div>
                <div class="message me"><img src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg">
                    <p class="info">I'm Fine.</p>
                </div>
            </div>
            <div class="sender">
                <input type="text" placeholder="Send Message">
                <button class="btn btn-primary" type="button"><i class="fa fa-lg fa-fw fa-paper-plane"></i></button>
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
<script type="text/javascript" src="js/plugins/chart.js"></script>
<script type="text/javascript">
    var pdata = <?= json_encode( $pie_data ); ?>;
    var chart_data = <?= json_encode( $bar_chart_data ); ?>;
    var line_data = <?= json_encode( $line_chart_data ); ?>;

    var ctxl = $("#lineChartDemo").get(0).getContext("2d");
    var lineChart = new Chart(ctxl).Line(line_data);

    var ctxb = $("#prediction-bar-chart").get(0).getContext("2d");
    var barChart = new Chart(ctxb).Bar(chart_data);

    var ctxp = $("#pieChartDemo").get(0).getContext("2d");
    var pieChart = new Chart(ctxp).Pie(pdata);
</script>
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
