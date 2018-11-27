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
   }
  else{
    header("Location:admin.php?success=2");exit;
  }
}


if ($action == 'view') {
     $sql = "SELECT * FROM administrators where eStatus != 'Delete'";
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
                         <? }?>
                         <div id="add-hide-show-div">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <h2>ADMIN</h2>
                                        <!--<input type="button" id="" value="ADD A DRIVER" class="add-btn">-->
                                        <a class="add-btn" href="admin_action.php" style="text-align: center;">ADD A ADMIN</a>
                                        <input type="button" id="cancel-add-form" value="CANCEL" class="cancel-btn">
                                   </div>
                              </div>
                              <hr />
                         </div>
                         <div id="add-hide-div">
                              <form name = "myForm" method="post" action="">
                                   <div class="page-form">
                                        <h2>ADD ADMIN</h2>
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
                                                  USER NAME<br>
                                                  <input type="text" name="vUser" class="form-control" placeholder="" required>
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
                                                  ADMIN
                                             </div>
                                             <div class="panel-body">
                                                  <div class="table-responsive">
                                                       <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                            <thead>
                                                                 <tr>
                                                                      <th>ADMIN NAME</th>

                                                                      <th>EMAIL</th>
                                                                      <!--<th>SERVICE LOCATION</th>-->
                                                                      <th>MOBILE</th>

																	  <th>STATUS</th>
                                                                      <td>EDIT</td>


                                                                 </tr>
                                                            </thead>
                                                            <tbody>
                                                                 <? for ($i = 0; $i < count($data_drv); $i++) { ?>
                                                                 <tr class="gradeA">
                                                                      <td><?= $data_drv[$i]['vFirstName'] . ' ' . $data_drv[$i]['vLastName']; ?></td>

                                                                      <td><?= $generalobjAdmin->clearEmail($data_drv[$i]['vEmail']);?></td>

                                                                      <td><?= $generalobjAdmin->clearPhone($data_drv[$i]['vContactNo']); ?></td>

																	  <td width="10%" align="center">
																	  <?php if($data_drv[$i]['eDefault']!='Yes'){?>
																	<a href="admin.php?iAdminId=<?= $data_drv[$i]['iAdminId']; ?>&status=<?= ($data_drv[$i]['eStatus'] == "Active") ? 'Inactive' : 'Active' ?>">
																			<button class="btn">
																				<i class="<?= ($data_drv[$i]['eStatus'] == "Active") ? 'icon-eye-open' : 'icon-eye-close' ?>"></i> <?= ucfirst($data_drv[$i]['eStatus']); ?>
																			</button>
																		</a>
																		<?php
																	  }
																	  else
																	  {
																		echo "<b> Active</b>";
																		 }
																	  ?>
																		</td>
                                                                     <td class="center" width="10%">
																<a href="admin_action.php?id=<?=$data_drv[$i]['iAdminId'];?>">
																	<button class="btn btn-primary">
																		<i class="icon-pencil icon-white"></i> Edit
																	</button>
																</a>
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
