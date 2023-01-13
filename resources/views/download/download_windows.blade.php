<a href="?file=neostaff_Setup_1.2.2.exe">click HERE</a>
 
<?php 
if(!empty($_GET['file']))
{
	$filename ='neostaff_Setup_1.2.2.exe';
	$filepath = 'files/' . $filename;
	if(!empty($filename) && file_exists($filepath)){

//Define Headers
		header("Cache-Control: public");
		header("Content-Description: FIle Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/zip");
		header("Content-Transfer-Emcoding: binary");

		readfile($filepath);
		exit;
	} 
	else{
		echo "This File Does not exist.";
	}
}

 ?>