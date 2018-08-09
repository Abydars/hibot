<?php
require_once( 'config.php' );

if ( ! isset( $_SESSION['user_id'] ) ) {
	header( "location: page-login.php" );
}

$symptoms = [];
$reports = $user_symptoms = $bar_chart_data = [];
$user_id  = $_SESSION['user_id'];
$user     = getUser( $user_id );
$diseases	  = getDiseases();
$all_symptoms = getSymptoms();
$edit_disease = false;
$edit_symptoms_ids = [];
$edit_symptoms = [];

if ( $user['role'] != 'admin' ) {
	header( "location: page-login.php" );
}

if(isset($_GET['id'])) {
	$disease_id = $_GET['id'];

	if(isset($_GET['action']) && $_GET['action'] == 'delete') {
		deleteDiseaseSymptomRelation($disease_id, false);

		$query = "DELETE FROM diseases WHERE id = '{$disease_id}'";
		$deleted = $con->query($query);

		if($deleted) {
			header("location: diseases.php");
			die();
		}
	}

	if($_POST) {
		$new_symptoms = $_POST['symptoms'];
		$disease = $_POST['disease'];
		$edit_symptoms = getSymptoms($disease_id);
		$edit_symptoms_ids = array_map(function($symptom) {
			return $symptom['id'];
		}, $edit_symptoms);

		$query = "UPDATE diseases SET label = '{$disease}' WHERE id = '{$disease_id}'";
		$updated = $con->query($query);

		if($updated) {
			$intersect = array_intersect($edit_symptoms_ids, $new_symptoms);
			foreach($edit_symptoms_ids as $esi) {
				if(!in_array($esi, $intersect)) {
					deleteDiseaseSymptomRelation($disease_id, $esi);
				}
			}
			foreach($new_symptoms as $esi) {
				if(!in_array($esi, $intersect)) {
					createDiseaseSymptomRelation($disease_id, $esi);
				}
			}
		}
	}
	$edit_disease = getDiseases($_GET['id']);
	$edit_symptoms = getSymptoms($_GET['id']);
	$edit_symptoms_ids = array_map(function($symptom) {
		return $symptom['id'];
	}, $edit_symptoms);
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
<header class="app-header"><a class="app-header__logo" href="index.php">Ed Systems</a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">

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
                <h3 class="tile-title">Edit <?= $edit_disease['label'] ?>&nbsp;<a href="new-disease.php" class="btn btn-info">Add New Disease</a></h3>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-md-12">
			<form method="POST">
			<div class="form-group">
				<label>Disease</label>
				<input type="text" class="form-control" name="disease" value="<?= $edit_disease['label'] ?>" />
			</div>
			<div class="form-group">
				<label>Symptoms&nbsp;<a href="new-symptom.php" class="btn btn-info">Add New Symptom</a></label>
				<select name="symptoms[]" class="form-control" multiple>
					<?php foreach($all_symptoms as $symptom) { ?>
						<option<?= (in_array($symptom['id'], $edit_symptoms_ids) ? " selected" : "") ?> value="<?= $symptom['id'] ?>"><?= $symptom['name'] ?></option>
					<?php } ?>
				</select>
			</div>
			<input type="submit" class="btn btn-success" />
			</form>
        </div>
    </div>
	<?php } else { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Diseases&nbsp;<a href="new-disease.php" class="btn btn-info">Add New Disease</a></h3>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-md-12">
			<table class="table table-bordered table-dark" width="100%">
				<thead>
					<tr>
						<th>Disease</th>
						<th>Symtoms</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($diseases as $disease) {
						$symptoms = [];
						$symptoms_ = getSymptoms($disease['id']);
						foreach($symptoms_ as $symptom) {
							$symptoms[] = $symptom['name'];
						}
						$symptoms = implode(", ", $symptoms);
						?>
						<tr>
							<td><?= $disease['label'] ?></td>
							<td><?= $symptoms ?></td>
							<td><a href="diseases.php?id=<?= $disease['id'] ?>" class="btn btn-success">Edit</a>&nbsp;<a href="diseases.php?id=<?= $disease['id'] ?>&action=delete" class="btn btn-danger">Delete</a></td>
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
