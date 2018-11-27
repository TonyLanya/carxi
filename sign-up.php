<?php 

    include_once("common.php");

	$generalobj->go_to_home();

    $script="Driver Sign-Up";

    $sql="select * from  currency where eStatus='Active'";

    $db_currency=$obj->MySQLSelect($sql);

    $sql = "SELECT * from country where eStatus = 'Active'" ;

    $db_code = $obj->MySQLSelect($sql);

	$meta_arr = $generalobj->getsettingSeo(5);

    $Mobile=$MOBILE_VERIFICATION_ENABLE;

	$error = isset($_REQUEST['error']) ? $_REQUEST['error'] : '';

	$var_msg = isset($_REQUEST['var_msg']) ? $_REQUEST['var_msg'] : '';

	

	if(isset($_SESSION['postDetail'])) {

		$_REQUEST = $_SESSION['postDetail'];

		$user_type = isset($_REQUEST['user_type']) ? $_REQUEST['user_type'] : 'driver';

		$vEmail = isset($_REQUEST['vEmail']) ? $_REQUEST['vEmail'] : '';

		$vCountry = isset($_REQUEST['vCountry']) ? $_REQUEST['vCountry'] : '';

		$vCode = isset($_REQUEST['vCode']) ? $_REQUEST['vCode'] : '';

		$vPhone = isset($_REQUEST['vPhone']) ? $_REQUEST['vPhone'] : '';

		$vRefCode = isset($_REQUEST['vRefCode']) ? $_REQUEST['vRefCode'] : '';

		$vFirstName = isset($_REQUEST['vFirstName']) ? $_REQUEST['vFirstName'] : '';

		$vLastName = isset($_REQUEST['vLastName']) ? $_REQUEST['vLastName'] : '';

		$vCompany = isset($_REQUEST['vCompany']) ? $_REQUEST['vCompany'] : '';

		$vCaddress = isset($_REQUEST['vCaddress']) ? $_REQUEST['vCaddress'] : '';

		$vCadress2 = isset($_REQUEST['vCadress2']) ? $_REQUEST['vCadress2'] : '';

		$vCity = isset($_REQUEST['vCity']) ? $_REQUEST['vCity'] : '';

		$vZip = isset($_REQUEST['vZip']) ? $_REQUEST['vZip'] : '';

		$vCurrencyDriver = isset($_REQUEST['vCurrencyDriver']) ? $_REQUEST['vCurrencyDriver'] : '';

		$vDay = isset($_REQUEST['vDay']) ? $_REQUEST['vDay'] : '';

		$vMonth = isset($_REQUEST['vMonth']) ? $_REQUEST['vMonth'] : '';

		$vYear = isset($_REQUEST['vYear']) ? $_REQUEST['vYear'] : '';

		

		unset($_SESSION['postDetail']);

	}

?>

<!DOCTYPE html>

<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width,initial-scale=1">

   <!-- <title><?=$COMPANY_NAME?>| Signup</title>-->

	<title><?php echo $meta_arr['meta_title'];?></title>

	<meta name="keywords" value="<?=$meta_arr['meta_keyword'];?>"/>

	<meta name="description" value="<?=$meta_arr['meta_desc'];?>"/>

    <!-- Default Top Script and css -->

    <?php include_once("top/top_script.php");?>

    <link href="assets/css/checkbox.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/radio.css" rel="stylesheet" type="text/css" />

    <?php include_once("top/validation.php");?>

    <!-- End: Default Top Script and css-->

   

</head>

<body>

<!-- home page -->

    <div id="main-uber-page">

<!-- Left Menu -->

    <?php include_once("top/left_menu.php");?>

    <!-- End: Left Menu-->

        <!-- Top Menu -->

        <?php include_once("top/header_topbar.php");?>

        <!-- End: Top Menu-->

    <!-- contact page-->

    <div class="page-contant">

        <div class="page-contant-inner">

            <h2 class="header-page trip-detail"><?=$langage_lbl['LBL_SIGNUP_SIGNUP']; ?>

                <p><?=$langage_lbl['LBL_SIGN_UP_TELL_US_A_BIT_ABOUT_YOURSELF']; ?></p>

            </h2>

            <!-- trips detail page -->

            <form name="frmsignup" id="frmsignup" method="post" action="signup_a.php">

                <div class="driver-signup-page">

                <?php

                if ($error != "") {

                ?>

                    <div class="row">

                        <div class="col-sm-12 alert alert-danger">

                             <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>

                    <?=$var_msg; ?>

                        </div>

                    </div>

                <?php 

                    }

                ?>

                    <?php /*<h3><?=$langage_lbl['LBL_Contact_Info']; ?></h3> */ ?>

                    <p><?=$langage_lbl['LBL_IF_YOU_ARE_AN_INDIVIDUAL']; ?></p>

                    <p><?=$langage_lbl['LBL_IF_YOU_ARE_A_COMPANY']; ?></p>

                    <div class="individual-driver">

                        <h4><?=$langage_lbl['LBL_ARE_YOU_AN_INDIVIDUAL']; ?></h4>

                        <span>

                            <em><?=$langage_lbl['LBL_Member_Type:']; ?> </em>

                            <div class="radio-but"> 

                            <b>

                                <input id="r1" name="user_type" type="radio" value="driver" <?php if($user_type == 'driver') { echo 'checked'; } ?> onChange="show_company(this.value);" checked="checked">

                                <label for="r1"><?=$langage_lbl['LBL_SIGNUP_INDIVIDUAL_DRIVER']; ?></label>

                            </b> 

                            <b>

                                <input id="r2" name="user_type" type="radio" value="company" <?php if($user_type == 'company') { echo 'checked'; } ?> onChange="show_company(this.value);" class="">

                                <label for="r2"><?=$langage_lbl['LBL_Company']; ?></label>

                            </b> 

                            </div>

                        </span> 

                        

                    </div>

                    <div class="create-account">

                        <h3><?=$langage_lbl['LBL_SIGN_UP_CREATE_ACCOUNT']; ?></h3>

                        <span class="newrow">

                            <strong id="emailCheck"><label><?=$langage_lbl['LBL_EMAIL_TEXT_SIGNUP']; ?></label>

                            <input type="text" placeholder="<?=$langage_lbl['LBL_EMAIL_name@email.com']; ?>" name="vEmail" class="create-account-input " id="vEmail_verify" value="<?php echo $vEmail; ?>" /></strong>

                            <strong><label><?=$langage_lbl['LBL_PASSWORD_SIGNUP']; ?></label>

                            <input id="pass" type="password" name="vPassword" placeholder="<?=$langage_lbl['LBL_PASSWORD_SIGNUP']; ?>" class="create-account-input create-account-input1 " value="" /></strong>

                        </span> 

                        <span>

                            <?php /*<input type="hidden" name="mobile_verification"  id="mobile_verification" value="<?=$Mobile;?>"> */ ?>

                            <input type="hidden" placeholder="" name="iRefUserId" id="iRefUserId"  class="create-account-input" value="" />

                            <input type="hidden" placeholder="" name="eRefType" id="eRefType" class="create-account-input" value=""  />

                        </span> 



                        <span class="c_country newrow">

                            <strong>

                                <select name="vCountry" class="custom-select-new" onChange="changeCode(this.value); ">

                                    

                                    <?php for($i=0;$i<count($db_code);$i++) { ?>

                                    <option value="<?=$db_code[$i]['vCountryCode']?>" <?php if($db_code[$i]['vCountryCode']== $DEFAULT_COUNTRY_CODE_WEB){echo 'selected';}?>><?=$db_code[$i]['vCountry']?></option>

                                    <?php } ?>

                                </select>

                            </strong>

                        </span> 

                         

                         <span class="c_code_ph_no newrow">

                            <strong class="c_code"><input type="text"  name="vCode" readonly  class="create-account-input " value="<?php echo $vCode; ?>" id="code" /></strong>

                            <strong class="ph_no newrow" id="mobileCheck"><input type="text"  id="vPhone" value="<?php echo $vPhone; ?>" placeholder="<?=$langage_lbl['LBL_SIGNUP_777-777-7777']; ?>" class="create-account-input create-account-input1 vPhone_verify" name="vPhone"/></strong>

                        </span>

                         <?php 



                        if($REFERRAL_SCHEME_ENABLE == 'Yes'){ ?>

                               <span class="newrow"><strong id="refercodeCheck"><input id="vRefCode" type="text" name="vRefCode" placeholder="<?=$langage_lbl['LBL_SIGNUP_REFERAL_CODE']; ?>" class="create-account-input create-account-input1 vRefCode_verify" value="<?php echo $vRefCode; ?>" onBlur=" validate_refercode(this.value)"/></strong></span> 

                        <?php }

                        ?>





                    </div>

                    <div class="create-account">

                        <h3 class="company" style="display: none;"><?=$langage_lbl['LBL_Company_Information']; ?></h3>

                        <h3 class="driver"><?=$langage_lbl['LBL_Driver_Information']; ?></h3>

                        <span class="driver newrow">

                            <strong><label><?=$langage_lbl['LBL_SIGN_UP_FIRST_NAME_HEADER_TXT']; ?></label>

                            <input name="vFirstName" type="text" class="create-account-input" placeholder="<?=$langage_lbl['LBL_SIGN_UP_FIRST_NAME_HEADER_TXT']; ?>" id="vFirstName"value="<?php echo $vFirstName; ?>" /></strong>

                            <strong><label><?=$langage_lbl['LBL_SIGN_UP_LAST_NAME_HEADER_TXT']; ?></label>

                            <input name="vLastName" type="text" class="create-account-input create-account-input1" placeholder="<?=$langage_lbl['LBL_SIGN_UP_LAST_NAME_HEADER_TXT']; ?>" id="vLastName" value="<?php echo $vLastName; ?>" /></strong>

                        </span> 

                        <span class="company newrow" style="display: none;">

                            <strong><label><?=$langage_lbl['LBL_COMPANY_SIGNUP']; ?></label>

                            <input type="text" id="company_name" placeholder="<?=$langage_lbl['LBL_COMPANY_SIGNUP']; ?>" class="create-account-input" name="vCompany" value="<?php echo $vCompany; ?>"  /></strong>

                        </span>

                        <span class="newrow">

                            <strong><label><?=$langage_lbl['LBL_ADDRESS_SIGNUP']; ?></label>

                            <input name="vCaddress" type="text" class="create-account-input" placeholder="<?=$langage_lbl['LBL_ADDRESS_SIGNUP']; ?>" value="<?php echo $vCaddress; ?>" /></strong>

                            <strong><label><?=$langage_lbl['LBL_ADDRESS2_SIGNUP']; ?></label>

                            <input name="vCadress2" type="text" class="create-account-input create-account-input1" placeholder="<?=$langage_lbl['LBL_ADDRESS2_SIGNUP']; ?>" value="<?php echo $vCaddress2; ?>" />

                            </strong>

                        </span> 

                        <span class="newrow">

                            <strong><label><?=$langage_lbl['LBL_CITY_SIGNUP']; ?></label>

                            <input name="vCity" type="text" class="create-account-input" placeholder="<?=$langage_lbl['LBL_CITY_SIGNUP']; ?>" value="<?php echo $vCity; ?>" /></strong>

                            <strong><label><?=$langage_lbl['LBL_ZIP_CODE_SIGNUP']; ?></label>

                            <input name="vZip" type="text" class="create-account-input create-account-input1" placeholder="<?=$langage_lbl['LBL_ZIP_CODE_SIGNUP']; ?>" value="<?php echo $vZip; ?>" /></strong>

                        </span>

                        <span class="newrow">

                            <strong>

                            <label><?=$langage_lbl['LBL_SELECT_CURRENCY_SIGNUP']; ?></label>

                                <select class="custom-select-new" name = 'vCurrencyDriver'>

                                    <?php for($i=0;$i<count($db_currency);$i++){ ?>

                                    <option value = "<?= $db_currency[$i]['vName'] ?>" <?php if($db_currency[$i]['eDefault']=="Yes"){?>selected<?php } ?>>

                                    <?= $db_currency[$i]['vName'] ?>

                                    </option>

                                    <?php } ?>

                                </select>

                            </strong>

                            <b id="li_dob">

                                <strong>

								<?=$langage_lbl['LBL_Date_of_Birth']; ?></strong>

                                <select name="vDay" data="DD" class="custom-select-new">

                                    <option><?=$langage_lbl['LBL_DATE_SIGNUP']; ?></option>

                                    <?php for($i=1;$i<=31;$i++) {?>

                                    <option value="<?=$i?>">

                                    <?=$i?>

                                    </option>

                                    <?php }?>

                                </select>

                                <select data="MM" name="vMonth" class="custom-select-new">

                                    <option><?=$langage_lbl['LBL_MONTH_SIGNUP']; ?></option>

                                    <?php for($i=1;$i<=12;$i++) {?>

                                    <option value="<?=$i?>">

                                    <?=$i?>

                                    </option>

                                    <?php }?>

                                </select>

                                <select data="YYYY" name="vYear" class="custom-select-new">

                                    <option><?=$langage_lbl['LBL_YEAR_SIGNUP']; ?></option>

                                    <?php for($i=1950;$i<=date("Y");$i++) {?>

                                    <option value="<?=$i?>">

                                    <?=$i?>

                                    </option>

                                    <?php }?>

                                </select>

                            </b>

                        </span> 

                        <span class="newrow">

                            <strong class="captcha-signup"><label><?=$langage_lbl['LBL_CAPTCHA_SIGNUP']; ?></label>

                            <input id="POST_CAPTCHA" class="create-account-input" size="5" maxlength="5" name="POST_CAPTCHA" type="text">

							 <em class="captcha-dd"><img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' alt="" class="chapcha-img" />&nbsp;<?=$langage_lbl['LBL_CAPTCHA_CANT_READ_SIGNUP']; ?> <a href='javascript: refreshCaptcha();'><?=$langage_lbl['LBL_CLICKHERE_SIGNUP']; ?></a></em>

                             </strong>

							 

                            

                        </span>

						<span class="newrow">

						<strong class="captcha-signup1">

							<abbr><?=$langage_lbl['LBL_SIGNUP_Agree_to']; ?> <a href="terms_condition.php" target="_blank"><?=$langage_lbl['LBL_SIGN_UP_TERMS_AND_CONDITION']; ?></a>

                                <div class="checkbox-n">

                                    <input id="c1" name="remember-me" type="checkbox" class="required" value="remember">

                                    <label for="c1"></label>

                                </div>

                            </abbr>

                            </strong>

						 </span>

                   

                    <p><button type="submit" class="submit" name="SUBMIT"><?=$langage_lbl['LBL_BTN_SIGN_UP_SUBMIT_TXT']; ?></button></p>

                    </div>

                </div>

            </form>

            <div class="col-lg-12">

                <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                <h4 class="modal-title" id="H2"><?=$langage_lbl['LBL_SIGNUP_PHONE_VERI']; ?></h4>

                            </div>

                            <div class="modal-body">

                                <form role="form" name="verification" id="verification">

                                    <p class="help-block"><?=$langage_lbl['LBL_SIGNUP_PHONE_VERI_TEXT']; ?></p>

                                    <div class="form-group">

                                        <label><?=$langage_lbl['LBL_SIGNUP_ENTER_CODE']; ?></label>

                                        <input class="form-control" type="text" id="vCode1"/>

                                    </div>

                                    <p class="help-block" id="verification_error"></p>

                                </form>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary" onClick="check_verification('verify')"><?=$langage_lbl['LBL_SIGNUP_VERIFY']; ?></button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- -->

        </div>

    </div>

    <!-- footer part -->

    <?php include_once('footer/footer_home.php');?>

    <!-- footer part end -->

    <!-- -->

    <div style="clear:both;"></div>

    </div>

    <!-- home page end-->

    <!-- Footer Script -->

    <?php include_once('top/footer_script.php');?>

	<script type="text/javascript" src="assets/js/validation/jquery.validate.min.js" ></script>

	<script type="text/javascript" src="assets/js/validation/additional-methods.js" ></script>

    <script type="text/javascript">

	

	$('#frmsignup').validate({

		ignore: 'input[type=hidden]',

		errorClass: 'help-block',

		errorElement: 'span',

		errorPlacement: function (error, e) {

			e.parents('.newrow > strong').append(error);

		},

		highlight: function (e) {

			$(e).closest('.newrow').removeClass('has-success has-error').addClass('has-error');

			$(e).closest('.newrow strong input').addClass('has-shadow-error');

			$(e).closest('.help-block').remove();

		},

		success: function (e) {

			e.prev('input').removeClass('has-shadow-error');

			e.closest('.newrow').removeClass('has-success has-error');

			e.closest('.help-block').remove();

			e.closest('.help-inline').remove();

		},

		rules: {

			vEmail: {required: true, email: true,

					remote: {

							url: 'ajax_validate_email_new.php',

							type: "post",

							data: {iDriverId: ''},

						}

			},

			vPassword: {required: true, minlength: 6},

			vPhone: {required: true, phonevalidate: true,

						remote: {

							url: 'ajax_driver_mobile_new.php',

							type: "post",

							data: {iDriverId: ''},

						}

			},

			vCompany: {required: function(e){

                            return $('input[name=user_type]:checked').val() == 'company';

                        }, minlength: function(e){

							if($('input[name=user_type]:checked').val() == 'company') { return 2; } else {return false;}

                        }},

			vFirstName: {required: function(e){

							return $('input[name=user_type]:checked').val() == 'driver';

						}, minlength: function(e){

							if($('input[name=user_type]:checked').val() == 'driver') { return 2; } else {return false;}

                        }},

			vLastName: {required: function(e){

							return $('input[name=user_type]:checked').val() == 'driver';

						}, minlength: function(e){

							if($('input[name=user_type]:checked').val() == 'driver') { return 2; } else {return false;}

                        }},

			vCaddress: {required: true, minlength: 2},

			vCity: {required: true},

			vZip: {required: true},

			POST_CAPTCHA: {required: true, remote: {

							url: 'ajax_captcha_new.php',

							type: "post",

							data: {iDriverId: ''},

						}},

			'remember-me': {required: true},

		},

		messages: {

			vEmail: {remote: 'Email address is already exists.'},

			'remember-me': {required: 'Please agree to the Terms & Conditions.'},

			vPhone: {remote: 'Phone Number is already exists.'},

			POST_CAPTCHA: {remote: 'Captcha did not match.'}

		}

	});

	

	

		$('#verification').bind('keydown',function(e){

            if(e.which == 13){

                check_verification('verify'); return false;

            }

        });

        

        

        function changeCode(id)

        {



            var request = $.ajax({

                 type: "POST",

                 url: 'change_code.php',

                 data: 'id=' + id,

                 success: function (data)

                 {

                      document.getElementById("code").value = data;

                      //window.location = 'profile.php';

                 }

            });

        }

		  /*ajax for unique username*/

        



        $(document).ready(function(){



            $("#company").hide();

            $("#radio_1").prop("checked", true)

            $( "#company_name" ).removeClass( "required" );

             show_company('driver');

			 

			var newUser = $("input[name=user_type]:checked").val();

			if(newUser=='company')

			{

				$(".company").show();

				$(".driver").hide();

				$("#li_dob").hide();

				$("#vRefCode").hide();

				$('#div-phone').show();

			}

			else if(user=='driver')

			{

				$(".company").hide();

				$(".driver").show();

				$("#li_dob").show();

				$("#vRefCode").show();

				$('#div-phone').hide();

			}



        });

		

		function validate_refercode(id){        

    

            if(id == ""){

                return true;

            }else{

            

        

                var request = $.ajax({

                    type: "POST",

                    url: 'ajax_validate_refercode.php',

                    data: 'refcode=' +id,

                    success: function (data)

                    {

                        if(data == 0){

						$("#referCheck").remove();

                        $(".vRefCode_verify").addClass('required-active');

						$('#refercodeCheck').append('<div class="required-label" id="referCheck" >*Refer code Not Found.</div>');

                        $('#vRefCode').attr("placeholder", "Referal Code (Optional)");

                        $('#vRefCode').val("");

                        return false;

                        }else{

                            var reponse = data.split('|');              

                            $('#iRefUserId').val(reponse[0]);

                            $('#eRefType').val(reponse[1]);

                        }

                    }

                });

            

            }   

            

        }



        function show_company(user)

        {

            if(user=='company')

            {

                $(".company").show();

                $(".driver").hide();

                $("#li_dob").hide();

                $("#vRefCode").hide();

                $('#div-phone').show();

            }

            else if(user=='driver')

            {

                $(".company").hide();

                $(".driver").show();

                $("#li_dob").show();

                $("#vRefCode").show();

                $('#div-phone').hide();

            }

        }

		

		

		

		function refreshCaptcha()

		{

			var img = document.images['captchaimg'];

			img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;

		}

    </script>

    <!-- End: Footer Script -->

</body>

</html>

