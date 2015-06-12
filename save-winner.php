<? 
	$db = new PDO('mysql:host=localhost;dbname=circleka_bapp', 'circleka_bapp', 'w*I1TG[+(X_X');
	if(isset($_POST['submit'])){
		if(isset($_FILES['image'])){
			$hash = hash('sha512', $_FILES['image']['tmp_name']);
			$ext = explode(".",$_FILES['image']['name']);
			$ext = $ext[count($ext)-1];
			move_uploaded_file($_FILES['image']['tmp_name'], "uimages/$hash.$ext");
		}
		$query=$db->query("insert into winners (name, city, state, image) values ('{$_POST['name']}','{$_POST['city']}','{$_POST['state']}','$hash.$ext');");
	}
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
		<tr>";
	}
	$table .= "	</tbody>
</table>";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Enter a Winner</title>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
	<label>Name: <input type="text" name="name" /></label>
	<label>City: <input type="text" name="city" /></label>
	<label>State:
		<select name="state">
			<option value="ME">Maine</option>
			<option value="MD">Maryland</option>
			<option value="MA">Massachusetts</option>
			<option value="MI">Michigan</option>
			<option value="NH">New Hampshire</option>
			<option value="OH">Ohio</option>
			<option value="PA">Pennsylvania</option>
			<option value="VT">Vermont</option>
			<option value="WV">West Virginia</option>
		</select>
	</label>
	<input type="file" name="image" />
	<input type="submit" value="Submit" name="submit" />
</form>
<?=$table?>
</body>
</html>