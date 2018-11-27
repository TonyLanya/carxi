<?php
include_once('../common.php');

if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");

     $generalobjAdmin = new General_admin();
}
$actionType = $_REQUEST['type'];
$generalobjAdmin->check_member_login();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$iDriverId = isset($_REQUEST['iDriverId']) ? $_REQUEST['iDriverId'] : '';
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'view';
$hdn_del_id = isset($_REQUEST['hdn_del_id']) ? $_REQUEST['hdn_del_id'] : '';
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$ksuccess=isset($_REQUEST['ksuccess']) ? $_REQUEST['ksuccess'] : 0;
$msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '';
$script = 'Driver';

$sql = "select * from country";
$db_country = $obj->MySQLSelect($sql);

$sql = "select * from company WHERE eStatus != 'Deleted'";
$db_company = $obj->MySQLSelect($sql);

$sql = "select * from language_master where eStatus = 'Active'";
$db_lang = $obj->MySQLSelect($sql);

if ($iDriverId != '' && $status != '') {
	$ssl = " AND register_driver.eStatus = 'inactive'";
	if($actionType != "" && $actionType == "approve") {
		$ssl = " AND register_driver.eStatus = 'active'";
	}
 $sql="SELECT register_driver.iDriverId from register_driver
  LEFT JOIN company on register_driver.iCompanyId=company.iCompanyId
  LEFT JOIN driver_vehicle on driver_vehicle.iDriverId=register_driver.iDriverId
  WHERE company.eStatus='Active' AND driver_vehicle.eStatus='Active' AND register_driver.iDriverId='".$iDriverId."'".$ssl;
  $Data=$obj->MySQLSelect($sql);
//  echo "<pre>";print_r($Data);exit;
  if(count($Data)>0)
  {
    	 $sql="SELECT * FROM register_driver WHERE iDriverId = '" . $iDriverId . "'";
    	$db_status = $obj->MySQLSelect($sql);
    	$maildata['EMAIL'] =$db_status[0]['vEmail'];
        $maildata['NAME'] = $db_status[0]['vName'];
    	 $maildata['LAST_NAME'] = $db_status[0]['vName'];
    	 $maildata['DETAIL']="Your Account is ".$db_status[0]['eStatus'];
    	$generalobj->send_email_user("ACCOUNT_STATUS",$maildata);
  }
  else {
    $msg='Driver Have not Any Active Company Or Vehicle';
    header("Location:driver.php?success=2&msg=".$msg."&type=".$actionType);exit;
    //echo "<script>alert('Driver Have not Any Active Company Or Vehicle')</script>";
  }

  if(SITE_TYPE !='Demo' && count($Data)>0 ){

    //echo $status; exit;
     $query = "UPDATE register_driver SET eStatus = '" . $status . "' WHERE iDriverId = '" . $iDriverId . "'";
     $obj->sql_query($query);

     if($status == "active"){

         $msg=' Record Active Successfully';
        header("Location:driver.php?type=approve&success=1&msg=".$msg);exit;

     }else{

         $msg=' Record Inactive Successfully';
        header("Location:driver.php?type=pending&success=2&msg=".$msg);exit;

     }
    

   }
   else{
     header("Location:driver.php?success=2&type=".$actionType);exit;
   }
	//$generalobj->send_email_user("DELETE_ACCOUNT",$maildata);
}


if ($action == 'delete' && $hdn_del_id != '') {
	$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
     //$query    = "DELETE FROM `" . $tbl_name . "` WHERE iDriverId = '" . $id . "'";
     if(SITE_TYPE !='Demo'){
       $query = "UPDATE register_driver SET eStatus = 'Deleted' WHERE iDriverId = '" . $hdn_del_id . "'";
       $obj->sql_query($query);
       $action = "view";
       $success = "1";
       $ksuccess="3";
     }
     else{
       header("Location:driver.php?success=2&type=".$actionType);exit;
     }
     //header("Location:driver.php?success=1");
}

$vName = isset($_POST['vName']) ? $_POST['vName'] : '';
$vLname = isset($_POST['vLname']) ? $_POST['vLname'] : '';
$vEmail = isset($_POST['vEmail']) ? $_POST['vEmail'] : '';
$vPassword = isset($_POST['vPassword']) ? $_POST['vPassword'] : '';
$vPhone = isset($_POST['vPhone']) ? $_POST['vPhone'] : '';
$vCode = isset($_POST['vCode']) ? $_POST['vCode'] : '';
$vCountry = isset($_POST['vCountry']) ? $_POST['vCountry'] : '';
$iCompanyId = isset($_POST['iCompanyId']) ? $_POST['iCompanyId'] : '';
$vLang = isset($_POST['vLang']) ? $_POST['vLang'] : '';
$vPass = $generalobj->encrypt($vPassword);
$eStatus = isset($_POST['eStatus']) ? $_POST['eStatus'] : '';
$tbl_name = "register_driver";

if (isset($_POST['submit'])) {

     $q = "INSERT INTO ";
     $where = '';

     if ($action == 'Edit') {
          $eStatus = ", eStatus = 'Inactive' ";
     } else {
          $eStatus = '';
     }

     if ($id != '') {
          $q = "UPDATE ";
          $where = " WHERE `iDriverId` = '" . $id . "'";
     }


     $query = $q . " `" . $tbl_name . "` SET
			`vName` = '" . $vName . "',
			`vLastName` = '" . $vLname . "',
			`vCountry` = '" . $vCountry . "',
			`vCode` = '" . $vCode . "',
			`vEmail` = '" . $vEmail . "',
			`vLoginId` = '" . $vEmail . "',
			`vPassword` = '" . $vPass . "',
			`vPhone` = '" . $vPhone . "',
			`vLang` = '" . $vLang . "',
			`eStatus` = '" . $eStatus . "',
			`iCompanyId` = '" . $iCompanyId . "'" . $where;
       $obj->sql_query($query);
       $id = ($id != '') ? $id : mysql_insert_id();
       if($action=="Add")
       {
          $ksuccess="1";
        }
        else if ($action=="delete") 
        {
          $ksuccess="3";
        }
       else
       {
          $ksuccess="2";
       }
       header("Location:driver.php?id=" . $id . '&success=1 &ksuccess='.$ksuccess."&type=".$actionType);
}
$cmp_ssql = "";
if(SITE_TYPE =='Demo'){
	$cmp_ssql = " And rd.tRegistrationDate > '".WEEK_DATE."'";
}
if ($action == 'view') {
	$ssl = " AND rd.eStatus = 'inactive'";
	$title = "Pending ";
	if($actionType != "" && $actionType == "approve") {
		$title = "Approved ";
		$ssl = " AND rd.eStatus = 'active'";
	}
     //$sql = "SELECT rd.*, c.vName companyFirstName, c.vLastName companyLastName FROM register_driver rd, company c WHERE rd.iCompanyId = c.iCompanyId AND rd.eStatus != 'Deleted' AND c.eStatus != 'Deleted'";
    $sql = "SELECT rd.*, c.vCompany companyFirstName, c.vLastName companyLastName FROM register_driver rd LEFT JOIN company c ON rd.iCompanyId = c.iCompanyId and c.eStatus != 'Deleted' WHERE  rd.eStatus != 'Deleted'".$ssl.$cmp_ssql;
     $data_drv = $obj->MySQLSelect($sql);
     
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

     <!-- BEGIN HEAD-->
     <head>
          <meta charset="UTF-8" />
          <title>Admin | <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> </title>
          <meta content="width=device-width, initial-scale=1.0" name="viewport" />

          <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

          <? include_once('global_files.php');?>
          <script>
               $(document).ready(function () {
                    $("#show-add-form").click(function () {
                         $("#show-add-form").hide(1000);
                         $("#add-hide-div").show(1000);
                         $("#cancel-add-form").show(1000);
                    });

               });
          </script>
          <script>
               $(document).ready(function () {
                    $("#cancel-add-form").click(function () {
                         $("#cancel-add-form").hide(1000);
                         $("#show-add-form").show(1000);
                         $("#add-hide-div").hide(1000);
                    });

               });

          </script>
     </head>
     <!-- END  HEAD-->
     <!-- BEGIN BODY-->
     <body class="padTop53 " >

          <!-- MAIN WRAPPER -->
          <div id="wrap">
               <? include_once('header.php'); ?>
               <? include_once('left_menu.php'); ?>

               <!--PAGE CONTENT -->
               <div id="content">
                    <div class="inner">
                         <div id="add-hide-show-div">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <h2><?=strtoupper($title);?><?php echo $langage_lbl_admin['LBL_DRIVERS_TXT_ADMIN'];?> </h2>
                                        <!--<input type="button" id="" value="ADD A DRIVER" class="add-btn">-->
                                        <a class="add-btn" href="driver_action.php" style="text-align: center;">ADD A DRIVER</a>
                                        <input type="button" id="cancel-add-form" value="CANCEL" class="cancel-btn">
                                   </div>
                              </div>
                              <hr />
                         </div>
                         <? if($success == 1) { ?>
                         <div class="alert alert-success alert-dismissable">
                              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                              
                                <?php if($ksuccess == "1")
                                    {?>
                                        Record Insert Successfully.
                                    <?php }
                                     else if ($ksuccess=="2")
                                     {?>
                                        Record Updated Successfully.
                                     <?php }
                                      else if($ksuccess=="3") 
                                    {?>
                                        Record Deleted Successfully.
                                    <?php } ?>
                                    <?echo $msg;?>
                              
                         </div><br/>
                         <? }elseif ($success == 2 & $msg == '') { ?>
                           <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                           </div><br/>
                         <? } elseif ($success == 2 & $msg != '') { ?>
                           <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?echo $msg;?>
                           </div><br/>
                         <? } ?>
                         <div id="add-hide-div">
                              <form name = "myForm" method="post" action="">
                                   <div class="page-form">
                                        <h2>ADD DRIVER</h2>
                                        <br><br>
                                        <ul>
                                             <li>
                                                  FIRST NAME<br>
                                                  <input type="text" name="vName" class="form-control" placeholder="First" required>
                                             </li>
                                             <li>
                                                  LAST NAME<br>
                                                  <input type="text" name="vLname" class="form-control" placeholder="Last" required>
                                             </li>
                                             <li>
                                                  EMAIL<br>
                                                  <input type="email" name="vEmail" class="form-control" placeholder="" required>
                                             </li>
                                             <li>
                                                  Company<br>
                                                  <select class="form-control" name = 'iCompanyId' id = 'iCompanyId' required>
                                                       <option value="">--select--</option>
                                                       <? for ($i = 0; $i < count($db_company); $i++) { ?>
                                                       <option value ="<?= $db_company[$i]['iCompanyId'] ?>"><?= $db_company[$i]['vName'] . " " . $db_company[$i]['vLastName'] . " (" . $db_company[$i]['vCompany'] . ")"; ?></option>
                                                       <? } ?>
                                                  </select>
                                                  <!--<input type="text" name="vEmail" class="form-control" placeholder="" >-->
                                             </li>
                                             <li>
                                                  Country<br>
                                                  <select class="contry-select" name = 'vCountry' onChange="changeCode(this.value);" required>
                                                       <option value="">--select--</option>
                                                       <? for ($i = 0; $i < count($db_country); $i++) { ?>
                                                       <option value = "<?= $db_country[$i]['vCountryCode'] ?>"><?= $db_country[$i]['vCountry'] ?></option>
                                                       <? } ?>
                                                  </select>
                                                  <!--<input type="text" name="vEmail" class="form-control" placeholder="" >-->
                                             </li>
                                             <li>
                                                  Language<br>
                                                  <select name = 'vLang' class="language-select" required>
                                                       <option value="">--select--</option>
                                                       <?	for ($i = 0; $i < count($db_lang); $i++) { ?>
                                                       <option value = "<?= $db_lang[$i]['vCode'] ?>"><?= $db_lang[$i]['vTitle'] ?></option>
                                                       <? } ?>
                                                  </select>
                                                  <!--<input type="text" name="vEmail" class="form-control" placeholder="" >-->
                                             </li>
                                             <li>
                                                  MOBILE<br>
                                                  <input type="text" class="form-select-2" id="code" name="vCode">
                                                  <input type="text" name="vPhone" class="mobile-text" placeholder="" required pattern=".{10}"/>
                                             </li>

                                             <li>
                                                  PASSWORD<br>
                                                  <input type="password" class="form-control" placeholder="" name="vPassword" required>
                                             </li>

                                             <li>
                                                  <input type="submit" name="submit" class="submit-btn" value="SUBMIT" >
                                             </li>
                                        </ul>
                                   </div>
                              </form>
                         </div>
                         <div class="table-list">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <div class="panel panel-default">
                                             <div class="panel-heading">
                                                  <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> 
                                             </div>
                                             <div class="panel-body">
                                                  <div class="table-responsive">
                                                       <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                            <thead>
                                                                 <tr>
                                                                      <th>DRIVER NAME</th>
                                                                      <th>COMPANY NAME</th>
                                                                      <th>EMAIL</th>
                                                                      <th>Sign up date</th>
                                                                      <!--<th>SERVICE LOCATION</th>-->
                                                                      <th>MOBILE</th>
                                                                      <!--<th>LANGUAGE</th>-->
																	  <th>STATUS</th>
                                                                      <th>EDIT DOCUMENT</th>
                                                                      <th style="text-align: center;">ACTION</th>
                                                                      
                                                                 </tr>
                                                            </thead>
                                                            <tbody>
                                                                 <? for ($i = 0; $i < count($data_drv); $i++) { ?>
                                                                 <tr class="gradeA">
                                                                      <td><?= $data_drv[$i]['vName'] . ' ' . $data_drv[$i]['vLastName']; ?></td>
                                                                      <td><?= $data_drv[$i]['companyFirstName'] . ' ' . $data_drv[$i]['companyLastName']; ?></td>
                                                                      <td><?= $generalobjAdmin->clearEmail($data_drv[$i]['vEmail']);?></td>
                                                                      <td data-order="<?=$data_drv[$i]['iDriverId']; ?>"><?= $data_drv[$i]['tRegistrationDate']; ?></td>
                                                                      <!--<td class="center"><?= $data_drv[$i]['vServiceLoc']; ?></td>-->
                                                                      <td><?= $generalobjAdmin->clearPhone($data_drv[$i]['vPhone']);?></td>
                                                                      <!--<td><?= $data_drv[$i]['vLang']; ?></td>-->
                                  																	  <td width="10%" align="center">
          <a href="driver.php?iDriverId=<?= $data_drv[$i]['iDriverId']; ?>&status=<?= ($data_drv[$i]['eStatus'] == "active") ? 'inactive' : 'active' ?>&type=<?=$actionType;?>">
                                    																			<button class="btn">
                                    																				<i class="<?= ($data_drv[$i]['eStatus'] == "Active") ? 'icon-eye-open' : 'icon-eye-close' ?>"></i> <?= ucfirst($data_drv[$i]['eStatus']); ?>
                                    																			</button>
                                  																		  </a>
                                                                      </td>
                                                                      <td align="center" width="10%">
                                                                                <a href="driver_document_action.php?id=<?= $data_drv[$i]['iDriverId']; ?>&action=edit&type=<? echo $_REQUEST['type'] ?>">
                                                                                     <button class="btn btn-primary">
                                                                                          <i class="icon-pencil icon-white"></i> Edit Documents
                                                                                     </button>
                                                                                </a>
                                                                        </td>

                                                                      <td align="center" width="17%">
                                                                           <a href="driver_action.php?id=<?= $data_drv[$i]['iDriverId']; ?>" style="float: left;">
                                                                                <button class="btn btn-primary">
                                                                                     <i class="icon-pencil icon-white"></i> Edit
                                                                                </button>
                                                                           </a>
                                                                           <form name="delete_form" id="delete_form" method="post" action="" onSubmit="return confirm('Are you sure you want to delete <?= $data_drv[$i]['vName']; ?> <?= $data_drv[$i]['vLastName']; ?> record?')" class="margin0">
                                                                                <input type="hidden" name="hdn_del_id" id="hdn_del_id" value="<?= $data_drv[$i]['iDriverId']; ?>">
                                                                                <input type="hidden" name="action" id="action" value="delete">
                                                                                <button class="btn btn-danger">
                                                                                     <i class="icon-remove icon-white"></i> Delete
                                                                                </button>
                                                                           </form>
                                                                      </td>
                                                                      
                                                                 </tr>
                                                                 <? } ?>
                                                            </tbody>
                                                       </table>
                                                  </div>

                                             </div>
                                        </div>
                                   </div> <!--TABLE-END-->
                              </div>
                         </div>
                         <div style="clear:both;"></div>
                    </div>
               </div>
               <!--END PAGE CONTENT -->
          </div>
          <!--END MAIN WRAPPER -->


        <? include_once('footer.php');?>
    <script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/plugins/dataTables/dataTables.bootstrap.js"></script>
	<script>
		$(document).ready(function () {
			$('#dataTables-example').dataTable({
        "order": [[ 3, "desc" ]]
      });
		});


	</script>
</body>
<!-- END BODY-->
</html>