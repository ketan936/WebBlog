<?php

session_start();
require("config.php");

if(isset($_SESSION['USERNAME']) == FALSE) {
	header("Location: " . $config_basedir);
}

$db = mysql_connect($dbhost, $dbuser, $dbpassword);
mysql_select_db($dbdatabase, $db);

if(isset($_GET['id']) == TRUE) {
	if(is_numeric($id) == FALSE) {
		$error = 1;
	}

	if($error == 1) {
		header("Location: " . $config_basedir);
	}
	else {
		$validentry = $_GET['id'];
	}
}
else {
	$validentry = 0;
}

if($_POST['submit']) {
	$sql = "UPDATE entries SET cat_id = " . $_POST['cat'] . ", subject = '" . $_POST['subject'] ."', body = '" . $_POST['body'] . "' WHERE id = " . $validentry . ";";
	mysql_query($sql);
	
	header("Location: " . $config_basedir . "/viewentry.php?id=" . $validentry);
	
}
else {

	require("header.php");

	$fillsql = "SELECT * FROM entries WHERE id = " . $validentry . ";";	
	$fillres = mysql_query($fillsql);
	$fillrow = mysql_fetch_assoc($fillres);

?>
<h1>Update Entry</h1>
<form action="<?php echo $SCRIPT_NAME . "?id=" . $validentry; ?>" method="post">

<table>
<tr>
	<td>Category</td>
	<td>
	<select name="cat">
	<?php
		$catsql = "SELECT * FROM categories;";
		$catres = mysql_query($catsql);
		while($catrow= mysql_fetch_assoc($catres)) {
			echo "<option value='" . $catrow['id'] . "'";
			
			if($catrow['id'] == $fillrow['cat_id']) {
				echo " selected";
			}
			
			echo ">" . $catrow['cat'] . "</option>";
		}
	?>
	</select>
	</td>
</tr>
<tr>
	<td>Subject</td>
	<td><input type="text" name="subject" value="<?php echo $fillrow['subject']; ?>"></td>
</tr>
<tr>
	<td>Body</td>
	<td><textarea name="body" rows="10" cols="50"><?php echo $fillrow['body']; ?></textarea></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" name="submit" value="Update Entry!"></td>
</tr>
</table>
</form>

<?php
}
require("footer.php");
?>