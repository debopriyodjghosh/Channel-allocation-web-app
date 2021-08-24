<?php

session_start();
if (empty($_SESSION["refreshed_round"])) {
    $_SESSION["refreshed_round"] = 0;
}
$_SESSION["refreshed_round"]++;


?>
<!DOCTYPE html>
<html>

<head>
    <title>Sending data...</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta http-equiv="refresh" content="2">
</head>

<body>
    <center>
        <style>
            h1 {
                margin-top: 180px;

            }

            h2 {
                color: red;
                font-weight: 400;
                font-size: 40;
            }
        </style>
        <?php
        include 'database.php';

        echo "<h1>Sending random client data to server after every 3 seconds.....</h1>";
        echo "<h2>Data Sent <br><b>" . $_SESSION["refreshed_round"] . "</b><br> times</h2>";
        echo "<br><div class='spinner-border' style='width: 3rem; height: 3rem;' role='status'>
    <span class='sr-only'>Loading...</span>
  </div><br>";

        $q = "select x_cod FROM base LIMIT 1";
        $r = mysqli_query($conn, $q);
        while ($i = mysqli_fetch_array($r)) {
            $base_x = $i['x_cod'];
        }
        $q1 = "select y_cod FROM base LIMIT 1";
        $r1 = mysqli_query($conn, $q1);
        while ($i = mysqli_fetch_array($r1)) {
            $base_y = $i['y_cod'];
        }
        $query = 'SELECT * FROM device';

        $result = mysqli_query($conn, $query);
        $device_array = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            array_push($device_array, $row);
        }

        $device_count = sizeof($device_array);
        $data_rate = rand(1, 30);
        $x_cod = rand(0, 200);
        $y_cod = rand(0, 200);
        $data_size = rand(10, 100);
        // $dev_id = uniqid('dev');
        $temp = $data_rate / 20;
        $tollerence = 1 / (pow(2, $temp) - 1);
        $allocation = 0;
        $x3 = pow(($x_cod - $base_x), 2); //x2-x1 sqr
        $y3 = pow(($y_cod - $base_y), 2); //y2-y1 sqr
        $distance = sqrt($x3 + $y3);
        $priority = 0;
        $data_rate_given = 0; //calculate later
        $time_required = 1000; //change later
        $sql = "INSERT INTO `device`( `data_rate`, `x_cod`, `y_cod`, `distance`, `tollerence`, `allocation`, `data_size`, `priority`, `data_rate_given`, `time_required`) 
	VALUES ('$data_rate', '$x_cod', '$y_cod', '$distance', '$tollerence', '$allocation', '$data_size', '$priority', '$data_rate_given', '$time_required')";
        if (mysqli_query($conn, $sql)) {
            //echo json_encode(array("statusCode" => 200));
            echo '<br><br><a href="chart.php" target="_blank"><button type="button" class="btn btn-dark btn-lg">View Chart</button></a>';
        } else {
            echo json_encode(array("statusCode" => 201));
        }
        mysqli_close($conn);
        $flag = 0; //to check that allocation have not started already
        for ($m = 0; $m < sizeof($device_array); $m++) {
            if ($device_array[$m]['allocation'] > 0) {
                $flag = 1;
                break;
            }
        }
        ?>

    </center>

    <script type="text/javascript">
        var c = <?php print($device_count); ?>;
        var f = <?php print($flag); ?>;
        if (c == 17 && f==0) {
            window.open(
                "algo.php", "_blank");
        }
    </script>
</body>

</html>