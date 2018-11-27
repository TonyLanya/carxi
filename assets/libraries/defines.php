<?php
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);
error_reporting(0);

defined( '_TEXEC' ) or die( 'Restricted access' );
$parts = explode( DS, TPATH_BASE );
define( 'TPATH_ROOT', TPATH_BASE );
define( 'TPATH_CLASS', TPATH_ROOT.DS.'assets'.DS.'libraries/' );


//include('db.php');

if($_SERVER["HTTP_HOST"] == "localhost")
{
	define( 'TSITE_SERVER','localhost');
	define( 'TSITE_DB','ridelati_ridelati');
	define( 'TSITE_USERNAME','ridelati_ridelat');
	define( 'TSITE_PASS','}b4J%kv;#f}~');
}
else
{
	define( 'TSITE_SERVER','localhost');
	define( 'TSITE_DB','ridelati_ridelati');
	define( 'TSITE_USERNAME','ridelati_ridelat');
	define( 'TSITE_PASS','}b4J%kv;#f}~');
}

define('PAYPAL_CLIENT_ID', 'AXE55Ggx7B1NpuhxfmKTcYipHIen2Lc1l9ZTU5Qt-4LbTpNmRm0vqCivgr1xkJF5uvg5rrzDwvB_30U-'); // Paypal client id
define('PAYPAL_SECRET', 'EMRwFwWhwXOQbD085uJN-3lugC00D2A2OGH-jQkowzwqQGiY14kwnsxrEuOu0dXmbZZ_xAR547Q1tghd'); // Paypal secret


if(!isset($obj))
{
    require_once(TPATH_CLASS."class.dbquery.php");
	$obj=	new DBConnection(TSITE_SERVER, TSITE_DB, TSITE_USERNAME,TSITE_PASS);
}

if(!isset($generalobj)){
	require_once(TPATH_CLASS."class.general.php");
	$generalobj = new General();
}

$generalobj->xss_cleaner_all();
$generalobj->getGeneralVar();

#Payment Option Settings
$date_before = date('Y-m-d');
$date_new = date('Y-m-d 00:00:00', strtotime('-1 week', strtotime($date_before)));
define('WEEK_DATE',$date_new);

define('SITE_TYPE','Live'); //Live  //Demo
define('PAYMENT_OPTION','Manual');
define('SITE_COLOR','#1fbad6');
#define('PAYMENT_OPTION','PayPal');
#define('PAYMENT_OPTION','Contact');
/*Language Label*/
if(!isset($_SESSION['sess_lang']) || $_SESSION['sess_lang']==""){
	$_SESSION['sess_lang']=$generalobj->get_default_lang();
}

$sql="select vLabel,vValue from language_label where vCode='".$_SESSION['sess_lang']."'";
    $db_lbl=$obj->MySQLSelect($sql);
    
    foreach ($db_lbl as $key => $value) {
    	$langage_lbl[$value['vLabel']] = $value['vValue'];	           
}

/*Language Label Other*/
$sql="select vLabel,vValue from language_label_other where vCode='".$_SESSION['sess_lang']."'";
$db_lbl=$obj->MySQLSelect($sql);
foreach ($db_lbl as $key => $value) {
	$langage_lbl[$value['vLabel']] = $value['vValue'];
//	$langage_lbl[$value['vLabel']] = $value['vValue']."  <span style='font-size:9px;'>".$value['vLabel'].'</span>';          
}

$sql="select vLabel,vValue from language_label where vCode='EN'";
$db_lbl_admin=$obj->MySQLSelect($sql);
    
foreach ($db_lbl_admin as $key => $value) {
    	$langage_lbl_admin[$value['vLabel']] = $value['vValue'];	           
//	$langage_lbl[$value['vLabel']] = $value['vValue']."  <span style='font-size:9px;'>".$value['vLabel'].'</span>';          
}

/*Language Label Other*/
$sql="select vLabel,vValue from language_label_other where vCode='EN'";
$db_lbl_admin=$obj->MySQLSelect($sql);
    
foreach ($db_lbl_admin as $key => $value) {

    	$langage_lbl_admin[$value['vLabel']] = $value['vValue'];	 
    	//$langage_lbl_admin[$value['vLabel']] = $value['vValue']."  <span style='font-size:9px;'>".$value['vLabel'].'</span>';          
}



define('RIIDE_LATER','YES');
define('PROMO_CODE','YES');
$APP_TYPE = $generalobj->getConfigurations("configurations","APP_TYPE");
$WALLET_ENABLE = $generalobj->getConfigurations("configurations","WALLET_ENABLE");
$REFERRAL_SCHEME_ENABLE = $generalobj->getConfigurations("configurations","REFERRAL_SCHEME_ENABLE");
$ALLOW_SERVICE_PROVIDER_AMOUNT = $generalobj->getConfigurations("configurations","ALLOW_SERVICE_PROVIDER_AMOUNT");

//$langage_lbl="";
?>
