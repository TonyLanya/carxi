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
$script = 'Admin';


if ($iAdminId != '' && $status != '') {
  	if(SITE_TYPE !='Demo'){
     $query = "UPDATE administrators SET eStatus = '" . $status . "' WHERE iAdminId = '" . $iAdminId . "'";
     $obj->sql_query($query);
	 $var_msg="Admin ".$status." Successfully.";
	 header("Location:admin.php?success=3&var_msg=".$var_msg);
   }
  else{
    header("Location:admin.php?success=2");exit;
  }
}


if ($action == 'view') {
     $sql = "SELECT ad.*,ag.vGroup FROM administrators ad left join admin_groups ag on
			ad.iGroupId=ag.iGroupId
			where ad.eStatus != 'Delete'";
     $data_drv = $obj->MySQLSelect($sql);
	 //echo "<pre>";print_r($data_drv);exit;
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
		  <!-- Main LOading -->
			
          <!-- MAIN WRAPPER -->
          <div id="wrap">
               <? include_once('header.php'); ?>
               <? include_once('left_menu.php'); ?>

               <!--PAGE CONTENT -->
               <div id="content">
                    <div class="inner">
                         <? if($success == 1) { ?>
                         <div class="alert alert-success alert-dismissable">
                              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                              Record Updated successfully.
                         </div><br/>
                         <? }elseif ($success == 2) { ?>
                           <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                           </div><br/>
                         <? }else if($success == 3) { ?>
                         <div class="alert alert-success alert-dismissable">
                              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                             <?=isset($_REQUEST['var_msg'] )? $_REQUEST['var_msg'] : '';?>
                         </div><br/>
                         <? }?>
                         <div id="add-hide-show-div">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <h2>Admin</h2>
                                        <!--<input type="button" id="" value="ADD A DRIVER" class="add-btn">-->
                                        <a class="add-btn" href="admin_action.php" style="text-align: center;">Add Admin</a>
                                        <input type="button" id="cancel-add-form" value="CANCEL" class="cancel-btn">
                                   </div>
                              </div>
                              <hr />
                         </div>
                         <div class="table-list">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading driver-neww1 driver-neww2">
                                                 <b>Admin</b>
                                             </div>
                                             <div style="clear:both;"></div>
                                             <div class="panel-body">
                                                  <div class="table-responsive">
                                                       <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                            <thead>
                                                                 <tr>
                                                                      <th>Admin Name</th>
                                                                      <th>Email</th>
                                                                      <!--<th>SERVICE LOCATION</th>-->
                                                                      <th>Admin Roles</th>
                                                                      <th>Mobile</th>
																	  <th>Status</th>
                                                                      <th align="center" style="text-align:center;">Action</th>


                                                                 </tr>
                                                            </thead>
                                                            <tbody>
                                                                 <? for ($i = 0; $i < count($data_drv); $i++) { ?>
																 
                                                                 <tr class="gradeA">
                                                                      <td><?= $data_drv[$i]['vFirstName'] . ' ' . $data_drv[$i]['vLastName']; ?></td>

                                                                      <td><?= $generalobjAdmin->clearEmail($data_drv[$i]['vEmail']);?></td>
                                                                      
																	  <td><?=$data_drv[$i]['vGroup'] ;?></td>

                                                                      <td><?= $generalobjAdmin->clearPhone($data_drv[$i]['vContactNo']); ?></td>

																	  <td width="10%" align="center">
																	  <?php if($data_drv[$i]['eDefault']!='Yes'){?>
																
																	<? if($data_drv[$i]['eStatus'] == 'Active') {
																			$dis_img = "img/active-icon.png";
																		}else if($data_drv[$i]['eStatus'] == 'Inactive'){
																			 $dis_img = "img/inactive-icon.png";
																		}else if($data_drv[$i]['eStatus'] == 'Deleted'){
																			$dis_img = "img/delete-icon.png";
																		}?>
																			<img src="<?=$dis_img;?>" alt="image">
																		<?php
																	  }
																	  else
																	  {
																		?><img src="img/active-icon.png" alt="image"><?
																		}
																	  ?>
																		</td>
                                                                 <!--    <td class="center" width="10%">
																<a href="admin_action.php?id=<?//=$data_drv[$i]['iAdminId'];?>">
																	<!--<button class="btn btn-primary">
																		<i class="icon-pencil icon-white"></i> Edit
																	</button>
																	<img src="img/edit-icon.png" alt="image">
																</a>
															</td>-->
															<td class="center" width="15%"  align="center" style="text-align:center;">
											
											<?php /* Edit Icon */ ?>
											<a href="admin_action.php?id=<?=$data_drv[$i]['iAdminId'];?>" data-toggle="tooltip" title="Edit">
												<img src="img/edit-icon.png" alt="Edit">
											</a>
											
											<?php /* Status Icon */ ?>
											<?php if($data_drv[$i]['eDefault']!='Yes'){?>
											<a href="admin.php?iAdminId=<?= $data_drv[$i]['iAdminId']; ?>&status=Active" data-toggle="tooltip" title="Active Admin">
												<img src="img/active-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
											</a>
											<a href="admin.php?iAdminId=<?= $data_drv[$i]['iAdminId']; ?>&status=Inactive" data-toggle="tooltip" title="Inactive Admin">
												<img src="img/inactive-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >	
											</a>
											
											<?php /* Delete Icon */ ?>
											<!--	<a href="admin.php?iAdminId=<?//= $data_drv[$i]['iAdminId']; ?>&status=<?= ($data_drv[$i]['eStatus'] == "Active") ? 'Inactive' : 'Active' ?>" onclick="changeStatusDelete('<?php echo $data_drv[$i]['iAdminId']; ?>')"  data-toggle="tooltip" title="Delete">
												<img src="img/delete-icon.png" alt="Delete" >
											</a>-->
											<?php } ?>
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
                                                                                                                                                                                                        $('#dataTables-example').dataTable();
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
