<?php
include 'config.php';

$base_x = rand(0, 200);
$base_y = rand(0, 200);


if (isset($_POST)) {
    $device_count =  $_POST['device_count'];
    $channel_count = $_POST['channel_count'];
    $priority_device =  $_POST['priority_device'];
    //delete database
    $sql0 = "DELETE from base";
    $result = $conn->query($sql0);
    $sqli = "DELETE from device";
    $result = $conn->query($sqli);
    $sqlj = "ALTER TABLE device AUTO_INCREMENT = 0;";
    $result = $conn->query($sqlj);

    $sql = "INSERT INTO base (device_count, channel_count, priority_device, base_x, base_y ) 
            VALUES ($device_count, $channel_count, $priority_device, $base_x, $base_y)";
    if ($conn->query($sql) === true) { ?>

        <script type="text/javascript">
            alert('Base Station Placed!');
            //window.location.href = "device.html";
        </script>

    <?php } else {
        echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')javascript:history.go(-1);</script>";
    }
}

for ($i = 0; $i < $priority_device; $i++) { //priority device
    $x_cod = rand(0, 200);
    $y_cod = rand(0, 200);
    $data_rate = rand(21, 30);

    $temp = $data_rate / 20;
    $tollerence = 1 / (pow(2, $temp) - 1);

    $allocation = 0;
    $x3 = pow(($x_cod - $base_x), 2); //x2-x1 sqr
    $y3 = pow(($y_cod - $base_y), 2); //y2-y1 sqr
    $distance = sqrt($x3 + $y3);

    $sql1 = "INSERT INTO device ( data_rate, x_cod, y_cod, distance, tollerence, allocation ) 
    VALUES ($data_rate, $x_cod, $y_cod, $distance, $tollerence, $allocation)";
    if ($conn->query($sql1) === true) { ?>


    <?php } else {
        echo "<script>alert('Error: ' . $sql1 . '<br>' . $conn->error')javascript:history.go(-1);</script>";
    }
}

//<script type="text/javascript"> 
#alert('Device Placed!');
//window.location.href = "device.html";
//</script>

for ($j = 0; $j < ($device_count - $priority_device); $j++) { //non priority device
    $x_cod = rand(0, 200);
    $y_cod = rand(0, 200);
    $data_rate = rand(1, 20);

    $temp = $data_rate / 20;
    $tollerence = 1 / (pow(2, $temp) - 1);

    $allocation = 0;
    $x3 = pow(($x_cod - $base_x), 2); //x2-x1 sqr
    $y3 = pow(($y_cod - $base_y), 2); //y2-y1 sqr
    $distance = sqrt($x3 + $y3);

    $sql1 = "INSERT INTO device ( data_rate, x_cod, y_cod, distance, tollerence, allocation ) 
    VALUES ($data_rate, $x_cod, $y_cod, $distance, $tollerence, $allocation)";
    if ($conn->query($sql1) === true) { ?>


<?php } else {
        echo "<script>alert('Error: ' . $sql1 . '<br>' . $conn->error')javascript:history.go(-1);</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Channel Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <div class="login">
        <div class="heading">
            <h2>Base and Device Details</h2>
        </div>
        <div class="heading">
            <h3>View Base Details</h3>
        </div>
        <hr>
        <div class="heading">
            <div class="card-body table-responsive p-0" style="height: 100px;">
                <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>Base ID</th>
                            <th>Device Count</th>
                            <th>Channel Count</th>
                            <th>Priority Dev Count</th>
                            <th>X</th>
                            <th>Y</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql2 = "SELECT * from base";
                        $result = $conn->query($sql2);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                echo " <tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['device_count'] . "</td>";
                                echo "<td>" . $row['channel_count'] . "</td>";
                                echo "<td>" . $row['priority_device'] . "</td>";
                                echo "<td>" . $row['base_x'] . "</td>";
                                echo "<td>" . $row['base_y'] . "</td>";
                                echo "</tr>";
                            }
                        } ?>


                    </tbody>
                </table>
            </div>
        </div>
        <div class="heading">
            <h3>View Device Details</h3>
        </div>
        <div class="heading">

            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>Dev ID</th>
                            <th>Data Rate</th>
                            <th>X</th>
                            <th>Y</th>
                            <th>Distance</th>
                            <th>Tollerence</th>
                            <th>Allocation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql2 = "SELECT * from device";
                        $result = $conn->query($sql2);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                echo " <tr>";
                                echo "<td>" . $row['dev_id'] . "</td>";
                                echo "<td>" . $row['data_rate'] . "</td>";

                                echo "<td>" . $row['x_cod'] . "</td>";
                                echo "<td>" . $row['y_cod'] . "</td>";
                                echo "<td>" . $row['distance'] . "</td>";
                                echo "<td>" . $row['tollerence'] . "</td>";
                                echo "<td>" . $row['allocation'] . "</td>";
                                echo "</tr>";
                            }
                        } ?>


                    </tbody>
                </table>
            </div>



        </div>

    </div>
    </div>
    <hr>



</body>

</html>
<?php
//$conn->close();
?>