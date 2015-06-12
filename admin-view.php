<?php
$db = new PDO('mysql:host=localhost;dbname=circleka_bapp', 'circleka_bapp', 'w*I1TG[+(X_X');
	$query = $db->query("select * from entries where length(image) > 0 order by id desc;");
	$query = $query->fetchALL(PDO::FETCH_NUM);
	$table = "<table>
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
		$table .= "		<tr>
			<td>$j</td><td><a href=\"https://facebook.com/{$query[$i][3]}\">{$query[$i][1]} {$query[$i][2]}</a></td><td>{$query[$i][4]}</td><td>{$query[$i][5]}</td><td><img height=\"75\" src=\"data:image;base64,{$query[$i][6]}\" /></td><td>{$query[$i][7]}</td>
		<tr>";
	}
	$table .= "	</tbody>
</table>";
echo $table;