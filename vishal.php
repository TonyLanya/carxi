<?php
	include_once('common.php');
	include_once($tconfig["tsite_libraries_v"]."/Imagecrop.class.php");
	$thumb = new thumbnail();
	$temp_gallery = $tconfig["tsite_temp_gallery"];
	
	include_once($tconfig["tsite_libraries_v"]."/SimpleImage.class.php");
	$img = new SimpleImage();           
	echo $baseurl = "http://graph.facebook.com/636010959884844/picture?type=large";
	echo "<br/>";
	echo $url = "636010959884844.jpg";
	echo "<br/>";
	echo $Photo_Gallery_folder = $tconfig["tsite_upload_images_passenger_path"]."/61/";
	echo "<br/>";
	
	// mkdir($Photo_Gallery_folder, 0777);
	echo $image_name =  system("wget --no-check-certificate -O ".$Photo_Gallery_folder.$url." ".$baseurl);
	//save_image($baseurl,$Photo_Gallery_folder.$url);
	
	if(is_file($Photo_Gallery_folder.$url))
          {
             //include_once($tconfig["tsite_libraries_v"]."/SimpleImage.class.php");
               //	 $img = new SimpleImage();
                  //var_dump($img);
             list($width, $height, $type, $attr)= getimagesize($Photo_Gallery_folder.$url);           
    
             if($width < $height){
                $final_width = $width;
             }else{
                $final_width = $height;
             }       
             /*$img->load($Photo_Gallery_folder.$url)->crop(0, 0, $final_width, $final_width)->save($Photo_Gallery_folder.$url);
             $vFile = $generalobj->fileupload($Photo_Gallery_folder,$image_object,$image_name,$prefix='', $vaildExt="pdf,doc,docx,jpg,jpeg,gif,png");  */
             $img->load($Photo_Gallery_folder.$url)->crop(0, 0, $final_width, $final_width)->save($Photo_Gallery_folder.$url);
            echo $imgname = $generalobj->img_data_upload($Photo_Gallery_folder,$url,$Photo_Gallery_folder, $tconfig["tsite_upload_images_member_size1"], $tconfig["tsite_upload_images_member_size2"], $tconfig["tsite_upload_images_member_size3"],""); 
            var_dump($imgname);
            // $imgname = $generalobj->general_upload_image($image_object, $image_name, $Photo_Gallery_folder, $tconfig["tsite_upload_images_member_size1"], $tconfig["tsite_upload_images_member_size2"], $tconfig["tsite_upload_images_member_size3"], '', '', '', 'Y', '', $Photo_Gallery_folder);
                                 
          }  
?>