<?
	include_once('common.php');
	$email = isset($_POST['femail'])?$_POST['femail']:'';
	$action = isset($_POST['action'])?$_POST['action']:'';

	//echo SITE_TYPE;	
	if($action == 'driver')
	{
		$sql = "SELECT * from company where vEmail = '".$email."' and eStatus != 'Deleted'";
		$db_login = $obj->MySQLSelect($sql);

		if(count($db_login)>0)
		{
			if(SITE_TYPE != 'Demo'){
					$milliseconds = time();
					$tempGenrateCode = substr($milliseconds, 1);
					$url = $tconfig["tsite_url"].'reset_password.php?type='.$action.'&generatepsw='.$tempGenrateCode;
					$maildata['EMAIL'] = $db_login[0]["vEmail"];
					$maildata['NAME'] = $db_login[0]["vCompany"];				
					$maildata['LINK'] = $url;
					//$status = $generalobj->send_email_user("CUSTOMER_FORGETPASSWORD",$db_login);
					
					$status = $generalobj->send_email_user("CUSTOMER_RESET_PASSWORD",$maildata);
					$sql = "UPDATE company set vPasswordToken='".$tempGenrateCode."' WHERE vEmail='".$email."' and eStatus != 'Deleted'" ;
					$obj->sql_query($sql);					
			}
			else {
				$status = 1;
			}
			

			if($status == 1)
			{
				$var_msg = "Your Password has been sent Successfully.";
				$error_msg = "1";
			}
			else
			{
				$var_msg = "Error in Sending password.";
				$error_msg = "0";
			}
		}
		else
		{
			$sql = "SELECT * from register_driver where vEmail = '".$email."' and eStatus != 'Deleted'";
			$db_login = $obj->MySQLSelect($sql);
			if(count($db_login)>0)
			{
				if(SITE_TYPE != 'Demo'){
				
					$milliseconds = time();
					$tempGenrateCode = substr($milliseconds, 1);
					$url = $tconfig["tsite_url"].'reset_password.php?type='.$action.'&generatepsw='.$tempGenrateCode;
					$maildata['EMAIL'] = $db_login[0]["vEmail"];
					$maildata['NAME'] = $db_login[0]["vName"]." ".$db_login[0]["vLastName"];				
					$maildata['LINK'] = $url;
					//$status = $generalobj->send_email_user("CUSTOMER_FORGETPASSWORD",$db_login);
					
					$status = $generalobj->send_email_user("CUSTOMER_RESET_PASSWORD",$maildata);
					$sql = "UPDATE register_driver set vPasswordToken='".$tempGenrateCode."' WHERE vEmail='".$email."' and eStatus != 'Deleted'";
					$obj->sql_query($sql);
					
				}
				else {
					$status = 1;
				}
				//echo $status;exit;
				if($status == 1)
				{
					$var_msg = "Your Password has been sent Successfully.";
					$error_msg = "1";
				}
				else
				{
					$var_msg = "Error in Sending Password.";
					$error_msg = "0";
				}
			}
			else
			{
				 $var_msg = "Sorry ! The Email address you have entered is not found.";
				 $error_msg = "0";
			}
		}
		//echo $error_msg;
	}
	if($action == 'rider')
	{
		$sql = "SELECT * from register_user where vEmail = '".$email."' and eStatus != 'Deleted'";
		$db_login = $obj->MySQLSelect($sql);
		if(count($db_login)>0)
		{
			if(SITE_TYPE != 'Demo'){
				$milliseconds = time();
				$tempGenrateCode = substr($milliseconds, 1);
				$url = $tconfig["tsite_url"].'reset_password.php?type='.$action.'&generatepsw='.$tempGenrateCode;
				$maildata['EMAIL'] = $db_login[0]["vEmail"];
				$maildata['NAME'] = $db_login[0]["vName"]." ".$db_login[0]["vLastName"];				
				$maildata['LINK'] = $url;
				//$status = $generalobj->send_email_user("CUSTOMER_FORGETPASSWORD",$db_login);
				
				$status = $generalobj->send_email_user("CUSTOMER_RESET_PASSWORD",$maildata);
				$sql = "UPDATE register_user set vPasswordToken='".$tempGenrateCode."' WHERE vEmail='".$email."' and eStatus != 'Deleted'";
				$obj->sql_query($sql);
			}
			else {
				$status = 1;
			}
			if($status == 1)
			{
				$var_msg = "Your Password has been sent Successfully.";
				$error_msg = "1";
			}
			else
			{
				$var_msg = "Error in Sending Password.";
				$error_msg = "0";
			}
		}
		else
		{
			$var_msg = "Sorry ! The Email address you have entered is not found.";
			$error_msg = "3";
		}
	}
	$data['msg'] = $var_msg;
	$data['status'] = $error_msg;
	echo json_encode($data);
?>
