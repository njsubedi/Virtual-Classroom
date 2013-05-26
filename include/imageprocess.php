<?php
include_once "session.php";

function saveThumbnail($source, $ext, $dest, $x , $y )
{
$ext = strtolower($ext);
	if($ext === 'jpg' || $ext === 'jpeg')
	{
		$src_img = imagecreatefromjpeg($source);
	}
	else if($ext == 'png')
	{
		$src_img = imagecreatefrompng($source);
	}
	
	$oldX = imageSX($src_img);
	$oldY = imageSY($src_img);
	
	$ratio = $oldX/$oldY;
	if($ratio > 2 || $ratio < 0.5)
		{
			$_SESSION['editProfileErr'] = "The photo is too wide or too long. Please upload another photo";
			return;
		}

	$thumbWidth = $x;
	$thumbHeight = floor($x / $ratio);
	
	$dst_img = ImageCreateTrueColor($thumbWidth, $thumbHeight);
	
	imagecopyresampled($dst_img, $src_img, 0,0,0,0, $thumbWidth, $thumbHeight, $oldX, $oldY);
	
	if($ext == 'png')
		imagepng($dst_img, $dest);
	else
		imagejpeg($dst_img, $dest);
	
	imagedestroy($dst_img);
	imagedestroy($src_img);
	
	$_SESSION['editProfileErr'] = "<b>Profile picture successfully changed.</b>";
	header("Cache-control: no cache/ must revalidate");
	header("Location: ".OV_ROOT."/profile.php?go=edit");

}

function createThumbnailFromFile($image)
{
	$imgName = $image["name"];
	$imgType = $image["type"];
	$imgErr = $image["error"];
	$imgSize = $image["size"];
	$imgTmp = $image["tmp_name"];

	if (( $imgType == "image/gif") || ($imgType == "image/jpeg") || ($imgType == "image/pjpeg") && ($imgSize < 10240000))
	{
		if ($imgErr > 0)
		{
			$_SESSION['editProfileErr'] = "The photo contains errors. Please upload another photo";
			break;
		}
		else
		{
				$imgExt = @end(explode(".", $imgName));
				
				$targetPic = "../userdata/thumbs/pic_".$_SESSION['userid'].".".$imgExt;
				$targetThumb = "../userdata/thumbs/thumb_".$_SESSION['userid'].".".$imgExt;
				
				$thumb = saveThumbnail($imgTmp, $imgExt, $targetThumb, 60, 60);
				$thumb = saveThumbnail($imgTmp, $imgExt, $targetPic, 180, 180);
		}
	}
	else
	{
		$_SESSION['editProfileErr'] = "The photo was invalid or too large. Please upload another photo";
	}	
}
?>
<?php
	if(!isset($_FILES['image']))
	{
		?>
			<form action = '<?php echo OV_ROOT; ?>/include/imageprocess.php' method='post' enctype='multipart/form-data'>
			<input type = 'file' name = 'image'>
			<input type = 'submit' class="ovButton" value="Change photo">
			</form>
		<?php
	}
	else
	{
		$img = @$_FILES['image'];
		createThumbnailFromFile($img);
		header("Location: ../profile.php?go=edit");
	}
?>