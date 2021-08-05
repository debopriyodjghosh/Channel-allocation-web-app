<?php

//Base station coordinate
$base_x = rand(0, 200);
$base_y = rand(0, 200);

//device count and channel count
if (isset($_POST)) {
    $device_count =  $_POST['device_count'];
    $channel_count = $_POST['channel_count'];
    $priority_device =  $_POST['priority_device'];
}
//base station print to be done
class device
{
    public $dev_id;
    public $data_rate;
    public $distance;
    public $tolerance;
    public $allocation;
    public $x_cod;
    public $y_cod;
    function __construct($d_id, $d_rate, $dis, $tol, $allo, $x_cod, $y_cod)
    {
        $this->dev_id = $d_id;
        $this->data_rate = $d_rate;
        $this->distance = $dis;
        $this->tolerance = $tol;
        $this->allocation = $allo;
        $this->x_cod = $x_cod;
        $this->y_cod = $y_cod;
    }
}
$device1 = array();
$device2 = array();
//insert priority device with high data rate
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
    $dev_id = uniqid('dev');
    $device1[$i] = new device($dev_id, $data_rate, $distance, $tollerence, $allocation, $x_cod, $y_cod);
}

//insert non priority device with less data rate
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
    $dev_id = uniqid('dev');
    $device2[$j] = new device($dev_id, $data_rate, $distance, $tollerence, $allocation, $x_cod, $y_cod);
}
function comparator($object1, $object2)
//function for comparing data rate
{
    return $object1->data_rate > $object2->data_rate;
}
function In($dis1, $dis2)
{ //function for calculating interference

    $value = $dis1 / $dis2;
    return pow($value, 4);
}
//sort
usort($device2, 'comparator');
//merge
$device_final = array_merge($device1, $device2);


//print device table before allocation to be done

//initial allocation

for ($i = 0; $i < $device_count; $i++) {
    if ($i < $priority_device) {
        $device_final[$i]->allocation = $i + 1;
    } else {
        $device_final[$i]->allocation = 0;
    }
}

for ($i = $priority_device; $i < $device_count; $i++) {
    for ($c = 1; $c <= $channel_count; $c++) {
        $device_final[$i]->allocation = $c;
        $D = array();
        //making a set of those device which are allocated to same channel
        for ($x = 0; $x < $device_count; $x++) {
            if ($device_final[$x]->allocation == $c) {
                array_push($D, (new device($device_final[$x]->dev_id, $device_final[$x]->data_rate, $device_final[$x]->distance, $device_final[$x]->tolerance, $device_final[$x]->allocation, $device_final[$x]->x_cod, $device_final[$x]->y_cod)));
            }
        }
        for ($x = 0; $x < sizeof($D); $x++) {
            $sum = 0.0;
            for ($y = 0; $y < sizeof($D); $y++) {
                if ($x == $y) {
                    $sum = $sum + 0.0;
                } else {
                    $sum = $sum + In($D[$x]->distance, $D[$y]->distance);
                }
            }
            //checking if sum of interference greater than tolerance
            if ($sum > $D[$x]->tolerance) {
                $device_final[$i]->allocation = 0;
                break;
            }
        }
        if ($device_final[$i]->allocation == $c)
            break;
    }
}
//print_r($device_final);

$x = [];
$y = [];
$x1 = [];
$y1 = [];

for ($i = 0; $i < $device_count; $i++) {
    //allocated
    if ($device_final[$i]->allocation > 0) {
        // $x[$i] = $device_final[$i]->x_cod;
        array_push($x, $device_final[$i]->x_cod);
        //$y[$i] = $device_final[$i]->y_cod;
        array_push($y, $device_final[$i]->y_cod);
    }
    //requested
    else {
        // $x1[$i] = $device_final[$i]->x_cod;
        array_push($x1, $device_final[$i]->x_cod);
        //$y1[$i] = $device_final[$i]->y_cod;
        array_push($y1, $device_final[$i]->y_cod);
    }
}
// echo "php array";
// print_r($x1);
// print_r($y1);

?>
<script>
    // Access the array elements
    var xarr = <?php echo json_encode($x); ?>;
    var yarr = <?php echo json_encode($y); ?>;

    var x1arr = <?php echo json_encode($x1); ?>;
    var y1arr = <?php echo json_encode($y1); ?>;

    var basex = <?php echo json_encode($base_x); ?>;

    var basey = <?php echo json_encode($base_y); ?>;



    //  Display the array elements
    //  document.write("js array");
    //  for (var i = 0; i < xarr.length; i++) {
    //        document.write( x1arr[i]);
    //    }


    var data1 = [];
    var data2 = [];
    var data0 = [];
    data0.push({
        'x': basex,
        'y': basey
    });
    for (var i = 0; i < xarr.length; i++) {

        data1.push({
            'x': xarr[i],
            'y': yarr[i]
        });
    }

    for (var i = 0; i < x1arr.length; i++) {

        data2.push({
            'x': x1arr[i],
            'y': y1arr[i]
        });
    }





    // for (var i = 0; i < xarr.length; i++) {
    //   document.write( JSON.stringify(data1[i]));
    // }
    //console.log( JSON.stringify(plotData));
</script>
<html lang="en">

<head>
    <title>Channel Allocation </title>
    <meta http-equiv="refresh" content="5">
</head>

<body translate="no">
    <div style="width:80%; float: center; margin-left: 3rem; margin-top: 3rem;">
        <canvas id="chart1"></canvas>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script>
        // var jitter = function(data) {
        //     return data.map(function(e) {
        //         var xJitter = Math.random() * (-1 - 1) + 1;
        //         var yJitter = Math.random() * (-1 - 1) + 1;
        //         return {
        //             x: e.x + xJitter,
        //             y: e.y + yJitter,
        //         }
        //     });
        // };
        //base
        // var data0 = [{
        //     x: 100,
        //     y: 100
        // }]
        //alloated dev
        //data1;

        //req dev
        //data2


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
                    pointStyle: 'rect',

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
                },
                animation: {
                    duration: 0
                }
            }
        });
        // myScatter.datasets[2].point.radius=140;
        // myscatter.update();
    </script>
    <script>
        window.setTimeout(function() {
            window.location.reload();
        }, 5000);
    </script>

</body>

</html>