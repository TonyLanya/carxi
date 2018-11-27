<?php
include_once('../common.php');

if(!isset($generalobjAdmin)){
require_once(TPATH_CLASS."class.general_admin.php");
$generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();


$script   = "Driver Log Report";

$action=(isset($_REQUEST['action'])?$_REQUEST['action']:'');
$iDriverIdBy=(isset($_REQUEST['iDriverId'])?$_REQUEST['iDriverId']:'');

$ssql='';
if($action!='')
{
	$startDate=$_REQUEST['startDate'];
	$endDate=$_REQUEST['endDate'];
	//$startDate1=$startDate.' '."00:00:00";
	//$endDate1=$endDate.' '."23:59:59";
	//$iDriverIdBy=$_REQUEST['iDriverId'];
	if($startDate!=''){
		$ssql.=" AND dlr.dLoginDateTime BETWEEN '".$startDate."' AND '".$endDate."'";
	}
	/*if($endDate!=''){
		$ssql.=" AND Date(dlr.dLoginDateTime) <='".$endDate."'";
	}*/
	if($iDriverIdBy!='')
	{
		$ssql.=" And rd.iDriverId = '".$iDriverIdBy."'";
	}
}

$sql = "SELECT rd.vName, rd.vLastName, rd.vEmail, dlr.dLoginDateTime, dlr.dLogoutDateTime
FROM driver_log_report AS dlr
LEFT JOIN register_driver AS rd ON rd.iDriverId = dlr.iDriverId where 1=1 ".$ssql." order by dlr.iDriverLogId DESC";
$db_log_report = $obj->MySQLSelect($sql);
$sql = "select * from register_driver WHERE eStatus != 'Deleted' order by vName";
$db_company = $obj->MySQLSelect($sql);
//echo "<pre>"; print_r($db_log_report); exit;

$Today=Date('Y-m-d');
$tdate=date("d")-1;
$mdate=date("d");
$Yesterday = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

$curryearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")));
$curryearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")));
$prevyearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")-1));
$prevyearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")-1));

$currmonthFDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$tdate,date("Y")));
$currmonthTDate = date("Y-m-d",mktime(0,0,0,date("m")+1,date("d")-$mdate,date("Y")));
$prevmonthFDate = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d")-$tdate,date("Y")));
$prevmonthTDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$mdate,date("Y")));

$monday = date( 'Y-m-d', strtotime( 'sunday this week -1 week' ) );
$sunday = date( 'Y-m-d', strtotime( 'saturday this week' ) );

$Pmonday = date( 'Y-m-d', strtotime('sunday this week -2 week'));
$Psunday = date( 'Y-m-d', strtotime('saturday this week -1 week'));
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

     <!-- BEGIN HEAD-->
     <head>
          <meta charset="UTF-8" />
          <title><?=$SITE_NAME?> | Driver Log Report<?php echo $langage_lbl_admin['LBL_DRIVER_LOG_REPORT_SMALL_ADMIN'];?></title>
          <meta content="width=device-width, initial-scale=1.0" name="viewport" />
          <link href="../assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

          <? include_once('global_files.php');?>         
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
                                        <h2>Driver Log Report</h2>
                                       
                                   </div>
                              </div>
                              <hr />
                         </div>
                         <? if($success == 1) { ?>
                         <div class="alert alert-success alert-dismissable">
                              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                             <?php echo $_REQUEST['succe_msg']; echo isset($_REQUEST['succe_msg'])? $_REQUEST['succe_msg'] : ''; ?>
                         </div><br/>
                         <? }elseif ($success == 2) { ?>
                           <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                           </div><br/>
                         <? } ?>
                        
                         <div class="table-list">
                              <div class="row">
                                   <div class="col-lg-12">
                                        <div class="panel panel-default">
                                             <div class="panel-heading">
                                                  <?php echo $langage_lbl_admin['LBL_DRIVER_LOG_REPORT_SMALL_ADMIN'];?>
                                             </div>
                                             <div class="panel-body">
											 <form name="search" id="searchIt" action="" method="post" onSubmit="return checkvalid()">
												<div class="Posted-date mytrip-page mytrip-page-select">
													<input type="hidden" name="action" value="search" />
													<h3><?=$langage_lbl['LBL_MYTRIP_SEARCH_RIDES_POSTED_BY_DATE']; ?></h3>
													<span>
													<a onClick="return todayDate('dp4','dp5');"><?=$langage_lbl['LBL_MYTRIP_Today']; ?></a>
													<a onClick="return yesterdayDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Yesterday']; ?></a>
													<a onClick="return currentweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Current_Week']; ?></a>
													<a onClick="return previousweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Previous_Week']; ?></a>
													<a onClick="return currentmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Current_Month']; ?></a>
													<a onClick="return previousmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Previous Month']; ?></a>
													<a onClick="return currentyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Current_Year']; ?></a>
													<a onClick="return previousyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_MYTRIP_Previous_Year']; ?></a>
													</span> 
													<span>
													<input type="text" id="dp4" name="startDate" placeholder="From Driver Online Date" class="form-control" value=""/>
													<input type="text" id="dp5" name="endDate" placeholder="To Driver Online Date" class="form-control" value=""/>
						        <select name="iDriverId" id="iDriverId" class="form-control input-sm driver-trip-detail-select" style="display:table-row-group;">
                                <option value="">Search By <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?> Name</option>
                                <?for($j=0;$j<count($db_company);$j++){?>
								
                                <option value="<?=$db_company[$j]['iDriverId'];?>" <?php if($iDriverIdBy == $db_company[$j]['iDriverId']){ ?>selected <?php } ?> ><?=$db_company[$j]['vName']." ".$db_company[$j]['vLastName'];?></option>
                                <?}?>
                              </select>
													<b><button class="driver-trip-btn"><?=$langage_lbl['LBL_Search']; ?></button>
														<button onClick="resetform();" class="driver-trip-btn"><?=$langage_lbl['LBL_RESET']; ?></button></b> 
													</span>
												</div>
											</form>
                                                  <div class="table-responsive">
                                                       <table class="table table-striped table-bordered table-hover" id="dataTables-example1">
                                                            <thead>
                                                                 <tr>
                                                                      <th>NAME</th>
                                                                      <th>EMAIL</th>
                                                                      <th>Online Time</th>
                                                                      <th>Offline Time</th>                                       
                                                                      <th>Total Hours Login</th>
                                                                     
                                                                 </tr>
                                                            </thead>
                                                            <tbody>
                                                                 <?php for($i=0;$i<count($db_log_report);$i++) {

                                                                  $dstart = $db_log_report[$i]['dLoginDateTime'];
                                                                    if( $db_log_report[$i]['dLogoutDateTime'] == '0000-00-00 00:00:00' || $db_log_report[$i]['dLogoutDateTime'] == '' ){

                                                                       $dLogoutDateTime = '--';
                                                                       $totalTimecount = '--';

                                                                    }else{

                                                                       $dLogoutDateTime = $db_log_report[$i]['dLogoutDateTime'];
                                                                       $totalhours = get_left_days_jobsave ($dLogoutDateTime,$dstart);
                                                                       $totalTimecount = mediaTimeDeFormater ($totalhours);
                                                                       //$totalTimecount = $totalTimecount.' hrs';
                                                                    }     ?>

                                                                 <tr class="gradeA">
                                                                      <td><? echo $db_log_report[$i]['vName'].' '.$db_log_report[$i]['vLastName']; ?></td>
                                                                      <td><? echo $generalobjAdmin->clearEmail($db_log_report[$i]['vEmail']); ?></td>
                                                                      <td><? echo $db_log_report[$i]['dLoginDateTime']; ?></td>
                                                                      <td><? echo $dLogoutDateTime; ?></td>                          
                                                                      <td><? echo $totalTimecount; ?></td>
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


          <? include_once('footer.php');?>
          <script src="../assets/plugins/dataTables/jquery.dataTables.js"></script>
          <script src="../assets/plugins/dataTables/dataTables.bootstrap.js"></script>
		  <link rel="stylesheet" href="../assets/plugins/datepicker/css/datepicker.css" />
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
            $(document).ready(function () {
                 $('#dataTables-example1').dataTable({
                    "pageLength": 50,
                   "order": [[ 2, "desc" ]]
                 });
            });
           
          </script>
		  
		   <script>
         $(document).ready(function () {
			 if('<?=$startDate?>'!=''){
				 $("#dp4").val('<?=$startDate?>');
				 $("#dp4").datepicker('update' , '<?=$startDate?>');
			 }
			 if('<?=$endDate?>'!=''){
				 $("#dp5").datepicker('update' , '<?= $endDate;?>');
				 $("#dp5").val('<?= $endDate;?>');
			 }
             $('#dataTables-example').dataTable({
				  "order": [[ 0, "desc" ]]
				 });
			 formInit();
         });
		 
		 function setRideStatus(actionStatus) {
			 window.location.href = "trip.php?type="+actionStatus;
		 }
		 function todayDate()
		 {
			//alert('sa');
			 $("#dp4").val('<?= $Today;?>');
			 $("#dp5").val('<?= $Today;?>');
		 }
		 function resetform()
		 {
		 	//location.reload();
			document.search.reset();
			document.getElementById("iDriverId").value=" ";
		}	
		 function yesterdayDate()
		 {
			 $("#dp4").val('<?= $Yesterday;?>');
			 $("#dp4").datepicker('update' , '<?= $Yesterday;?>');
			 $("#dp5").datepicker('update' , '<?= $Yesterday;?>');
			 $("#dp4").change();
			 $("#dp5").change();
			 $("#dp5").val('<?= $Yesterday;?>');
		 }
		 function currentweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $monday;?>');
			 $("#dp4").datepicker('update' , '<?= $monday;?>');
			 $("#dp5").datepicker('update' , '<?= $sunday;?>');
			 $("#dp5").val('<?= $sunday;?>');
		 }
		 function previousweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $Pmonday;?>');
			 $("#dp4").datepicker('update' , '<?= $Pmonday;?>');
			 $("#dp5").datepicker('update' , '<?= $Psunday;?>');
			 $("#dp5").val('<?= $Psunday;?>');
		 }
		 function currentmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $currmonthFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $currmonthFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $currmonthTDate;?>');
			 $("#dp5").val('<?= $currmonthTDate;?>');
		 }
		 function previousmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevmonthFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $prevmonthFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $prevmonthTDate;?>');
			 $("#dp5").val('<?= $prevmonthTDate;?>');
		 }
		 function currentyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $curryearFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $curryearFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $curryearTDate;?>');
			 $("#dp5").val('<?= $curryearTDate;?>');
		 }
		 function previousyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevyearFDate;?>');
			 $("#dp4").datepicker('update' , '<?= $prevyearFDate;?>');
			 $("#dp5").datepicker('update' , '<?= $prevyearTDate;?>');
			 $("#dp5").val('<?= $prevyearTDate;?>');
		 }
		 function checkvalid(){
			 if($("#dp5").val() < $("#dp4").val()){
				 alert("From date should be lesser than To date.")
				 return false;
			 }
		 }
    </script>
     </body>
     <!-- END BODY-->
     <?php

   function get_left_days_jobsave($dend,$dstart){
 

    $dayinpass = $dstart;
    $today = strtotime($dend); 
    $dayinpass= strtotime($dayinpass);
    return round(abs($today-$dayinpass));
    // return round(abs($today-$dayinpass)/60/60);
 }
 function mediaTimeDeFormater($seconds) {
    $ret = "";
   
    $hours = (string )floor($seconds / 3600);
    $secs = (string )$seconds % 60;
    $mins = (string )floor(($seconds - ($hours * 3600)) / 60);

    if (strlen($hours) == 1)
        $hours = "0" . $hours;
    if (strlen($secs) == 1)
        $secs = "0" . $secs;
    if (strlen($mins) == 1)
        $mins = "0" . $mins;


    if ($hours == 0){

        if($mins > 1){

         $ret = "$mins mins";
        }else{
          $ret = "$mins min";
        }
      
    }      
    else{
          $mint="";
           if($mins > 01){

              $mint = "$mins mins";
            }else{

               echo $mint = "$mins min";

            }    
    
          if($hours > 1){                  

          $ret = "$hours hrs $mint";
        }else{

            $ret = "$hours hr $mint";
        }
       
      }
  //echo  $ret;
return  $ret;


  
} ?>
</html>
