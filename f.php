<?php
if (isset($_POST["download"])){
	$file_path=$_POST['path']; 
	if(!file_exists($file_path)){ 
		echo "No such file"; 
		return ; 
	} 
	$fp=fopen($file_path,"r"); 
	$file_size=filesize($file_path); 
	Header("Content-type: application/octet-stream"); 
	Header("Accept-Ranges: bytes"); 
	Header("Accept-Length:".$file_size); 
	Header("Content-Disposition: attachment; filename=". basename($file_path)); 
	$buffer=1024; 
	$file_count=0; 
	while(!feof($fp) && $file_count<$file_size){ 
		$file_con=fread($fp,$buffer); 
		$file_count+=$buffer; 
		echo $file_con;
	} 
	fclose($fp);
}
else{
?>
<html>
<body>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
<input type="file" name="file" />
<input type="text" name="path" />
<br />
force:<input type="checkbox" name="force[]" value="force" />
<br />
<input type="submit" name="upload" value="Upload" />
<input type="submit" name="download" value="Download" />
</form>
	<?php
	if (isset($_POST["upload"])){
		if ($_FILES["file"]["error"] >0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else {
			echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			echo "Type: " . $_FILES["file"]["type"] . "<br />";
			echo "Size: " . $_FILES["file"]["size"] . " Byte<br />";
			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
			if (file_exists($_POST["path"])) {
				if(isset($_POST["force"])){
					echo "deleting " . $_POST["path"] . "<br />";
					unlink($_POST["path"]);
					move_uploaded_file($_FILES["file"]["tmp_name"], $_POST["path"]);
					echo "Stored in: " . $_POST["path"];
				}
				else{
					echo $_POST["path"] . " already exists, use force";
				}
			}
			else {
				move_uploaded_file($_FILES["file"]["tmp_name"], $_POST["path"]);
				echo "Stored in: " . $_POST["path"];
			}
		}
	}
?>
</body>
</html>
	<?php
}
?>
