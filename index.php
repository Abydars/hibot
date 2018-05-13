<?php
require_once( 'config.php' );

if ( ! isset( $_SESSION['user_id'] ) ) {
	header( "location: page-login.php" );
}

$symptoms = [];
$user_id  = $_SESSION['user_id'];
$user     = getUser( $user_id );
$result   = $con->query( "SELECT * FROM weekly_form WHERE user_id = '{$user_id}'" );

while ( $row = mysqli_fetch_assoc( $result ) ) {
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
    <title>HI BOT</title>
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
        <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon fa fa-pie-chart"></i><span
                        class="app-menu__label">Charts</span></a></li>


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
        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                <div class="info">
                    <h4>Users</h4>
                    <p><b>5</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                <div class="info">
                    <h4>Likes</h4>
                    <p><b>25</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                <div class="info">
                    <h4>Uploades</h4>
                    <p><b>10</b></p>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-6 col-lg-3">
          <div class="widget-small danger coloured-icon"><i class="icon fa fa-star fa-3x"></i>
            <div class="info">
              <h4>Stars</h4>
              <p><b>500</b></p>
            </div>
          </div>
        </div> -->
    </div>
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
    <!-- <div class="row">
	  <div class="col-md-6">
		<div class="tile">
		  <h3 class="tile-title">Features</h3>
		  <ul>
			<li>Built with Bootstrap 4, SASS and PUG.js</li>
			<li>Fully responsive and modular code</li>
			<li>Seven pages including login, user profile and print friendly invoice page</li>
			<li>Smart integration of forgot password on login page</li>
			<li>Chart.js integration to display responsive charts</li>
			<li>Widgets to effectively display statistics</li>
			<li>Data tables with sort, search and paginate functionality</li>
			<li>Custom form elements like toggle buttons, auto-complete, tags and date-picker</li>
			<li>A inbuilt toast library for providing meaningful response messages to user's actions</li>
		  </ul>
		  <p>Vali is a free and responsive admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.</p>
		  <p>Vali is is light-weight, expendable and good looking theme. The theme has all the features required in a dashboard theme but this features are built like plug and play module. Take a look at the <a href="http://pratikborsadiya.in/blog/vali-admin" target="_blank">documentation</a> about customizing the theme colors and functionality.</p>
		  <p class="mt-4 mb-4"><a class="btn btn-primary mr-2 mb-2" href="http://pratikborsadiya.in/blog/vali-admin" target="_blank"><i class="fa fa-file"></i>Docs</a><a class="btn btn-info mr-2 mb-2" href="https://github.com/pratikborsadiya/vali-admin" target="_blank"><i class="fa fa-github"></i>GitHub</a><a class="btn btn-success mb-2" href="https://github.com/pratikborsadiya/vali-admin/archive/master.zip" target="_blank"><i class="fa fa-download"></i>Download</a></p>
		</div>
	  </div>
	  <div class="col-md-6">
		<div class="tile">
		  <h3 class="tile-title">Compatibility with frameworks</h3>
		  <p>This theme is not built for a specific framework or technology like Angular or React etc. But due to it's modular nature it's very easy to incorporate it into any front-end or back-end framework like Angular, React or Laravel.</p>
		  <p>Go to <a href="http://pratikborsadiya.in/blog/vali-admin" target="_blank">documentation</a> for more details about integrating this theme with various frameworks.</p>
		  <p>The source code is available on GitHub. If anything is missing or weird please report it as an issue on <a href="https://github.com/pratikborsadiya/vali-admin" target="_blank">GitHub</a>. If you want to contribute to this theme pull requests are always welcome.</p>
		</div>
	  </div>
	</div> -->
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
