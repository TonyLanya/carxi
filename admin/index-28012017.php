<?php
	include_once('../common.php');
	
	if(!isset($generalobjAdmin))
	{
		require_once(TPATH_CLASS."class.general_admin.php");
		$generalobjAdmin = new General_admin();
	}
	$generalobjAdmin->go_to_home();
	
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--><html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="UTF-8" />
<title>Admin | Login Page</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<link rel="icon" href="../favicon.ico" type="image/x-icon">
<!--[if IE]>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<![endif]-->
<!-- GLOBAL STYLES -->
<!-- PAGE LEVEL STYLES -->
<link rel="stylesheet" href="css/bootstrap.css" />
<link rel="stylesheet" href="css/login.css" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="../assets/css/animate/animate.min.css" />
<link rel="stylesheet" href="../assets/plugins/magic/magic.css" />
<link rel="stylesheet" href="css/font-awesome.css" />
<link rel="stylesheet" href="../assets/plugins/font-awesome-4.6.3/css/font-awesome.min.css" />
<!-- END PAGE LEVEL STYLES -->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="nobg loginPage">
<div class="topNav">
  <div class="userNav">
    <ul>
      <li><a href="../index.php" title=""><i class="icon-reply"></i><span>Main website</span></a></li>
      <li><a href="../rider" title=""><i class="icon-user"></i><span><?=$langage_lbl_admin['LBL_RIDER']?> Login</span></a></li>
      <li><a href="../driver" title=""><i class="icon-comments"></i><span><?=$langage_lbl_admin['LBL_DRIVER']?> Login</span></a></li>
    </ul>
  </div>
</div>
<!-- PAGE CONTENT -->
<div class="container animated fadeInDown">
  <div class="text-center"> <img src="../assets/img/logo.png" id="Admin" alt=" Admin" /> </div>
  <!--div class="sign-in-heading" >
				<h3>PROJECT NAME</h3>
			</div-->
  <div class="tab-content ">
    <div id="login" class="tab-pane active">
      <form action="" class="form-signin" method = "post" id="login_box" onSubmit="return chkValid();">
        <p style="display:none;" class="btn-block btn btn-rect btn-success" id="success" ></p>
        <p style="display:none;" class="btn-block btn btn-rect btn-danger text-muted text-center" id="errmsg"></p>
        <br>
        <p class="head_login_005">Administrator Login</p>
        <!-- <span class="glyphicon glyphicon-envelope form-control-feedback"></span> -->
        <input type="text" placeholder="Email Address" class="form-control" name="vEmail" id="vEmail" required <?if(SITE_TYPE=='Demo'){ echo "Value='demo@demo.com'";} ?>/>
        <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
        <input type="password" placeholder="Password" class="form-control" name="vPassword" id="vPassword" required <?if(SITE_TYPE=='Demo'){ echo "Value='123456'";} ?>/>
        <br>
        <input type="submit" class="btn text-muted text-center btn-default" value="SIGN IN"/>
        <br>
      </form>
      <? if(SITE_TYPE=='Demo'){?>
      <div class="footer-text-center">
        <h3 style="padding-top:15px;"> Use below Detail for Demo Version</h3>
        <h5>
          <p>User Name: demo@demo.com</p>
          <p> Password: 123456 </p>
        </h5>
      </div>
      <? } ?>
      <!--
						<ul class="list-inline">
						
						<li><a class="text-muted" href="#forgot" data-toggle="tab" onClick="change_heading('Forgot Password','login','forgot')">Forgot Password</a></li>
						
					</ul>-->
    </div>
    <div id="forgot" class="tab-pane">
      <form  class="form-signin" method="post" id="frmforget">
        <!--<p class="text-muted text-center btn-block btn btn-primary btn-rect">Enter your valid e-mail</p>
						<input type="hidden" name="action" value="<?=$action?>">-->
        <input type="email"  required="required" placeholder="Your E-mail"  class="form-control" id="femail"/>
        <br />
        <button class="btn text-muted text-center btn-success" type="submit" onClick="forgotPass();">Recover Password</button>
      </form>
    </div>
  </div>
</div>
<!--END PAGE CONTENT -->
<!-- PAGE LEVEL SCRIPTS -->
<script src="../assets/plugins/jquery-2.0.3.min.js"></script>
<script src="../assets/plugins/bootstrap/js/bootstrap.js"></script>
<script src="../assets/js/login.js"></script>
<script>
				$('input').keyup(function(){
					$this = $(this);
					if($this.val().length == 1)
					{
						var x =  new RegExp("[\x00-\x80]+"); // is ascii
						
						//alert(x.test($this.val()));
						
						var isAscii = x.test($this.val());
						if(isAscii)
						{
							$this.attr("dir", "ltr");
						}
						else
						{
							$this.attr("dir", "rtl");
						}
					}
					
				});
				function change_heading(heading, addClass, removeClass)
				{
					document.getElementById("login").innerHTML= heading;
					document.getElementById(addClass).className = "tab-pane";
					document.getElementById(removeClass).className = "tab-pane active";
				}
				function chkValid()
				{
					var id = document.getElementById("vEmail").value;
					var pass = document.getElementById("vPassword").value;
					if(id == '' || pass == '')
					{
						document.getElementById("errmsg").style.display = '';
						setTimeout(function() {document.getElementById('errmsg').style.display='none';},2000);
					}
					else
					{
						var request = $.ajax({
							type: "POST",
							url: 'ajax_login_action.php',
							data: $("#login_box").serialize(),
							
							success: function(data)
							{// alert(data);
								
								if(data == 1){
									document.getElementById("errmsg").innerHTML = 'You are not active.Please contact administrator to activate your account.';
									document.getElementById("errmsg").style.display = '';
									return false;
								}
								else if(data == 2){
									document.getElementById("errmsg").style.display = 'none';
									window.location = "dashboard.php";
									
									return true; // success registration
								}
								else if(data == 3) {
									document.getElementById("errmsg").innerHTML = 'Invalid combination of username & Password';
									document.getElementById("errmsg").style.display = '';
									return false;
									
								}
								else {
									document.getElementById("errmsg").innerHTML = 'Invalid Email or Password';
									document.getElementById("errmsg").style.display = '';
									//setTimeout(function() {document.getElementById('errmsg1').style.display='none';},2000);
									return false;
								}
								
								/* if(data == 1){
									//showNotification({type : 'error', message: '{/literal}{$smarty.const.LBL_ACC_NOT_ACTIVE}.{literal}'});
									//alert("Not Registered");
									document.getElementById("errmsg1").style.display = '';
									//setTimeout(function() {document.getElementById('errmsg1').style.display='none';},2000);
									document.getElementById("vPassword").value = '';
									//window.location = 'login.php';
									}
									
									else if(data == 3)
									{
									//alert("Invalid Email Id and Password");
									document.getElementById("errmsg2").style.display = '';
									// setTimeout(function() {document.getElementById('errmsg2').style.display='none';},2000);
									document.getElementById("vEmail").value = '';
									document.getElementById("vPassword").value = '';
									//window.location = 'login.php';
									}
									else if(data == 4)
									{
									//alert("Invalid Email Id and Password");
									document.getElementById("errmsg3").style.display = '';
									//setTimeout(function() {document.getElementById('errmsg3').style.display='none';},2000);
									document.getElementById("vEmail").value = '';
									document.getElementById("vPassword").value = '';
									//window.location = 'login.php';
									}
									else{
									window.location = 'dashboard.php';
								} */
							}
						});
						
						request.fail(function(jqXHR, textStatus) {
							alert( "Request failed: " + textStatus );
						});
						
					}
					return false;
				}
				function forgotPass()
				{
					var id = document.getElementById("femail").value;
					if(id == '')
					{
						
						document.getElementById("errmsg_email").style.display = '';
						document.getElementById("errmsg_email").innerHTML = 'Please enter Email Address';
						return false;
					}
					else {
						
						var request = $.ajax({
							type: "POST",
							url: 'ajax_fpass_action.php',
							data: $("#frmforget").serialize(),
							beforeSend:function()
							{
								alert(data);
							},
							success: function(data)
							{
								if(data == 1)
								{
									document.getElementById("page_title").innerHTML= "Login";
									document.getElementById("forgot").className = "tab-pane";
									document.getElementById("login").className = "tab-pane active";
									document.getElementById("success").innerHTML = 'Your Password has been sent Successfully.';
									document.getElementById("success").style.display = '';
									return false;
								}
								else if(data == 0)
								{
									document.getElementById("errmsg_email").innerHTML = 'Error in Sending Password.';
									document.getElementById("errmsg_email").style.display = '';
									return false;
									
								}
								else if(data == 3)
								{
									document.getElementById("errmsg_email").innerHTML = 'Sorry ! The Email address you have entered is not found.';
									document.getElementById("errmsg_email").style.display = '';
									return false;
								}
								return false;
							}
						});
						request.fail(function(jqXHR, textStatus) {
							alert( "Request failed: " + textStatus );
							return false;
						});
						
						return false;
					}
					return false;
				}
				
			</script>
<!--END PAGE LEVEL SCRIPTS -->
</body>
<!-- END BODY -->
</html>