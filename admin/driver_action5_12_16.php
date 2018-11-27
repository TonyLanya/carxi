<?php
include_once('../common.php');

require_once(TPATH_CLASS . "/Imagecrop.class.php");
$thumb = new thumbnail();

if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$sql = "select * from country where eStatus='Active'";
$db_country = $obj->MySQLSelect($sql);

//For Currency
$sql="select * from  currency where eStatus='Active'";
$db_currency=$obj->MySQLSelect($sql);

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$message_print_id=$id;
$ksuccess=isset($_REQUEST['ksuccess']) ? $_REQUEST['ksuccess'] : 0;
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$action = ($id != '') ? 'Edit' : 'Add';



$tbl_name = 'register_driver';
$script = 'Driver';

$sql = "select * from language_master where eStatus = 'Active'";
$db_lang = $obj->MySQLSelect($sql);

$sql = "select * from company where eStatus != 'Deleted' ORDER BY vCompany ASC";
$db_company = $obj->MySQLSelect($sql);

//echo '<prE>'; print_R($_REQUEST); echo '</pre>';
// set all variables with either post (when submit) either blank (when insert)
$vName = isset($_POST['vName']) ? $_POST['vName'] : '';
$iCompanyId = isset($_POST['iCompanyId']) ? $_POST['iCompanyId'] : '';
$vLastName = isset($_POST['vLastName']) ? $_POST['vLastName'] : '';
$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
$vUserName = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
$vPassword = isset($_POST['vPassword']) ? $_POST['vPassword'] : '';
$vPhone = isset($_POST['vPhone']) ? $_POST['vPhone'] : '';
$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
$iCompanyId = isset($_POST['iCompanyId']) ? $_POST['iCompanyId'] : '';
$vCode = isset($_POST['vCode']) ? $_POST['vCode'] : '';
$eStatus = isset($_POST['eStatus']) ? $_POST['eStatus'] : '';
$vLang = isset($_POST['vLang']) ? $_POST['vLang'] : '';
$vImage = isset($_POST['vImage']) ? $_POST['vImage'] : '';
$vPaymentEmail = isset($_POST['vPaymentEmail']) ? $_POST['vPaymentEmail'] : '';
$vBankAccountHolderName = isset($_POST['vBankAccountHolderName']) ? $_POST['vBankAccountHolderName'] : '';
$vAccountNumber = isset($_POST['vAccountNumber']) ? $_POST['vAccountNumber'] : '';
$vBankLocation = isset($_POST['vBankLocation']) ? $_POST['vBankLocation'] : '';
$vBankName = isset($_POST['vBankName']) ? $_POST['vBankName'] : '';
$vBIC_SWIFT_Code = isset($_POST['vBIC_SWIFT_Code']) ? $_POST['vBIC_SWIFT_Code'] : '';
$vCurrencyDriver=isset($_POST['vCurrencyDriver']) ? $_POST['vCurrencyDriver'] : '';
$vPass = $generalobj->encrypt($vPassword);

if (isset($_POST['submit'])) {
     //echo '<pre>'; print_r($_POST); exit;
     //Start :: Upload Image Script
      if(!empty($id)){
          if(SITE_TYPE=='Demo')
          {
            header("Location:driver_action.php?id=" . $id . '&success=2');
            exit;
          }
          if(isset($_FILES['vImage'])){
          $id = $_GET['id'];
          $img_path = $tconfig["tsite_upload_images_driver_path"];
          $temp_gallery = $img_path . '/';
          $image_object = $_FILES['vImage']['tmp_name'];
          $image_name = $_FILES['vImage']['name'];
          $check_file_query = "select iDriverId,vImage from register_driver where iDriverId=" . $id;
          $check_file = $obj->sql_query($check_file_query);
          if ($image_name != "") {
               $check_file['vImage'] = $img_path . '/' . $id . '/' . $check_file[0]['vImage'];

               if ($check_file['vImage'] != '' && file_exists($check_file['vImage'])) {
                      unlink($img_path . '/' . $id. '/' . $check_file[0]['vImage']);
                      unlink($img_path . '/' . $id. '/1_' . $check_file[0]['vImage']);
                      unlink($img_path . '/' . $id. '/2_' . $check_file[0]['vImage']);
                      unlink($img_path . '/' . $id. '/3_' . $check_file[0]['vImage']);
               }

               $filecheck = basename($_FILES['vImage']['name']);
               $fileextarr = explode(".", $filecheck);
               $ext = strtolower($fileextarr[count($fileextarr) - 1]);
               $flag_error = 0;
               if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp") {
                    $flag_error = 1;
                    $var_msg = "Not valid image extension of .jpg, .jpeg, .gif, .png";
               }
               if ($_FILES['vImage']['size'] > 1048576) {
                    $flag_error = 1;
                    $var_msg = "Image Size is too Large";
               }
               if ($flag_error == 1) {
                    $generalobj->getPostForm($_POST, $var_msg, "driver_action?success=0&var_msg=" . $var_msg);
                    exit;
               } else {

                    $Photo_Gallery_folder = $img_path . '/' . $id . '/';
                    if (!is_dir($Photo_Gallery_folder)) {
                         mkdir($Photo_Gallery_folder, 0777);
                    }
                    $img = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_images_member_size1"], $tconfig["tsite_upload_images_member_size2"], $tconfig["tsite_upload_images_member_size3"], '', '', '', 'Y', '', $Photo_Gallery_folder);
                    $vImage = $img;
               }
          }else{
                    $vImage = $check_file[0]['vImage'];
          }
     }
      }
//End :: Upload Image Script
	if(!empty($id)){
		$SQL1 = "SELECT 'vName' FROM $tbl_name WHERE vEmail = '$vEmail' AND iDriverId != '$id'";
		$email_exist = $obj->MySQLSelect($SQL1);
		if(count($email_exist) > 0) {
			$var_msg = "Email Id Already Exist,Select Another.";
			$generalobj->getPostForm($_POST, $var_msg, "driver_action?success=0&var_msg=" . $var_msg);
			exit;
		}
	}else {
		$SQL1 = "SELECT 'vName' FROM $tbl_name WHERE vEmail = '$vEmail'";
		$email_exist = $obj->MySQLSelect($SQL1);
		if(count($email_exist) > 0) {
			$var_msg = "Email Id Already Exist,Select Another.";
			$generalobj->getPostForm($_POST, $var_msg, "driver_action?success=0&var_msg=" . $var_msg);
			exit;
		}
	}
     $q = "INSERT INTO ";
     $where = '';
     if ($action == 'Edit') {
          $str = " ";
     } else {
          $str = " , eStatus = 'active' ";
     }
	 
	 if(SITE_TYPE=='Demo')
	 {
		  $str = " , eStatus = 'active' ";
	}
	 
     if ($id != '') {
          $q = "UPDATE ";
          $where = " WHERE `iDriverId` = '" . $id . "'";
     }


     $query = $q . " `" . $tbl_name . "` SET
		`vName` = '" . $vName . "',
		`vLastName` = '" . $vLastName . "',
		`vCountry` = '" . $vCountry . "',
		`vCode` = '" . $vCode . "',
		`vEmail` = '" . $vEmail . "',
		`vLoginId` = '" . $vEmail . "',
		`vPassword` = '" . $vPass . "',
		`iCompanyId` = '" . $iCompanyId . "',
		`vPhone` = '" . $vPhone . "',
    `vImage` = '" . $vImage . "',
    `vPaymentEmail` = '" . $vPaymentEmail . "',
    `vBankAccountHolderName` = '" . $vBankAccountHolderName . "',
    `vBankLocation` = '" . $vBankLocation . "',
    `vBankName` = '" .$vBankName . "',
    `vAccountNumber` = '" . $vAccountNumber . "',
		`vBIC_SWIFT_Code` = '" . $vBIC_SWIFT_Code . "',
    `vCurrencyDriver`='" . $vCurrencyDriver . "',
		`vLang` = '" . $vLang . "' $str" . $where;
     //echo '<pre>'; print_r($query); exit;
     $obj->sql_query($query);

     if (mysql_insert_id() != '') {
               if(isset($_FILES['vImage'])){
                $id = mysql_insert_id();
                $img_path = $tconfig["tsite_upload_images_driver_path"];
                $temp_gallery = $img_path . '/';
                $image_object = $_FILES['vImage']['tmp_name'];
                $image_name = $_FILES['vImage']['name'];
                $check_file_query = "select iDriverId,vImage from register_driver where iDriverId=" . $id;
                $check_file = $obj->sql_query($check_file_query);
                if ($image_name != "") {
                     $check_file['vImage'] = $img_path . '/' . $id . '/' . $check_file[0]['vImage'];

                     if ($check_file['vImage'] != '' && file_exists($check_file['vImage'])) {
                            unlink($img_path . '/' . $id. '/' . $check_file[0]['vImage']);
                            unlink($img_path . '/' . $id. '/1_' . $check_file[0]['vImage']);
                            unlink($img_path . '/' . $id. '/2_' . $check_file[0]['vImage']);
                            unlink($img_path . '/' . $id. '/3_' . $check_file[0]['vImage']);
                     }

                     $filecheck = basename($_FILES['vImage']['name']);
                     $fileextarr = explode(".", $filecheck);
                     $ext = strtolower($fileextarr[count($fileextarr) - 1]);
                     $flag_error = 0;
                     if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp") {
                          $flag_error = 1;
                          $var_msg = "Not valid image extension of .jpg, .jpeg, .gif, .png";
                     }
                     if ($_FILES['vImage']['size'] > 1048576) {
                          $flag_error = 1;
                          $var_msg = "Image Size is too Large";
                     }
                     if ($flag_error == 1) {
                          $generalobj->getPostForm($_POST, $var_msg, "driver_action?success=0&var_msg=" . $var_msg);
                          exit;
                     } else {

                          $Photo_Gallery_folder = $img_path . '/' . $id . '/';
                          if (!is_dir($Photo_Gallery_folder)) {
                               mkdir($Photo_Gallery_folder, 0777);
                          }
                          $img = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_images_member_size1"], $tconfig["tsite_upload_images_member_size2"], $tconfig["tsite_upload_images_member_size3"], '', '', '', 'Y', '', $Photo_Gallery_folder);
                          $vImage = $img;

                          $sql = "UPDATE ".$tbl_name." SET `vImage` = '" . $vImage . "' WHERE `iDriverId` = '" . $id . "'";
                          $obj->sql_query($sql);
                     }
                }
           }
     }
     
     $id = ($id != '') ? $id : mysql_insert_id();
     
     
	  if ($action == 'Add') {
         $maildata['EMAIL'] = $vEmail;
        $maildata['NAME'] = $vName;
        $maildata['PASSWORD'] = $vPassword;
	    $generalobj->send_email_user("MEMBER_REGISTRATION_USER",$maildata);
		 
     }
     if($action=="Add")
     {
        $ksuccess="1";
      }
     else
     {
        $ksuccess="2";
     }
     //echo $ksuccess;exit;
     header("Location:driver_action.php?id=" . $id . '&success=1 &ksuccess='.$ksuccess);
}
// for Edit

if ($action == 'Edit') {
     $sql = "SELECT * FROM " . $tbl_name . " WHERE iDriverId = '" . $id . "'";
     $db_data = $obj->MySQLSelect($sql);
     //echo "<pre>";print_R($db_data);echo "</pre>";
     $vPass = $generalobj->decrypt($db_data[0]['vPassword']);
	 if($db_data[0]['eStatus'] == "active") {
		 $actionType = "approve";
	 }else {
		 $actionType = "pending";
	 }
     $vLabel = $id;
     if (count($db_data) > 0) {
          foreach ($db_data as $key => $value) {
               $vName = $value['vName'];
               $iCompanyId = $value['iCompanyId'];
               $vLastName = $value['vLastName'];
               $vCountry = $value['vCountry'];
               $vCode = $value['vCode'];
               $vEmail = $generalobjAdmin->clearEmail($value['vEmail']);
               $vUserName = $value['vLoginId'];
               $vPassword = $value['vPassword'];
               $vPhone = $generalobjAdmin->clearPhone($value['vPhone']);
               $vLang = $value['vLang'];
               $vImage = $value['vImage'];
               $vCurrencyDriver=$value['vCurrencyDriver'];               
               $vPaymentEmail=$value['vPaymentEmail'];
               $vBankAccountHolderName=$value['vBankAccountHolderName'];
               $vAccountNumber=$value['vAccountNumber'];
               $vBankLocation=$value['vBankLocation'];
               $vBankName=$value['vBankName'];
               $vBIC_SWIFT_Code=$value['vBIC_SWIFT_Code'];
          }
     }
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

     <!-- BEGIN HEAD-->
     <head>
          <meta charset="UTF-8" />
          <title>Admin | <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>  <?= $action; ?></title>
          <meta content="width=device-width, initial-scale=1.0" name="viewport" />
          <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
          <?
          include_once('global_files.php');
          ?>
          <!-- On OFF switch -->
          <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
          <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
     </head>
     <!-- END  HEAD-->
     <!-- BEGIN BODY-->
     <body class="padTop53 " >

          <!-- MAIN WRAPPER -->
          <div id="wrap">
               <?
               include_once('header.php');
               include_once('left_menu.php');
               ?>
               <!--PAGE CONTENT -->
               <div id="content">
                    <div class="inner">
                         <div class="row">
                              <div class="col-lg-12">
                                   <h2><?= $action; ?> <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?>  <?= $vName; ?></h2>
                                   <a href="driver.php?type=<?=$actionType;?>">
                                        <input type="button" value="Back to Listing" class="add-btn">
                                   </a>
                              </div>
                         </div>
                         <hr />
                         <div class="body-div">
                              <div class="form-group">
                                   <? if ($success == 1) {?>
                                   <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                          <?php
                                          if($ksuccess == "1")
                                          {?>
                                              Record Insert Successfully.
                                          <?php } else
                                          {?>
                                              Record Updated Successfully.
                                          <?php } ?>
                                        
                                   </div><br/>
                                   <?} ?>

                                   <? if ($success == 2) {?>
                                   <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                                   </div><br/>
                                   <?} ?>
                                   <form method="post" action="" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= $id; ?>"/>
                                       <?php if($id){?>
                                        <div class="row" id="hide-profile-div">
                                             <div class="col-lg-4">
                                                  <b><?php if ($vImage == 'NONE' || $vImage == '') { ?>
                                                                 <img src="../assets/img/profile-user-img.png" alt="">
                                                            <?}else{?>
                                                                 <img src = "<?php echo $tconfig["tsite_upload_images_driver"]. '/' .$id. '/3_' .$vImage ?>" style="height:150px;"/>
                                                            <?}?>
                                                       </b>
                                             </div>
                                        </div>
                                        <?php }?>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>First Name<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" pattern="[a-zA-Z]+" title="Only Alpha character allow" class="form-control" name="vName"  id="vName" value="<?= $vName; ?>" placeholder="First Name" required>
                                             </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Last Name<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" pattern="[a-zA-Z]+" title="Only Alpha character allow" class="form-control" name="vLastName"  id="vLastName" value="<?= $vLastName; ?>" placeholder="Last Name" required>
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Email<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" name="vEmail" onChange="validate_email(this.value)"  id="vEmail" value="<?= $vEmail; ?>" placeholder="Email" required <?php  if(!empty($_REQUEST['id'])){?> readonly="readonly" <?php } ?>>
                                             </div><div id="emailCheck"></div>
                                        </div>
										<div class="row">
                                             <div class="col-lg-12">
                                                  <label>Password</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="password" pattern=".{6,}" title="Six or more characters" class="form-control" name="vPassword"  id="vPassword" value="<?= $vPass ?>" placeholder="Password Label" required>
                                             </div>
                                        </div>

										 <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Profile Picture</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="file" class="form-control" name="vImage"  id="vImage" placeholder="Name Label" style="padding-bottom: 39px;">
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Country<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <select class="form-control" name = 'vCountry' onChange="changeCode(this.value);" required>
                                                       <option value="">--select--</option>
                                                       <? for($i=0;$i<count($db_country);$i++){ ?>
                                                       <option value = "<?= $db_country[$i]['vCountryCode'] ?>" <?if($vCountry==$db_country[$i]['vCountryCode']){?>selected<? } ?>><?= $db_country[$i]['vCountry'] ?></option>
                                                       <? } ?>
                                                  </select>
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Phone<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" class="form-select-2" id="code" name="vCode" value="<?= $vCode ?>" required readonly style="width:10%; margin:0;"/>
                                                  <input type="text" maxlength="10" pattern="[0-9]{10}" title="Please enter 10 digit mobile number." class="form-control" name="vPhone"  id="vPhone" value="<?= $vPhone; ?>" placeholder="Phone"  required style="width:90%;">
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Company<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <select  class="form-control" name = 'iCompanyId'  id= 'iCompanyId' required>
                                                       <option value="">--select--</option>
                                                       <? for ($i = 0; $i < count($db_company); $i++) { ?>
                                                       <option value = "<?= $db_company[$i]['iCompanyId'] ?>" <?= ($db_company[$i]['iCompanyId'] == $iCompanyId) ? 'selected' : ''; ?>>
<?= $db_company[$i]['vName'] . " " . $db_company[$i]['vLastName'] . " (" . $db_company[$i]['vCompany'] . ")"; ?>
                                                       </option>
                                                       <? } ?>
                                                  </select>
                                             </div>
                                        </div>


                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Language<span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <select  class="form-control" name = 'vLang' required>
                                                       <option value="">--select--</option>
                                                       <? for ($i = 0; $i < count($db_lang); $i++) { ?>
                                                       <option value = "<?= $db_lang[$i]['vCode'] ?>" <?= ($db_lang[$i]['vCode'] == $vLang) ? 'selected' : ''; ?>>
<?= $db_lang[$i]['vTitle'] ?>
                                                       </option>
                                                       <? } ?>
                                                  </select>
                                             </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Currency <span class="red"> *</span></label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <select class="form-control" name = 'vCurrencyDriver' required>
                                                       <option value="">--select--</option>
                                                       <? for($i=0;$i<count($db_currency);$i++){ ?>
                                                       <option value = "<?= $db_currency[$i]['vName'] ?>" <?if($vCurrencyDriver==$db_currency[$i]['vName']){?>selected<? } else if($db_currency[$i]['eDefault']=="Yes"){?>selected<?} ?>><?= $db_currency[$i]['vName'] ?></option>
                                                       <? } ?>
                                                  </select>
                                             </div>
                                        </div>                                     

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Payment Email</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="email"  class="form-control" name="vPaymentEmail"  id="vPaymentEmail" value="<?= $vPaymentEmail ?>" placeholder="Payment Email" >
                                             </div>
                                        </div>


                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Account Holder Name</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text"  class="form-control" name="vBankAccountHolderName"  id="vBankAccountHolderName" value="<?= $vBankAccountHolderName ?>" placeholder="Account Holder Name" >
                                             </div>
                                        </div>


                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Account Number</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text"  class="form-control" name="vAccountNumber"  id="vAccountNumber" value="<?=$vAccountNumber ?>" placeholder="Account Number" >
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Name of Bank</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text"  class="form-control" name="vBankName"  id="vBankName" value="<?= $vBankName ?>" placeholder="Name of Bank" >
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>Bank Location</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text" class="form-control" name="vBankLocation"  id="vBankLocation" value="<?= $vBankLocation ?>" placeholder="Bank Location" >
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <label>BIC/SWIFT Code</label>
                                             </div>
                                             <div class="col-lg-6">
                                                  <input type="text"  class="form-control" name="vBIC_SWIFT_Code"  id="vBIC_SWIFT_Code" value="<?= $vBIC_SWIFT_Code ?>" placeholder="BIC/SWIFT Code" >
                                             </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-12">
                                                  <input type="submit" class="save btn-info" name="submit" id="submit" value="<?= $action; ?> Driver"  >
                                             </div>
                                        </div>
                                   </form>
                              </div>
                         </div>
                            <div style="clear:both;"></div>
                    </div>
                 
               </div>
               <!--END PAGE CONTENT -->
          </div>
          <!--END MAIN WRAPPER -->


          <?
          include_once('footer.php');
          ?>
          <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
          <script>
           function changeCode(id)
           {
                var request = $.ajax({
                     type: "POST",
                     url: 'change_code.php',
                     data: 'id=' + id,
                     success: function (data)
                     {
                          document.getElementById("code").value = data;
                          //window.location = 'profile.php';
                     }
                });
           }
          function validate_email(id)
          {

            var request = $.ajax({
                 type: "POST",
                 url: 'validate_email.php',
                 data: 'id=' +id,
                 success: function (data)
                 {
                  	if(data==0)
                  	{
                      $('#emailCheck').html('<i class="icon icon-remove alert-danger alert">Already Exist,Select Another</i>');
                  	 $('input[type="submit"]').attr('disabled','disabled');
                  	}
                  	else if(data==1)
                  	{
                  		var eml=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                  		result=eml.test(id);
                  		if(result==true)
                  		{
                    		$('#emailCheck').html('<i class="icon icon-ok alert-success alert"> Valid</i>');
                    		$('input[type="submit"]').removeAttr('disabled');
                  		}
                  		else
                  		{
                  			$('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> Enter Proper Email</i>');
                  			 $('input[type="submit"]').attr('disabled','disabled');
                  		}
                  	}
                  	/*else if(data==2)
                  	{
                       $('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> This Account is deleted</i>');
                  	 $('input[type="submit"]').attr('disabled','disabled');
                  	}*/
                  }
              });
            }
          </script>
     </body>
     <!-- END BODY-->
</html>
