<?php

class S3Uploads {

    public static function uploadNewWatermarkToS3($sourceFolderPath,$destinationFolderPathForWM) 
    {
        Yii::log("Paths >>>>>".$sourceFolderPath." >>> ".$destinationFolderPathForWM);
        $mime=mime_content_type ( $sourceFolderPath );

        /*if($mime=='image/jpeg' || $mime=='image/tiff'){
			$exif = exif_read_data($sourceFolderPath);
			if (array_key_exists("Orientation",$exif)){
			$ort = $exif['Orientation'];
			Yii::log("ORIENTATION from exif of ".$sourceFolderPath." is ".$ort);	
			}
			else{
				$ort=1;
				Yii::log("No orientation!!!! Defaulting orientation to 1");
			}
		}
		else{
			Yii::log("File other than JPEG/TIFF. No meta-data!!! Defaulting orientation to 1");
			$ort=1;
		}*/
        if($mime=='image/jpeg' || $mime=='image/tiff'){
            try{
                $exif = @exif_read_data($sourceFolderPath);
                if (array_key_exists("Orientation",$exif)){
                    $ort = $exif['Orientation'];
                    //echo "ORIENTATION from exif of ".$sourceFolderPath." is ".$ort;
                    Yii::log("ORIENTATION from exif of ".$sourceFolderPath." is ".$ort);	
                }
                else{
                    //echo "No orientation!!!! Defaulting orientation to 1";
                    $ort=1;
                    Yii::log("No orientation!!!! Defaulting orientation to 1");
                }
            }
            catch(Exception $exp){
                $ort=1;
                //echo "Error in EXIF_READ_DATA!!!! Defaulting orientation to 1";
                Yii::log("Error in EXIF_READ_DATA!!!! Defaulting orientation to 1");
            }
        }
        else{
            Yii::log("File other than JPEG/TIFF. No meta-data!!! Defaulting orientation to 1");
            $ort=1;
        }


        $realignedImage=null;
        $temp=null;
        //Create image instance from roadies image
        if($mime=='image/jpeg'){
            $temp = imagecreatefromjpeg($sourceFolderPath);
        }
        elseif($mime=='image/png'){
            $temp = imagecreatefrompng($sourceFolderPath);
        }
        elseif($mime=='image/gif'){
            $temp = imagecreatefromgif($sourceFolderPath);

        }
        else{
            Yii::log("Invalid image format...discontinuing the s3 upload and Watermarking");
            return false;
        }

        switch($ort)
        {
            case 1: // nothing
                $realignedImage=$temp;
                break;

            case 2: // horizontal flip
                if(imageflip($temp,IMG_FLIP_HORIZONTAL))
                {
                    $realignedImage=$temp;
                    Yii::log("Horizontally flipped the image");
                }
                else{
                    Yii::log("Unable to horizontally flip the image");
                    $realignedImage=$temp;
                }
                break;

            case 3: // 180 rotate right
                $realignedImage=imagerotate($temp,-180,0);
                break;

            case 4: // vertical flip
                if(imageflip($temp,IMG_FLIP_VERTICAL))
                {
                    $realignedImage=$temp;
                    Yii::log("Vertically flipped the image");
                }
                else{
                    Yii::log("Unable to vertically flip the image");
                    $realignedImage=$temp;
                }
                break;

            case 5: // vertical flip + 90 rotate right
                if(imageflip($temp,IMG_FLIP_VERTICAL))
                {
                    $realignedImage=imagerotate($temp,-90,0);
                    Yii::log("Vertically flipped + 90 rotate right the image");
                }
                else{
                    Yii::log("Unable to vertically flip the image");
                    $realignedImage=$temp;
                }
                break;

            case 6: // 90 rotate right

                $realignedImage=imagerotate($temp,-90,0);
                break;

            case 7: // horizontal flip + 90 rotate right
                if(imageflip($temp,IMG_FLIP_HORIZONTAL))
                {
                    $realignedImage=imagerotate($temp,-90,0);
                    Yii::log("Horizontally flipped + 90 rotate right the image");
                }
                else{
                    Yii::log("Unable to horizontally flip the image");
                    $realignedImage=$temp;
                }
                break;

            case 8:    // 270 rotate right
                $realignedImage=imagerotate($temp,-270,0);
                break;
            default :
                Yii::log("Unknown orientation.Uploading the image as is.");
                $realignedImage=$temp;
        }
        $mime=mime_content_type ( $sourceFolderPath );
        //Create image instance from roadies image
        if($mime=='image/jpeg'){
            imagejpeg($realignedImage,$sourceFolderPath);
            $im = imagecreatefromjpeg($sourceFolderPath);
        }
        elseif($mime=='image/png'){
            imagepng($realignedImage,$sourceFolderPath);
            $im = imagecreatefrompng($sourceFolderPath);
        }
        elseif($mime=='image/gif'){
            imagegif($realignedImage,$sourceFolderPath);
            $im = imagecreatefromgif($sourceFolderPath);

        }
        else{
            Yii::log("Invalid image format...discontinuing the S3 upload and Watermarking");
            return false;
        }
        if(!$im){
            Yii::log("src creation failed");


        }
        //$isOriginalUploadSuccessful=false;
        $isWMUploadSuccessful=false;
        //instantiate the class
        $s3 = new S3(S3_ACCESS_KEY,S3_SCRET_KEY);

        /*if ($s3->putObjectFile($sourceFolderPath,S3_FIRST_BUCKET,$destinationFolderPathForOriginals, S3::ACL_PUBLIC_READ)) 
        {
            $isOriginalUploadSuccessful=true;
        }
        else
        {//donot do watermarking if the original image upload fails
            $isOriginalUploadSuccessful=false;
			Yii::log("Original Image Upload Failure");
			return false;

        }*/


        //Create image instance for watermark image
        //$watermark = imagecreatefrompng(Yii::getPathOfAlias('webroot')."/customer-assets/images/medinfilogo.png");

        $watermark = imagecreatefrompng(ROOT_PATH."/customer-assets/images/medti.png");

        if(!$watermark){
            Yii::log("watermark creation failed");
        }

        $originalWMWidth=imagesx($watermark);
        $originalWMHeight=imagesy($watermark);
        $aspectRatioWM=$originalWMWidth/$originalWMHeight;

        $sourceImageWidth=imagesx($im);
        $sourceImageHeight=imagesy($im);

        //54 is the width of the original watermark image (/customer-assets/images/medti.png)
        //349 is the width of the reference image as per the solution document(/customer-assets/images/D592503393_close-up.jpeg)
        $widthRatio=ORIGINAL_WATERMARK_WIDTH/REFERENCE_IMAGE_WIDTH;
        //41 is the height of the original watermark image (/customer-assets/images/medti.png)
        //349 is the height of the reference image as per the solution document(/customer-assets/images/D592503393_close-up.jpeg)
        $heightRatio=ORIGINAL_WATERMARK_HEIGHT/REFERENCE_IMAGE_HEIGHT;
        Yii::log("Ratio w/h ".$widthRatio."/".$heightRatio);

        //Parameters to store the re-scaled watermark height and width
        $newWMWidth=0;
        $newWMHeight=0;

        //Logic to calculate the re-scaled watermark height and width
        if($sourceImageHeight<$sourceImageWidth){
            $newWMHeight=$heightRatio*$sourceImageHeight;
            $newWMWidth=$newWMHeight*$aspectRatioWM;
        }
        else{
            $newWMWidth=$widthRatio*$sourceImageWidth;
            $newWMHeight=$newWMWidth/$aspectRatioWM;
        }

        $newWMHeight=intval($newWMHeight);
        $newWMWidth=intval($newWMWidth);
        Yii::log("New width and height ".$newWMHeight."/".$newWMWidth);

        //create a blank black image of size of watermark
        $newWMImage = imagecreatetruecolor($newWMWidth,$newWMHeight);
        //donot allow the underlying colors to mix with the transparent images
        imagealphablending($newWMImage, false);
        //allow alpha channel in the image which allows to have multi color transparency.
        imagesavealpha($newWMImage,true);
        //create a transparent color
        $transparent = imagecolorallocatealpha($newWMImage, 255, 255, 255, 127);
        //Create a transparent rectangle of the size of watermark and which will be transparent
        imagefilledrectangle($newWMImage, 0, 0, $newWMWidth, $newWMHeight, $transparent);
        //Rescale the original watermark as per the scaled width and height
        imagecopyresampled($newWMImage, $watermark, 0, 0, 0, 0, $newWMWidth, $newWMHeight,$originalWMWidth, $originalWMHeight);

        //Uncomment this line to view the resized watermark under the root project directory
        //imagepng($newWMImage, Yii::getPathOfAlias('webroot')."/newlogo.png");

        //10 is the fixed margin of watermark from the edge of image in the reference image
        //349 is the height of the reference image as per the solution document(/customer-assets/images/D592503393_close-up.jpeg)
        $marginRightRatio = REFERENCE_IMAGE_RIGHT_MARGIN/REFERENCE_IMAGE_WIDTH;
        $marginBottomRatio = REFERENCE_IMAGE_BOTTOM_MARGIN/REFERENCE_IMAGE_HEIGHT;
        $margin_right = intval($sourceImageWidth*$marginRightRatio);
        $margin_bottom = intval($sourceImageWidth*$marginBottomRatio);

        //Yii::log("Margin ratio r/b".$marginRightRatio."/".$marginBottomRatio);
        //Yii::log("Margins r/b".$margin_right."/".$margin_bottom);

        /*Old code for watermarking removed by Harsh on making the watermark dynamic
		$sx = imagesx($watermark);
		$sy = imagesy($watermark);


		imagecopy($im, $watermark, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));*/

        imagecopy($im, $newWMImage, $sourceImageWidth - $newWMWidth - $margin_right, $sourceImageHeight - $newWMHeight - $margin_bottom, 0, 0, $newWMWidth, $newWMHeight);

        //imagecopymerge($im, $newWMImage, $sourceImageWidth - $newWMWidth - $margin_right, $sourceImageHeight - $newWMHeight - $margin_bottom, 0, 0, $newWMWidth, $newWMHeight, 50);

        // Create a temp watermarked PNG image to preserve transparency
        $pos= strrpos ( $sourceFolderPath , ".");
        $sourceFolderPathPng=$sourceFolderPath.".png";
        Yii::log("PNG PATH>>>".$sourceFolderPathPng);
        imagepng($im, $sourceFolderPathPng);
        $temp=imagecreatefrompng($sourceFolderPathPng);

        //Convert temp file PNG to file to JPEG file to reduce the file size
        $sourceFolderPathF=$sourceFolderPath."F.jpeg";
        Yii::log("JPEG PATH>>>".$sourceFolderPathF);
        imagejpeg($temp,$sourceFolderPathF,WM_IMAGE_QUALITY);

        if ($s3->putObjectFile($sourceFolderPathF,S3_FIRST_BUCKET,$destinationFolderPathForWM, S3::ACL_PUBLIC_READ)) 
        {
            $isWMUploadSuccessful=true;
        }
        else
        {
            $isWMUploadSuccessful=false;
        }
        imagedestroy($im);
        imagedestroy($temp);
        //unlink($sourceFolderPathPng);
        //unlink($sourceFolderPathF);
        //Yii::log("upload results>>>".$isWMUploadSuccessful.">>>>>".$isOriginalUploadSuccessful);
        return ($isWMUploadSuccessful);

    }
    /*
	 *function olduploadToS3(...) was used before we started using thumbnails.
	*/
    /*public static function olduploadToS3($sourceFolderPath,$destinationFolderPathForOriginals,$destinationFolderPathForWM) 
    {
		Yii::log("Paths >>>>>".$sourceFolderPath.">>>".$destinationFolderPathForOriginals.">>>".$destinationFolderPathForWM);
		$mime=mime_content_type ( $sourceFolderPath );
        		if($mime=='image/jpeg' || $mime=='image/tiff'){
        			try{
        				$exif = @exif_read_data($sourceFolderPath);
        				if (array_key_exists("Orientation",$exif)){
        				$ort = $exif['Orientation'];
        				//echo "ORIENTATION from exif of ".$sourceFolderPath." is ".$ort;
        				Yii::log("ORIENTATION from exif of ".$sourceFolderPath." is ".$ort);
        				}
        				else{
        					//echo "No orientation!!!! Defaulting orientation to 1";
        					$ort=1;
        					Yii::log("No orientation!!!! Defaulting orientation to 1");
        				}
        			}
        			catch(Exception $exp){
        				$ort=1;
        				//echo "Error in EXIF_READ_DATA!!!! Defaulting orientation to 1";
        				Yii::log("Error in EXIF_READ_DATA!!!! Defaulting orientation to 1");
        			}
        		}
        		else{
        			Yii::log("File other than JPEG/TIFF. No meta-data!!! Defaulting orientation to 1");
        			$ort=1;
        		}


		$realignedImage=null;
		$temp=null;
		//Create image instance from roadies image
		if($mime=='image/jpeg'){
			$temp = imagecreatefromjpeg($sourceFolderPath);
		}
		elseif($mime=='image/png'){
			$temp = imagecreatefrompng($sourceFolderPath);
		}
		elseif($mime=='image/gif'){
			$temp = imagecreatefromgif($sourceFolderPath);

		}
		else{
			Yii::log("Invalid image format...discontinuing the s3 upload and Watermarking");
			return false;
		}

		switch($ort)
		{
			case 1: // nothing
				$realignedImage=$temp;
			break;

			case 2: // horizontal flip
				if(imageflip($temp,IMG_FLIP_HORIZONTAL))
				{
					$realignedImage=$temp;
					Yii::log("Horizontally flipped the image");
				}
				else{
					Yii::log("Unable to horizontally flip the image");
					$realignedImage=$temp;
				}
			break;

			case 3: // 180 rotate right
				$realignedImage=imagerotate($temp,-180,0);
			break;

			case 4: // vertical flip
				if(imageflip($temp,IMG_FLIP_VERTICAL))
				{
					$realignedImage=$temp;
					Yii::log("Vertically flipped the image");
				}
				else{
					Yii::log("Unable to vertically flip the image");
					$realignedImage=$temp;
				}
			break;

			case 5: // vertical flip + 90 rotate right
				if(imageflip($temp,IMG_FLIP_VERTICAL))
				{
					$realignedImage=imagerotate($temp,-90,0);
					Yii::log("Vertically flipped + 90 rotate right the image");
				}
				else{
					Yii::log("Unable to vertically flip the image");
					$realignedImage=$temp;
				}
			break;

			case 6: // 90 rotate right

				$realignedImage=imagerotate($temp,-90,0);
			break;

			case 7: // horizontal flip + 90 rotate right
				if(imageflip($temp,IMG_FLIP_HORIZONTAL))
				{
					$realignedImage=imagerotate($temp,-90,0);
					Yii::log("Horizontally flipped + 90 rotate right the image");
				}
				else{
					Yii::log("Unable to horizontally flip the image");
					$realignedImage=$temp;
				}
			break;

			case 8:    // 270 rotate right
				$realignedImage=imagerotate($temp,-270,0);
			break;
			default :
				Yii::log("Unknown orientation.Uploading the image as is.");
				$realignedImage=$temp;
		}
		$mime=mime_content_type ( $sourceFolderPath );
		//Create image instance from roadies image
		if($mime=='image/jpeg'){
			imagejpeg($realignedImage,$sourceFolderPath);
			$im = imagecreatefromjpeg($sourceFolderPath);
		}
		elseif($mime=='image/png'){
			imagepng($realignedImage,$sourceFolderPath);
			$im = imagecreatefrompng($sourceFolderPath);
		}
		elseif($mime=='image/gif'){
			imagegif($realignedImage,$sourceFolderPath);
			$im = imagecreatefromgif($sourceFolderPath);

		}
		else{
			Yii::log("Invalid image format...discontinuing the S3 upload and Watermarking");
			return false;
		}
		if(!$im){
			Yii::log("src creation failed");


		}
        $isOriginalUploadSuccessful=false;
		$isWMUploadSuccessful=false;
		//instantiate the class
		$s3 = new S3(S3_ACCESS_KEY,S3_SCRET_KEY);

		if ($s3->putObjectFile($sourceFolderPath,S3_FIRST_BUCKET,$destinationFolderPathForOriginals, S3::ACL_PUBLIC_READ)) 
        {
            $isOriginalUploadSuccessful=true;
        }
        else
        {//donot do watermarking if the original image upload fails
            $isOriginalUploadSuccessful=false;
			Yii::log("Original Image Upload Failure");
			return false;

        }


		//Create image instance for watermark image
		//$watermark = imagecreatefrompng(Yii::getPathOfAlias('webroot')."/customer-assets/images/medinfilogo.png");

		$watermark = imagecreatefrompng(Yii::getPathOfAlias('webroot')."/customer-assets/images/medti.png");

		if(!$watermark){
			Yii::log("watermark creation failed");
		}

		$originalWMWidth=imagesx($watermark);
		$originalWMHeight=imagesy($watermark);
		$aspectRatioWM=$originalWMWidth/$originalWMHeight;

		$sourceImageWidth=imagesx($im);
		$sourceImageHeight=imagesy($im);

		//54 is the width of the original watermark image (/customer-assets/images/medti.png)
		//349 is the width of the reference image as per the solution document(/customer-assets/images/D592503393_close-up.jpeg)
		$widthRatio=ORIGINAL_WATERMARK_WIDTH/REFERENCE_IMAGE_WIDTH;
		//41 is the height of the original watermark image (/customer-assets/images/medti.png)
		//349 is the height of the reference image as per the solution document(/customer-assets/images/D592503393_close-up.jpeg)
		$heightRatio=ORIGINAL_WATERMARK_HEIGHT/REFERENCE_IMAGE_HEIGHT;
		Yii::log("Ratio w/h ".$widthRatio."/".$heightRatio);

		//Parameters to store the re-scaled watermark height and width
		$newWMWidth=0;
		$newWMHeight=0;

		//Logic to calculate the re-scaled watermark height and width
		if($sourceImageHeight<$sourceImageWidth){
			$newWMHeight=$heightRatio*$sourceImageHeight;
			$newWMWidth=$newWMHeight*$aspectRatioWM;
		}
		else{
			$newWMWidth=$widthRatio*$sourceImageWidth;
			$newWMHeight=$newWMWidth/$aspectRatioWM;
		}

		$newWMHeight=intval($newWMHeight);
		$newWMWidth=intval($newWMWidth);
		Yii::log("New width and height ".$newWMHeight."/".$newWMWidth);

		//create a blank black image of size of watermark
		$newWMImage = imagecreatetruecolor($newWMWidth,$newWMHeight);
		//donot allow the underlying colors to mix with the transparent images
		imagealphablending($newWMImage, false);
		//allow alpha channel in the image which allows to have multi color transparency.
		imagesavealpha($newWMImage,true);
		//create a transparent color
		$transparent = imagecolorallocatealpha($newWMImage, 255, 255, 255, 127);
		//Create a transparent rectangle of the size of watermark and which will be transparent
		imagefilledrectangle($newWMImage, 0, 0, $newWMWidth, $newWMHeight, $transparent);
		//Rescale the original watermark as per the scaled width and height
		imagecopyresampled($newWMImage, $watermark, 0, 0, 0, 0, $newWMWidth, $newWMHeight,$originalWMWidth, $originalWMHeight);

		//Uncomment this line to view the resized watermark under the root project directory
		//imagepng($newWMImage, Yii::getPathOfAlias('webroot')."/newlogo.png");

		//10 is the fixed margin of watermark from the edge of image in the reference image
		//349 is the height of the reference image as per the solution document(/customer-assets/images/D592503393_close-up.jpeg)
		$marginRightRatio = REFERENCE_IMAGE_RIGHT_MARGIN/REFERENCE_IMAGE_WIDTH;
		$marginBottomRatio = REFERENCE_IMAGE_BOTTOM_MARGIN/REFERENCE_IMAGE_HEIGHT;
		$margin_right = intval($sourceImageWidth*$marginRightRatio);
		$margin_bottom = intval($sourceImageWidth*$marginBottomRatio);

		//Yii::log("Margin ratio r/b".$marginRightRatio."/".$marginBottomRatio);
		//Yii::log("Margins r/b".$margin_right."/".$margin_bottom);

		//Old code for watermarking removed by Harsh on making the watermark dynamic
		//$sx = imagesx($watermark);
		//$sy = imagesy($watermark);
		//imagecopy($im, $watermark, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));

		imagecopy($im, $newWMImage, $sourceImageWidth - $newWMWidth - $margin_right, $sourceImageHeight - $newWMHeight - $margin_bottom, 0, 0, $newWMWidth, $newWMHeight);

		//imagecopymerge($im, $newWMImage, $sourceImageWidth - $newWMWidth - $margin_right, $sourceImageHeight - $newWMHeight - $margin_bottom, 0, 0, $newWMWidth, $newWMHeight, 50);

		// Create a temp watermarked PNG image to preserve transparency
		$pos= strrpos ( $sourceFolderPath , ".");
		$sourceFolderPathPng=$sourceFolderPath.".png";
		Yii::log("PNG PATH>>>".$sourceFolderPathPng);
		imagepng($im, $sourceFolderPathPng);
		$temp=imagecreatefrompng($sourceFolderPathPng);

		//Convert temp file PNG to file to JPEG file to reduce the file size
		$sourceFolderPathF=$sourceFolderPath."F.jpeg";
		Yii::log("JPEG PATH>>>".$sourceFolderPathF);
		imagejpeg($temp,$sourceFolderPathF,WM_IMAGE_QUALITY);

        if ($s3->putObjectFile($sourceFolderPathF,S3_FIRST_BUCKET,$destinationFolderPathForWM, S3::ACL_PUBLIC_READ)) 
        {
            $isWMUploadSuccessful=true;
        }
        else
        {
            $isWMUploadSuccessful=false;
        }
		imagedestroy($im);
		imagedestroy($temp);
		unlink($sourceFolderPathPng);
		unlink($sourceFolderPathF);
        //Yii::log("upload results>>>".$isWMUploadSuccessful.">>>>>".$isOriginalUploadSuccessful);
        return ($isOriginalUploadSuccessful && $isWMUploadSuccessful);

    }*/
    public static function uploadToS3($sourceFolderPath,$destinationFolderPathForOriginals,$destinationFolderPathForWM,$picName,$picType,$identityType) 
    {
        Yii::log("Paths >>>>>".$sourceFolderPath.">>>".$destinationFolderPathForOriginals.">>>".$destinationFolderPathForWM.">>>".$picName.">>>".$picType.">>>".$identityType);
        $mime=mime_content_type ( $sourceFolderPath );
        $validPicTypesForThumbnail=array('pic','second_image','emergency_entrance_image');
        $thumbnailDimensions=array('doctor'=>array(80,125),'hospital'=>array(100));
        /*if($mime=='image/jpeg' || $mime=='image/tiff'){
        			$exif = exif_read_data($sourceFolderPath);
        			if (array_key_exists("Orientation",$exif)){
        			$ort = $exif['Orientation'];
        			Yii::log("ORIENTATION from exif of ".$sourceFolderPath." is ".$ort);
        			}
        			else{
        				$ort=1;
        				Yii::log("No orientation!!!! Defaulting orientation to 1");
        			}
        		}
        		else{
        			Yii::log("File other than JPEG/TIFF. No meta-data!!! Defaulting orientation to 1");
        			$ort=1;
        		}*/
        if($mime=='image/jpeg' || $mime=='image/tiff'){
            try{
                $exif = @exif_read_data($sourceFolderPath);
                if (array_key_exists("Orientation",$exif)){
                    $ort = $exif['Orientation'];
                    //echo "ORIENTATION from exif of ".$sourceFolderPath." is ".$ort;
                    Yii::log("ORIENTATION from exif of ".$sourceFolderPath." is ".$ort);
                }
                else{
                    //echo "No orientation!!!! Defaulting orientation to 1";
                    $ort=1;
                    Yii::log("No orientation!!!! Defaulting orientation to 1");
                }
            }
            catch(Exception $exp){
                $ort=1;
                //echo "Error in EXIF_READ_DATA!!!! Defaulting orientation to 1";
                Yii::log("Error in EXIF_READ_DATA!!!! Defaulting orientation to 1");
            }
        }
        else{
            Yii::log("File other than JPEG/TIFF. No meta-data!!! Defaulting orientation to 1");
            $ort=1;
        }


        $realignedImage=null;
        $temp=null;
        //Create image instance from roadies image
        if($mime=='image/jpeg'){
            $temp = imagecreatefromjpeg($sourceFolderPath);
        }
        elseif($mime=='image/png'){
            $temp = imagecreatefrompng($sourceFolderPath);
        }
        elseif($mime=='image/gif'){
            $temp = imagecreatefromgif($sourceFolderPath);

        }
        else{
            Yii::log("Invalid image format...discontinuing the s3 upload and Watermarking");
            return false;
        }

        switch($ort)
        {
            case 1: // nothing
                $realignedImage=$temp;
                break;

            case 2: // horizontal flip
                if(imageflip($temp,IMG_FLIP_HORIZONTAL))
                {
                    $realignedImage=$temp;
                    Yii::log("Horizontally flipped the image");
                }
                else{
                    Yii::log("Unable to horizontally flip the image");
                    $realignedImage=$temp;
                }
                break;

            case 3: // 180 rotate right
                $realignedImage=imagerotate($temp,-180,0);
                break;

            case 4: // vertical flip
                if(imageflip($temp,IMG_FLIP_VERTICAL))
                {
                    $realignedImage=$temp;
                    Yii::log("Vertically flipped the image");
                }
                else{
                    Yii::log("Unable to vertically flip the image");
                    $realignedImage=$temp;
                }
                break;

            case 5: // vertical flip + 90 rotate right
                if(imageflip($temp,IMG_FLIP_VERTICAL))
                {
                    $realignedImage=imagerotate($temp,-90,0);
                    Yii::log("Vertically flipped + 90 rotate right the image");
                }
                else{
                    Yii::log("Unable to vertically flip the image");
                    $realignedImage=$temp;
                }
                break;

            case 6: // 90 rotate right

                $realignedImage=imagerotate($temp,-90,0);
                break;

            case 7: // horizontal flip + 90 rotate right
                if(imageflip($temp,IMG_FLIP_HORIZONTAL))
                {
                    $realignedImage=imagerotate($temp,-90,0);
                    Yii::log("Horizontally flipped + 90 rotate right the image");
                }
                else{
                    Yii::log("Unable to horizontally flip the image");
                    $realignedImage=$temp;
                }
                break;

            case 8:    // 270 rotate right
                $realignedImage=imagerotate($temp,-270,0);
                break;
            default :
                Yii::log("Unknown orientation.Uploading the image as is.");
                $realignedImage=$temp;
        }
        $mime=mime_content_type ( $sourceFolderPath );
        //Create image instance from roadies image
        if($mime=='image/jpeg'){
            imagejpeg($realignedImage,$sourceFolderPath);
            $im = imagecreatefromjpeg($sourceFolderPath);
        }
        elseif($mime=='image/png'){
            imagepng($realignedImage,$sourceFolderPath);
            $im = imagecreatefrompng($sourceFolderPath);
        }
        elseif($mime=='image/gif'){
            imagegif($realignedImage,$sourceFolderPath);
            $im = imagecreatefromgif($sourceFolderPath);

        }
        else{
            Yii::log("Invalid image format...discontinuing the S3 upload and Watermarking");
            return false;
        }
        if(!$im){
            Yii::log("src creation failed");


        }
        $isOriginalUploadSuccessful=false;
        $isWMUploadSuccessful=false;
        //instantiate the class
        $s3 = new S3(S3_ACCESS_KEY,S3_SCRET_KEY);

        if ($s3->putObjectFile($sourceFolderPath,S3_FIRST_BUCKET,$destinationFolderPathForOriginals, S3::ACL_PUBLIC_READ)) 
        {
            $isOriginalUploadSuccessful=true;
        }
        else
        {//donot do watermarking if the original image upload fails
            $isOriginalUploadSuccessful=false;
            Yii::log("Original Image Upload Failure");
            return false;

        }


        //Create image instance for watermark image
        //$watermark = imagecreatefrompng(Yii::getPathOfAlias('webroot')."/customer-assets/images/medinfilogo.png");

        $watermark = imagecreatefrompng(Yii::getPathOfAlias('webroot')."/customer-assets/images/medti.png");

        if(!$watermark){
            Yii::log("watermark creation failed");
        }

        $originalWMWidth=imagesx($watermark);
        $originalWMHeight=imagesy($watermark);
        $aspectRatioWM=$originalWMWidth/$originalWMHeight;

        $sourceImageWidth=imagesx($im);
        $sourceImageHeight=imagesy($im);

        //54 is the width of the original watermark image (/customer-assets/images/medti.png)
        //349 is the width of the reference image as per the solution document(/customer-assets/images/D592503393_close-up.jpeg)
        $widthRatio=ORIGINAL_WATERMARK_WIDTH/REFERENCE_IMAGE_WIDTH;
        //41 is the height of the original watermark image (/customer-assets/images/medti.png)
        //349 is the height of the reference image as per the solution document(/customer-assets/images/D592503393_close-up.jpeg)
        $heightRatio=ORIGINAL_WATERMARK_HEIGHT/REFERENCE_IMAGE_HEIGHT;
        Yii::log("Ratio w/h ".$widthRatio."/".$heightRatio);

        //Parameters to store the re-scaled watermark height and width
        $newWMWidth=0;
        $newWMHeight=0;

        //Logic to calculate the re-scaled watermark height and width
        if($sourceImageHeight<$sourceImageWidth){
            $newWMHeight=$heightRatio*$sourceImageHeight;
            $newWMWidth=$newWMHeight*$aspectRatioWM;
        }
        else{
            $newWMWidth=$widthRatio*$sourceImageWidth;
            $newWMHeight=$newWMWidth/$aspectRatioWM;
        }

        $newWMHeight=intval($newWMHeight);
        $newWMWidth=intval($newWMWidth);
        Yii::log("New width and height ".$newWMHeight."/".$newWMWidth);

        //create a blank black image of size of watermark
        $newWMImage = imagecreatetruecolor($newWMWidth,$newWMHeight);
        //donot allow the underlying colors to mix with the transparent images
        imagealphablending($newWMImage, false);
        //allow alpha channel in the image which allows to have multi color transparency.
        imagesavealpha($newWMImage,true);
        //create a transparent color
        $transparent = imagecolorallocatealpha($newWMImage, 255, 255, 255, 127);
        //Create a transparent rectangle of the size of watermark and which will be transparent
        imagefilledrectangle($newWMImage, 0, 0, $newWMWidth, $newWMHeight, $transparent);
        //Rescale the original watermark as per the scaled width and height
        imagecopyresampled($newWMImage, $watermark, 0, 0, 0, 0, $newWMWidth, $newWMHeight,$originalWMWidth, $originalWMHeight);

        //Uncomment this line to view the resized watermark under the root project directory
        //imagepng($newWMImage, Yii::getPathOfAlias('webroot')."/newlogo.png");

        //10 is the fixed margin of watermark from the edge of image in the reference image
        //349 is the height of the reference image as per the solution document(/customer-assets/images/D592503393_close-up.jpeg)
        $marginRightRatio = REFERENCE_IMAGE_RIGHT_MARGIN/REFERENCE_IMAGE_WIDTH;
        $marginBottomRatio = REFERENCE_IMAGE_BOTTOM_MARGIN/REFERENCE_IMAGE_HEIGHT;
        $margin_right = intval($sourceImageWidth*$marginRightRatio);
        $margin_bottom = intval($sourceImageWidth*$marginBottomRatio);

        //Yii::log("Margin ratio r/b".$marginRightRatio."/".$marginBottomRatio);
        //Yii::log("Margins r/b".$margin_right."/".$margin_bottom);

        /*Old code for watermarking removed by Harsh on making the watermark dynamic
		$sx = imagesx($watermark);
		$sy = imagesy($watermark);


		imagecopy($im, $watermark, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));*/

        imagecopy($im, $newWMImage, $sourceImageWidth - $newWMWidth - $margin_right, $sourceImageHeight - $newWMHeight - $margin_bottom, 0, 0, $newWMWidth, $newWMHeight);

        //imagecopymerge($im, $newWMImage, $sourceImageWidth - $newWMWidth - $margin_right, $sourceImageHeight - $newWMHeight - $margin_bottom, 0, 0, $newWMWidth, $newWMHeight, 50);

        // Create a temp watermarked PNG image to preserve transparency
        $pos= strrpos ( $sourceFolderPath , ".");
        $sourceFolderPathPng=$sourceFolderPath.".png";
        Yii::log("PNG PATH>>>".$sourceFolderPathPng);
        imagepng($im, $sourceFolderPathPng);
        $temp=imagecreatefrompng($sourceFolderPathPng);

        //Convert temp file PNG to file to JPEG file to reduce the file size
        $sourceFolderPathF=$sourceFolderPath."F.jpeg";
        Yii::log("JPEG PATH>>>".$sourceFolderPathF);
        imagejpeg($temp,$sourceFolderPathF,WM_IMAGE_QUALITY);

        if ($s3->putObjectFile($sourceFolderPathF,S3_FIRST_BUCKET,$destinationFolderPathForWM, S3::ACL_PUBLIC_READ)) 
        {
            $isWMUploadSuccessful=true;
        }
        else
        {
            $isWMUploadSuccessful=false;
        }
        //call function to create thumbnail for the image being processed currently
        $isThumbnailUploadSuccessful=S3Uploads::createThumbnails($sourceFolderPathF,$picName,$picType,$identityType,$validPicTypesForThumbnail,$thumbnailDimensions);


        imagedestroy($im);
        imagedestroy($temp);
        unlink($sourceFolderPathPng);
        unlink($sourceFolderPathF);
        //Yii::log("upload results>>>".$isWMUploadSuccessful.">>>>>".$isOriginalUploadSuccessful);
        return ($isOriginalUploadSuccessful && $isWMUploadSuccessful && $isThumbnailUploadSuccessful);

    }
    /*
	 *This function will check whether the image requested for is applicable for thumbnail creation.
	 *If no then it returns true without creating any thumbnail.
	 *Else if applicable then it checks the type of identity(doctor or hospital) and according to that it creates a thumbnail and uploads it.
	 *If the upload is successful then it returns true else it returns false.
	*/
    public static function createThumbnails($watermarkedImagePath,$picName,$picType,$identityType,$validPicTypesForThumbnail,$thumbnailDimensions){
        $destinatonPathForThumbnail="NA";
        if(in_array($picType,$validPicTypesForThumbnail)){
            foreach($thumbnailDimensions[$identityType] as $dimension){
                if($identityType=='doctor'){
                    if($dimension==80){
                        $destinatonPathForThumbnail=S3_FIRST_FOLDER_PREFIX.S3_DOC_80_THUMBNAIL.$picName;
                    }
                    elseif($dimension==125){
                        $destinatonPathForThumbnail=S3_FIRST_FOLDER_PREFIX.S3_DOC_125_THUMBNAIL.$picName;
                    }
                }
                else{
                    if($dimension==100){
                        $destinatonPathForThumbnail=S3_FIRST_FOLDER_PREFIX.S3_HOS_100_THUMBNAIL.$picName;
                    }
                }
                $watermarkedImage=imagecreatefromjpeg($watermarkedImagePath);
                $width=imagesx($watermarkedImage);
                $height=imagesy($watermarkedImage);
                $aspectRatio=$width/$height;
                $newheight=0;
                $newwidth=0;

                if($width<$height){
                    $newwidth=$dimension;
                    $newheight=$newwidth/$aspectRatio;
                }
                else{
                    $newheight=$dimension;
                    $newwidth=$aspectRatio*$newheight;
                }
                $newwidth=intval($newwidth);
                $newheight=intval($newheight);

                $thumbnail = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($thumbnail, $watermarkedImage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                $tempThumbnailPath=$watermarkedImagePath."thumbnail_".$dimension.".jpeg";
                //Yii::log("Thumbnail JPEG PATH>>>".$sourceFolderPathThumbnail);
                imagejpeg($thumbnail,$tempThumbnailPath,WM_IMAGE_QUALITY);
                if (S3Uploads::uploadfile($tempThumbnailPath,$destinatonPathForThumbnail)){
                    Yii::log($tempThumbnailPath."Thumbnail Upload Successful ");
                    unlink($tempThumbnailPath);
                }
                else{
                    echo "Upload failure". $tempThumbnailPath;
                    return false;
                }
            }
        }
        else{
            Yii::log("No need to create thumbnails of image of type ".$picType."/".$identityType);
        }
        return true;
    }
    public static function uploadfile($sourceFolderPath,$destinationFolderPath) 
    {
        $uploadResult=false;
        //instantiate the class
        $s3 = new S3(S3_ACCESS_KEY,S3_SCRET_KEY);
        if ($s3->putObjectFile($sourceFolderPath,S3_FIRST_BUCKET,$destinationFolderPath, S3::ACL_PUBLIC_READ)) 
        {
            $uploadResult=true;
        }
        else
        {
            $uploadResult=false;
        }

        return $uploadResult;

    }
    public static function deletefile($filePath)
    {
        $deleteResult=false;
        $s3 = new S3(S3_ACCESS_KEY,S3_SCRET_KEY);

        if($s3->deleteObject(S3_FIRST_BUCKET,$filePath))
        {
            $deleteResult=true; 
        }
        else
        {
            $deleteResult=false;
        }

        return $deleteResult;
    }

}

?>
