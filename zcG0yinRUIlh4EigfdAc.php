<?php
$db = new PDO('mysql:host=localhost;dbname=circleka_bapp', 'circleka_bapp', 'w*I1TG[+(X_X');
	$query = $db->query("select id from entries where length(image) > 0 order by id desc;");
	$query = $query->fetchALL(PDO::FETCH_NUM);
	echo "<table>
	<thead>
		<tr>
			<th>&num;</th>
			<th>Name</th>
			<th>City</th>
			<th>State</th>
			<th>Image</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody>";
	for($i=0; $i<count($query); $i++){
		$j = $i+1;
		$images = $db->query("select * from entries where id = {$query[$i][0]}");
		$images = $images->fetchALL(PDO::FETCH_NUM);
		echo "		<tr>
			<td>$j</td><td><a href=\"https://facebook.com/{$images[0][3]}\">{$images[0][1]} {$images[0][2]}</a></td><td>{$images[0][4]}</td><td>{$images[0][5]}</td><td><img height=\"75\" src=\"data:image;base64,{$images[0][6]}\" /></td><td>{$images[0][7]}</td>
		<tr>";
		unset($images);
	}
	echo "	</tbody>
</table>";