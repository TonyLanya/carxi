<?php
//echo "<pre>"; print_r($_SESSION);

if($_SESSION['sess_systype'] == "ridedelivery")
{
		define( 'TSITE_SERVER','localhost');
		define( 'TSITE_DB','uberridedelivery');
		define( 'TSITE_USERNAME','root');
		define( 'TSITE_PASS','root');
}elseif($_SESSION['sess_systype'] == "rideonly")
{
		define( 'TSITE_SERVER','localhost');
		define( 'TSITE_DB','uberapp');
		define( 'TSITE_USERNAME','root');
		define( 'TSITE_PASS','root');
}elseif($_SESSION['sess_systype'] == "deliveryonly")
{
		define( 'TSITE_SERVER','localhost');
		define( 'TSITE_DB','uberdeliveryonly');
		define( 'TSITE_USERNAME','root');
		define( 'TSITE_PASS','root');
}elseif($_SESSION['sess_systype'] == "uberforx")
{
	if($host_system == "carwash"){
		define( 'TSITE_SERVER','localhost');
		define( 'TSITE_DB','uber_for_x_carwash');
		define( 'TSITE_USERNAME','root');
		define( 'TSITE_PASS','root');
	}
}elseif($_SESSION['sess_systype'] == "motoonly")
{
		define( 'TSITE_SERVER','localhost');
		define( 'TSITE_DB','ubermoto');
		define( 'TSITE_USERNAME','root');
		define( 'TSITE_PASS','root');
}elseif($_SESSION['sess_systype'] == "ufxall")
{
		define( 'TSITE_SERVER','localhost');
		define( 'TSITE_DB','ufx_for_all');
		define( 'TSITE_USERNAME','root');
		define( 'TSITE_PASS','root');
}
else
{
		define( 'TSITE_SERVER','localhost');
		define( 'TSITE_DB','uberapp');
		define( 'TSITE_USERNAME','root');
		define( 'TSITE_PASS','root');
}
//echo $_SESSION['sess_systype'];exit;
?>