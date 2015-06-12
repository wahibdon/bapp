<?
	$db = new PDO('mysql:host=localhost;dbname=circleka_bapp', 'circleka_bapp', 'w*I1TG[+(X_X');
	$query = $db->query("select * from winners order by id desc;");
	$query = $query->fetchALL(PDO::FETCH_NUM);
	$table = "<table>
	<thead>
		<tr>
			<th>&num;</th>
			<th>Name</th>
			<th>City</th>
			<th>State</th>
			<th>Image</th>
		</tr>
	</thead>
	<tbody>";
	for($i=0; $i<count($query); $i++){
		$j = $i+1;
		$table .= "		<tr>
			<td>$j</td><td>{$query[$i][1]}</td><td>{$query[$i][2]}</td><td>{$query[$i][3]}</td><td><a href=\"uimages/{$query[$i][4]}\">Image</a></td>
		</tr>";
	}
	$table .= "	</tbody>
</table>";
echo $table;