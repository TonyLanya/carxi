<!-- MENU SECTION -->
<div id="left" class="sidebar_hide">
	<div class="media user-media well-small">
		<!-- <a class="user-link" href="#">
			<img class="media-object img-thumbnail user-img" alt="User Picture" src="assets/img/user.gif" />
			</a>
		<br /> -->
		<div class="media-body">
			<h5 class="media-heading"><?=$_SESSION['sess_vAdminFirstName'] ." ".$_SESSION['sess_vAdminLastName']; ?>
				<!---
				<a href="logout.php" class="pull-right">
					<span class="btn btn-success btn-xs btn-circle" style="width: 100%;"> Logout</span>
				</a>
				--->
			</h5>
		</div>
		<br />
	</div>
	
	<ul id="menu" class="collapse">
		
		<!-- If groupId = 2 -->
		<?php if($_SESSION['sess_iGroupId'] == '2') { ?>		
			<?php if(RIIDE_LATER == 'YES'){ ?>
			<li class="<?=(isset($script) && $script == 'booking')?'active':'';?>"><a href="add_booking.php"><i class="fa fa-taxi"></i> Manual Taxi Dispatch </a></li> <?php } ?>
			<?php if(RIIDE_LATER == 'YES'){ ?>
			<li class="<?=(isset($script) && $script == 'CabBooking')?'active':'';?>"><a href="cab_booking.php"><i class="icon-book" aria-hidden="true"></i>  <?php echo $langage_lbl_admin['LBL_RIDE_LATER_BOOKINGS_ADMIN'];?> </a></li> <?php } ?>
			<li class="<?=(isset($script) && $script == 'Map')?'active':'';?>"><a href="map.php"><i class="icon-map-marker" aria-hidden="true"></i> God's View </a></li>
			<li class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><a href="trip.php"><i class="fa fa-exchange" aria-hidden="true"></i> <?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?>  </a></li>
		<!-- If groupId = 3 -->	
		<?php } else if($_SESSION['sess_iGroupId'] == '3') { ?>
		
			<li class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><a href="trip.php"><i class="fa fa-exchange" aria-hidden="true"></i> <?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?>  </a></li>
			<li class="panel <?=(isset($script) && ($script == 'Payment Report' || $script == 'referrer' || $script == 'Wallet Report' || $script == 'Driver Payment Report'))?'active':'';?>">
				<a href="javascript:void(0);" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-nav-report">
					<i class="icon-cogs"> </i> Reports
					
					<span class="pull-right">
						<i class="icon-angle-left"></i>
					</span>
					
				</a>
				<ul class="<?=(isset($script) && ($script == 'Payment Report' || $script == 'referrer' || $script == 'Wallet Report' || $script == 'Driver Payment Report'))?'in':'collapse';?>" id="component-nav-report">
					<li class="<?=(isset($script) && $script == 'Payment Report')?'active':'';?>"><a href="payment_report.php"><i class="icon-money"></i> Payment Report</a></li>
					<?php 
						
						if($REFERRAL_SCHEME_ENABLE == 'Yes'){ ?>
						
						<li class="<?=(isset($script) && $script == 'referrer')?'active':'';?>"><a href="referrer.php"><i class="fa fa-hand-peace-o" aria-hidden="true"></i> Referral Report</a></li>
					<?php } ?>
					
					<?php if($WALLET_ENABLE == 'Yes'){ ?> 	 
						
					<li class="<?=(isset($script) && $script == 'Wallet Report')?'active':'';?>"><a href="wallet_report.php"><i class="fa fa-google-wallet" aria-hidden="true"></i> User Wallet Report</a></li> <?php } ?>	
					
					<li class="<?=(isset($script) && $script == 'Driver Payment Report')?'active':'';?>"><a href="driver_pay_report.php"><i class="icon-money"></i> Driver Payment Report</a></li>
					
					<?php /*<li class="<?=(isset($script) && $script == 'Driver Registration Report')?'active':'';?>"><a href="driver_registration_report.php"><i class="icon-money"></i> Driver Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Passenger Registration Report')?'active':'';?>"><a href="passenger_registration_report.php"><i class="icon-money"></i> Passenger Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Finished Rides Report')?'active':'';?>"><a href="finished_rides_report.php"><i class="icon-money"></i> Finished Rides Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Cancelled Rides Report')?'active':'';?>"><a href="cancelled_rides_report.php"><i class="icon-money"></i> Cancelled Rides Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Rides By Month Report')?'active':'';?>"><a href="rides_by_month_report.php"><i class="icon-money"></i> Rides By Month Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Online Report')?'active':'';?>"><a href="online_report.php"><i class="icon-money"></i> Online Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Offline Report')?'active':'';?>"><a href="offline_report.php"><i class="icon-money"></i> Offline Report</a></li> */ ?>
					
				</ul>
			</li>
		<?php }else { ?>	
		
		
		<li class="panel <?=(!isset($script)?'active':'');?>">
			<a href="dashboard.php" >
				<i class="icon-dashboard"></i> Dashboard
			</a>
		</li>
		
		<li class="<?=(isset($script) && $script == 'Admin')?'active':'';?>"><a href="admin.php"><i class="icon-user"></i> Admin </a></li>
		<li class="<?=(isset($script) && $script == 'Company')?'active':'';?>"><a href="company.php"><i class="fa fa-building" aria-hidden="true"></i> Company </a></li>
		<li class="<?=(isset($script) && $script == 'Driver')?'active':'';?>"><a href="driver.php"><i class="icon-group"></i> <?php echo $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'];?></a></li>
		
		<?php if($APP_TYPE == 'UberX'){ ?>
			
			<li class="<?=(isset($script) && $script == 'PetType')?'active':'';?>"><a href="javascript:void(0);" data-parent="#component-nav1" data-toggle="collapse" class="accordion-toggle" data-target="#component-driver-nav1"><i class="icon-group"></i> <?php echo $langage_lbl_admin['LBL_PET_TYPE'];?>  <span class="pull-right">
				<i class="icon-angle-left"></i>
			</span></a>
			<ul class="<?=(isset($script) && $script == 'PetType')?'in':'collapse';?>" id="component-driver-nav1">
				<li><a href="pettype.php"><i class="icon-angle-right"></i> <?php echo $langage_lbl_admin['LBL_PET_TYPE'];?>  </a></li>
				<li><a href="user_pets.php"><i class="icon-angle-right"></i> <?php echo $langage_lbl_admin['LBL_USER_PETS_ADMIN'];?>  </a></li>
				
			</ul>
			</li>
		<?php }?>
		<li class="<?=(isset($script) && $script == 'Vehicle')?'active':'';?>"><a href="vehicles.php"><i class="fa fa-cab" aria-hidden="true"></i> <?php echo $langage_lbl_admin['LBL_VEHICLE_TXT_ADMIN'];?> </a></li>
		<li class="<?=(isset($script) && $script == 'VehicleType')?'active':'';?>"><a href="vehicle_type.php"><i class="fa fa-yelp" aria-hidden="true"></i> <?php echo $langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'];?>  </a></li>
		<?php if($APP_TYPE == 'UberX'){ ?>
			<li class="<?=(isset($script) && $script == 'VehicleCategory')?'active':'';?>"><a href="vehicle_category.php"><i class="fa fa-plus-square" aria-hidden="true"></i> Vehicle Category </a></li>
		<?php }?>
		
		<li class="<?=(isset($script) && $script == 'Rider')?'active':'';?>"><a href="rider.php"><i class="icon-group"></i> <?php echo $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'];?>  </a></li>
		<?php if(RIIDE_LATER == 'YES'){ ?>
		<li class="<?=(isset($script) && $script == 'booking')?'active':'';?>"><a href="add_booking.php"><i class="fa fa-taxi"></i> Manual Taxi Dispatch </a></li> <?php } ?>
		<li class="<?=(isset($script) && $script == 'Trips')?'active':'';?>"><a href="trip.php"><i class="fa fa-exchange" aria-hidden="true"></i> <?php echo $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'];?>  </a></li>
		<?php if(RIIDE_LATER == 'YES'){ ?>
		<li class="<?=(isset($script) && $script == 'CabBooking')?'active':'';?>"><a href="cab_booking.php"><i class="icon-book" aria-hidden="true"></i>  <?php echo $langage_lbl_admin['LBL_RIDE_LATER_BOOKINGS_ADMIN'];?> </a></li> <?php } ?>
		<li class="<?=(isset($script) && $script == 'Coupon')?'active':'';?>"><a href="coupon.php"><i class="fa fa-product-hunt" aria-hidden="true"></i> Promo Code </a></li>
		<li class="<?=(isset($script) && $script == 'Map')?'active':'';?>"><a href="map.php"><i class="icon-map-marker" aria-hidden="true"></i> God's View </a></li>
		<li class="<?=(isset($script) && $script == 'Heat Map')?'active':'';?>"><a href="heatmap.php"><i class="fa fa-header" aria-hidden="true"></i> Heat View </a></li>
		<li class="<?=(isset($script) && $script == 'Review')?'active':'';?>"><a href="review.php"><i class="icon-comments"></i> Reviews </a></li>
		
		<li class="panel <?=(isset($script) && ($script == 'Payment Report' || $script == 'referrer' || $script == 'Wallet Report' || $script == 'Driver Log Report' || $script == 'CancelledTrips' || $script == 'Driver Accept Report' || $script == 'Driver Payment Report' || $script == 'Driver Trip Detail'))?'active':'';?>">
				<a href="javascript:void(0);" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-nav-report">
					<i class="icon-cogs"> </i> Reports
					
					<span class="pull-right">
						<i class="icon-angle-left"></i>
					</span>
					
				</a>
				<ul class="<?=(isset($script) && ($script == 'Payment Report' || $script == 'referrer' || $script == 'Wallet Report' || $script == 'Driver Payment Report' || $script == 'Driver Accept Report' ||$script == 'CancelledTrips' || $script == 'Driver Log Report' || $script == 'Driver Trip Detail'))?'in':'collapse';?>" id="component-nav-report">
					<li class="<?=(isset($script) && $script == 'Payment Report')?'active':'';?>"><a href="payment_report.php"><i class="icon-money"></i> Payment Report</a></li>
					<?php 
						
						if($REFERRAL_SCHEME_ENABLE == 'Yes'){ ?>
						
						<li class="<?=(isset($script) && $script == 'referrer')?'active':'';?>"><a href="referrer.php"><i class="fa fa-hand-peace-o" aria-hidden="true"></i> Referral Report</a></li>
					<?php } ?>
					
					<?php if($WALLET_ENABLE == 'Yes'){ ?> 	 
						
					<li class="<?=(isset($script) && $script == 'Wallet Report')?'active':'';?>"><a href="wallet_report.php"><i class="fa fa-google-wallet" aria-hidden="true"></i> User Wallet Report</a></li> <?php } ?>	
					
					<li class="<?=(isset($script) && $script == 'Driver Payment Report')?'active':'';?>"><a href="driver_pay_report.php"><i class="icon-money"></i> Driver Payment Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Driver Log Report')?'active':'';?>"><a href="driver_log_report.php"><i class="glyphicon glyphicon-list-alt"></i> Driver Log Report</a></li>
					
					 <li class="<?=(isset($script) && $script == 'CancelledTrips')?'active':'';?>"><a href="cancelled_trip.php"><i class="fa fa-exchange" aria-hidden="true"></i> Cancelled Trip</a></li> 

		     <!--  <li class="<?=(isset($script) && $script == 'Driver Accept Report')?'active':'';?>"><a href="ride_acceptance_report.php"><i class="icon-group"></i> Ride Acceptance Report </a></li>-->
			   
			   <li class="<?=(isset($script) && $script == 'Driver Trip Detail')?'active':'';?>"><a href="driver_trip_detail.php"><i class="fa fa-taxi"></i> Trip Time Variance </a></li>
					<?php /*<li class="<?=(isset($script) && $script == 'Driver Registration Report')?'active':'';?>"><a href="driver_registration_report.php"><i class="icon-money"></i> Driver Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Passenger Registration Report')?'active':'';?>"><a href="passenger_registration_report.php"><i class="icon-money"></i> Passenger Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Finished Rides Report')?'active':'';?>"><a href="finished_rides_report.php"><i class="icon-money"></i> Finished Rides Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Cancelled Rides Report')?'active':'';?>"><a href="cancelled_rides_report.php"><i class="icon-money"></i> Cancelled Rides Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Rides By Month Report')?'active':'';?>"><a href="rides_by_month_report.php"><i class="icon-money"></i> Rides By Month Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Online Report')?'active':'';?>"><a href="online_report.php"><i class="icon-money"></i> Online Report</a></li>
					
					<li class="<?=(isset($script) && $script == 'Offline Report')?'active':'';?>"><a href="offline_report.php"><i class="icon-money"></i> Offline Report</a></li> */ ?>
					
				</ul>
		</li>
		
		<li class="panel <?=(isset($script) && ($script == 'Settings' || $script == 'Language Settings'))?'active':'';?>">
			<a href="javascript:void(0);" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-nav">
				<i class="icon-cogs"> </i> Settings
				
				<span class="pull-right">
					<i class="icon-angle-left"></i>
				</span>
				<!-- &nbsp; <span class="label label-default">10</span>&nbsp; -->
			</a>
			<ul class="<?=(isset($script) && $script == 'Settings' || $script == 'Language Settings')?'in':'collapse';?>" id="component-nav">
				<li><a href="general.php"><i class="icon-angle-right"></i> General </a></li>
				<li><a href="email_template.php"><i class="icon-angle-right"></i> Email Templates </a></li>
				<li><a href="javascript:void(0);" data-parent="#component-nav" data-toggle="collapse" class="accordion-toggle" data-target="#language-nav"><i class="icon-angle-right"></i> Language Label
					<span class="pull-right">
						<i class="icon-angle-left"></i>
					</span></a>
					<ul class="<?=(isset($script) && $script == 'Language Settings')?'in':'collapse';?>" id="language-nav">
					    <li><a href="languages.php"><i class="icon-angle-right"></i> General Label </a></li>
						<li><a href="languages_admin.php"><i class="icon-angle-right"></i> Ride Label </a></li> 
					</ul>
				</li>
				<?php  if($APP_TYPE != 'UberX'){ ?>
					<li><a href="make.php"><i class="icon-angle-right"></i> <?php echo $langage_lbl_admin['LBL_CAR_MAKE_ADMIN'];?> </a></li>
					<li><a href="model.php"><i class="icon-angle-right"></i> <?php echo $langage_lbl_admin['LBL_CAR_MODEL_ADMIN'];?>  </a></li>
				<?php } ?>

				<li><a href="country.php"><i class="icon-angle-right"></i> Country</a></li>
				<li><a href="page.php"><i class="icon-angle-right"></i> Pages</a></li>
				<li><a href="currency.php"><i class="icon-angle-right"></i> Currency</a></li>
				<li><a href="faq.php"><i class="icon-angle-right"></i> Faq</a></li>
				<li><a href="faq_categories.php"><i class="icon-angle-right"></i>  Faq Categories</a></li>
				<li><a href="app_screenshot.php"><i class="icon-angle-right"></i>  App Screenshots</a></li>
				<li><a href="home_driver.php"><i class="icon-angle-right"></i>  Our Drivers</a></li>
				<li><a href="seo_setting.php"><i class="icon-angle-right"></i>  SEO Settings</a></li>
				<li><a href="banner.php"><i class="icon-angle-right"></i>  Banner</a></li>
				<li><a href="backup.php"><i class="icon-angle-right"></i>  DB Backup</a></li>
			</ul>
		</li>
		<?php } ?>
		<li><a href="logout.php"><i class="icon-signin"></i> Logout </a></li>
	</ul>
	
</div>
<!--END MENU SECTION -->
<script>
$('.sidebar-toggle').click(function(){
	$("#left").toggleClass("sidebar_hide");
	if ($("#left").hasClass("sidebar_hide")) {
		$("#left").addClass("sidebar-minize");
		$("#left").addClass("sidebar-collapse");
		//setMenuEnable(0);
	} else {
		$("#left").removeClass("sidebar-minize");
		$("#left").removeClass("sidebar-collapse");
		//setMenuEnable(1);
	}
});
</script>
