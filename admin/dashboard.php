<?
	include_once('../common.php');

	if(!isset($generalobjAdmin)){
	
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();

	}
	//echo"<pre>";print_r($_SESSION);exit;
	$generalobjAdmin->check_member_login();
	
	$company 	= $generalobjAdmin->getCompanycount();
	$driver 	= $generalobjAdmin->getDrivercount();
	$rider 		= $generalobjAdmin->getRiderCount("all");
	$vehicle	= $generalobjAdmin->getVehicleDetails();
	$trips		= $generalobjAdmin->getTripsDetails();
	$totalEarns	= $generalobjAdmin->getTotalEarns();
	$totalRides = $generalobjAdmin->getTripStatescount('total');
	$onRides = $generalobjAdmin->getTripStatescount('on ride');
	$finishRides = $generalobjAdmin->getTripStatescount('finished');
	$cancelRides = $generalobjAdmin->getTripStatescount('cancelled');
	$actDrive = $generalobjAdmin->getDriverDetails('active');
	$inaDrive = $generalobjAdmin->getDriverDetails('inactive');	
	
	
	/*****************************/
	$finishRides_1 = $generalobjAdmin->getTripStatescount('finished',date('Y-m', strtotime(date('Y-m')." -2 month"))."-"."01",date('Y-m', strtotime(date('Y-m')." -2 month"))."-"."31");
	$finishRides_2 = $generalobjAdmin->getTripStatescount('finished',date('Y-m', strtotime(date('Y-m')." -1 month"))."-"."01",date('Y-m', strtotime(date('Y-m')." -1 month"))."-"."31");
	$finishRides_3 = $generalobjAdmin->getTripStatescount('finished',date('Y-m', strtotime(date('Y-m').""))."-"."01",date('Y-m', strtotime(date('Y-m').""))."-"."31");
	/*****************************/
	
	/*****************************/
	$sql = "SELECT * FROM register_driver WHERE 1  AND tRegistrationDate BETWEEN '".date('Y-m', strtotime(date('Y-m-d')." -2 month"))."-"."01"." 00:00:00' AND '".date('Y-m', strtotime(date('Y-m-d')." -2 month"))."-"."31"." 23:59:59'";
	$driver_2 = $obj->MySQLSelect($sql);
	
	$sql = "SELECT * FROM register_driver WHERE 1  AND tRegistrationDate BETWEEN '".date('Y-m', strtotime(date('Y-m-d')." -1 month"))."-"."01"." 00:00:00' AND '".date('Y-m', strtotime(date('Y-m-d')." -1 month"))."-"."31"." 23:59:59'";
	$driver_1 = $obj->MySQLSelect($sql);
	
	$sql = "SELECT * FROM register_driver WHERE 1  AND tRegistrationDate BETWEEN '".date('Y-m', strtotime(date('Y-m-d')))."-"."01"." 00:00:00' AND '".date('Y-m', strtotime(date('Y-m-d')))."-"."31"." 23:59:59'";
	$driver_0 = $obj->MySQLSelect($sql);
	/*****************************/
	
	/*****************************/
	$sql = "SELECT * FROM register_user WHERE 1  AND tRegistrationDate BETWEEN '".date('Y-m', strtotime(date('Y-m-d')." -2 month"))."-"."01"." 00:00:00' AND '".date('Y-m', strtotime(date('Y-m-d')." -2 month"))."-"."31"." 23:59:59'";
	$pass_2 = $obj->MySQLSelect($sql);
	
	$sql = "SELECT * FROM register_user WHERE 1  AND tRegistrationDate BETWEEN '".date('Y-m', strtotime(date('Y-m-d')." -1 month"))."-"."01"." 00:00:00' AND '".date('Y-m', strtotime(date('Y-m-d')." -1 month"))."-"."31"." 23:59:59'";
	$pass_1 = $obj->MySQLSelect($sql);
	
	$sql = "SELECT * FROM register_user WHERE 1  AND tRegistrationDate BETWEEN '".date('Y-m', strtotime(date('Y-m-d')))."-"."01"." 00:00:00' AND '".date('Y-m', strtotime(date('Y-m-d')))."-"."31"." 23:59:59'";
	$pass_0 = $obj->MySQLSelect($sql);
	/*****************************/
	
	
	
	
	/*$sql="SELECT *,lf.iDriverId as did,lf.iCompanyId as cid, rd.vName as Driver,c.vName as Company FROM log_file lf LEFT JOIN company c ON lf.iCompanyId=c.iCompanyId LEFT JOIN register_driver rd ON lf.iDriverId=rd.iDriverId ORDER BY tDate DESC LIMIT 0,10";
	$db_notification = $obj->MySQLSelect($sql);


	if(isset($_REQUEST['allnotification']))
	{
		$sql="SELECT *,rd.vName as Driver,c.vName as Company FROM log_file lf LEFT JOIN company c ON lf.iCompanyId=c.iCompanyId LEFT JOIN register_driver rd ON lf.iDriverId=rd.iDriverId ORDER BY tDate DESC";
		$db_notification = $obj->MySQLSelect($sql);
	}*/
	//print_r($db_finished); exit;
	//print_r($db_notification);exit;
	//echo $t=Date('Y-m-d H:i:s');
	//echo "<br/>". $reDate=$db_notification[0]['tDate'] ;exit;
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

	<!-- BEGIN HEAD-->
	<head>
		<meta charset="UTF-8" />
		<title>Admin | Dashboard</title>
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<!--[if IE]>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<![endif]-->
		<!-- GLOBAL STYLES -->
		<? include_once('global_files.php');?>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/adminLTE/AdminLTE.min.css" />
		<script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="js/plugins/morris/morris.min.js"></script> 
		<script type="text/javascript" src="js/actions.js"></script>
        <!-- END THIS PAGE PLUGINS-->
		<!--END GLOBAL STYLES -->

		<!-- PAGE LEVEL STYLES -->
		<!-- END PAGE LEVEL  STYLES -->
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
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

				<div class="inner" style="min-height: 700px;">
					<div class="row">
						<div class="col-lg-12">
							<h1> Admin Dashboard </h1>
						</div>
					</div>
					<hr />
					<!--BLOCK SECTION -->
					
					<!--div class="row">
						<div class="col-lg-12">
							<div style="text-align: center;">
								<a class="quick-btn" href="company.php">
									<i class="icon-check icon-2x"></i>
									<span>Company</span>
									<span class="label label-danger"><? //=count($company);?></span>
								</a>
								<a class="quick-btn" href="driver.php">
									<i class="icon-envelope icon-2x"></i>
									<span>Driver</span>
									<span class="label label-success"><? //=count($driver);?></span>
								</a>
								<a class="quick-btn" href="vehicles.php">
									<i class="icon-bolt icon-2x"></i>
									<span>Vehicle</span>
									<span class="label label-default"><? //=count($vehicle);?></span>
								</a>
								<a class="quick-btn" href="rider.php">
									<i class="icon-signal icon-2x"></i>
									<span>Rider</span>
									<span class="label label-warning"><? //=count($rider);?></span>
								</a>
								<a class="quick-btn" href="trip.php">
									<i class="icon-external-link icon-2x"></i>
									<span>Trips</span>
									<span class="label btn-metis-2"><? //=count($trips);?></span>
								</a>
							</div>
						</div>

					</div-->
					<!--END BLOCK SECTION -->
					
					<div class="row">
					<div class="col-lg-6">
					<div class="panel panel-primary bg-gray-light">
                            <div class="panel-heading">
								<div class="panel-title-box">
								   <i class="fa fa-bar-chart"></i> Site Statistics
								</div>                                  
							</div>
							<div class="row padding_005">
                            <div class="col-lg-6"><a href="rider.php">
								<div class="info-box bg-aqua">
									<span class="info-box-icon"><i class="fa fa-users"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_DASHBOARD_USERS_ADMIN'];?> </span>
										<span class="info-box-number"><?=$rider[0]['tot_rider'];?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							<!-- /.col -->
							<div class="col-lg-6"><a href="driver.php?type=approve">
								<div class="info-box bg-yellow">
									<span class="info-box-icon"><i class="fa fa-male"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_DASHBOARD_DRIVERS_ADMIN'];?> </span>
										<span class="info-box-number"><?=($driver[0]['tot_driver']);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							<div class="col-lg-6"><a href="company.php">
								<div class="info-box bg-red">
									<span class="info-box-icon"><i class="fa fa-building-o"></i></span>

									<div class="info-box-content">
										<span class="info-box-text">Companies</span>
										<span class="info-box-number"><?=($company[0]['tot_company']);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>

							<div class="col-lg-6"><a href="trip.php">
								<div class="info-box bg-green">
									<span class="info-box-icon"><i class="fa fa-money"></i></span>

									<div class="info-box-content">
										<span class="info-box-text">Total Earnings</span>
										<!--<span class="info-box-number"><?=number_format($totalEarns,2);?></span>-->
										<span class="info-box-number"><?=$generalobj->trip_currency($totalEarns,'','',2);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							</div>
                        </div>
					</div>
					
					<div class="col-lg-6">
					<div class="panel panel-primary bg-gray-light">
							<div class="panel-heading">
								<div class="panel-title-box">
								   <i class="fa fa-area-chart"></i> <?php echo $langage_lbl_admin['LBL_RIDE_STATISTICS_ADMIN'];?>
								</div>                                  
							</div>
							<div class="row padding_005">
                            <div class="col-lg-6"><a href="trip.php">
								<div class="info-box bg-aqua">
									<span class="info-box-icon"><i class="fa fa-cubes"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_TOTAL_RIDES_ADMIN'];?> </span>
										<span class="info-box-number"><?=($totalRides[0]['tot_trip']);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							<!-- /.col -->
							<div class="col-lg-6"><a href="trip.php?type=onRide">
								<div class="info-box bg-yellow">
									<span class="info-box-icon"><i class="fa fa-clone"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_ON_RIDES_ADMIN'];?> </span>
										<span class="info-box-number"><?=($onRides[0]['tot_trip']);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							
							<div class="col-lg-6"><a href="trip.php?type=cancel">
								<div class="info-box bg-red">
									<span class="info-box-icon"><i class="fa fa-times-circle-o"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_CANCELLED_RIDES_ADMIN'];?> </span>
										<span class="info-box-number"><?=($cancelRides[0]['tot_trip']);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							<!-- /.col -->


							<div class="col-lg-6"><a href="trip.php?type=complete">
								<div class="info-box bg-green">
									<span class="info-box-icon"><i class="fa fa-check"></i></span>

									<div class="info-box-content">
										<span class="info-box-text"><?php echo $langage_lbl_admin['LBL_COMPLETED_RIDES_ADMIN'];?> </span>
										<span class="info-box-number"><?=($finishRides[0]['tot_trip']);?></span>
									</div>
									<!-- /.info-box-content -->
								</div></a>
								<!-- /.info-box -->
							</div>
							</div>
                        </div>
					</div>
					</div>
					
					<hr />
					
					<div class="row">
						<div class="col-lg-6">
							<div class="panel-heading">
							<div class="panel-title-box">
							<i class="fa fa-bar-chart"></i> Finished <?php echo $langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?> For last 3 Months
							</div>                                  
							</div>
							<div class="panel-body padding-0">
							<div id="last-6-rides"></div>
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="panel-heading">
							<div class="panel-title-box">
							<i class="fa fa-bar-chart"></i> Users
							</div>                                  
							</div>
							<div class="panel-body padding-0">
							<div id="total-users"></div>
							</div>
						</div>
					
					</div>
					
					<hr />
					
					<div class="row">
					<div class="col-lg-6">
					<div class="panel panel-primary bg-gray-light">
                            
							
							
							
							<div class="panel-heading">
								<div class="panel-title-box">
								   <i class="fa fa-bar-chart"></i> <?php echo $langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>
								</div>                                  
							</div>
							
							
							
							<div class="panel-body padding-0">
							<div class="col-lg-6">
								<div class="chart-holder" id="dashboard-rides" style="height: 200px;"></div>
							</div>
							<div class="col-lg-6">
								<h3><?php echo $langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>  Count : <?=count($totalRides);?></h3>
								<p>Today : <b><?=count($generalobjAdmin->getTripDateStates('today'));?></b></p>
								<p>This Month : <b><?=count($generalobjAdmin->getTripDateStates('month'));?></b></p>
								<p>This Year : <b><?=count($generalobjAdmin->getTripDateStates('year'));?></b></p>
								<br />
								<br />
								<p>
									* This is count for all <?=$langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?> (Finished, ongoing, cancelled.)
								</p>
							</div>
							</div>
						</div>
						<!-- END VISITORS BLOCK -->
					</div>
					
					<div class="col-lg-6">
					<div class="panel panel-primary bg-gray-light">
                            <div class="panel-heading">
								<div class="panel-title-box">
								   <i class="fa fa-bar-chart"></i> <?php echo $langage_lbl_admin['LBL_DRIVERS_NAME_ADMIN'];?>
								</div>                                  
							</div>
							<div class="panel-body padding-0">
							<div class="col-lg-6">
								<div class="chart-holder" id="dashboard-drivers" style="height: 200px;"></div>
							</div>
							<div class="col-lg-6">
								<h3><?php echo $langage_lbl_admin['LBL_DRIVERS_NAME_ADMIN'];?>  Count : <?=count($driver);?></h3>
								<p>Today : <b><?=count($generalobjAdmin->getDriverDateStatus('today'));?></b></p>
								<p>This Month : <b><?=count($generalobjAdmin->getDriverDateStatus('month'));?></b></p>
								<p>This Year : <b><?=count($generalobjAdmin->getDriverDateStatus('year'));?></b></p>
							</div>
							</div>
						</div>
						<!-- END VISITORS BLOCK -->
					</div>
					</div>
					<!-- COMMENT AND NOTIFICATION  SECTION -->
					<div class="row">
						<div class="col-lg-6">
						<div class="chat-panel panel panel-success">
								<div class="panel-heading">
									<div class="panel-title-box">
									   <i class="icon-comments"></i> Latest <?php echo $langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>
									</div>                                  
								</div>
								<?php  for($i=0,$n=$i+2;$i<count($db_finished);$i++,$n++){?>
									<div class="panel-heading" style="background:none;">
										<ul class="chat">
											<?php if($n%2==0){ ?>
											<a target="blank" href=<?echo "invoice.php?iTripId=".$db_finished[$i]['iTripId'];?>>
												<li class="left clearfix">
													<span class="chat-img pull-left">
	<? if($db_finished[0]['vImage']!='' && $db_finished[0]['vImage']!="NONE" && file_exists( "../webimages/upload/Driver/".$db_finished[0]['iDriverId']."/".$db_finished[0]['vImage'])){?>
															<img src="../webimages/upload/Driver/<?php echo $db_finished[0]['iDriverId']."/".$db_finished[0]['vImage'];?>" alt="User Avatar" class="img-circle"  height="50" width="50"/>
														<? }else{?>

														<img src="../assets/img/profile-user-img.png" alt="" class="img-circle"  height="50" width="50">
														<?}?>
													</span>
													<div class="chat-body clearfix">
														<div class="header">
															<strong class="primary-font "> <?php echo $db_finished[$i]['vName']." ".$db_finished[$i]['vLastName']; ?> </strong>
															<small class="pull-right text-muted label label-danger">
																<i class="icon-time"></i>
																<?php
																	$regDate=$db_finished[$i]['tEndDate'];
																	$dif=strtotime(Date('Y-m-d H:i:s'))-strtotime($regDate);
																	if($dif<60)
																	{
																		$time=floor($dif/(60));
																		echo "Just Now";
																	}
																	else if($dif<3600)
																	{
																		$time=floor($dif/(60));
																		echo $time." minites ago";
																	}
																	else if($dif<86400)
																	{
																		$time=floor($dif/(60*60));
																		echo $time." hour ago";
																	}
																	else
																	{
																		$time=floor($dif/(24*60*60));
																		echo $time." Days ago";
																	}
																?>
															</small>
														</div>
														<br />
														<p>
															<?php echo $db_finished[$i]['tSaddress']." ".$db_finished[$i]['tDaddress']."<br/>";
																echo "Status: ".$db_finished[$i]['iActive'];
															?>
														</p>
													</div>
												</li>
												</a>
												<?php } else { ?>
												<li class="right clearfix">
													<a  target="blank" href=<?echo "invoice.php?iTripId=".$db_finished[$i]['iTripId'];?>>
													<span class="chat-img pull-right">
														<? if($db_finished[$i]['vImage']!='' && $db_finished[$i]['vImage']!="NONE" && file_exists( "../webimages/upload/Driver/".$db_finished[$i]['iDriverId']."/".$db_finished[$i]['vImage'])){?>
															<img src="../webimages/upload/Driver/<?php echo $db_finished[$i]['iDriverId']."/".$db_finished[$i]['vImage'];?>" alt="User Avatar" class="img-circle"  height="50" width="50"/>
														<? }else{?>

														<img src="../assets/img/profile-user-img.png" alt="" class="img-circle"  height="50" width="50">
														<?}?>
													</span>
													<div class="chat-body clearfix">
														<div class="header">
															<small class=" text-muted label label-info">
																<i class="icon-time"></i> <?php
																	$regDate=$db_finished[$i]['tEndDate'];
																	$dif=strtotime(Date('Y-m-d H:i:s'))-strtotime($regDate);
																	if($dif<60)
																	{
																		$time=floor($dif/(60));
																		echo "Just Now";
																	}
																	else if($dif<3600)
																	{
																		$time=floor($dif/(60));
																		echo $time." minites ago";
																	}
																	else if($dif<86400)
																	{
																		$time=floor($dif/(60*60));
																		echo $time." hour ago";
																	}
																	else
																	{
																		$time=floor($dif/(24*60*60));
																		echo $time." Days ago";
																	}
																?></small>
																<strong class="pull-right primary-font"> <?php echo $db_finished[$i]['vName']." ".$db_finished[$i]['vLastName']; ?></strong>
														</div>
														<br />
														<p>
															<?php echo $db_finished[$i]['tSaddress']." ".$db_finished[$i]['tDaddress']."<br/>";
																echo "Status: ".$db_finished[$i]['iActive'];
															?>
														</p>
													</div>
												</a>
												</li>
											<?php }?>
										</ul>
									</div>
								<?php } ?>
						</div>


					</div>
					<div class="col-lg-6">
						<div class="panel panel-danger">
								<div class="panel-heading">
									<div class="panel-title-box">
									   <i class="icon-bell"></i> Notifications Alerts Panel
									</div>                                  
								</div>

							<div class="panel-body">
								<?php
								if(count($db_notification)>0)
								{
								for($i=0;$i<count($db_notification);$i++) {?>
										<div class="list-group">
											<?php
												if($db_notification[$i]['eUserType']=='driver' && $db_notification[$i]['cid']== 0){
													$url = "driver_document_action.php";
													$id = $db_notification[$i]['did'];
													$msg = strtoupper($db_notification[$i]['eType'])." uploaded by ".$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN']." : ".$db_notification[$i]['Driver'];
												}
												else if($db_notification[$i]['eUserType']=='company' && $db_notification[$i]['did']== 0)
												{
													$url = "company_document_action.php";
													$id = $db_notification[$i]['cid'];
													$msg = strtoupper( $db_notification[$i]['eType'])." uploaded by ".$db_notification[$i]['eUserType']." : ".$db_notification[$i]['Company'];
												}
												else
												{
													$url = "driver_document_action.php";
													$id = $db_notification[$i]['did'];
													/* $msg =strtoupper($db_notification[$i]['eType']) ." uploaded by ".$db_notification[$i]['eUserType']." : ".$db_notification[$i]['Company']." (Driver: ".$db_notification[$i]['Driver'].")"; */
													$msg =strtoupper($db_notification[$i]['eType'])." uploaded by ".$db_notification[$i]['eUserType']." : ".$db_notification[$i]['Company']. " for ".$langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'] ." : ".$db_notification[$i]['Driver'];
												}
												?>
												<a href="<?=$url;?>?id=<?echo $id;?>&action=edit" class="list-group-item">
													<i class=" icon-comment"></i>

													<?=$msg ;?>
													<span class="pull-right text-muted small">
													<em>
														<?php $reDate=$db_notification[$i]['tDate'];

															$dif=strtotime(Date('Y-m-d H:i:s'))-strtotime($reDate);
															if($dif<3600)
															{
																$time=floor($dif/(60));
																echo $time." minites ago";
															}
															else if($dif<86400)
															{
																$time=floor($dif/(60*60));
																echo $time." hour ago";
															}
															else
															{
																$time=floor($dif/(24*60*60));
																echo $time." Days ago";
															}


														?>
													</em>
													</span>
												</a>

												</div>

								<?} }
											else
											{
												echo "No Notification";
											}

											?>
								</div>

							</div>



						</div>
					</div>
					<!-- END COMMENT AND NOTIFICATION  SECTION -->
				</div>
			</div>

			<!--END PAGE CONTENT -->
		</div>

		<? include_once('footer.php'); ?>

	</body>
	<!-- END BODY-->
	<?
		// if(SITE_TYPE=='Demo'){
			// $generalobjAdmin->remove_unwanted();
		  // }
	?>
</html>
<script>
	$(document).ready(function(){
			/* Donut dashboard chart */
			var total_ride = '<?=count($totalRides);?>';
			var complete_ride = '<?=count($finishRides);?>';
			var cancel_ride = '<?=count($cancelRides);?>';
			var on_ride = '<?=count($onRides);?>';
			Morris.Donut({
				element: 'dashboard-rides',
				data: [
					{label: "On Going <?=$langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>", value: on_ride},
					{label: "Completed <?=$langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>", value: complete_ride},
					{label: "Cancelled <?=$langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>", value: cancel_ride}
				],
				formatter: function (x) { return (x/total_ride *100).toFixed(2)+'%'+ ' ('+x+')'; },
				colors: ['#33414E', '#1caf9a', '#FEA223'],
				resize: true
			});
			var total_drive = '<?=count($driver);?>';
			var active_drive = '<?=count($actDrive);?>';
			var inactive_drive = '<?=count($inaDrive);?>';
			Morris.Donut({
				element: 'dashboard-drivers',
				data: [
					{label: "Active <?=$langage_lbl_admin['LBL_DRIVERS_NAME_ADMIN'];?>", value: active_drive},
					{label: "Pending <?=$langage_lbl_admin['LBL_DRIVERS_NAME_ADMIN'];?>", value: inactive_drive},
				],
				formatter: function (x) { return (x/total_drive *100).toFixed(2)+'%'+ '('+x+')'; },
				colors: ['#33414E', '#1caf9a', '#FEA223'],
				resize: true
			});
			/* END Donut dashboard chart */
	});
</script>



<script>
	$(document).ready(function(){
			/* Donut chart */
			Morris.Bar({
				  element: 'last-6-rides',
				  data: [
					{ y: '<?=date('M - y', strtotime(date('Y-m')." -2 month"));?>', a: <?=($finishRides_1[0]['tot_trip'])?>},
					{ y: '<?=date('M - y', strtotime(date('Y-m')." -1 month"));?>', a: <?=($finishRides_2[0]['tot_trip'])?>},
					{ y: '<?=date('M - y', strtotime(date('Y-m').""));?>', a: <?=($finishRides_3[0]['tot_trip'])?>},

				  ],
				  xkey: 'y',
				  ykeys: ['a'],
				  labels: ['<?=$langage_lbl_admin['LBL_RIDES_NAME_ADMIN'];?>']
				});
			/* END Donut chart */
			
			/* Donut chart */
			Morris.Bar({
			element: 'total-users',
			data: [
				{ yy: '<?=date('M - y', strtotime(date('Y-m')." -2 month"));?>', aa: <?=count($driver_2);?>, bb: <?=count($pass_2);?>},
				
				{ yy: '<?=date('M - y', strtotime(date('Y-m')." -1 month"));?>', aa: <?=count($driver_1);?>,  bb: <?=count($pass_1);?> },
				{ yy: '<?=date('M - y', strtotime(date('Y-m').""));?>', aa: <?=count($driver_0);?>,  bb: <?=count($pass_0);?> },
			],
			xkey: 'yy',
			ykeys: ['aa', 'bb'],
			labels: ['<?=$langage_lbl_admin['LBL_DRIVERS_NAME_ADMIN'];?>', '<?=$langage_lbl_admin['LBL_DASHBOARD_USERS_ADMIN'];?>']
			});
			/* END Donut chart */
			
			
	});
</script>
