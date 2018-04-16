<?php
require_once('config.php');

if(!isset($_SESSION['user_id']))
  header("location: page-login.php");

$user_id = $_SESSION['user_id'];
$error = $success = false;

if(isset($_POST['is_submitted'])) {
  unset($_POST['is_submitted']);
  foreach($_POST as $key => $value) {
    if(is_array($value))
      $value = json_encode($value);

      $query = "SELECT * FROM user_meta WHERE meta_key = '{$key}' AND user_id = '{$user_id}';";
      $result = mysqli_query($con, $query);
      $meta_exists = $result->num_rows > 0;

    $query = "INSERT INTO user_meta(meta_key, meta_value, user_id) VALUES('{$key}', '{$value}', '{$user_id}');";
    if($meta_exists) {
      $query = "UPDATE user_meta SET meta_value = '{$value}' WHERE meta_key = '{$key}' AND user_id = '{$user_id}';";
    }

    $created = $con->query($query);
    if(!$created) {
      $error = 'Failed to update ' . $key;
    }
  }
  $success = 'Updated successfully.';
}

$metas = getUserMetas($user_id);
//if($_POST)
//{echo '<pre>';var_dump($_POST);exit;}
function print_val($key, $index = 0, $detailed = false) {
  global $metas;

  if($detailed && isset($metas[$detailed][$key])) {
    if(is_array($metas[$detailed][$key]))
      return $metas[$detailed][$key][$index];

    return $metas[$detailed][$key];
  }

  if(isset($metas[$key]) && is_array($metas[$key]) && isset($metas[$key][$index]))
    return $metas[$key][$index];

  if(isset($metas[$key]))
    return $metas[$key];

  return "";
}

function isChecked($key, $value, $detailed = false, $index = 0) {
  global $metas;

  if(isset($metas[$detailed][$key])) {
    if(is_array($metas[$detailed][$key]) && isset($metas[$detailed][$key][$index])) {
      if(in_array($value, $metas[$detailed][$key])) {
        return "checked";
      }
    } else if($metas[$detailed][$key] === $value) {
      return "checked";
    }
  }

  if(isset($metas[$key])) {
    if($metas[$key] === $value) {
      return "checked";
    }
  }
  return "";
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
  <title>HI BOT</title>
</head>
<body>
  <section class="material-half-bg">
    <div class="cover"></div>
  </section>
  <section class="login-content">
    <div class="logo">
      <h1>Health Intelligent Bot</h1>
    </div>
    <div class="col-md-6">
      <div class="tile">
        <h3 class="tile-title">Health Questionnaire</h3>
        <?php if($error) { ?><div class="alert alert-danger"><?php echo $error; ?></div><?php } ?>
        <?php if($success) { ?><div class="alert alert-success"><?php echo $success; ?></div><?php } ?>
        <form method="post">
        <div class="tile-body">
            <div class="form-group">
              <label class="control-label">Name</label>
              <input class="form-control" name="reg-name" value="<?= print_val('reg-name') ?>" type="text" placeholder="Enter full name">
            </div>
            <div class="form-group">
              <label class="control-label">Date Of Birth</label>
              <input class="form-control" value="<?= print_val('reg-dob') ?>"id="reg-dob" name="reg-dob" type="text" placeholder="Select Date Of Birth">
            </div>
            <div class="form-group">
              <label class="control-label">Address</label>
              <textarea class="form-control" name="reg-address" rows="4" placeholder="Enter your address"> <?= print_val('reg-address') ?></textarea>
            </div>
            <div class="form-group">
              <label class="control-label">City</label>
              <input class="form-control col-md-4" name="reg-city" type="text" placeholder="Enter City" value="<?= print_val('reg-city') ?>">
            </div>
            <div class="form-group">
              <label class="control-label">Country</label>
              <input class="form-control col-md-4" value="<?= print_val('reg-country') ?>" name="reg-country" type="text" placeholder="Enter Country">
            </div>
            <div class="form-group">
              <label class="control-label">Phone Number</label>
              <input class="form-control col-md-4" name="reg-phone" type="number"  value="<?= print_val('reg-phone') ?>" placeholder="Enter Your CellPhone Number">
            </div>
            <div class="form-group">
              <label class="control-label">Your Age</label>
              <input class="form-control col-md-4" name="reg-age" type="number" placeholder="Enter Your Age" value="<?= print_val('reg-age') ?>">
            </div>
            <div class="form-group">
              <label class="control-label">Blood Group</label>
              <input class="form-control col-md-4" value="<?= print_val('reg-bloodgroup') ?>" name="reg-bloodgroup" type="text" placeholder="Enter Your Blood Group">
            </div>
            <div class="form-group">
              <label class="control-label">Your Previous Physicians</label>
              <input class="form-control col-md-4" name="physicians[]" value="<?= print_val('physicians', 0) ?>" type="text" placeholder="Enter Name Of Physician">
              <br/>
              <input class="form-control col-md-4" name="physicians[]" value="<?= print_val('physicians', 1) ?>" type="text" placeholder="Enter Name Of Physician">
              <br/>
              <input class="form-control col-md-4" name="physicians[]" value="<?= print_val('physicians', 2) ?>" type="text" placeholder="Enter Name Of Physician">
            </div>
            <div class="form-group">
              <label class="control-label">On a scale of 1 to 5, how healthy your are?</label>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-healthy" value="1" <?= isChecked('reg-healthy', "1") ?>>1
                </label>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-healthy" value="2" <?= isChecked('reg-healthy', "2") ?>>2
                </label>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-healthy" value="3" <?= isChecked('reg-healthy', "3") ?>>3
                </label>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-healthy" value="4" <?= isChecked('reg-healthy', "4") ?>>4
                </label>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-healthy" value="5" <?= isChecked('reg-healthy', "5") ?>>5
                </label>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label">Gender</label>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-gender" value="male" <?= isChecked('reg-gender', "male") ?>>Male
                </label>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-gender" value="female" <?= isChecked('reg-gender', "female") ?>>Female
                </label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label">Have You Ever Had Any Injury Before?</label>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-injury" value="yes" <?= isChecked('reg-injury', "yes") ?>>Yes
                </label>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="reg-injuryreason" rows="4" placeholder="Enter Details of Your Injury"><?= print_val('reg-injuryreason') ?></textarea>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-injury" value="no" <?= isChecked('reg-injury', "no") ?>>No
                </label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label">Do You Have Any Disorder , Sickness Or Disease?</label>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-disease" value="yes" <?= isChecked('reg-disease', "yes") ?>>Yes
                </label>
              </div>
            </div>
              <div class="form-group">
                <input class="form-control col-md-5" name="diseases[]" value="<?= print_val('diseases', 0) ?>" type="text" placeholder="Enter The Name Of Disease Eg: Sugar">
                <br/>
                <input class="form-control col-md-5" name="diseases[]" value="<?= print_val('diseases', 1) ?>" type="text" placeholder="Enter The Name Of Disorder Eg: Bp">
                <br/>
                <input class="form-control col-md-5" name="diseases[]" value="<?= print_val('diseases', 2) ?>" type="text" placeholder="Enter The Name Of Disease Eg: cancer">
                <br/>
                <input class="form-control col-md-5" name="diseases[]" value="<?= print_val('diseases', 3) ?>" type="text" placeholder="Enter The Name Of Disease Eg: Migraine">
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="reg-injury" value="no" <?= isChecked('reg-injury', "no") ?>>No
                </label>
              </div>
            <div class="form-group">
              <label class="control-label">Your Frequently Used Medicine Name</label>
              <input class="form-control col-md-4" name="medicines[]" value="<?= print_val('medicines', 0) ?>" type="text" placeholder="Enter Name of medicine">
              <br/>
              <input class="form-control col-md-4" name="medicines[]" value="<?= print_val('medicines', 1) ?>" type="text" placeholder="Enter Name of medicine">
              <br/>
              <input class="form-control col-md-4" name="medicines[]" value="<?= print_val('medicines', 2) ?>" type="text" placeholder="Enter Name of medicine">
            </br>
            <textarea class="form-control" name="reg-medicinereason" rows="4" placeholder="Reason To Use These"><?= print_val('reg-medicinereason') ?></textarea>
          </div>
          <div class="form-group">
              <label class="control-label"><strong>ClICK ON BUTTON TO VIEW PARTICULAR FORM</strong></label></br>
            <button type='button' class="btn btn-info" id='hideshowchest'>CHEST</button>
            <button type='button' class="btn btn-info" id='hideshowheart'>HEART</button>
            <button type='button' class="btn btn-info" id='hideshowkidney'>KIDNEY</button>
            <button type='button' class="btn btn-info" id='hideshowsmoker'>SMOKERS</button>
            <button type='button' class="btn btn-info" id='hideshowsugar'>SUGAR</button>
          </div>
              <!-- CHEST START -->
          <div class="form-group" id="contentchest" style="display:none">


          <div class="form-group">
            <label class="control-label">Have you had chest discomfort?</label>
            <textarea class="form-control" name="chest[discomfort]" rows="4" placeholder="Detail here"></textarea>
          </div>
          <div class="form-group">
            <label class="control-label">When did you first experience chest discomfort?</label>
            <textarea class="form-control" name="chest[experience]" rows="4" placeholder="Detail here"></textarea>
          </div>

          <div class="form-group">
            <label class="control-label">How frequently does the chest discomfort occur? </label>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[occur]" value="onceaday" <?= isChecked('occur', "onceaday", 'chest') ?>>Once a day
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[occur]" value="onceaweek <?= isChecked('occur', "onceaweek", 'chest') ?>" >Once a week
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[occur]" value="onceamonth" <?= isChecked('occur', "onceamonth", 'chest') ?>>Once a month
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[occur]" value="morefrequently" <?= isChecked('occur', "morefrequently", 'chest') ?>>More frequently
              </label>
            </div>
          </div>


          <div class="form-group">
            <label class="control-label">Check the following words that describe your chest discomfort:</label>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[describe]" value="sharp" <?= isChecked('describe', "sharp", 'chest') ?>>Sharp
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[describe]" value="burning" <?= isChecked('describe', "burning", 'chest') ?>>Burning
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[describe]" value="tightness" <?= isChecked('describe', "tightness", 'chest') ?>>Tightness
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[describe]" value="stabbing" <?= isChecked('describe', "stabbing", 'chest') ?>>Stabbing
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[describe]" value="fullness" <?= isChecked('describe', "fullness", 'chest') ?>>Fullness
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[describe]" value="crushing" <?= isChecked('describe', "crushing", 'chest') ?>>Crushing
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[describe]" value="aching" <?= isChecked('describe', "aching", 'chest') ?>>Aching
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[describe]" value="pinching" <?= isChecked('describe', "pinching", 'chest') ?>>Pinching
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label">Does this discomfort radiate from your chest to:</label>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[radiate]" value="rightarm" <?= isChecked('radiate', "rightarm", 'chest') ?>>Right arm
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[radiate]" value="leftarm" <?= isChecked('radiate', "leftarm", 'chest') ?>>Leftarm
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[radiate]" value="neck" <?= isChecked('radiate', "neck", 'chest') ?>>Neck
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" value="jaw"<?= isChecked('radiate', "jaw", 'chest') ?> type="radio" name="chest[radiate]" >Jaw
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" value="teeth" <?= isChecked('radiate', "teeth", 'chest') ?> name="chest[radiate]">Teeth
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[radiate]" value="throat" <?= isChecked('radiate', "throat", 'chest') ?>>Throat
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" value="shoulder" <?= isChecked('radiate', "shoulder", 'chest') ?> type="radio" name="chest[radiate]" >Shoulder
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" value="back" <?= isChecked('radiate', "back", 'chest') ?> name="chest[radiate]">Back
              </label>
            </div> solo
          </div>
          <div class="form-group">
            <label class="control-label">What activities bring on this Discomfort?</label>
            <textarea class="form-control" name="chest[activities]" rows="4" placeholder="Detail here"><?= print_val('chest[activities]')?></textarea>
          </div>
          <div class="form-group">
            <label class="control-label">Have you experienced this discomfort at rest?</label>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[rest]" value="yes">Yes
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[rest]" value="no">No
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label">How long does this discomfort last?</label>

            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[long]" value="less1min">Less than 1 minute
              </label>
            </div>


            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[long]" value="morethan5min">1-5 minutes
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[long]" value="morethan30min">6-30 minutes
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="chest[long]" value="1hr">More than 1hour
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label">What do you do to relieve this discomfort?</label>
            <textarea class="form-control" name="chest[relieve]" rows="4" placeholder="Detail here"></textarea>
          </div>



        <div class="form-group">
          <label class="control-label">Have you ever been hospitalized because of this discomfort?</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="chest[been]" value="yes">Yes
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="chest[been]" value="no">No
            </label>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">Does this discomfort restrict your activity?</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="chest[ristrict]" value="yes">Yes
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" value="no" name="chest[ristrict]">No
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">ON a scale of 1 to 5 rate your chest discomfort(5 being the most intense and 1 being the least intense)</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="chest[intense]" value="1">1
            </label>
          </div>

          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="chest[intense]" value="2">2
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="chest[intense]" value="3">3
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="chest[intense]" value="4">4
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="chest[intense]" value="5">5
            </label>
          </div>
        </div>

</div>
          <!-- CHEST Questionnaire END -->
          <!-- Heart Questionnaire Start -->

          <div class="form-group" id="contentheart" style="display:none">
        <div class="form-group">
          <label class="control-label">Do you have abnormal heart beat</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[beat]" value="yes">Yes
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[beat]" value="no">No
            </label>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">When did you first notice this abnormal heartbeat?</label>
          <input class="form-control" id="reg-select" name="heart[notice]" type="text" placeholder="Select Date">
        </div>
        <div class="form-group">
          <label class="control-label">Does your heart beat"Too fast"?</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[fast]" value="yes">Yes
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[fast]" value="no">No
            </label>
          </div>
        </div>


        <div class="form-group">
          <label class="control-label">Does your heart beat"Too slow"?</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[slow]" value="yes">Yes
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[slow]" value="no">No
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">Does your heart "skip beats"?</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[skip]" value="yes">Yes
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[skip]" value="no">No
            </label>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">Does your heart beat:</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" value="regularly" type="radio" name="heart[beat]">Regularly
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[beat]" value="irregularly">Irregularly
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">Does this start:</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[start]" value="suddenly">Suddenly
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[start]" value="gradually">Gradually
            </label>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">Does this stop:</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[stop]" value="stopsuddenly">Suddenly
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[stop]" value="stopgradually">Gradually
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">How often does this occur?</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[often]" value="daily">Daily
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[often]" value="weekly">Weekly
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[often]" value="monthly">Monthly
            </label>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">How long does it last when it occurs?</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[last]" value="sec">Seconds
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[last]" value="min">Minutes
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[last]" value="hours">Hours
            </label>
          </div>
        </div>
        <div class="form-group" style="display:none">
          <label class="control-label">ON a scale of 1 to 5 rate your chest discomfort(5 being the most intense and 1 being the least intense)</label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[intense]" value="1">1
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[intense]" value="2">2
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[intense]" value="3">3
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[intense]" value="4">4
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[intense]" value="5">5
            </label>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">Are there activities, foods, etc. that can bring it on? </label>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[bring]" value="yes">Yes
            </label>
          </div>

          <input class="form-control col-md-5" name="heart[bring1]" type="text" placeholder="Enter Activity Or Food">
        </div>
            <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="radio" name="heart[bring]" value="no">No
            </label>
          </div>
          </br>
          <div class="form-group">
            <label class="control-label">Check Symptoms That Have Occurred</label>
          </div>
            <div class="form-group">
              <div class="animated-checkbox">
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="chestpain" <?= isChecked('symtoms', 'chestpain', 'heart') ?>><span class="label-text">Chest Pain</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="dizz" <?= isChecked('symtoms', 'dizz', 'heart') ?>><span class="label-text">Dizziness</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="sweaty" <?= isChecked('symtoms', 'sweaty', 'heart') ?>><span class="label-text">Sweaty</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="breathing" <?= isChecked('symtoms', 'breathing', 'heart') ?>><span class="label-text">Breathing difficulty</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="discomfort" <?= isChecked('symtoms', 'discomfort', 'heart') ?>><span class="label-text">Discomfort</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="headache" <?= isChecked('symtoms', 'headache', 'heart') ?>><span class="label-text">Headache</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="stomach" <?= isChecked('symtoms', 'stomach', 'heart') ?>><span class="label-text">Sick Stomach</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="insomnia" <?= isChecked('symtoms', 'insomnia', 'heart') ?>><span class="label-text">Insomnia</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="bodypain" <?= isChecked('symtoms', 'bodypain', 'heart') ?>><span class="label-text">Body Pain</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="lowenergy" <?= isChecked('symtoms', 'lowenergy', 'heart') ?>><span class="label-text">Energy Loss</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="lowbp" <?= isChecked('symtoms', 'lowbp', 'heart') ?>><span class="label-text">Low Blood Pressure</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="hibp" <?= isChecked('symtoms', 'hibp', 'heart') ?>><span class="label-text">High Blood Pressure</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="fever" <?= isChecked('symtoms', 'fever', 'heart') ?>><span class="label-text">Fever</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="cough" <?= isChecked('symtoms', 'cough', 'heart') ?>><span class="label-text">Cough Problem</span> 	&nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="heart[symtoms][]" value="none" <?= isChecked('symtoms', 'none', 'heart') ?>><span class="label-text">None</span> 	&nbsp;&nbsp;
                </label>

              </div>
            </div>
        </div>

        <!-- HEART Questionnaire END -->

        <!-- KIDNEY Questionnaire START -->
        <div class="form-group" id="contentkidney" style="display:none">
        <div class="form-group">
                    <label class="control-label">Have you ever been told you have kidney dieases?</label>
                  </div>
                   <div class="form-check">

                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="kidney[kidney]" value="yes">Yes
                          </label>
                      </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="kidney[kidney]" value="no">NO
                          </label>
                        </div>
                        <div class="form-group">
                    <label class="control-label">How long has it been since you were first diagnosed?(Select one)</label>
                  </div>
                   <div class="form-check">

                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="kidney[kidneydiagnosed]" value="1year"> 1year
                          </label>
                      </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="kidney[kidneydiagnosed]" value="1-3">1-3 years
                          </label>
                        </div>
                         <div class="form-check">

                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="kidney[kidneydiagnosed]" value="3-5">3-5 years
                          </label>
                      </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="kidney[kidneydiagnosed]" value="5-10">5-10 years
                          </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="kidney[kidneydiagnosed]" value="10years">10 years
                          </label>
                        </div>

                        <div class="form-group">
                    <label class="control-label">How was this diagnosed (Check those that apply)</label>
              </div>
                  <div class="form-group">
                      <div class="animated-checkbox">
                        <label>
                          <input type="checkbox" name="kidney[kindeysymptomdiagnosed][]" value="bloodtest" <?= isChecked('kindeysymptomdiagnosed', 'bloodtest', 'kidney') ?>><span class="label-text">blood test(elevated creatinine)</span>  &nbsp;&nbsp;
                        </label>
                         <label>
                          <input type="checkbox" name="kidney[kindeysymptomdiagnosed][]" value="protein" <?= isChecked('kindeysymptomdiagnosed', 'protein', 'kidney') ?>><span class="label-text">protein in the urine</span>  &nbsp;&nbsp;
                        </label>
                         <label>
                          <input type="checkbox" name="kidney[kindeysymptomdiagnosed][]" value="other" <?= isChecked('kindeysymptomdiagnosed', 'other', 'kidney') ?>><span class="label-text">other:</span>  &nbsp;&nbsp;
                        </label>
                          </div>
                        </div>

                    <div class="form-group">
                        <label class="control-label">Have you been told what caused your kidney disease(e.g. diabete,highblood pressure, glomerulonephritis, kidney stones, medication, related to surgery or serve medical illness)?</label>
                      </div>
                      <div class="form-group">
                         <textarea class="form-control" name="kidney[kidneydieases]" rows="4" placeholder="Detail here"></textarea>
                       </div>

                       <div class="form-group">
                        <label class="control-label">Have you ever had any of the following(Check if yes):</label>
                        </div>
                        <div class="form-group">
                      <div class="animated-checkbox">
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="kidneyproblem" <?= isChecked('symptoms', 'kidneyproblem', 'kidney') ?>><span class="label-text">kidney problems at birth or in childhood?</span>  &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="hospitalized" <?= isChecked('symptoms', 'hospitalized', 'kidney') ?>><span class="label-text">hospitalized due to kidney faliure?</span>  &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="anotherreason" <?= isChecked('symptoms', 'anotherreason', 'kidney') ?>><span class="label-text">kidney faliure while hospitalized for another reason?</span>   &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="stones" <?= isChecked('symptoms', 'stones', 'kidney') ?>><span class="label-text">kidney stones?</span>  &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="bladder" <?= isChecked('symptoms', 'bladder', 'kidney') ?>><span class="label-text">bladder or kidney infection?</span>   &nbsp;&nbsp;
                         </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="difficulty" <?= isChecked('symptoms', 'difficulty', 'kidney') ?>><span class="label-text">difficulty emptying your bladder?</span>   &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="urologic" <?= isChecked('symptoms', 'urologic', 'kidney') ?>><span class="label-text">Bladder or other urologic surgery?</span>   &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="Radiation" <?= isChecked('symptoms', 'Radiation', 'kidney') ?>><span class="label-text">Radiation to the abdomen or pelvis</span>  &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="Chemothrapy" <?= isChecked('symptoms', 'Chemothrapy', 'kidney') ?>><span class="label-text">Chemothrapy for cancer?</span>   &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="family" <?= isChecked('symptoms', 'family', 'kidney') ?>><span class="label-text">family history of kidney disease?</span>  &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="urine" <?= isChecked('symptoms', 'urine', 'kidney') ?>><span class="label-text">Blood in the urien?</span>   &nbsp;&nbsp;
                        </label>
                        <label>
                          <input type="checkbox" name="kidney[symptoms][]" value="foamurine" <?= isChecked('symptoms', 'foamurine', 'kidney') ?>><span class="label-text">Foamy urien?</span>  &nbsp;&nbsp;
                        </label>
                        <label>
                        </div>

                        <div class="form-group">
                        <label class="control-label">If you answered yes to any of the above, please enter more details here:</label>
                      </div>
                      <div class="form-group">
                         <textarea class="form-control" name="kidney[kidneydetails]" rows="4" placeholder="Detail here"></textarea>
                       </div>
                       </div>
                     </div>

                       <!-- KIDNEY Questionnaire END -->


                      <!-- SMOKER Questionnaire Start -->
                      <div class="form-group" id="contentsmoker" style="display:none">



                   <div class="form-group">
            <label class="control-label">Do you monitor your blood pressure?</label>
          </div>

                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[monitorbp]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[monitorbp]">NO
                  </label>
                </div>
                <div class="form-group">
            <label class="control-label">Do you currently smoke,or are you a former smoker?</label>
          </div>

                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[smoke]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[smoke]">NO
                  </label>
                </div>
                <div class="form-group">
            <label class="control-label">Do you smoke cigarettes?</label>
          </div>
                   <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[cigarettes]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[cigarettes]">NO
                  </label>
                </div>
                <div class="form-group">
            <label class="control-label">Do you smoke cigars?</label>
          </div>
                 <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[cigars]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[cigras]">NO
                  </label>
                </div>
                <div class="form-group">
            <label class="control-label">Do you chew smokeless tobacco?</label>
          </div>
             <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[chew]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[chew]">NO
                  </label>
                </div>

                <div class="form-group">
            <label class="control-label"># of packsday</label>
          </div>

                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[packsday]">< 1
                  </label>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[packsday]">2 or more
                  </label>
                </div>
                <div class="form-group">
            <label class="control-label"># years smoked</label>
          <input class="form-control" name="smoker[smoked]" type="text" placeholder="detail here">
              </div>
               <div class="form-group">
            <label class="control-label">year smoking started</label>
      <input class="form-control" name="smoker[started]" type="text" placeholder="detail here">
              </div>
               <div class="form-group">
            <label class="control-label">year smoking stopped</label>
          <input class="form-control" name="smoker[stopped]" type="text" placeholder="detail here">
              </div>
              <div class="form-group">
            <label class="control-label">Have you ever been given advice to quit smoking?</label>
          </div>
             <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[advice]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[advice]">NO
                  </label>
                </div>
                <div class="form-group">
            <label class="control-label">Have you ever had diabetes?</label>
          </div>
             <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[diabetes]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[diabetes]">NO
                  </label>
                </div>
                <div class="form-group">
                <label class="control-label">If yes,give lenght of time:</label>
                  <input class="form-control" name="smoker[length]" type="text" placeholder="Time Duration">
               </div>

               <div class="form-group">
            <label class="control-label">Is there any history of heart disease in your family?</label>
          </div>
             <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[heartd]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[heartd]">NO
                  </label>
                </div>
                <div class="form-group">
            <label class="control-label">Do you exercise regularly?</label>
          </div>
             <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[exercise]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[exercise]">NO
                  </label>
                </div>

               <div class="form-group">
            <label class="control-label">Do you use any home exercise equipment?</label>
          </div>

                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[equipment]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[equipment]">NO
                  </label>
                </div>
                <div class="form-group">
                <label class="control-label">If yes,what equipment do you use?</label>
                 <textarea class="form-control" name="smoker[use]" rows="4" placeholder="Detail here"></textarea>
               </div>
               <div class="form-group">
            <label class="control-label">Do you modify your diet for:</label>
          </div>
          <div class="form-group">
              <div class="animated-checkbox">
                <label>
                  <input type="checkbox" name="smoker[modify][]" value="modify1" <?= isChecked('modify', 'modify1', 'smoker') ?>><span class="label-text">Sodium(salt)</span>  &nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="smoker[modify][]" value="modify2" <?= isChecked('modify', 'modify2', 'smoker') ?>><span class="label-text">Cholestrol</span>  &nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="smoker[modify][]" value="modify3" <?= isChecked('modify', 'modify3', 'smoker') ?>><span class="label-text">Saturated fat</span>   &nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="smoker[modify][]" value="modify4" <?= isChecked('modify', 'modify4', 'smoker') ?>><span class="label-text">Sugar</span>  &nbsp;&nbsp;
                </label>
                <label>
                  <input type="checkbox" name="smoker[modify][]" value="modify5" <?= isChecked('modify', 'modify5', 'smoker') ?>><span class="label-text">Calories</span>   &nbsp;&nbsp;
                </label>
            </div>
          </div>

 <div class="form-group">
            <label class="control-label">Do you wish to speak with a dietitian?</label>
          </div>

                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[dietition]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="smoker[dietition]">NO
                  </label>
                </div>
              </div>
<!-- SMOKER Questionnaire End -->

<div class="form-group" id="contentsugar" style="display:none">
  <div class="form-group">
                <label class="control-label">When were you first diagnosed with diabetes?</label>
                <input class="form-control" id="reg-diabetes" name="sugar[diabetes]" type="text">
              </div>

               <div class="form-group">
                <label class="control-label">Please list all medication(s) you take, including dosage:</label>
                <textarea class="form-control" name="sugar[list]" rows="4" placeholder="detail here"></textarea>
              </div>
              <div class="form-group">
                <label class="control-label">Where do you give your injection?</label>
                 <input class="form-control" name="sugar[injection]" type="text">
              </div>
              <div class="form-group">
                <label class="control-label">Do you exercise regularly?</label>
              </div>
               <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[exercise1]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[exercise1]">NO
                  </label>
                </div>
                 <div class="form-group">
                <label class="control-label">Types of exercise:</label>
                <textarea class="form-control" name="sugar[type]" rows="2" placeholder="detail here"></textarea>
              </div>
               <div class="form-group">
                <label class="control-label">Any complications of diabetes </label>
                <input class="form-control" name="sugar[complications]" type="text">
              </div>
               <div class="form-group">
                <label class="control-label">Do you smoke?</label>
              </div>
               <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[smoking]">Daily
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[smoking]">Occasionly
                  </label>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[smoking]">Never
                  </label>
                </div>
                <div class="form-group">
                <label class="control-label">Do you check your blood sugar?</label>
              </div>
               <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[sugar]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[sugar]">NO
                  </label>
                </div>
                 <div class="form-group">
                <label class="control-label">Do you check your urine for ketones?</label>
              </div>
               <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[urien]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[urien]">NO
                  </label>
                </div>
                 <div class="form-group">
                <label class="control-label">Have you had low blood sugar lately?</label>
              </div>
               <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[low]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[low]">NO
                  </label>
                </div>
                 <div class="form-group">
                <label class="control-label">Have you had symptoms of high blood sugar lately?</label>
              </div>
               <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[sugarblood]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[sugarblood]">NO
                  </label>
                </div>
                 <div class="form-group">
                <label class="control-label">Have you had problems with infections?</label>
              </div>
               <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[infections]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[infections]">NO
                  </label>
                </div>
                 <div class="form-group">
                <label class="control-label">Have you been hospitalized for your diabetes?</label>
              </div>
               <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[hospatilized]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[hospatilized]">NO
                  </label>
                </div>
                 <div class="form-group">
                <label class="control-label">What is the most challenging aspect of nutrition for you?</label>
              </div>
               <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[nutrition]">Yes
                  </label>
              </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="sugar[nutrition]">NO
                  </label>
                </div>
                <div class="form-group">
                <label class="control-label">In what ways have you have adapted to having diabetes</label>

                <textarea class="form-control" name="sugar[type]" rows="2" placeholder="detail here"></textarea>
                </div>
                <div class="form-group">
                <label class="control-label">Regarding diabetes, have you recently felt?</label>
              </div>


              <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="sugar[felt][]">angry
                  </label>
                  </div>
                  <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="sugar[felt][]" value="sad" <?= isChecked('felt', 'sad', 'sugar') ?>>Sad
                  </label>
                  </div>
                  <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="sugar[felt][]" value="scared" <?= isChecked('felt', 'scared', 'sugar') ?>>Scared
                  </label>
                  </div>
                  <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="sugar[felt][]" value="stressed" <?= isChecked('felt', 'stressed', 'sugar') ?>>Stressed
                  </label>
                  </div>
                  <div class="form-group">
                <label class="control-label">What goals do you have for living well with diabetes?</label>
                   <input class="form-control" id="reg-goals" name="sugar[goals]" type="text">
              </div>
              <div class="form-group">
                <label class="control-label">Who have you seen recently for diabetes care?(Doctor's name,address,phone)</label>

                <textarea class="form-control" name="sugar[doctordetails]" rows="4" placeholder="Detail here"></textarea>
                </div>
</div>




























        <di id="content" class="form-group">
          <label class="control-label">Identity Proof</label>
          <input class="form-control" type="file">
        </div>
        <div class="form-group">
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox">I accept the terms and conditions
            </label>
          </div>
        </div>
    </div>

    <div class="tile-footer">
      <input type="hidden" name="is_submitted" value="1" />
      <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
    </div>
    </form>
  </div>
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
<script type="text/javascript" src="js/plugins/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="js/plugins/select2.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-datepicker.min.js"></script>
</html>



<script type="text/javascript">
// Login Page Flipbox control
$('.login-content [data-toggle="flip"]').click(function() {
  $('.login-box').toggleClass('flipped');
  return false;
});

jQuery(document).ready(function() {
  jQuery('#hideshowchest').on('click', function(event){
    jQuery('#contentchest').toggle('show');
  });
  jQuery('#hideshowheart').on('click', function(event){
    jQuery('#contentheart').toggle('show');
  });
  jQuery('#hideshowkidney').on('click',function(event){
    jQuery('#contentkidney').toggle('show');
  });
  jQuery('#hideshowsmoker').on('click',function(event){
    jQuery('#contentsmoker').toggle('show');
  });
  jQuery('#hideshowsugar').on('click',function(event){
    jQuery('#contentsugar').toggle('show');
  });

});



$('#reg-select').datepicker({
  format: "dd/mm/yyyy",
  autoclose: true,
  todayHighlight: true
});
$('#reg-dob').datepicker({
  format: "dd/mm/yyyy",
  autoclose: true,
  todayHighlight: true
});
</script>
