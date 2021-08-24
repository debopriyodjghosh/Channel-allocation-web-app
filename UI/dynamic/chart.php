<?php

include 'database.php';

//Base station coordinate fetch to be done
//priority device
$q = "select channel_count, x_cod, y_cod FROM base";
$r = mysqli_query($conn, $q);
while ($i = mysqli_fetch_array($r)) {
    $channel_count = $i['channel_count'];
    $base_x = $i['x_cod'];
    $base_y = $i['y_cod'];
}


$query = 'SELECT * FROM device';

$result = mysqli_query($conn, $query);
$device_array = array();
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_push($device_array, $row);
}

//print_r($device_final);
//chart start
$x = [];
$y = [];
$x1 = [];
$y1 = [];

for ($i = 0; $i < sizeof($device_array); $i++) {
    if ($device_array[$i]['allocation'] > 0) {
        array_push($x, $device_array[$i]['x_cod']);
        array_push($y, $device_array[$i]['y_cod']);
    }  //allocated to chart
    else {
        array_push($x1, $device_array[$i]['x_cod']);
        array_push($y1, $device_array[$i]['y_cod']);
    } //requested to chart
}

?>

<script>
    // Access the array elements
    //allocated chart
    var xarr = <?php echo json_encode($x); ?>;
    var yarr = <?php echo json_encode($y); ?>;
    //requested chart
    var x1arr = <?php echo json_encode($x1); ?>;
    var y1arr = <?php echo json_encode($y1); ?>;
    //base chart
    var basex = <?php echo json_encode($base_x); ?>;
    var basey = <?php echo json_encode($base_y); ?>;

    var data1 = [];
    var data2 = [];
    var data0 = [];
    //base convert
    data0.push({
        'x': basex,
        'y': basey
    });
    //allocated convert
    for (var i = 0; i < xarr.length; i++) {

        data1.push({
            'x': xarr[i],
            'y': yarr[i]
        });
    }
    //request convert
    for (var i = 0; i < x1arr.length; i++) {
        data2.push({
            'x': x1arr[i],
            'y': y1arr[i]
        });
    }
</script>

<html lang="en">

<head>
    <title>Visualization Chart</title>
    <meta http-equiv="refresh" content="1">
</head>

<body translate="no">
    <div style="width:80%; float: center; margin-left: 3rem; margin-top: 3rem;">
        <canvas id="chart1"></canvas>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script>
        var ctx = document.getElementById("chart1").getContext("2d");
        var myScatter = Chart.Scatter(ctx, {
            data: {
                datasets: [{
                    label: "Allocated Device",
                    borderColor: 'green',
                    backgroundColor: 'green',
                    pointBackgroundColor: 'green',
                    data: data1
                }, {
                    label: "Requsted Device",
                    borderColor: 'red',
                    backgroundColor: 'red',
                    pointBackgroundColor: 'red',
                    data: data2
                }, {
                    label: "Base Station",
                    pointStyle: 'rectRounded',

                    borderColor: 'blue',
                    backgroundColor: 'blue',
                    pointBackgroundColor: 'blue',
                    data: data0

                }]
            },
            options: {
                title: {
                    display: true,
                    fontSize: 18,
                    text: 'Channel Allocation'
                },
                showLines: false,
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Y Rank',
                            fontSize: 16
                        },
                        ticks: {
                            min: 0,
                            max: 200,
                            fontSize: 14,

                        }

                    }],
                    xAxes: [{
                        type: 'linear',
                        position: 'bottom',
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'X Rank',
                            fontSize: 16
                        },
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            min: 0,
                            max: 200,
                            fontSize: 14,
                        }
                    }]
                },
                elements: {
                    point: {
                        radius: 9
                    }
                }
                // ,
                // animation: {
                //     duration: 0
                // }
            }
        });
    </script>
    <script>
        window.setTimeout(function() {
            window.location.reload();
        }, 2000);
    </script>

</body>

</html>
<!-- end of chart -->




