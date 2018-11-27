<?
include_once("common.php");

$dist_fare = isset($_REQUEST['dist_fare'])?$_REQUEST['dist_fare']:'';
$time_fare = isset($_REQUEST['time_fare'])?$_REQUEST['time_fare']:'';

$dist_fare = $dist_fare/1.609344;

if($dist_fare != '' && $time_fare != "")
{
	$priceRatio = 1;
	
	$sql = "select * from vehicle_type";
	$db_vType = $obj->MySQLSelect($sql);
	
	$cont = '';
	$cont .= '<ul>';
    for($i=0;$i<count($db_vType);$i++){    	
		
		$Minute_Fare =round($db_vType[$i]['fPricePerMin']*$time_fare,2) * $priceRatio;

		$iBaseFare =round($db_vType[$i]['iBaseFare'],2)* $priceRatio;
		
		if( $dist_fare < 2.5 ){
			$total_fare=$iBaseFare;
		} else {
			// $above_4_dist_fare = $dist_fare - 2.5;
			
			// echo $Distance_Fare =round($db_vType[$i]['fPricePerKM']* 2.5 ,2)* $priceRatio;
			// Rest after 2.5 miles
			// echo '---';
			// $New_Distance_Fare =round( 2 * $above_4_dist_fare ,2)* $priceRatio;

			// $Distance_Fare =  $Distance_Fare + $New_Distance_Fare + 2 - $iBaseFare;

			$Distance_Fare = ( round($db_vType[$i]['fPricePerKM'] * $dist_fare )* $priceRatio ) + 2 - $iBaseFare;
			$total_fare=$iBaseFare+$Minute_Fare+$Distance_Fare;

		}
		


		
		
		
		$cont .= '<li><label>'.$db_vType[$i]['vVehicleType'].'<img src="assets/img/question-icon.jpg" alt="" title="'.$langage_lbl['LBL_APPROX_DISTANCE_TXT'].' '.$langage_lbl['LBL_FARE_ESTIMATE_TXT'].'"><b>'.$generalobj->trip_currency($total_fare).'</b></label></li>';		
    }

	$cont .= '<li><p>'.$langage_lbl['LBL_HOME_PAGE_GET_FIRE_ESTIMATE_TXT'].'</p></li>';
	if(!isset($_SESSION['sess_user']) && $_SESSION['sess_user'] == "") {
		$cont .= '<li><strong><a href="sign-up-rider"><em>'.$langage_lbl['LBL_RIDER_SIGNUP1_TXT'].'</em></a></strong></li>';
	}
	$cont .= '</ul>';
    echo $cont; exit;
}
?>
