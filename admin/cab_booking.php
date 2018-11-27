<?php
include_once('../common.php');

if (!isset($generalobjAdmin)) {
     require_once(TPATH_CLASS . "class.general_admin.php");
     $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$iAdminId = isset($_REQUEST['iAdminId']) ? $_REQUEST['iAdminId'] : '';
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'view';
$hdn_del_id = isset($_REQUEST['hdn_del_id']) ? $_REQUEST['hdn_del_id'] : '';
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
$vassign = isset($_REQUEST['vassign']) ? $_REQUEST['vassign'] : 0;
$script = 'CabBooking';

if ($iAdminId != '' && $status != '') {
  	if(SITE_TYPE !='Demo'){
     $query = "UPDATE administrators SET eStatus = '" . $status . "' WHERE iAdminId = '" . $iAdminId . "'";
     $obj->sql_query($query);
   }
  else{
    header("Location:admin.php?success=2");exit;
  }
}

$cmp_ssql = "";
if(SITE_TYPE =='Demo'){
	$cmp_ssql = " And cb.dAddredDate > '".WEEK_DATE."'";
}

if ($action == 'view') {
     $sql = "SELECT cb.*,CONCAT(ru.vName,' ',ru.vLastName) as rider,CONCAT(rd.vName,' ',rd.vLastName) as driver,vt.vVehicleType FROM cab_booking as cb
	 LEFT JOIN register_user as ru on ru.iUserId=cb.iUserId
	 LEFT JOIN register_driver as rd on rd.iDriverId=cb.iDriverId
	 LEFT JOIN vehicle_type as vt on vt.iVehicleTypeId=cb.iVehicleTypeId WHERE 1".$cmp_ssql;
     $data_drv = $obj->MySQLSelect($sql);
	 // echo "<pre>";
	 // print_r($data_drv); die;
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

     <!-- BEGIN HEAD-->
     <head>
          <meta charset="UTF-8" />
          <title>Admin | Company</title>
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
                         <? if ($success == "1") {?>
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php
                                if ($vassign != "1") {
                                    ?>
                                    Booking Has Been Added Successfully.
                                <?php } else {
                                    ?>
                                    Driver Has Been Assigned Successfully.
                                <?php } ?>

                            </div><br/>
                         <? }elseif ($success == 2) { ?>
                           <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                           </div><br/>
                         <? }?>
                         <div id="add-hide-show-div">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <h2><?php echo $langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?>  Later Bookings</h2>
                                   </div>
                              </div>
                              <hr />
                         </div>
                         <div class="table-list">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <div class="panel panel-default">
                                             <div class="panel-heading driver-neww1 driver-neww2">
                                             <b><?php echo $langage_lbl_admin['LBL_RIDE_TXT_ADMIN'];?>  Later Bookings</b>
                                             </div>
                                             <div style="clear:both;"></div>
                                             <div class="panel-body">
                                                  <div class="table-responsive">
                                                       <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                            <thead>
                                                                 <tr>
                                                                      <th><?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?> </th>
                                                                      <th>Date</th>
                                                                      <th>Pickup</th>
                                                                      <th>Destination</th>
																	  <th><?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> </th>
																	  <th><?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?>  Details</th>
																	  <th>Status</th>
                                                                 </tr>
                                                            </thead>
                                                            <tbody>
                                                                 <? for ($i = 0; $i < count($data_drv); $i++) { ?>
                                                                 <tr class="gradeA">
                                                                      <td width="10%"><?= $data_drv[$i]['rider']; ?></td>
																	  <td width="10%" data-order="<?=$data_drv[$i]['iCabBookingId']; ?>"><?= date('dS M Y,',strtotime($data_drv[$i]['dBooking_date'])); ?> <?= date('H:i',strtotime($data_drv[$i]['dBooking_date'])); ?></td>
																	  <td><?= $data_drv[$i]['vSourceAddresss']; ?></td>
																	  <td><?= $data_drv[$i]['tDestAddress']; ?></td>
																		<?php if ($data_drv[$i]['eAutoAssign'] == "Yes") { ?>
																			<td width="10%">Driver Auto Assigned<a class="btn btn-info" href="add_booking.php?booking_id=<?= $data_drv[$i]['iCabBookingId']; ?>" data-tooltip="tooltip" title="Edit"><i class="icon-edit icon-flip-horizontal icon-white"></i></a><br>( Car Type : <?= $data_drv[$i]['vVehicleType']; ?>)</td>
																		<?php } else if ($data_drv[$i]['eStatus'] == "Pending") { ?>
																			<td width="10%"><a class="btn btn-info" href="add_booking.php?booking_id=<?= $data_drv[$i]['iCabBookingId']; ?>"><i class="icon-shield icon-flip-horizontal icon-white"></i> Assign Driver</a><br>( Car Type : <?= $data_drv[$i]['vVehicleType']; ?>)</td>
																		<?php } else if($data_drv[$i]['eCancelBy'] == "Driver" && $data_drv[$i]['eStatus'] == "Cancel") { ?>
																			<td width="10%"><a class="btn btn-info" href="add_booking.php?booking_id=<?= $data_drv[$i]['iCabBookingId']; ?>"><i class="icon-shield icon-flip-horizontal icon-white"></i> Assign Driver</a><br>( Car Type : <?= $data_drv[$i]['vVehicleType']; ?>)</td>
																		<?php } else if ($data_drv[$i]['driver'] != "" && $data_drv[$i]['driver'] != "0") { ?>
																			<td width="10%"><b><?= $data_drv[$i]['driver']; ?></b><br>( Car Type : <?= $data_drv[$i]['vVehicleType']; ?>) </td>
																		<?php } else  { ?>
																			<td width="10%">---<br>( Car Type : <?= $data_drv[$i]['vVehicleType']; ?>)</td>
																		<?php } ?>
																	  <td width="10%"><?php if($data_drv[$i]['iTripId'] != "" && $data_drv[$i]['eStatus'] == "Completed") { ?>
																		<a class="btn btn-primary" href="javascript:void(0);" onclick='javascript:window.open("invoice.php?iTripId=<?=$data_drv[$i]['iTripId']?>","_blank")';>View</a>
																		  <?php }else {echo "---"; } ?>
																		  
																		  
																		  </td>
																	  <td width="15%"><?php if($data_drv[$i]['eStatus'] == "Assign") {
																		  echo "Driver Assigned";
																	   }
																	   else
																	   { 
																			$sql="select iActive from trips where iTripId=".$data_drv[$i]['iTripId'];
																			$data_stat=$obj->MySQLSelect($sql);
																			//echo "<pre>";print_r($data_stat); die;
																			if($data_stat)
																			{
																				for($d=0;$d<count($data_stat);$d++)
																				{
																					if($data_stat[$d]['iActive'] == "Canceled")
																					{
																						echo "Canceled By Passenger";
																					}
																					else
																					{
																						echo $data_stat[$d]['iActive']; 	
																					}
																					
																				}
																			}
																			else
																			{
																				if($data_drv[$i]['eStatus'] == "Cancel")
																				{
																					echo "Canceled By Driver";
																				}
																				else
																				{
																					echo $data_drv[$i]['eStatus'];
																				}
																				
																			}
																		}
																		?>
																	<?
																		if ($data_drv[$i]['eStatus'] == "Cancel") {
																	?>
																		<br /><a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#uiModal_<?=$data_drv[$i]['iCabBookingId'];?>">Cancel Reason</a>
																	<?           
																		}
																	?>
																  </td>
																</tr>
																 <div class="modal fade" id="uiModal_<?=$data_drv[$i]['iCabBookingId'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																	  <div class="modal-content image-upload-1" style="width:400px;">
																		   <div class="upload-content" style="width:350px; padding:0px;">
																				<h3>Booking Cancel Reason</h3>
																				<h4>Cancel By: <?=$data_drv[$i]['eCancelBy'];?></h4>
																				<h4>Cancel Reason: <?=$data_drv[$i]['vCancelReason'];?></h4>
																				<form class="form-horizontal" id="frm6" method="post" enctype="multipart/form-data" action="" name="frm6">
																				<input style="margin:10px 0 20px;" type="button" class="save" data-dismiss="modal" name="cancel" value="Close"></form>
																		   </div>
																	  </div>
																 </div>
                                                                 <? } ?>
                                                            </tbody>
                                                       </table>
                                                  </div>
                                             </div>
                                        </div>
                                   </div> <!--TABLE-END-->
                              </div>
                         </div>
                         <div class="clear"></div>
                    </div>
               </div>
               <!--END PAGE CONTENT -->
          </div>
          <!--END MAIN WRAPPER -->


          <?
          include_once('footer.php');
          ?>
          <script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
          <script src="../assets/plugins/dataTables/dataTables.bootstrap.js"></script>
          <script>
                                                                                                                                                                                                     $(document).ready(function () {
                                                                                                                                                                                                        $('#dataTables-example').dataTable({
        "order": [[ 1, "desc" ]]
      });
                                                                                                                                                                                                     });
                                                                                                                                                                                                     function confirm_delete()
                                                                                                                                                                                                     {
                                                                                                                                                                                                        var confirm_ans = confirm("Are You sure You want to Delete Driver?");
                                                                                                                                                                                                        return confirm_ans;
                                                                                                                                                                                                        //document.getElementById(id).submit();
                                                                                                                                                                                                     }
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
          </script>
     </body>
     <!-- END BODY-->
</html>
