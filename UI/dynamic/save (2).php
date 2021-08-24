<?php
	include 'database.php';
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

	$data_rate=$_POST['data_rate'];
	$x_cod=$_POST['x_cod'];
	$y_cod=$_POST['y_cod'];
	$data_size=$_POST['data_size'];
    $temp = $data_rate / 20;
    $tollerence = 1 / (pow(2, $temp) - 1);
    $allocation = 0;
    $x3 = pow(($x_cod - $base_x), 2); //x2-x1 sqr
    $y3 = pow(($y_cod - $base_y), 2); //y2-y1 sqr
    $distance = sqrt($x3 + $y3);
    $priority = 0;
    $data_rate_given=0; //calculate later
    $time_required= 1000; //change later

	$sql = "INSERT INTO `device`(`data_rate`, `x_cod`, `y_cod`, `distance`, `tollerence`, `allocation`, `data_size`, `priority`, `data_rate_given`, `time_required`) 
	VALUES ('$data_rate', '$x_cod', '$y_cod', '$distance', '$tollerence', '$allocation', '$data_size', '$priority', '$data_rate_given', '$time_required')";
	if (mysqli_query($conn, $sql)) {
		echo json_encode(array("statusCode"=>200));
	} 
	else {
		echo json_encode(array("statusCode"=>201));
	}
	mysqli_close($conn);
    ?>