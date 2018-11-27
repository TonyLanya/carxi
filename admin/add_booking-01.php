	<?php
include_once('../common.php');



if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
$generalobjAdmin->check_member_login();

$tbl_name = 'cab_booking';
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : '';
$iCabBookingId = isset($_REQUEST['booking_id']) ? $_REQUEST['booking_id'] : '';
$action = ($iCabBookingId != '') ? 'Edit' : 'Add';
$scrtipt = "Add Booking";

$sql1 = "SELECT * FROM `vehicle_type` WHERE 1";
$db_carType = $obj->MySQLSelect($sql1);

$sql2 = "select * FROM register_driver WHERE 1 AND eStatus='active' ORDER BY vName ASC";
$db_records_online = $obj->MySQLSelect($sql2);
// echo "<pre>";
// print_r($db_records_online); die;

if ($action == 'Edit') {
     $sql = "SELECT * FROM " . $tbl_name . " LEFT JOIN register_user on register_user.iUserId=" . $tbl_name . ".iUserId WHERE " . $tbl_name . ".iCabBookingId = '" . $iCabBookingId . "'";
     $db_data = $obj->MySQLSelect($sql);
     //echo "<pre>";print_R($db_data);echo "</pre>"; die;
     $vPass = $generalobj->decrypt($db_data[0]['vPassword']);
     $vLabel = $id;
     if (count($db_data) > 0) {
          foreach ($db_data as $key => $value) {
			   $iUserId = $value['iUserId'];
			   $vDistance = $value['vDistance'];
			   $vDuration = $value['vDuration'];
			   $dBooking_date = $value['dBooking_date'];
			   $vSourceAddresss = $value['vSourceAddresss'];
			   $tDestAddress = $value['tDestAddress'];
			   $iVehicleTypeId = $value['iVehicleTypeId'];
			   $vPhone = $value['vPhone'];
			   $vName = $value['vName'];
			   $vLastName = $value['vLastName'];
			   $vEmail = $value['vEmail'];
			   $from_lat_long = '('.$value['vSourceLatitude'].', '.$value['vSourceLongitude'].')';
			   $to_lat_long = '('.$value['vDestLatitude'].', '.$value['vDestLongitude'].')';
			   #$vCurrencyDriver=$value['vCurrencyDriver'];
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
        <title>Admin | Manual Taxi Dispatch </title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
        <?
        include_once('global_files.php');
        ?>
        <!-- On OFF switch -->
        <link href="../assets/css/jquery-ui.css" rel="stylesheet" />
        <link rel="stylesheet" href="../assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />
        <!-- Google Map Js -->
        <!--script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=en&key=<?=$GOOGLE_SEVER_API_KEY_WEB?>"></script>-->
        <script type="text/javascript"  src="http://maps.googleapis.com/maps/api/js?key=<?=$GOOGLE_SEVER_API_KEY_WEB?>&sensor=false&libraries=places"></script>


    </head>
    <!-- END  HEAD-->
    <!-- BEGIN BODY-->
    <body class="padTop53 " >

        <!-- MAIN WRAPPER -->
        <div id="wrap">
            <?
            include_once('header.php');
            include_once('left_menu.php');
            ?>
            <!--PAGE CONTENT -->
            <div id="content">
                <div class="inner">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>Manual Taxi Dispatch</h2>
							<?php if ($action == 'Edit') { ?>
							<a href="cab_booking.php">
									<input type="button" value="Back to Listing" class="add-btn">
							</a>
							<?php } ?>
                        </div>
                    </div>
                    <hr />
                    <div class="body-div">
						<a class="btn btn-primary how_it_work_btn" data-toggle="modal" data-target="#myModal"><i class="fa fa-question-circle" style="font-size: 18px;"></i> How It Works?</a>
						
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog modal-large">
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								  <span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title" id="myModalLabel"> How It Works?</h4>
							  </div>
							  <div class="modal-body">
							<p><b>Flow </b>: Through "Manual Taxi Dispatch" Feature, you can book Rides for customers who ordered for a Ride by calling you. Tyhere will be customers who may not have iPhone or Android Phone or may not have app installed on their phone. In this case, they will call Taxi Company (your company) and order Ride which may be needed immediately or after some time later.</p>
							<p>Here, you will fill their info in the form and dispatch book a taxi ride for him.</p>
							<p>The Driver will receive info on his App and will pickup the rider at the scheduled time.</p>
							<p>- If the customer is already registered with us, just enter his phone number and his info will be fetched from the database when "Get Details" button is clicked. Else fill the form.</p>
							<p>- Once the Trip detail is added, Fare estimate will be calculated based on Pick-Up Location, Drop-Off Location and Car Type.</p>
							<p>- Admin will need to communicate & confirm with Driver and then select him as Driver so the Ride can be allotted to him. </p>
							<p>- Clicking on "Book" Button, the Booking detail will be saved and will take Administrator to the "Ride Later Booking" Section. This page will show all such bookings.</p>
							<p>- The assigned Driver can see the upcoming Bookings from his App under "My Bookings" section.</p>
							<p>- Driver will have option to "Start Trip" when he reaches the Pickup Location at scheduled time or "Cancel Trip" if he cannot take the ride for some reason. If the Driver clicks on "Cancel Trip", a notification will be sent to Administrator so he can make alternate arrangements.</p>
							<p>- Upon clicking on "Start Trip", the ride will start in driver's App in regular way.</p>
								<p><span><img src="images/mobile_app_booking.png"></img></span></p>
							  </div>
							  <div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
							  </div>
							</div>
						  </div>
						</div>
						
                        <div class="form-group">
                            <? if ($success == "1") {?>
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">�</button>
                                <?php
                                if ($vassign != "1") {
                                    ?>
                                    Booking Has Been Added Successfully.
                                <?php } else {
                                    ?>
                                    Driver Has Been Assigned Successfully.
                                <?php } ?>

                            </div><br/>
                            <? } ?>

                            <? if ($success == 2) {?>
                            <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">�</button>
                                "Edit / Delete Record Feature" has been disabled on the Demo Admin Panel. This feature will be enabled on the main script we will provide you.
                            </div><br/>
                            <? } ?>
                            <div class="col-lg-5">
                                <form name="add_booking_form" id="add_booking_form" method="post" action="action_booking.php" enctype="multipart/form-data">
                                    <input type="hidden" name="distance" id="distance" value="<?= $vDistance; ?>">
                                    <input type="hidden" name="duration" id="duration" value="<?= $vDuration; ?>">
                                    <input type="hidden" name="from_lat_long" id="from_lat_long" value="<?= $from_lat_long; ?>" >
                                    <input type="hidden" name="to_lat_long" id="to_lat_long" value="<?= $to_lat_long; ?>" >
                                    <input type="hidden" value="1" id="location_found" name="location_found">
                                    <input type="hidden" value="" id="user_type" name="user_type" >
                                    <input type="hidden" value="<?=$iUserId; ?>" id="iUserId" name="iUserId" >
                                    <input type="hidden" value="<?=$iCabBookingId; ?>" id="iCabBookingId" name="iCabBookingId" >
                                    <input type="hidden" value="<?=$GOOGLE_SEVER_API_KEY_WEB; ?>" id="google_server_key" name="google_server_key" >
                                    <input type="hidden" value="" id="fromlatitude" name="fromlatitude" >
                                    <input type="hidden" value="" id="fromlongitude" name="fromlongitude" >
                                    <input type="hidden" value="" id="getradius" name="getradius" >
									
									
									<div class="add-booking-form">
                                        
                                        <span>
                                          <input type="text" maxlength="12"  title="Please enter 10 digit mobile number." class="form-control add-book-input" name="vPhone"  id="vPhone" value="<?= $vPhone; ?>" placeholder="Enter Phone Number" required style="">
                                        <a class="btn btn-sm btn-info" id="get_details" >Get Details</a>
                                        </span>
                                        
                                        <span> <input type="text" pattern="[a-zA-Z]+" title="Only Alpha character allow" class="form-control first-name1" name="vName"  id="vName" value="<?= $vName; ?>" placeholder="First Name" required>  <input type="text" pattern="[a-zA-Z]+" title="Only Alpha character allow" class="form-control last-name1" name="vLastName"  id="vLastName" value="<?= $vLastName; ?>" placeholder="Last Name" required></span>
                                        <span><input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" name="vEmail" onChange="validate_email(this.value)"  id="vEmail" value="<?= $vEmail; ?>" placeholder="Email" required >
                                      <div id="emailCheck"></div></span>
                                        <span> <input type="text" class="ride-location1 highalert txt_active form-control first-name1" name="vSourceAddresss"  id="from" value="<?= $vSourceAddresss; ?>" placeholder="Pickup Location" required><input type="text" class="ride-location1 highalert txt_active form-control last-name1" name="tDestAddress"  id="to" value="<?= $tDestAddress; ?>" placeholder="Drop Off Location" required></span>
                                       
                                       <span> <input type="text" class=" form-control" name="dBooking_date"  id="datetimepicker4" value="<?= $dBooking_date; ?>" placeholder="Select Date / Time" required></span>


                                       <?php  $radius_driver = array(5,10,15,20,25); ?>                                      
                                           <span>
											<select class="form-control form-control-select" name='radius-id' id="radius-id" required onChange="getDriverRadius(this.value)">             
                                                <?php foreach ($radius_driver as $value) { ?>
                                                    <option value="<?php echo $value ?>"><?php echo $value . ' km Radius'; ?></option>
                                                <?php } ?>
                                            </select></span>                                     

                                        <?php if(!empty($db_records_online)) { ?>
                                       <span><select class="form-control form-control-select" name='iDriverId' id="iDriverId" required >
                                                <option value="" >Select Driver</option>
                                                <?php foreach ($db_records_online as $db_online) { ?>
                                                    <option value="<?php echo $db_online['iDriverId']; ?>"><?php echo $db_online['vName'].' '.$db_online['vLastName']; ?></option>
                                                <?php } ?>
                                            </select></span>
                                            <?php }else { ?>
									<div class="row show_drivers_lists">
										<div class="col-lg-12">
											<h5>No Drivers Found.</h5>
										</div>
									</div>
									<?php } ?>
                                    <span>
                                    <select class="form-control form-control-select" name='iVehicleTypeId' id="iVehicleTypeId" required onChange="getFarevalues(this.value)">
                                                <option value="" >Select Vehicle Type</option>
                                                <?php foreach ($db_carType as $db_car) { ?>
                                                    <option value="<?php echo $db_car['iVehicleTypeId']; ?>" <?php if($iVehicleTypeId == $db_car['iVehicleTypeId']){ echo "selected"; } ?> ><?php echo $db_car['vVehicleType']; ?></option>
                                                <?php } ?>
                                            </select></span>
                                   <span> <input type="submit" class="save btn-info button-submit" name="submit" id="submit" value="Book" >
                                            <input type="reset" class="save btn-info button-submit" name="reset" id="reset" value="Reset" ></span>     
                                    </div>
                                  

					
                                   
                                </form>
                                <div class="total-price">
                                   <ul>
                                    <li><b>Base Fare</b> : $ <em id="base_fare_price">0</em></li>
                                    <li><b>Distance (<em id="dist_fare">0</em> KMs)</b> : $ <em id="dist_fare_price">0</em></li>
                                    <li><b>Time (<em id="time_fare">0</em> Minutes)</b> : $ <em id="time_fare_price">0</em></li>
                                    </ul>
                              <span>Total Fare<b>$ <em id="total_fare_price">0</em></b></span>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="gmap-div gmap-div1"><div id="map-canvas" class="gmap3"></div></div>
                            </div>
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
		
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
		<script type="text/javascript" src="js/moment.min.js"></script>
		<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
        <script>
		
        var markers=[];
        var markers2=[];
        var locations1;
        var locations2 = [];
       var content1; 
      // var latlngset;
       
       // var infowindow = '';
       // var contentString ='';

			$('.gallery').each(function() { // the containers for all your galleries
				$(this).magnificPopup({
					delegate: 'a', // the selector for gallery item
					type: 'image',
					gallery: {
					  enabled:true
					}
				});
			});
		
			$(function () {
				newDate = new Date('Y-M-D');
                $('#datetimepicker4').datetimepicker({
					format: 'YYYY-MM-DD HH:mm:ss',
					minDate: moment().format('l'),
					ignoreReadonly: true,
					sideBySide: true,
				});
            });
            function getFarevalues(vehicleId) {
                $.ajax({
                    type: "POST",
                    url: 'ajax_find_rider_by_number.php',
                    data: 'vehicleId=' + vehicleId,
                    success: function (dataHtml)
                    {
                        if (dataHtml != "") {
                            var result = dataHtml.split(':');
                            $('#base_fare_price').text(parseFloat(result[0]).toFixed(2));
                            $('#dist_fare_price').text(parseFloat(result[1]*$('#dist_fare').text()).toFixed(2));
                            $('#time_fare_price').text(parseFloat(result[2]*$('#time_fare').text()).toFixed(2));
                            $('#total_fare_price').text((parseFloat($('#base_fare_price').text())+parseFloat($('#dist_fare_price').text())+parseFloat($('#time_fare_price').text())).toFixed(2));
                        }else {
                            $('#base_fare_price').text('0');
                            $('#dist_fare_price').text('0');
                            $('#time_fare_price').text('0');
                            $('#total_fare_price').text('0');
                        }
                    }
                });
            }
            
            $('#get_details').on('click', function () {
                var phone = $('#vPhone').val();
                $.ajax({
                    type: "POST",
                    url: 'ajax_find_rider_by_number.php',
                    data: 'phone=' + phone,
                    success: function (dataHtml)
                    {
                        if (dataHtml != "") {
                            $("#user_type").val('registered');
                            var result = dataHtml.split(':');
                            $('#vName').val(result[0]);
                            $('#vLastName').val(result[1]);
                            $('#vEmail').val(result[2]);
							$('#iUserId').val(result[3]);
                        }else {
                            $("#user_type").val('');
                            $('#vName').val('');
                            $('#vLastName').val('');
                            $('#vEmail').val('');
							$('#iUserId').val('');
                        }
                    }
                });
            });

           /* <?php //if($action != "Edit") { ?>*/

           	//callForedit
            var map;
             var setZoom=14;
            function initialize() {
                
                var mapOptions = {
                    zoom: 4,
                    center: new google.maps.LatLng('20.1849963', '64.4125062')
                };
                map = new google.maps.Map(document.getElementById('map-canvas'),
                        mapOptions);            
               
 				//callForedit();
			   
            }

            $(document).ready(function () {
                google.maps.event.addDomListener(window, 'load', initialize);
               
            });
            <?php if($action == "Edit") { ?>

            $(window).bind("load", function () { 
            	// Code here 
            	callForedit();
            });

              <?php } ?>

            



			/*<?php //} ?>*/
			function getDriverRadius(Radius)
                {                
                      
                        var fromlocation = document.getElementById('from').value;
                       
                        getLatitudeLongitude(showResult,fromlocation,Radius);  
                        
                }

            $(function () {  

                
                var getradius = $("#radius-id option:selected").val();
                var getradius = $("#getradius").val(getradius);
                // alert(radiusradiusdriver);

                var from = document.getElementById('from');
                var autocomplete_from = new google.maps.places.Autocomplete(from);
                google.maps.event.addListener(autocomplete_from, 'place_changed', function () {
                    var place = autocomplete_from.getPlace();
                    //go_for_action();  
                    var fromlocation = document.getElementById('from').value;
                    getLatitudeLongitude(showResult, fromlocation,getradius);      
                });

                var to = document.getElementById('to');
                var autocomplete_to = new google.maps.places.Autocomplete(to);
                google.maps.event.addListener(autocomplete_to, 'place_changed', function () {
                    var place = autocomplete_to.getPlace();
                    go_for_action();
                });

                function go_for_action() {

                    if ($("#from").val() != '' && $("#to").val() == '') {
                        show_location($("#from").val());
                    }

                    if ($("#to").val() != '' && $("#from").val() == '') {
                        show_location($("#to").val());
                    }

                    if ($("#from").val() != '' && $("#to").val() != '') {
                        from_to($("#from").val(), $("#to").val());
                    }
                }             
                
               
               
            });

            function getLatitudeLongitude(callback, address,getradius) {               
             

            
                // If adress is not supplied, use default value 'Ferrol, Galicia, Spain'
                address = address || 'Ferrol, Galicia, Spain';
                // Initialize the Geocoder
                geocoder = new google.maps.Geocoder();
                if (geocoder) {
                    geocoder.geocode({
                        'address': address
                    }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            callback(results[0],getradius);
                        }
                    });
                }
           }
               

            function showResult(result,limitdistance) {            
                   
                 

			   var limitdistance1 = document.getElementById('radius-id').value ;
			   //alert('showResult'+limitdistance1);
			   var getgoogleserverkey = document.getElementById('google_server_key').value ;  
				var getLat = document.getElementById('fromlatitude').value = result.geometry.location.lat();


				 
				var getLng= document.getElementById('fromlongitude').value = result.geometry.location.lng();      
								 
						   
				$.ajax({
				type: "POST",
				url: 'ajax_find_driver_by_number.php',
				data: {getlan:getLat,getlng:getLng,googlekeydata:getgoogleserverkey,limitdistance:limitdistance1},
				dataType:'json',
					success: function (response)
					{
					   var locations = [];                              
					   for (i = 0; i < response.length; i++) {
							locations[i] = [];                                    
							locations[i].push(response[i].name);
							locations[i].push(response[i].lat);
							locations[i].push(response[i].lag);
							locations[i].push(response[i].add);
							locations[i].push(response[i].iDriverId);							   
						}    
						if(limitdistance == 5){
						 setZoom = 14;
						
						}else if(limitdistance == 10){
							setZoom = 13;
							 
						}else if(limitdistance == 15){
							setZoom = 12;
							 
						}else if(limitdistance == 20){
						 setZoom = 11;
						
						}else if(limitdistance == 25){
							setZoom = 10;								
						}                                                     

						latlngset = new google.maps.LatLng(getLat, getLng);
						markers2 = new google.maps.Marker({  
						  map: map, title: name , position: latlngset,icon: "../webimages/upload/mapmarker/source_marker.png"  
						});
						map.setCenter(markers2.getPosition());
						map.setZoom(setZoom);

					   setMarkers(map,locations);
					   if(response == ""){  							
						latlngset = new google.maps.LatLng(getLat, getLng);
						 markers2 = new google.maps.Marker({  
						  map: map, title: name , position: latlngset,icon: "../webimages/upload/mapmarker/source_marker.png"  
						});
						 map.setCenter(markers2.getPosition());
					   }                          
					}
				}); 
            } 


			function setMarkers(map,locations){

				
			  var marker, i;

				for (i = 0; i < locations.length; i++)
				{    

					 var name = locations[i][0]
					 var lat = locations[i][1]
					 var long = locations[i][2]
					 var add =  locations[i][3]
					 var driverId =  locations[i][4]

					 latlngset = new google.maps.LatLng(lat, long);
					 marker = new google.maps.Marker({  
						  map: map, title: name , position: latlngset,icon: "../webimages/upload/mapmarker/green.png"  
					});
						//map.setCenter(marker.getPosition())


					var content = "Driver Name: " + name +  '</h3><br>' + " "+"Address: " + add +"<br> "+"<a href='javascript:void(0)' onClick='AssignDriver("+driverId+");'>Assign Driver</a>";    

					var infowindow = new google.maps.InfoWindow();

					google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
						return function() {
						   infowindow.setContent(content);
						   infowindow.open(map,marker);
						};
					})(marker,content,infowindow)); 

				 }
			}
			 function AssignDriver(driverId){
			
				$('#iDriverId').val(driverId);

			 }  
        </script>
        <script type="text/javascript" src="js/gmap3.js"></script>
        <script type="text/javascript">
            var chk_route;
            function show_location(address) {
                
                //clearThat();
                $('#map-canvas').gmap3({
                    marker: {
                        address: address
                    },
                    map: {
                        options: {
                            zoom: 18
                        }
                    }
                });
            }

            function clearThat() {
                var opts = {};
                opts.name = ["marker", "directionsrenderer"];
                opts.first = true;
                $('#map-canvas').gmap3({clear: opts});
            }

            function from_to(from, to) {
            	//alert('sds');
               //clearThat();
                if (from == '')
                    from = $('#from').val();

                if (to == '')
                    to = $('#to').val();
                //alert("from_to" + from +"   to "+to);
                $("#from_lat_long").val('');
                $("#to_lat_long").val('');

                var chks = document.getElementsByName('loc');
                var waypts = [];


                if (from != '') {
                    //alert("in from "+from);
                    $("#map-canvas").gmap3({
                        getlatlng: {
                            address: from,
                            callback: function (results) {                              
                                 
                                $("#from_lat_long").val(results[0].geometry.location);
                            }
                        }
                    });
                }
                if (to != '') {
                    $("#map-canvas").gmap3({
                        getlatlng: {
                            address: to,
                            callback: function (results) {
                                $("#to_lat_long").val(results[0].geometry.location);
                            }
                        }
                    });
                }

                $("#map-canvas").gmap3({

                    getroute: {
                        options: {
                            origin: from,
                            destination: to,
                            travelMode: google.maps.DirectionsTravelMode.DRIVING
                        },
                        callback: function (results, status) {
                            chk_route = status;
                            if (!results)
                                return;
                            /*$(this).gmap3({
                                map: {
                                    options: {
                                        zoom: 8,
                                        //center: [51.511214, -0.119824]
                                        center: [58.0000, 20.0000]
                                    }
                                },
                                directionsrenderer: {
                                    options: {
                                        directions: results
                                    }
                                }
                            });*/
                        }
                    }
                });

                $("#map-canvas").gmap3({  

                    getdistance: {
                        options: {
                            origins: from,
                            destinations: to,
                            travelMode: google.maps.TravelMode.DRIVING
                        },                           
                        callback: function (results, status) {                        	
                        	var html = "";
                            if (results) {
                                for (var i = 0; i < results.rows.length; i++) {
                                    var elements = results.rows[i].elements;
                                    for (var j = 0; j < elements.length; j++) {
                                        switch (elements[j].status) {
                                            case "OK":
                                                html += elements[j].distance.text + " (" + elements[j].duration.text + ")<br />";

                                                document.getElementById("distance").value = elements[j].distance.value;
                                                document.getElementById("duration").value = elements[j].duration.value;
                                                var dist_fare = parseInt(elements[j].distance.value, 10) / parseInt(1000, 10);
                                                $('#dist_fare').text(Math.round(dist_fare));
                                                var time_fare = parseInt(elements[j].duration.value, 10) / parseInt(60, 10);
                                                $('#time_fare').text(Math.round(time_fare));
                                                var vehicleId = $('#iVehicleTypeId').val();
                                                $.ajax({
                                                    type: "POST",
                                                    url: 'ajax_find_rider_by_number.php',
                                                    data: 'vehicleId=' + vehicleId,
                                                    success: function (dataHtml)
                                                    {
                                                        if (dataHtml != "") {
                                                            var result = dataHtml.split(':');
                                                            $('#base_fare_price').text(parseFloat(result[0]).toFixed(2));
                                                            $('#dist_fare_price').text(parseFloat(result[1]*$('#dist_fare').text()).toFixed(2));
                                                            $('#time_fare_price').text(parseFloat(result[2]*$('#time_fare').text()).toFixed(2));
                                                            $('#total_fare_price').text((parseFloat($('#base_fare_price').text())+parseFloat($('#dist_fare_price').text())+parseFloat($('#time_fare_price').text())).toFixed(2));
                                                        }else {
                                                            $('#base_fare_price').text('0');
                                                            $('#dist_fare_price').text('0');
                                                            $('#time_fare_price').text('0');
                                                            $('#total_fare_price').text('0');
                                                        }
                                                    }
                                                });
                                                document.getElementById("location_found").value = 1;
                                                break;
                                            case "NOT_FOUND":
                                                document.getElementById("location_found").value = 0;
                                                break;
                                            case "ZERO_RESULTS":
                                                document.getElementById("location_found").value = 0;
                                                break;
                                        }
                                    }
                                }
                            } else {
                                html = "error";
                            }
                            $("#results").html(html);
                        }
                    }
                });
            }
			
	


		function callForedit(){


			var fromlocation = document.getElementById('from').value;
			var limitdistance1 = document.getElementById('radius-id').value ;
			
                
                getLatitudeLongitude(showResult, fromlocation,getradius);
			
				var from = $('#from').val();
				//  alert(from);
				  
				var to = $('#to').val();
				//var waypts = [];
				if (from != '') {
					//alert("in from "+from);
					$("#map-canvas").gmap3({
						getlatlng: {
							address: from,
							callback: function (results) {
								console.log(results[0]);
								$("#from_lat_long").val(results[0].geometry.location);
							}
						}
					});
				}
				if (to != '') {
					$("#map-canvas").gmap3({
						getlatlng: {
							address: to,
							callback: function (results) {
								$("#to_lat_long").val(results[0].geometry.location);
							}
						}
					});
				}

				$("#map-canvas").gmap3({
					getroute: {
						options: {
							origin: from,
							destination: to,
							travelMode: google.maps.DirectionsTravelMode.DRIVING
						},
						callback: function (results, status) {
							chk_route = status;
							if (!results)
								return;
							/*$(this).gmap3({
								map: {
									options: {
										zoom: 8,
										//       center: [51.511214, -0.119824]
										center: [58.0000, 20.0000]
									}
								},
								directionsrenderer: {
									options: {
										directions: results
									}
								}
							});*/
						}
					}
				});

				$("#map-canvas").gmap3({
					getdistance: {
						options: {
							origins: from,
							destinations: to,
							travelMode: google.maps.TravelMode.DRIVING
						},
						callback: function (results, status) {
							var html = "";
							if (results) {
								for (var i = 0; i < results.rows.length; i++) {
									var elements = results.rows[i].elements;
									for (var j = 0; j < elements.length; j++) {
										switch (elements[j].status) {
											case "OK":
												html += elements[j].distance.text + " (" + elements[j].duration.text + ")<br />";
												document.getElementById("distance").value = elements[j].distance.value;
                                                document.getElementById("duration").value = elements[j].duration.value;
                                                var dist_fare = parseInt(elements[j].distance.value, 10) / parseInt(1000, 10);
                                                $('#dist_fare').text(Math.round(dist_fare));
                                                var time_fare = parseInt(elements[j].duration.value, 10) / parseInt(60, 10);
                                                $('#time_fare').text(Math.round(time_fare));
                                                var vehicleId = $('#iVehicleTypeId').val();
                                                $.ajax({
                                                    type: "POST",
                                                    url: 'ajax_find_rider_by_number.php',
                                                    data: 'vehicleId=' + vehicleId,
                                                    success: function (dataHtml)
                                                    {
                                                        if (dataHtml != "") {
                                                            var result = dataHtml.split(':');
                                                            $('#base_fare_price').text(parseFloat(result[0]).toFixed(2));
                                                            $('#dist_fare_price').text(parseFloat(result[1]*$('#dist_fare').text()).toFixed(2));
                                                            $('#time_fare_price').text(parseFloat(result[2]*$('#time_fare').text()).toFixed(2));
                                                            $('#total_fare_price').text((parseFloat($('#base_fare_price').text())+parseFloat($('#dist_fare_price').text())+parseFloat($('#time_fare_price').text())).toFixed(2));
                                                        }else {
                                                            $('#base_fare_price').text('0');
                                                            $('#dist_fare_price').text('0');
                                                            $('#time_fare_price').text('0');
                                                            $('#total_fare_price').text('0');
                                                        }
                                                    }
                                                });
												document.getElementById("location_found").value = 1;
												break;
											case "NOT_FOUND":
												document.getElementById("location_found").value = 0;
												break;
											case "ZERO_RESULTS":
												document.getElementById("location_found").value = 0;
												break;
										}
									}
								}
							} else {
								html = "error";
							}
							$("#results").html(html);
						}
					}
				});
			}


			
			
        </script>
        <script src="../assets/plugins/switch/static/js/bootstrap-switch.min.js"></script>
        <script>
            function changeCode(id)
            {
                var request = $.ajax({
                    type: "POST",
                    url: 'change_code.php',
                    data: 'id=' + id,
                    success: function (data)
                    {
                        document.getElementById("code").value = data;
                    }
                });
            }
            function validate_email(id)
            {

                var request = $.ajax({
                    type: "POST",
                    url: 'validate_email.php',
                    data: 'id=' + id,
                    success: function (data)
                    {
                        if (data == 0)
                        {
                            $('#emailCheck').html('<i class="icon icon-remove alert-danger alert">Already Exist,Select Another</i>');
                            $('input[type="submit"]').attr('disabled', 'disabled');
                        } else if (data == 1)
                        {
                            var eml = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                            result = eml.test(id);
                            if (result == true)
                            {
                                $('#emailCheck').html('<i class="icon icon-ok alert-success alert"> Valid</i>');
                                $('input[type="submit"]').removeAttr('disabled');
                            } else
                            {
                                $('#emailCheck').html('<i class="icon icon-remove alert-danger alert"> Enter Proper Email</i>');
                                $('input[type="submit"]').attr('disabled', 'disabled');
                            }
                        }
                    }
                });
            }
			
			function showhideDrivers(dtype) {
				if(dtype == "manual") {
					$('.show_drivers_lists').slideDown();
					$('select[name="iDriverId"]').attr('required','required');
				}else {
					$('.show_drivers_lists').slideUp();
					$('select[name="iDriverId"]').removeAttr('required');
				}
			}
        </script>
    </body>
    <!-- END BODY-->
</html>
