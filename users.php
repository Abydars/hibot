<?php
require_once( 'config.php' );

if ( ! isset( $_SESSION['user_id'] ) ) {
	header( "location: page-login.php" );
}

$symptoms = [];
$reports = $user_symptoms = $bar_chart_data = [];
$user_id  = $_SESSION['user_id'];
$user     = getUser( $user_id );
$users	  = getUser();
$edit_user = false;

if ( $user['role'] != 'admin' ) {
	header( "location: page-login.php" );
}

if(isset($_GET['id'])) {
	$edit_user = getUser($_GET['id']);
	$result  = $con->query( "SELECT * FROM weekly_form WHERE user_id = '{$_GET['id']}'" );

	while ( $row = mysqli_fetch_assoc( $result ) ) {
		$result2  = $con->query( "SELECT s.* FROM user_symptoms AS us JOIN symptoms AS s ON us.symptom_id = s.id WHERE form_id = '{$row['id']}'" );
		$entry_id = $row['id'];

		while ( $row2 = mysqli_fetch_assoc( $result2 ) ) {
			$row['symptoms'][] = $row2['name'];
			$user_symptoms[]   = $row2['id'];
		}

		$row['filename'] = false;

		$result3 = $con->query( "SELECT filename FROM user_uploads WHERE form_id = '{$entry_id}';" );
		while ( $row3 = mysqli_fetch_assoc( $result3 ) ) {
			$row['filename'] = $row3['filename'];
		}

		$row['symptoms']    = implode( ', ', $row['symptoms'] );
		$row['predictions'] = predict( $user_symptoms );
		arsort( $row['predictions'] );
		$reports[ $row['submitted_date'] ] = $row;

		$bar_chart_data[ $entry_id ]['labels']     = array_keys( $row['predictions'] );
		$bar_chart_data[ $entry_id ]['datasets'][] = [
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
	};
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
<header class="app-header"><a class="app-header__logo" href="index.php">ED System</a>
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
	<?php if(isset($_GET['id'])) { ?>
	<div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title"><?= $edit_user['name'] ?>'s Profile</h3>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-md-12">
            <div>
				<?php foreach ( array_keys( $reports ) as $date ) { ?>
                    <button type='button' class="btn btn-info btn-report"
                            data-content-id="#report-<?php echo $date; ?>"><?= $date ?></button>
				<?php } ?>
            </div>
            <div class="clearfix"></div>
			<?php foreach ( $reports as $date => $report ) { ?>
                <div id='report-<?= $date ?>' class="report-content" style="display: none;">
					<?php foreach ( $report as $key => $val ) {
						$labels = [
							'feeling'         => 'Feelings',
							'is_weird_health' => 'Feeling Bad',
							'symptoms'        => 'Symptoms',
							'submitted_date'  => 'Submitted On'
						];

						if ( ! isset( $labels[ $key ] ) ) {
							continue;
						}

						if ( $key == 'is_weird_health' ) {
							$val = $val == 0 ? "No" : "Yes";
						}
						?>
                        <div><strong><?= $labels[ $key ] ?></strong>: <span><?= $val ?></span></div>
					<?php } ?>
                    <br/>
					<?php if ( $report['filename'] ) { ?>
                        <div><a target="_blank" href="<?= $report['filename'] ?>" class="btn btn-primary">View
                                Attachment</a></div>
					<?php } ?>
                    <br/>
                    <h4>Predictions</h4>
                    <br/>
                    <div class="tile">
                        <div class="embed-responsive embed-responsive-16by9">
                            <canvas class="embed-responsive-item prediction-bar-chart"
                                    data-id="<?= $report['id'] ?>"></canvas>
                        </div>
                    </div>
					<?php
						$i = 0;
						foreach ( $report['predictions'] as $disease => $prediction ) {
									if($i++ > 4)
										break;
						?>
                        <div class="clearfix" style="display: block;">
                            <div class="pull-left">
                                <h5><?= $disease ?></h5>
                            </div>
                            <div class="pull-right">
                                <h5><?= number_format( 100 * $prediction, 2 ) ?>%</h5>
                            </div>
                        </div>
					<?php } ?>
                </div>
			<?php } ?>
        </div>
    </div>
	<?php } else { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Users</h3>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-md-12">
			<table class="table table-bordered table-dark" width="100%">
				<thead>
					<tr>
						<th>Name</th>
						<th>Phone</th>
						<th>Username/Email</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($users as $user) { ?>
						<tr>
							<td><?= $user['name'] ?></td>
							<td><?= $user['phone'] ?></td>
							<td><?= $user['username'] ?></td>
							<td><a href="users.php?id=<?= $user['user_id'] ?>" class="btn btn-success">Show</a></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php } ?>
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
    jQuery(function ($) {
        var data = <?php echo json_encode( $bar_chart_data ); ?>;

        $('.btn-report').on('click', function (event) {
            var content_id = $(this).data('content-id');

            $('.report-content').slideUp();
            $(content_id).slideDown(function () {
                $(content_id).find(".prediction-bar-chart").each(function () {
                    var id = $(this).data('id');
                    var chart_data = data[id];
                    var ctxb = $(this).get(0).getContext("2d");
                    var barChart = new Chart(ctxb).Bar(chart_data);
                });
            });
        });
    });
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
