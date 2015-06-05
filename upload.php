<?php
print_r($_POST);
//w*I1TG[+(X_X
//Array ( [first] => W. [last] => Hibdon [fbid] => 10100894152713109 [location] => Kentucky [url] => scontent.xx.fbcdn.net/hphotos-xfp1/v/t1.0-9/s720x720/10982387_672478932878315_8222275481080210708_n.jpg?oh=dab8fb9ce159569135ee21669c82ee0f&oe=56004ADA )
//Array ( [first] => W. [last] => Hibdon [fbid] => 10100894152713109 [location] => Kentucky ) Array ( [file] => Array ( [name] => bapp.jpg [type] => image/jpeg [tmp_name] => /tmp/phpbk8Txz [error] => 0 [size] => 187746 )
$base64image = "";
//print_r(scandir("../../"));
if(isset($_FILES['file'])){
	//echo "upload";
	$type = explode('/',$_FILES['file']['type']);
	if ($type[0] == 'image')
		$base64image = base64_encode(file_get_contents($_FILES['file']['tmp_name']));
}elseif(isset($_POST['url'])){
	$base64image = base64_encode(file_get_contents("https://".$_POST['url']));
}
$db = new PDO('mysql:host=localhost;dbname=circleka_bapp', 'circleka_bapp', 'w*I1TG[+(X_X');

$db->query("insert into entries (first, last, fbid, city, state, image) values ('{$_POST['first']}','{$_POST['last']}','{$_POST['fbid']}','{$_POST['city']}','{$_POST['state']}','$base64image');");