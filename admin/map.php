<?php
include_once('../common.php');

if(!isset($generalobjAdmin)){
	require_once(TPATH_CLASS."class.general_admin.php");

	$generalobjAdmin = new General_admin();
}
$script="Map";
$generalobjAdmin->check_member_login();
#$script="Map";

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
	<!--meta http-equiv="refresh" content="60"-->
	<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<!-- GLOBAL STYLES -->
	<? include_once('global_files.php');?>
	<link rel="stylesheet" href="css/style.css" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	
	<script src="http://maps.google.com/maps/api/js?sensor=true&key=<?=$GOOGLE_SEVER_API_KEY_WEB?>" type="text/javascript"></script>
	<script type='text/javascript' src='../assets/map/gmaps.js'></script>
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
<?php //echo '<pre>'; print_r($returnArr); ?>
		<div class="inner" style="min-height: 700px;">
			<div class="row">
				<div class="col-lg-12">
					<h1> God's View / Map View</h1>
				</div>
			</div>
			<hr />

			<!-- COMMENT AND NOTIFICATION  SECTION -->
			<div class="row">
				<div class="col-lg-12">

					<div class="chat-panel panel panel-default">
						<div class="panel-heading">
							<i class="icon-map-marker"></i>
							Locations
						</div>

						<div class="panel-heading" style="background:none;">
							<div class="google-map-wrap" >
								<div id="google-map" class="google-map">
								</div><!-- #google-map -->
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="hrhr"><hr /></div>
			<div class="row">
				<div class="col-lg-12">
					<div style="" class="quick-btn-bottom-part">
						<a class="quick-btn" href="map.php?type=online">
							<img src="../webimages/upload/mapmarker/green.png">
							<span>Online</span>
							<span id="onlineCnt"><?php echo $db_records_online[0]['ONLINE'];?></span>
						</a>
						<a class="quick-btn" href="map.php?type=enroute">
							<img src="../webimages/upload/mapmarker/orange.png">
							<span>On<?php echo $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'];?> </span>
							<span id="onTripCnt"><?php echo $db_records_offline[0]['ONTRIP'];?></span>
						</a>
						<a class="quick-btn" href="map.php?type=offline">
							<img src="../webimages/upload/mapmarker/gray.png">
							<span>Offline</span>
							<span id="offlineCnt"<?php echo $db_records_offline[0]['OFFLINE'];?>></span>
						</a>
						<a class="quick-btn" href="map.php">
							<b><i class="icon-align-justify icon-1x"></i></b>
							<span>All</span>
							<span id="allCnt"><?php echo $db_records_online[0]['ONLINE'] + $db_records_offline[0]['ONTRIP'] + $db_records_offline[0]['OFFLINE'];?></span>
						</a>
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

<script>
	jQuery( document ).ready( function($) {
		/* Do not drag on mobile. */
		var is_touch_device = 'ontouchstart' in document.documentElement;
		var newName; var newAddr; var newOnlineSt; var newLat; var newLong; var newImg; var map;
		var bounds = [];
		var markers = [];
		var latlng;
		var type = '<?php echo $_REQUEST['type']; ?>';

		$.ajax({
			type: "POST",
			url: "getMapDrivers_list.php",
			dataType: "json",
			data: {type: type},
			success: function(dataHtml){
				// console.log(type);
				// debugger;
				// console.log(dataHtml);
				$("#onlineCnt").text(dataHtml.newStatus.ONLINE);
				$("#offlineCnt").text(dataHtml.newStatus.OFFLINE);
				$("#onTripCnt").text(dataHtml.newStatus.ONTRIP);
				$("#allCnt").text(dataHtml.newStatus.All);
				var newLocations = dataHtml.locations;

				map = new GMaps({
                                        
					el: '#google-map',
					lat: newLocations[0].google_map.lat,
					lng: newLocations[0].google_map.lng,
					scrollwheel: false,
					draggable: ! is_touch_device
				});

				for (var i = 0; i < newLocations.length; i++) {
					newName = newLocations[i].location_name;
					newAddr = newLocations[i].location_address;
					newOnlineSt = newLocations[i].location_online_status;
					newLat = newLocations[i].google_map.lat;
					newLong = newLocations[i].google_map.lng;

					console.log(newLat);
					
					latlng = new google.maps.LatLng(newLat, newLong);
					bounds.push(latlng);

					if(newOnlineSt == 'Available') { newImg = '../webimages/upload/mapmarker/green.png'; } else if(newOnlineSt == 'Ontrip') { newImg = '../webimages/upload/mapmarker/orange.png'; }else { newImg = '../webimages/upload/mapmarker/gray.png'; }
					var marker = map.addMarker({
						lat: newLat,
						lng: newLong,
						title: newName,
						icon: newImg,
						infoWindow: {
							content: '<table style="font-weight:bold;"><tr><td>Name </td><td>- '+newName+'</tr><tr><td>Last Location </td><td>- '+newAddr+'</tr></table>'
						}
					});
					markers.push(marker);
				}
				map.fitLatLngBounds(bounds);
			},
			error: function(dataHtml){
				// console.log(type);
				// debugger;
				// console.log(dataHtml);
				var map = new GMaps({
                                        zoom: 2,
					el: '#google-map',
					lat: '',
					lng: '',
					scrollwheel: false,
					draggable: ! is_touch_device
				});
			}
		});

		/* Map Reload after a minute */
		setInterval(function() {
			$.ajax({
				type: "POST",
				url: "getMapDrivers_list.php",
				dataType: "json",
				data: {type: type},
				success: function(dataHtml){
					$("#onlineCnt").text(dataHtml.newStatus.ONLINE);
					$("#offlineCnt").text(dataHtml.newStatus.OFFLINE);
					$("#onTripCnt").text(dataHtml.newStatus.ONTRIP);
					$("#allCnt").text(dataHtml.newStatus.All);

					for (var i = 0; i < markers.length; i++) {
					  markers[i].setMap(null);
					}

					var newLocations = dataHtml.locations;
					for (var i = 0; i < newLocations.length; i++) {
					 	newName = newLocations[i].location_name;
						newAddr = newLocations[i].location_address;
						newOnlineSt = newLocations[i].location_online_status;
						newLat = newLocations[i].google_map.lat;
						newLong = newLocations[i].google_map.lng;

						latlng = new google.maps.LatLng(newLat, newLong);

						if(newOnlineSt == 'Available') { newImg = '../webimages/upload/mapmarker/green.png'; } else if(newOnlineSt == 'Ontrip') { newImg = '../webimages/upload/mapmarker/orange.png'; }else { newImg = '../webimages/upload/mapmarker/gray.png'; }
						var marker = map.addMarker({
							lat: newLat,
							lng: newLong,
							title: newName,
							icon: newImg,
							infoWindow: {
								content: '<table style="font-weight:bold;"><tr><td>Name </td><td>- '+newName+'</tr><tr><td>Last Location </td><td>- '+newAddr+'</tr></table>'
							}
						});
						markers.push(marker);
					}
				},
				error: function(dataHtml){

				}
			});
		},30000);
		/* Map Reload after a minute */


		var $window = $(window);
		function mapWidth() {
			var size = $('.google-map-wrap').width();
			$('.google-map').css({width: size + 'px', height: (size/2) + 'px'});
		}
		mapWidth();
		$(window).resize(mapWidth);
	});
</script>
</body>
<!-- END BODY-->
</html>
