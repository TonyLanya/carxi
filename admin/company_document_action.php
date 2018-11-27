<?php
	include_once('../common.php');
	if (!isset($generalobjAdmin)) {
		require_once(TPATH_CLASS . "class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->check_member_login();

	require_once(TPATH_CLASS . "/Imagecrop.class.php");
	$thumb = new thumbnail();

	$sql = "select * from country";
	$db_country = $obj->MySQLSelect($sql);

	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : 0;
	$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != '') ? 'Edit' : 'Add';
	$script = 'Company';
	$sql = "select * from language_master where eStatus = 'Active'";
	$db_lang = $obj->MySQLSelect($sql);

	$sql = "select * from company where iCompanyId = '" . $_REQUEST['id'] . "'";
	$db_user = $obj->MySQLSelect($sql);

	$vName=$db_user[0]['vName'];
	$vCompany=$db_user[0]['vCompany'];

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	$success = isset($_REQUEST["success"]) ? $_REQUEST["success"] : 0;
	$var_msg = isset($_REQUEST["var_msg"]) ? $_REQUEST["var_msg"] : '';

	if ($action == 'noc') {
		if(SITE_TYPE=='Demo')
		{
			$var_msg="Edit  Delete Record Feature has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.";
			header("location:company_document_action.php?success=2&id=".$_REQUEST['id']."&var_msg=" . $var_msg);
			exit;
		}

		if (isset($_POST['doc_path'])) {
			$doc_path = $_POST['doc_path'];
		}
		$temp_gallery = $doc_path . '/';
		$image_object = $_FILES['noc']['tmp_name'];
		$image_name = $_FILES['noc']['name'];

		if($image_name=="")
		{
			$var_msg="Please Upload file format for Image. Valid formats are pdf,doc,docx,jpg,jpeg,gif,png";
			header("location:company_document_action.php?success=1&id=".$_REQUEST['id']."&var_msg=" . $var_msg);
			//$generalobjAdmin->getPostForm($_POST, $var_msg, "company_document_action.php?success=0&id=".$_REQUEST['id']."&var_msg=".$var_msg);

			exit;
		}
		else if ($image_name != "") {
			$check_file_query = "select iCompanyId,vNoc from company where iCompanyId=" . $_REQUEST['id'];
			$check_file = $obj->sql_query($check_file_query);
			$check_file['vNoc'] = $doc_path . '/' . $_REQUEST['id'] . '/' . $check_file[0]['vNoc'];

			$filecheck = basename($_FILES['noc']['name']);
			$fileextarr = explode(".", $filecheck);
			$ext = strtolower($fileextarr[count($fileextarr) - 1]);
			$flag_error = 0;
			if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp" && $ext != "pdf" && $ext != "doc" && $ext != "docx") {
				$flag_error = 1;
				$var_msg = "You have selected wrong file format for Image. Valid formats are pdf,doc,docx,jpg,jpeg,gif,png";
			}

			if ($flag_error == 1) {
				header("location:company_document_action.php?success=1&id=".$_REQUEST['id']."&var_msg=" . $var_msg);
				//echo"<pre>";print_r($var_msg);exit;
				//$generalobjAdmin->getPostForm($_POST, $var_msg, "company_document_action.php?success=0&id=".$_REQUEST['id']."&var_msg=".$var_msg);
				exit;
				} else {
				$Photo_Gallery_folder = $doc_path . '/' . $_REQUEST['id'] . '/';
				if (!is_dir($Photo_Gallery_folder)) {
					mkdir($Photo_Gallery_folder, 0777);
				}
				//$img = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_documnet_size1"], $tconfig["tsite_upload_documnet_size2"], '', '', '', '', 'Y', '', $Photo_Gallery_folder);
				$vFile = $generalobj->fileupload($Photo_Gallery_folder,$image_object,$image_name,$prefix='', $vaildExt="pdf,doc,docx,jpg,jpeg,gif,png");
				$vImage = $vFile[0];
				$var_msg = "NOC File uploaded successfully";
				$tbl = 'company';
				$sql = "SELECT * FROM " . $tbl . " WHERE iCompanyId = '" .  $_REQUEST['id'] . "'";
				$db_data = $obj->MySQLSelect($sql);
				$q = "INSERT INTO ";
				$where = '';

				if (count($db_data) > 0) {
					$q = "UPDATE ";
					$where = " WHERE `iCompanyId` = '" . $_REQUEST['id'] . "'";
				}
				$query = $q . " `" . $tbl . "` SET `vNoc` = '" . $vImage . "'" . $where ;
				$obj->sql_query($query);

				//Start :: Log Data Save
				if(empty($check_file[0]['vNoc'])){ $vNocPath = $vImage ; }else{ $vNocPath = $check_file[0]['vNoc']; }
				$generalobj->save_log_data ($_SESSION['sess_iUserId'],$_REQUEST['id'],'company','noc',$vNocPath);
				//End :: Log Data Save

				// Start :: Status in edit a Document upload time
				//$set_value = "`eStatus` ='inactive'";
				//$generalobj->estatus_change('company','iCompanyId',$_REQUEST['id'],$set_value);
				// End :: Status in edit a Document upload time

				  header("location:company_document_action.php?success=1&id=".$_REQUEST['id']."&var_msg=" . $var_msg);
			}
		}

	}

	if ($action == 'certi') {
		if(SITE_TYPE=='Demo')
		{
			$var_msg="Edit  Delete Record Feature has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.";
			header("location:company_document_action.php?success=2&id=".$_REQUEST['id']."&var_msg=" . $var_msg);
			exit;
		}
		if (isset($_POST['doc_path'])) {
			$doc_path = $_POST['doc_path'];
		}
		$temp_gallery = $doc_path . '/';
		$image_object = $_FILES['certi']['tmp_name'];
		$image_name = $_FILES['certi']['name'];

		if($image_name=="")
		{
			$var_msg="Please Upload valid file format for Image. Valid formats are pdf,doc,docx,jpg,jpeg,gif,png";
			header("location:company_document_action.php?success=0&id=".$_REQUEST['id']."&var_msg=" . $var_msg);
			//$generalobjAdmin->getPostForm($_POST, $var_msg, "company_document_action.php?success=0&id=".$_REQUEST['id']."&var_msg=".$var_msg);

			exit;
		}

		else if ($image_name != "") {
			$check_file_query = "select iCompanyId,vCerti from company where iCompanyId=" . $_REQUEST['id'];
			$check_file = $obj->sql_query($check_file_query);
			$check_file['vCerti'] = $doc_path . '/' . $_REQUEST['id'] . '/' . $check_file[0]['vCerti'];

			/* if ($check_file['vCerti'] != '' && file_exists($check_file['vCerti'])) {
				unlink($doc_path . '/' . $_REQUEST['id'] . '/' . $check_file[0]['vCerti']);
				unlink($doc_path . '/' . $_REQUEST['id'] . '/1_' . $check_file[0]['vCerti']);
				unlink($doc_path . '/' . $_REQUEST['id'] . '/2_' . $check_file[0]['vCerti']);
			}*/

			$filecheck = basename($_FILES['certi']['name']);
			$fileextarr = explode(".", $filecheck);
			$ext = strtolower($fileextarr[count($fileextarr) - 1]);
			$flag_error = 0;
			if ($ext != "jpg" && $ext != "gif" && $ext != "png" && $ext != "jpeg" && $ext != "bmp" && $ext != "pdf" && $ext != "doc" && $ext != "docx") {
				$flag_error = 1;
				$var_msg = "You have selected wrong file format for Image. Valid formats are pdf,doc,docx,jpg,jpeg,gif,png";
			}
			/* if ($_FILES['certi']['size'] > 1048576) {
				$flag_error = 1;
				$var_msg = "Image Size is too Large";
			}*/
			if ($flag_error == 1) {
				$generalobj->getPostForm($_POST, $var_msg, "company_document_action.php?success=0&id=".$_REQUEST['id']."&var_msg=".$var_msg);
				exit;
				} else {
				$Photo_Gallery_folder = $doc_path . '/' . $_REQUEST['id'] . '/';
				if (!is_dir($Photo_Gallery_folder)) {
					mkdir($Photo_Gallery_folder, 0777);
				}
				//$img = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_documnet_size1"], $tconfig["tsite_upload_documnet_size2"], '', '', '', '', 'Y', '', $Photo_Gallery_folder);
				$vFile = $generalobj->fileupload($Photo_Gallery_folder,$image_object,$image_name,$prefix='', $vaildExt="pdf,doc,docx,jpg,jpeg,gif,png");
				$vImage = $vFile[0];
				$var_msg = "Certificate File uploaded successfully";
				$tbl = 'company';
				$sql = "SELECT * FROM " . $tbl . " WHERE iCompanyId = '" .$_REQUEST['id']. "'";
				$db_data = $obj->MySQLSelect($sql);
				$q = "INSERT INTO ";
				$where = '';

				if (count($db_data) > 0) {
					$q = "UPDATE ";
					$where = " WHERE `iCompanyId` = '" .$_REQUEST['id']. "'";
				}
				$query = $q . " `" . $tbl . "` SET `vCerti` = '" . $vImage . "'". $where;
				$obj->sql_query($query);

				//Start :: Log Data Save
				if(empty($check_file[0]['vCerti'])){ $vCertiPath = $vImage ; }else{ $vCertiPath = $check_file[0]['vCerti']; }
				$generalobj->save_log_data ($_SESSION['sess_iUserId'],$_REQUEST['id'],'company','certificate',$vCertiPath);
				//End :: Log Data Save

				// Start :: Status in edit a Document upload time
				//$set_value = "`eStatus` ='inactive'";
				//$generalobj->estatus_change('company','iCompanyId',$_REQUEST['id'],$set_value);
				// End :: Status in edit a Document upload time

				header("location:company_document_action.php?success=1&id=".$_REQUEST['id']."&var_msg=" . $var_msg);
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
		<title><?=$SITE_NAME?> | Driver <?= $action; ?></title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<meta content="" name="keywords" />
		<meta content="" name="description" />
		<meta content="" name="author" />
		<link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

		<?  include_once('global_files.php'); ?>
		<!-- On OFF switch -->
		<link href="../assets/css/jquery-ui.css" rel="stylesheet" />
		<link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
		<link rel="stylesheet" href="../assets/css/bootstrap-fileupload.min.css" >
		<script src="../assets/plugins/jasny/js/bootstrap-fileupload.js"></script>
	</head>
	<!-- END  HEAD-->
	<!-- BEGIN BODY-->
	<body class="padTop53 " >

		<!-- MAIN WRAPPER -->
		<div id="wrap">
			<?
				include_once('header.php');
			?>
			<?
				include_once('left_menu.php');
			?>
			<!--PAGE CONTENT -->
			<div id="content">
				<div class="inner">
					<div class="row">
						<div class="col-lg-12">
							<h2><?= ucfirst($action); ?> Document of  <?= $vCompany; ?></h2>
							<a href="company.php">
								<input type="button" value="Back to Listing" class="add-btn">
							</a>
						</div>
					</div>
					<hr />
					<div class="body-div">
						<div class="form-group">
							<? if ($success==3) {?>
								<div class="alert alert-success alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<?= $var_msg ?>
								</div><br/>
								<?}
							?>
							<? if ($success==1) {?>
								<div class="alert alert-success alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<?= $var_msg ?>
								</div><br/>
								<?}
							?>
							<? if ($success==2) {?>
								<div class="alert alert-danger alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<?= $var_msg ?>
								</div><br/>
								<?}
							?>
							<input type="hidden" name="id" value="<?= $id; ?>"/>
							<div class="row">
								<div class="col-sm-12">
									<h4 style="margin-top:0px;">DOCUMENTS</h4>
								</div>
							</div>
							<div class="row company-document-action">
								<div class="col-lg-3">
									<div class="panel panel-default upload-clicking">
									<div class="panel-heading">NOC</div>
									<div class="panel-body">
											<?php if ($db_user[0]['vNoc'] != '') {

												$img_path = $tconfig["tsite_upload_compnay_doc"];
											?>
											<?php $file_ext = $generalobj->file_ext($db_user[0]['vNoc']);
												if($file_ext == 'is_image'){ ?>
												<img src = "<?= $img_path  . '/' . $_REQUEST['id'] . '/' . $db_user[0]['vNoc'] ?>" style="width:200px;" alt ="NOC not found"/>
												<?php }else{ ?>
												<a href="<?= $img_path  . '/' . $_REQUEST['id'] . '/' . $db_user[0]['vNoc']  ?>" target="_blank">NOC File</a>
											<?php } ?>
											<?php } else { ?>
											Need to upload 
											<?php } ?>
											<b>
                                            <button class="btn btn-info" data-toggle="modal" data-target="#uiModal_2" >
												
												<?php if ($db_user[0]['vNoc'] != '') {
													echo $langage_lbl_admin['LBL_UPDATE_NOC'];
												}else{
													echo $langage_lbl_admin['LBL_ADD_NOC'];

												}?>


											</button></b>
										</div>
									</div>
								</div>

								<div class="col-lg-3">
									<div class="panel panel-default upload-clicking">
										<div class="panel-heading">
											VERIFICATION CERTIFICATE
										</div>
										<div class="panel-body">
											<?php
												if ($db_user[0]['vCerti'] != '') {
												$img_path = $tconfig["tsite_upload_compnay_doc"]; ?>
												<?php $file_ext = $generalobj->file_ext($db_user[0]['vCerti']);
													if($file_ext == 'is_image'){ ?>
													<img src = "<?= $img_path . '/' . $_REQUEST['id'] . '/' . $db_user[0]['vCerti'] ?>" style="width:200px;" alt ="NOC not found"/>
													<?php }else{ ?>
													<a href="<?= $img_path . '/' . $_REQUEST['id']  . '/' . $db_user[0]['vCerti']  ?>" target="_blank">NOC File</a>
												<?php } ?>
												<?php } else { ?>
												Need to upload
											<? } ?>
                                            <b>
											<button class="btn btn-info" data-toggle="modal" data-target="#uiModal_3" >
												
												<?php if ($db_user[0]['vNoc'] != '') {
													echo $langage_lbl_admin['LBL_UPDATE_CERTI'];
												}else{
													echo $langage_lbl_admin['LBL_ADD_CERTI'];

												}?>
											</button>
                                            </b>
										</div>
									</div>
								</div>


								<div class="col-lg-12">
									<div class="modal fade" id="uiModal_2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-content image-upload-1">
											<div class="upload-content">
												<h4>NOC</h4>
												<form class="form-horizontal" id="frm7" method="post" enctype="multipart/form-data" action="company_document_action.php?id=<?php echo $_REQUEST['id']; ?>" name="frm7">
													<input type="hidden" name="action" value ="noc"/>
													<input type="hidden" name="doc_path" value ="    <?php echo $tconfig["tsite_upload_compnay_doc_path"]; ?>"/>
													<div class="form-group">
														<div class="col-lg-12">
															<div class="fileupload fileupload-new" data-provides="fileupload">
																<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px; ">
																	<?php if ($db_user[0]['vNoc'] == '') { ?>
																		NOC Image
																		<?php } else { ?>
																		<?php $file_ext = $generalobj->file_ext($db_user[0]['vNoc']);
																			if($file_ext == 'is_image'){ ?>
																			<img src = "<?= $img_path . '/' . $_REQUEST['id']  . '/' . $db_user[0]['vNoc'] ?>" style="width:200px;" alt ="NOC not found"/>
																			<?php }else{ ?>
																			<a href="<?= $img_path .  '/' . $_REQUEST['id'] . '/' . $db_user[0]['vNoc'] ?>" target="_blank">NOC File</a>
																		<?php } ?>
																	<?php } ?>
																</div>
																<div>
																	<span class="btn btn-file btn-success"><span class="fileupload-new">Upload NOC</span><span class="fileupload-exists">Change</span><input type="file" name="noc"/></span>
																	<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
																</div>
															</div>
														</div>
													</div>
													<input type="submit" class="save" name="save" value="Save"><input type="button" class="cancel" data-dismiss="modal" name="cancel" value="Cancel">
												</form>


											</div>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="modal fade" id="uiModal_3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-content image-upload-1">
												<div class="upload-content">
													<h4>Police Verification Certificate</h4>
													<form class="form-horizontal" id="frm8" method="post" enctype="multipart/form-data" action="company_document_action.php?id=<?php echo $_REQUEST['id']; ?>">
														<input type="hidden" name="action" value="certi"/>
														<input type="hidden" name="doc_path" value ="<?php echo $tconfig["tsite_upload_compnay_doc_path"]; ?> "/>

														<div class="form-group">
															<div class="col-lg-12">
																<div class="fileupload fileupload-new" data-provides="fileupload">
																	<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px; ">
																		<?php if ($db_user[0]['vCerti'] == '') { ?>
																			Certificate Image
																			<?php } else {
																				$img_path = $tconfig["tsite_upload_compnay_doc"];
																			?>
																			<?php $file_ext = $generalobj->file_ext($db_user[0]['vCerti']);
																				if($file_ext == 'is_image'){ ?>
																				<img src = "<?= $img_path . '/'.$_REQUEST['id']. '/'. $db_user[0]['vCerti'] ?>" style="width:200px;" alt ="NOC not found"/>
																				<?php }else{ ?>
																				<a href="<?= $img_path . '/' . $_REQUEST['id']. '/' . $db_user[0]['vCerti']  ?>" target="_blank">NOC File</a>
																			<?php } ?>
                                      <?php } ?>
																	</div>
																	<div>
																		<span class="btn btn-file btn-success"><span class="fileupload-new">Upload Certificate</span><span class="fileupload-exists">Change</span>
																		<input type="file" name="certi"/></span>
																		<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
																	</div>
																</div>
															</div>
														</div>
														<input type="submit" class="save" name="save" value="Save"><input type="button" class="cancel" data-dismiss="modal" name="cancel" value="Cancel">
													</form>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>






		<!--END PAGE CONTENT -->
	</div>
	<!--END MAIN WRAPPER -->

	<? include_once('footer.php');?>
	<link rel="stylesheet" href="assets/plugins/datepicker/css/datepicker.css" />
	<!-- Start :: Datepicker-->

	<!-- Start :: Datepicker Script-->
	<script src="../assets/js/jquery-ui.min.js"></script>
	<script src="./assets/plugins/uniform/jquery.uniform.min.js"></script>
	<script src="../assets/plugins/inputlimiter/jquery.inputlimiter.1.3.1.min.js"></script>
	<script src="../assets/plugins/chosen/chosen.jquery.min.js"></script>
	<script src="../assets/plugins/colorpicker/js/bootstrap-colorpicker.js"></script>
	<script src="../assets/plugins/tagsinput/jquery.tagsinput.min.js"></script>
	<script src="../assets/plugins/validVal/js/jquery.validVal.min.js"></script>
	<script src="../assets/plugins/daterangepicker/daterangepicker.js"></script>
	<script src="../assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
	<script src="../assets/plugins/timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="../assets/plugins/autosize/jquery.autosize.min.js"></script>
	<script src="../assets/plugins/jasny/js/bootstrap-inputmask.js"></script>
	<script src="../assets/js/formsInit.js"></script>
	<script>
		$(function () {
			formInit();
		});
	</script>
	<script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
</body>
<!-- END BODY-->
</html>


<!-- Start :: Datepicker css-->
<link rel="stylesheet" href="../assets/plugins/datepicker/css/datepicker.css" />
<!-- Start :: Datepicker-->

<!-- Start :: Datepicker Script-->
<script src="../assets/js/jquery-ui.min.js"></script>
<script src="../assets/plugins/uniform/jquery.uniform.min.js"></script>
<script src="../assets/plugins/inputlimiter/jquery.inputlimiter.1.3.1.min.js"></script>
<script src="../assets/plugins/chosen/chosen.jquery.min.js"></script>
<script src="../assets/plugins/colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="../assets/plugins/tagsinput/jquery.tagsinput.min.js"></script>
<script src="../assets/plugins/validVal/js/jquery.validVal.min.js"></script>
<script src="../assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="../assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="../assets/plugins/timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="../assets/plugins/autosize/jquery.autosize.min.js"></script>
<script src="../assets/plugins/jasny/js/bootstrap-inputmask.js"></script>
<script src="../assets/js/formsInit.js"></script>
<script>
	$(function () {
		formInit();
	});
	</script>
