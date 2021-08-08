<!DOCTYPE html>
<html>

<head>
	<title>Sending  random clients data........</title>
    <meta http-equiv="refresh" content="2">
</head>

<body>
	<center>
<?php
	include 'database.php';
    echo "Sending random client data to server after every 2 secconds..................";
        $q = "select x_cod FROM base LIMIT 1";
        $r = mysqli_query($conn,$q);
        while ($i = mysqli_fetch_array($r)) {
            $base_x = $i['x_cod'];
        }
        $q1 = "select y_cod FROM base LIMIT 1";
        $r1 = mysqli_query($conn,$q1);
        while ($i = mysqli_fetch_array($r1)) {
            $base_y = $i['y_cod'];
        }
	$data_rate=rand(1,30);
	$x_cod=rand(0,200);
	$y_cod=rand(0,200);
	$data_size=rand(40,400);
    $dev_id = uniqid('dev');
    $temp = $data_rate / 20;
    $tollerence = 1 / (pow(2, $temp) - 1);
    $allocation = 0;
    $x3 = pow(($x_cod - $base_x), 2); //x2-x1 sqr
    $y3 = pow(($y_cod - $base_y), 2); //y2-y1 sqr
    $distance = sqrt($x3 + $y3);
    $priority = 0;
	$sql = "INSERT INTO `device`( `dev_id`, `data_rate`, `x_cod`, `y_cod`, `distance`, `tollerence`, `allocation`, `data_size`, `priority`) 
	VALUES ('$dev_id', '$data_rate', '$x_cod', '$y_cod', '$distance', '$tollerence', '$allocation', '$data_size', '$priority')";
	if (mysqli_query($conn, $sql)) {
		echo json_encode(array("statusCode"=>200));
	} 
	else {
		echo json_encode(array("statusCode"=>201));
	}
	mysqli_close($conn);
    ?>
    	</center>
</body>

</html>