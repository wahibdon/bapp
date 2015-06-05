<?
$db = new PDO('mysql:host=localhost;dbname=circleka_bapp', 'circleka_bapp', 'w*I1TG[+(X_X');
if(isset($_GET["ids"])){
	$query = $db->query('select id from entries where length(image) > 0 order by id desc');
	$results = $query->fetchAll(PDO::FETCH_NUM);
	$ids = [];
	foreach($results as $result){
		array_push($ids, $result[0]);
	}
	echo json_encode($ids);
}
if(isset($_GET['id'])){
	$query = $db->query("select * from entries where id='{$_GET['id']}'");
	$result = $query->fetchALL(PDO::FETCH_ASSOC);
	echo json_encode($result[0]);
}