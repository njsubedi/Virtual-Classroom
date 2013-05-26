<?php
	if(!isset($_FILES['image']))
	{
		?>
			<form action = 'exp.php' method='post' enctype='multipart/form-data'>
			<input type = 'file' name = 'image'>
			<input type = 'submit'>
			</form>
		<?php
	}
	else
	{
		include 'include/imageprocess.php';
		$img = $_FILES['image'];
		createThumbnailFromFile($img);	
	}

?>