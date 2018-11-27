<?php
include_once('common.php');
//echo $url = $_SERVER['HTTP_REFERER'];exit;
$generalobj->check_member_login();

require_once(TPATH_CLASS . "/Imagecrop.class.php");
$thumb = new thumbnail();
$script="Vehicle";
$abc = 'admin,driver,company';
$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$generalobj->setRole($abc, $url);
//$generalobj->cehckrole();
$success = isset($_GET['success']) ? $_GET['success'] :'';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : ''; // delete
$id = isset($_GET['id']) ? $_GET['id'] : ''; // delete
$error = isset($_GET['success']) && $_GET['success']==0 ? 1 : ''; // delete
$var_msg = isset($_REQUEST['var_msg']) ? $_REQUEST['var_msg'] : ''; // delete
$tbl_name = 'driver_vehicle';
//echo '<pre>'; print_r($_SESSION); exit;


if (isset($_REQUEST['Submit'])) {

     $iVehicleId = isset($_REQUEST['iVehicleId']) ? $_REQUEST['iVehicleId'] : '';
     $image_object = $_FILES['insurance']['tmp_name'];
     $image_name = $_FILES['insurance']['name'];
      $image_type=$_FILES['insurance']['type'];


     $image_object1 = $_FILES['permit']['tmp_name'];
     $image_name1 = $_FILES['permit']['name'];

     $image_object2 = $_FILES['regi']['tmp_name'];
     $image_name2 = $_FILES['regi']['name'];

     if ($image_name != "") {

          $check_file_query = "select iDriverVehicleId,vInsurance from driver_vehicle where iDriverVehicleId=" . $iVehicleId;
          $check_file = $obj->sql_query($check_file_query);
          $check_file['vInsurance'] = $tconfig["tsite_upload_vehicle_doc"] . '/' . $iVehicleId. '/' .$check_file[0]['vInsurance'];
          
          $temp_gallery = $tconfig["tsite_upload_vehicle_doc"] . '/';
          $filecheck = basename($_FILES['insurance']['name']);
          $fileextarr = explode(".", $filecheck);
          $ext = strtolower($fileextarr[count($fileextarr) - 1]);
          $flag_error = 0;
           if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp" && $ext != "pdf" && $ext != "doc" && $ext != "docx") {
               $flag_error = 1;
               $var_msg = "You have selected wrong file format for Image. Valid formats are pdf,doc,docx,jpg,jpeg,gif,png";
          }
         
          if ($flag_error == 1) {
               $generalobj->getPostForm($_POST, $var_msg,"vehicle.php?success=0&var_msg=" . $var_msg);
               exit;
          } else {
         
               $Photo_Gallery_folder = $tconfig["tsite_upload_vehicle_doc"]. '/' . $iVehicleId . '/';
               if (!is_dir($Photo_Gallery_folder)) {
                    mkdir($Photo_Gallery_folder, 0777);
               }
         
               $vFile = $generalobj->fileupload($Photo_Gallery_folder,$image_object,$image_name,$prefix='', $vaildExt="pdf,doc,docx,jpg,jpeg,gif,png");
         
              $vImage1 = $vFile[0];
              
               if ($iVehicleId != '') {
         
                    $q = "UPDATE ";
                    $where = " WHERE `iDriverVehicleId` = '" . $iVehicleId . "'";
               }


               $query = $q . " `" . $tbl_name . "` SET `vInsurance` = '" . $vImage1 . "'" . $where;
               $obj->sql_query($query);

               //Start :: Log Data Save
               if ($_SESSION['sess_user'] == 'company') {
                         if(empty($check_file[0]['vInsurance'])){ $vNocPath = $vImage ; }else{ $vNocPath = $check_file[0]['vInsurance']; }
                         $generalobj->save_log_data ($iCompanyId,$_SESSION["sess_iUserId"],'company','insurance',$vNocPath);

               }else if ($_SESSION['sess_user'] == 'driver') {
                         if(empty($check_file[0]['vInsurance'])){ $vNocPath = $vImage ; }else{ $vNocPath = $check_file[0]['vInsurance']; }
                         $generalobj->save_log_data ('0',$_SESSION["sess_iUserId"],'driver','insurance',$vNocPath);
               }

               $success = 1;
			   $var_msg = "Document Uploaded Successfully";
			   header("Location:vehicle?success=1&var_msg=".$var_msg);
          }
     }

     if ($image_name1 != "") {
         $check_file_query = "select iDriverVehicleId,vPermit from driver_vehicle where iDriverVehicleId=" . $iVehicleId;
          $check_file = $obj->sql_query($check_file_query);
          $check_file['vPermit'] = $tconfig["tsite_upload_vehicle_doc"] . '/' . $iVehicleId. '/' . $check_file[0]['vPermit'];
          
          $temp_gallery = $tconfig["tsite_upload_vehicle_doc"] . '/';
          $filecheck = basename($_FILES['permit']['name']);
          $fileextarr = explode(".", $filecheck);
          $ext = strtolower($fileextarr[count($fileextarr) - 1]);
          $flag_error = 0;

           if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp" && $ext != "pdf" && $ext != "doc" && $ext != "docx") {
               $flag_error = 1;
               $var_msg = "You have selected wrong file format for Image. Valid formats are pdf,doc,docx,jpg,jpeg,gif,png";
          }
          if ($flag_error == 1) {
               $generalobj->getPostForm($_POST, $var_msg, "vehicle.php?success=0");
               exit;
          } else {

               $Photo_Gallery_folder = $tconfig["tsite_upload_vehicle_doc"]. '/' . $iVehicleId . '/';
               if (!is_dir($Photo_Gallery_folder)) {
                    mkdir($Photo_Gallery_folder, 0777);
               }

               $vFile = $generalobj->fileupload($Photo_Gallery_folder,$image_object1,$image_name1,$prefix='', $vaildExt="pdf,doc,docx,jpg,jpeg,gif,png");
               $vImage1 = $vFile[0];
               if ($iVehicleId != '') {

                    $q = "UPDATE ";
                    $where = " WHERE `iDriverVehicleId` = '" . $iVehicleId . "'";
               }

               $query = $q . " `" . $tbl_name . "` SET `vPermit` = '" . $vImage1 . "'" . $where;
               $obj->sql_query($query);

                //Start :: Log Data Save
               if ($_SESSION['sess_user'] == 'company') {
                         if(empty($check_file[0]['vPermit'])){ $vNocPath = $vImage1 ; }else{ $vNocPath = $check_file[0]['vPermit']; }
                         $generalobj->save_log_data ($iCompanyId,$_SESSION["sess_iUserId"],'company','permit',$vNocPath);

              // Start :: Status in edit a Document upload time
                        // $set_value = "`eStatus` ='inactive'";
                       //  $generalobj->estatus_change('company','iCompanyId',$iCompanyId,$set_value);
               // End :: Status in edit a Document upload time

               }else if ($_SESSION['sess_user'] == 'driver') {
                         if(empty($check_file[0]['vPermit'])){ $vNocPath = $vImage ; }else{ $vNocPath = $check_file[0]['vPermit']; }
                         $generalobj->save_log_data ('0',$_SESSION["sess_iUserId"],'driver','permit',$vNocPath);
               
               }
               //End :: Log Data Save
               $success = 1;
               $var_msg = "Document Uploaded Successfully";
			   header("Location:vehicle?success=1&var_msg=".$var_msg);
          }
     }

     if ($image_name2 != "") {
          $check_file_query = "select iDriverVehicleId,vRegisteration from driver_vehicle where iDriverVehicleId=" . $iVehicleId;
          $check_file = $obj->sql_query($check_file_query);
          $check_file['vRegisteration'] = $tconfig["tsite_upload_vehicle_doc"]. '/' . $iVehicleId . '/' . $check_file[0]['vRegisteration'];

          $temp_gallery = $tconfig["tsite_upload_vehicle_doc"] . '/';
          $filecheck = basename($_FILES['regi']['name']);
          $fileextarr = explode(".", $filecheck);
          $ext = strtolower($fileextarr[count($fileextarr) - 1]);
          $flag_error = 0;


           if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp" && $ext != "pdf" && $ext != "doc" && $ext != "docx") {
               $flag_error = 1;
               $var_msg = "You have selected wrong file format for Image. Valid formats are pdf,doc,docx,jpg,jpeg,gif,png";
          }
           /* else if ($_FILES['regi']['size'] > 1048576) {
               $flag_error = 1;
               $var_msg = "Image Size is too Large";
          } */
          if ($flag_error == 1) {
               $generalobj->getPostForm($_POST, $var_msg, $tconfig['tsite_url'] . "vehicle.php?success=0");
               exit;
          } else {
               // $Photo_Gallery_folder = $tconfig["tsite_upload_vehicle_doc"];
                $Photo_Gallery_folder = $tconfig["tsite_upload_vehicle_doc"]. '/' . $iVehicleId . '/';
               if (!is_dir($Photo_Gallery_folder)) {
                    mkdir($Photo_Gallery_folder, 0777);
               }
               //$img = $generalobj->general_upload_image($image_object2, $image_name2, $Photo_Gallery_folder, $tconfig["tsite_upload_documnet_size1"], $tconfig["tsite_upload_documnet_size2"], '', '', '', '', 'Y', '', $Photo_Gallery_folder);
               $vFile = $generalobj->fileupload($Photo_Gallery_folder,$image_object2,$image_name2,$prefix='', $vaildExt="pdf,doc,docx,jpg,jpeg,gif,png");
               $vImage2 = $vFile[0];
               if ($iVehicleId != '') {
//   echo "<pre>";print_R($_REQUEST);exit;
                    $q = "UPDATE ";
                    $where = " WHERE `iDriverVehicleId` = '" . $iVehicleId . "'";
               }

               $query = $q . " `" . $tbl_name . "` SET `vRegisteration` = '" . $vImage2 . "'" . $where;
               $obj->sql_query($query);

               //Start :: Log Data Save
                    if ($_SESSION['sess_user'] == 'company') {
                         if(empty($check_file[0]['vRegisteration'])){ $vNocPath = $vImage2 ; }else{ $vNocPath = $check_file[0]['vRegisteration']; }
                         $generalobj->save_log_data ($iCompanyId,$_SESSION["sess_iUserId"],'company','registeration',$vNocPath);

               // Start :: Status in edit a Document upload time
                        // $set_value = "`eStatus` ='inactive'";
                       //  $generalobj->estatus_change('company','iCompanyId',$iCompanyId,$set_value);
               // End :: Status in edit a Document upload time

                    }else if($_SESSION['sess_user'] == 'driver') {
                          if(empty($check_file[0]['vRegisteration'])){ $vNocPath = $vImage ; }else{ $vNocPath = $check_file[0]['vRegisteration']; }
                          $generalobj->save_log_data ('0',$_SESSION["sess_iUserId"],'driver','registeration',$vNocPath);

               // Start :: Status in edit a Document upload time
                       //  $set_value = "`eStatus` ='inactive'";
                         //$generalobj->estatus_change('register_driver','iDriverId',$_SESSION["sess_iUserId"],$set_value);
               // End :: Status in edit a Document upload time
                    }
               //End :: Log Data Save
               $success = 1;
               $var_msg = "Document Uploaded Successfully";
			   header("Location:vehicle?success=1&var_msg=".$var_msg);	  
          }
     }
//echo $vImage." ".$vImage1." ".$vImage2;exit;
     //header('location:vehicle.php');
}

if ($_SESSION['sess_user'] == 'driver') {
     $sql = "select iCompanyId from `register_driver` where iDriverId = '" . $_SESSION['sess_iUserId'] . "'";
     $db_usr = $obj->MySQLSelect($sql);
     $iCompanyId = $db_usr[0]['iCompanyId'];
	 
     // $sql = "SELECT * FROM " . $tbl_name . " where iCompanyId = '" . $iCompanyId . "' and iDriverId = '" . $_SESSION['sess_iUserId'] . "' and eStatus != 'Deleted'";
     // $db_driver_vehicle = $obj->MySQLSelect($sql);

      if($APP_TYPE == 'UberX'){
        $sql = "SELECT * FROM " . $tbl_name . " dv  where iCompanyId = '" . $iCompanyId . "' and dv.iDriverId = '" . $_SESSION['sess_iUserId'] . "' and dv.eStatus != 'Deleted'";
		$db_driver_vehicle = $obj->MySQLSelect($sql);

      }else{

         $sql = "SELECT dv.*,m.vTitle, mk.vMake,dv.vLicencePlate,dv.eStatus  FROM " . $tbl_name . " dv  JOIN model m ON dv.iModelId=m.iModelId JOIN make mk ON  dv.iMakeId=mk.iMakeId where iCompanyId = '" . $iCompanyId . "' and iDriverId = '" . $_SESSION['sess_iUserId'] . "' and dv.eStatus != 'Deleted'";
		$db_driver_vehicle = $obj->MySQLSelect($sql);
      }

     //echo "<pre>";print_r($db_driver_vehicle);exit;
}
if ($_SESSION['sess_user'] == 'company') {
     $iCompanyId = $_SESSION['sess_iUserId'];
	 // $sql = "SELECT * FROM " . $tbl_name . " where iCompanyId = '" . $iCompanyId . "' and eStatus != 'Deleted'";
     // $db_driver_vehicle = $obj->MySQLSelect($sql);

      if($APP_TYPE == 'UberX'){
        $sql = "SELECT * FROM " . $tbl_name . " dv  where iCompanyId = '" . $iCompanyId . "'and dv.eStatus != 'Deleted'";
     
     $db_driver_vehicle = $obj->MySQLSelect($sql);

      }else{
         $sql = "SELECT dv.*,m.vTitle, mk.vMake,dv.vLicencePlate,dv.eStatus  FROM " . $tbl_name . " dv  JOIN model m ON dv.iModelId=m.iModelId JOIN make mk ON  dv.iMakeId=mk.iMakeId where iCompanyId = '" . $iCompanyId . "' and dv.eStatus != 'Deleted'";
       $db_driver_vehicle = $obj->MySQLSelect($sql);

      }
	//echo "<pre>";print_r($db_vehicle);exit;
     

}

if ($action == 'delete') {
     // to check user is valid or not to delete vehicle

      // if(SITE_TYPE == 'Demo')
     // {
           // header("Location:vehicle.php?success=2");
           // exit;

     // }
     $valid_user = false;
     foreach ($db_driver_vehicle as $val) {
          if ($val['iDriverVehicleId'] == $id)
               $valid_user = true;
     }
     if (!$valid_user)
          header("Location:vehicle.php?error=1&var_msg=You can not Delete this vehicle");
     else {

          $sql = "select count(*) as trip_cnt from trips where iDriverVehicleId = '" . $id . "' GROUP BY iDriverVehicleId";
          $db_usr = $obj->MySQLSelect($sql);

          if (count($db_usr) > 0 && $db_usr['trip_cnt'] > 0) {
               header("Location:vehicle.php?error=1&var_msg=Trips are available. You can not delete this vehicle");
               exit;
          } else {
               $query = "UPDATE `driver_vehicle` SET eStatus = 'Deleted' WHERE iDriverVehicleId = '" . $id . "'";
               $obj->sql_query($query);

               header("Location:vehicle.php?success=1&var_msg=Vehicle deleted successfully");
               exit;
          }
     }
}

for ($i = 0; $i < count($db_driver_vehicle); $i++) {
//echo "<br>id == ".$db_data[$i]['iDriverVehicleId'];
    $sql = "select vMake from make where iMakeId = '" . $db_driver_vehicle[$i]['iMakeId'] . "' where vMake !=''";
     $name1 = $obj->MySQLSelect($sql);
     $sql = "select vTitle from model where iModelId = '" . $db_driver_vehicle[$i]['iModelId'] . "' WHERE vTitle !=''";
     $name2 = $obj->MySQLSelect($sql);
     $db_msk[$i] = $name1[0]['vMake'] . ' ' . $name2[0]['vTitle'];
}

?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_VEHICLES']; ?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <link rel="stylesheet" href="assets/css/bootstrap-fileupload.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/vehicles.css">
    <style>
        .fileupload-preview  { line-height:150px;}
    </style>
    <!-- End: Default Top Script and css-->
</head>
<body>
     <!-- home page -->
    <div id="main-uber-page">
     <!-- Top Menu -->
    <!-- Left Menu -->
    <?php include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
        <?php include_once("top/header_topbar.php");?>
        <!-- End: Top Menu-->
        <!-- contact page-->
        <div class="page-contant">
            <div class="page-contant-inner">
          
                <h2 class="header-page add-car-vehicle"><?=$langage_lbl['LBL_VEHICLES']; ?>
                  <?php if($APP_TYPE != 'UberX'){ ?>
                    <a href="vehicle-add"><?=$langage_lbl['LBL_ADD_YOUR_CAR']; ?></a><?php } ?>
                </h2>
                
                
              <?php 
                  if(SITE_TYPE =='Demo'){
              ?>
              <div class="demo-warning">
                <p><?=$langage_lbl['LBL_SINCE_THIS']; ?></p>
                </div>
              <?php
                }
              ?>
              
          <!-- driver vehicles page -->
            <div class="driver-vehicles-page-new">
            <?php
                if ($error) {
            ?>
                <div class="row">
                    <div class="col-sm-12 alert alert-danger">
                         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?= $var_msg ?>
                    </div>
                </div>
            <?php 
                }
                if ($success==1) {
            ?>
                <div class="row">
                    <div class="alert alert-success paddiing-10">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?= $var_msg ?>
                    </div>
                </div>
            <?php
                }else if($success==2) {
            ?>
                <div class="row">
                    <div class="alert alert-danger paddiing-10">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?=$langage_lbl['LBL_VEHICLE_EDIT_DELETE_RECORD']; ?>
                    </div>
                </div>
            <?
                }
            ?>
                <div class="vehicles-page">
                    <div class="accordion">
                        <?php
                            if (count($db_driver_vehicle) > 0) {
                                for ($i = 0; $i < count($db_driver_vehicle); $i++) {
                        ?>
                        <form id="<?= $i ?>" method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="iVehicleId" value = "<?php echo $db_driver_vehicle[$i]['iDriverVehicleId']; ?>"/>
                            <div class="accordion-section">
                                <div class="accordionheading">
                                   <?php if($APP_TYPE == 'UberX'){
                                      $displayname =  $db_driver_vehicle[$i]['vLicencePlate'];
                                    }else{

                                      $displayname =$db_driver_vehicle[$i]['vMake']."   ".$db_driver_vehicle[$i]['vTitle']."  ".$db_driver_vehicle[$i]['vLicencePlate']."  "  ;
                                      }?> 
                                    <h3><?php echo $displayname  ;?></h3>
                                    <span> 
                                        <b>
                                          
                                          <?php 
                                          $class_name = ($db_driver_vehicle[$i]['eStatus'] == "Active")? 'badge success-vehicle-active': 'badge success-vehicle-inactive';
                                          ?>
                                           
                                      <span class="<?php echo $class_name; ?>">
                                         <i class="<?= ($db_driver_vehicle[$i]['eStatus'] == "Active") ? 'icon-eye-open' : 'icon-eye-close' ?>"></i> <?= ucfirst($db_driver_vehicle[$i]['eStatus']); ?>
                                    </span>
                                            <a href ="vehicle_add_form.php?id=<?=base64_encode(base64_encode($db_driver_vehicle[$i]['iDriverVehicleId'])) ?>" class="active"><?=$langage_lbl['LBL_VEHICLE_EDIT']; ?></a>
                                            <?php if($APP_TYPE != 'UberX'){?> 
                                            <a class="active active2" onClick="confirm_delete('<?= $db_driver_vehicle[$i]['iDriverVehicleId'] ?>');" href="javascript:void(0);"><?=$langage_lbl['LBL_DELETE']; ?></a><?php } ?>

                                        </b>
                                        <?php if($APP_TYPE != 'UberX'){?> 
                                        <strong><a class="accordion-section-title" href="#accordion-<?php echo $i;?>">&nbsp;</a></strong> 

                                        <?php } ?>

                                        </span>
                                </div>
                                <div id="accordion-<?php echo $i;?>" class="accordion-section-content">
                                    <div class="driver-vehicles-page-new">
                                        <h2><?=$langage_lbl['LBL_DOCUMENTS']; ?></h2>
                                        <?php
                                          // if(SITE_TYPE !='Demo'){
                                        ?>    
                                        <?if($db_driver_vehicle[$i]['vInsurance'] == ''){ ?>
                                        <div class="alert alert-warning alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$langage_lbl['LBL_PLESE_UPLOAD_INSURENCE_DOCUMENT']; ?> <!-- <a href="#" class="alert-link">Alert Link</a> -->.
                                        </div>
                                        <? } ?>
                                        <?if($db_driver_vehicle[$i]['vPermit'] == ''){?>
                                        <div class="alert alert-warning alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$langage_lbl['LBL_PLESE_UPLOAD_PERMIT_DOCUMENTS']; ?><!-- <a href="#" class="alert-link">Alert Link</a> -->.
                                        </div>
                                        <? } ?>
                                        <?if($db_driver_vehicle[$i]['vRegisteration'] == ''){?>
                                        <div class="alert alert-warning alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$langage_lbl['LBL_PLESE_UPLODEREGISTRATION_DOCUMENTS']; ?><!-- <a href="#" class="alert-link">Alert Link</a> -->.
                                        </div>
                                        <?  }
                                          // }
                                         ?>
                                        
                                        <ul>
                                            <li>
                                                <h4><?=$langage_lbl['LBL_VEHICAL_INSURENCE']; ?></h4>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px; ">
                                                        <b id="vInsurance_<?=$db_driver_vehicle[$i]['iDriverVehicleId']?>">
                                                        <?php 
                                                            if($db_driver_vehicle[$i]['vInsurance'] != ''){

                                                                $file_ext = $generalobj->file_ext($db_driver_vehicle[$i]['vInsurance']);
                                                                if($file_ext == 'is_image'){ 
                                                        ?>
														<a href="javascript:void(0);" class="btn btn-danger" onClick="return del_veh_doc('<?=$db_driver_vehicle[$i]['iDriverVehicleId']?>','vInsurance','<?=$db_driver_vehicle[$i]['vInsurance']?>');">X</a> 
														<img src = "<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/' . $db_driver_vehicle[$i]['iDriverVehicleId']. '/' .$db_driver_vehicle[$i]['vInsurance'] ?>" style="width:200px;" alt ="<?=$langage_lbl['LBL_INSURENCE_IMAGE']; ?>"/>
                                                        <?php
                                                                }else{ 
                                                        ?>
														
                                                            <a href="<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/' . $db_driver_vehicle[$i]['iDriverVehicleId']. '/' .$db_driver_vehicle[$i]['vInsurance']  ?>" target="_blank"><?=$langage_lbl['LBL_INSURENCE_DOC']; ?></a>
															<a href="javascript:void(0);" class="btn btn-success" onClick="return del_veh_doc('<?=$db_driver_vehicle[$i]['iDriverVehicleId']?>','vInsurance','<?=$db_driver_vehicle[$i]['vInsurance']?>');"><?=$langage_lbl['LBL_DELETE']; ?></a> 
															
                                                        <?php 
                                                                }
                                                            } 
                                                            else { 
                                                        ?> 
                                                            <?=$langage_lbl['LBL_PLESE_UPLOAD_INSURENCE_DOCUMENT']; ?>
                                                        <?php } ?>
                                                        </b> 
                                                    </div>
                                                    <div class="select-image1">
                                                        <span class="btn btn-file btn-success">
                                                            <span class="fileupload-new"><?=$langage_lbl['LBL_CHANGE_INSURENCE']; ?></span>
                                                            <span class="fileupload-exists w1"><?=$langage_lbl['LBL_VEHICLE_CHANGE']; ?></span>
                                                            <input type="file"  name="insurance" class="ins" />
                                                        </span>
                                                            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">X</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <h4><?=$langage_lbl['LBL_PERMIT']; ?></h4>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px; ">
                                                        <b id="vPermit_<?=$db_driver_vehicle[$i]['iDriverVehicleId']?>">
                                                        <?php 
                                                            if($db_driver_vehicle[$i]['vPermit'] != ''){

                                                                $file_ext = $generalobj->file_ext($db_driver_vehicle[$i]['vPermit']);
                                                                if($file_ext == 'is_image'){ 
                                                        ?>
							<a href="javascript:void(0);" class="btn btn-danger" onClick="return del_veh_doc('<?=$db_driver_vehicle[$i]['iDriverVehicleId']?>','vPermit','<?=$db_driver_vehicle[$i]['vPermit']?>');">X</a> 
                              <img src = "<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/'.$db_driver_vehicle[$i]['iDriverVehicleId']. '/'. $db_driver_vehicle[$i]['vPermit'] ?>" style="width:200px;" alt ="<?=$langage_lbl['LBL_VEHICLE_PERMIT_IMAGE']; ?>"/>
                                                        <?php
                                                                }else{ 
                                                        ?>
                                                            <a href="<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/' .$db_driver_vehicle[$i]['iDriverVehicleId']. '/'. $db_driver_vehicle[$i]['vPermit']  ?>" target="_blank"><?=$langage_lbl['LBL_PERMIT_DOC']; ?></a>
															<a href="javascript:void(0);" class="btn btn-success" onClick="return del_veh_doc('<?=$db_driver_vehicle[$i]['iDriverVehicleId']?>','vPermit','<?=$db_driver_vehicle[$i]['vPermit']?>');"><?=$langage_lbl['LBL_DELETE']; ?></a> 
                                                        <?php 
                                                                }
                                                            } 
                                                            else { 
                                                        ?> 
                                                            <?=$langage_lbl['LBL_PLESE_UPLOAD_PERMIT_DOCUMENTS']; ?>
                                                        <?php } ?>
                                                        </b> 
                                                    </div>
                                                    <div class="select-image1">
                                                        <span class="btn btn-file btn-success">
                                                            <span class="fileupload-new"><?=$langage_lbl['LBL_CHANGE_PERMIT']; ?></span>
                                                            <span class="fileupload-exists w1"><?=$langage_lbl['LBL_VEHICLE_CHANGE']; ?></span>
                                                        <input type="file"  name="permit" class="ins" />
                                                        </span>
                                                            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">x</a>
                                                   </div>
                                                </div>
                                            </li>
                                            <li>
                                                <h4><?=$langage_lbl['LBL_REGISTRATION']; ?></h4>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px; ">
                                                        <b id="vRegisteration_<?=$db_driver_vehicle[$i]['iDriverVehicleId']?>">
                                                        <?php 
                                                            if($db_driver_vehicle[$i]['vRegisteration'] != ''){

                                                                $file_ext = $generalobj->file_ext($db_driver_vehicle[$i]['vRegisteration']);
                                                                if($file_ext == 'is_image'){ 
                                                        ?>
														<a href="javascript:void(0);" class="btn btn-danger" onClick="return del_veh_doc('<?=$db_driver_vehicle[$i]['iDriverVehicleId']?>','vRegisteration','<?=$db_driver_vehicle[$i]['vRegisteration']?>');">X</a> 
														<img src = "<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/' .$db_driver_vehicle[$i]['iDriverVehicleId']. '/'.$db_driver_vehicle[$i]['vRegisteration'] ?>" style="width:200px;" alt ="<?=$langage_lbl['LBL_VEHICLE_REGI_IMAGE']; ?>"/>
                                                        <?php
                                                                }else{ 
                                                        ?>
                                                            <a href="<?= $tconfig["tsite_upload_vehicle_doc_panel"] . '/' .$db_driver_vehicle[$i]['iDriverVehicleId']. '/'. $db_driver_vehicle[$i]['vRegisteration']  ?>" target="_blank"><?=$langage_lbl['LBL_VEHICLE_REGISTRATION_DOC']; ?></a>
															<a href="javascript:void(0);" class="btn btn-success" onClick="return del_veh_doc('<?=$db_driver_vehicle[$i]['iDriverVehicleId']?>','vRegisteration','<?=$db_driver_vehicle[$i]['vRegisteration']?>');"><?=$langage_lbl['LBL_DELETE']; ?></a> 
                                                        <?php 
                                                                }
                                                            } 
                                                            else { 
                                                        ?> 
                                                            <?=$langage_lbl['LBL_PLESE_UPLODEREGISTRATION_DOCUMENTS']; ?>
                                                        <?php } ?>
                                                        </b> 
                                                    </div>
                                                   <div class="select-image1">
                                                        <span class="btn btn-file btn-success">
                                                            <span class="fileupload-new"><?=$langage_lbl['LBL_CHANGE_REGISTRATION']; ?></span>
                                                            <span class="fileupload-exists w1"><?=$langage_lbl['LBL_VEHICLE_CHANGE']; ?></span>
                                                         <input type="file"  name="regi" class="ins" />
                                                         </span>
														<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">x</a>
												   </div>
                                            </li>
                                        </ul>
                                        <abbr><input type="submit" name="Submit" class="save-document" value="<?=$langage_lbl['LBL_Save_Documents']; ?>"></abbr> 
                                    </div>
                                </div>
                                <!--end .accordion-section-content-->
                            </div>
                        <!--end .accordion-section-->
                        </form>
                        <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
          <div style="clear:both;"></div>
        </div>
  </div>
    <!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
    <!-- footer part end -->
            <!-- End:contact page-->
            <div style="clear:both;"></div>
    </div>
    <!-- home page end-->
    <!-- Footer Script -->
    <?php include_once('top/footer_script.php');?>
    <script type="text/javascript" src="assets/js/accordion.js"></script>
    <script src="assets/plugins/jasny/js/bootstrap-fileupload.js"></script>
    <script type="text/javascript">
        function confirm_delete(id)
        {
			//alert('sdf');
            var tsite_url = '<?php echo $tconfig["tsite_url"]; ?>';
            if (id != '') {
                 var confirm_ans = confirm("Are You sure You want to Delete Vehicle?");
                 if (confirm_ans == true) {
                      window.location.href = "vehicle.php?action=delete&id="+id;
                 }
                 }
            //document.getElementById(id).submit();
        }
		
		function del_veh_doc(id,type,img){
			ans=confirm('<?=$langage_lbl['LBL_CONFIRM_DELETE_DOC']?>');
			if(ans == true)
			{
				var request=$.ajax({
						type: "POST",
						url: "ajax_delete_docimage.php",
						data: "veh_id="+id+"&type="+type+"&img="+img+"&doc_type=veh_doc",
						success:function(data){
							var url      = window.location.href; 
							$("#"+type+"_"+id).load(url+" #"+type+"_"+id);
						}
					});
			}else{
				return false;
			}
		}
    </script>
    <!-- End: Footer Script -->
</body>
</html>

