<!DOCTYPE html>
<html>

<head>
	<title>Basic details inserted</title>
</head>

<body>
	<center>
		<?php

        include 'database.php';
		// Taking all 5 values from the form data(input)
		$x_cod = $_REQUEST['x_cod'];
		$y_cod = $_REQUEST['y_cod'];
		$channel_count = $_REQUEST['channel_count'];
        $base_id = uniqid('base');
		$sqldel="DELETE FROM `base`";
        mysqli_query($conn, $sqldel);
		
		$sql = "INSERT INTO `base`(`base_id`, `x_cod`, `y_cod`, `channel_count`) VALUES ('$base_id', '$x_cod', '$y_cod', '$channel_count')";
		
		if(mysqli_query($conn, $sql)){
			echo "<h3>data stored in a database successfully."
				. " Please browse your localhost php my admin"
				. " to view the updated data</h3>";

			echo nl2br("\n$base_id\n $x_cod\n "
				. "$y_cod\n $channel_count");
		} else{
			echo "ERROR: Hush! Sorry $sql. "
				. mysqli_error($conn);
		}
		
		// Close connection
		mysqli_close($conn);
		?>
	</center>
</body>

</html>
