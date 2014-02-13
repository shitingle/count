<?php 

session_start();
if(!isset($_SESSION['temp'])){
	if($fp=fopen("count.txt","r")==false){
		echo "打开文件失败";
	}else{
		$fp=fopen("count.txt","r");
		$counter=fgets(fopen("count.txt","r"),1024);
		fclose(fopen("count.txt","r"));
		$counter++;
		$fp=fopen("count.txt","w");
		fputs($fp,$counter);
		fclose($fp);
	}
	$_SESSION['temp']=1;
}
if($fp=fopen("count.txt","r")==false){
	echo "打开失败";
}else{
	$counter=fgets(fopen("count.txt","r"),1024);
	echo "$counter";
	$im=imagecreate(100,30);
    $bg=imagecolorallocate($im,240,240,240);
    $gray=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
    imagestring($im,5,160,5,$counter,$gray);
	imagepng($im);
	imagedestroy($im);
}

?>