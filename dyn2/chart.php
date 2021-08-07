<?php

include 'database.php';

//Base station coordinate fetch to be done
$device_count=20;
//priority device
$q = "select channel_count, x_cod, y_cod FROM base";
$r = mysqli_query($conn,$q);
while ($i = mysqli_fetch_array($r)) {
    $channel_count = $i['channel_count'];
    $base_x = $i['x_cod'];
    $base_y = $i['y_cod'];
}
$query = 'SELECT * FROM device';

$result = mysqli_query( $conn, $query );
$device_array = array();
while( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
array_push( $device_array, $row );
}
// print_r($device_array);
$device1=array();
$device2=array();
for($i=0;$i<sizeof($device_array);$i++){
if($device_array[$i]['data_rate']>20)
    array_push($device1,$device_array[$i]);
else
    array_push($device2,$device_array[$i]);   
}
class device
{
    public $dev_id;
    public $data_rate;
    public $x_cod;
    public $y_cod;
    public $distance;
    public $tollerence;
    public $allocation;
    public $data_size;
    public $priority;

    function __construct($d_id, $d_rate,$x_cod,$y_cod, $dis, $tol, $allo, $data_size, $priority)
    {
        $this->dev_id = $d_id;
        $this->data_rate = $d_rate;
        $this->x_cod=$x_cod;
        $this->y_cod=$y_cod;
        $this->distance = $dis;
        $this->tollerence = $tol;
        $this->allocation = $allo;
        $this->data_size=$data_size;
        $this->priority=$priority;
    }
}
$device1object=array();
$device2object=array();
for($i=0;$i<sizeof($device1);$i++){
    $device1object[$i] = new device($device1[$i]['dev_id'],$device1[$i]['data_rate'],$device1[$i]['x_cod'],$device1[$i]['y_cod'],$device1[$i]['distance'],$device1[$i]['tollerence'],$device1[$i]['allocation'],$device1[$i]['data_size'],$device1[$i]['priority']);
}
for($i=0;$i<sizeof($device2);$i++){
    $device2object[$i] = new device($device2[$i]['dev_id'],$device2[$i]['data_rate'],$device2[$i]['x_cod'],$device2[$i]['y_cod'],$device2[$i]['distance'],$device2[$i]['tollerence'],$device2[$i]['allocation'],$device2[$i]['data_size'],$device2[$i]['priority']);
}
function comparator($object1, $object2)
{
    return $object1->data_rate > $object2->data_rate;
}
usort($device2object, 'comparator');
$device_final = array_merge($device1object, $device2object);
echo "Initially";
print_r($device_final);
function In($dis1, $dis2)
{ //function for calculating interference

    $value = $dis1 / $dis2;
    return pow($value, 4);
}
//iinitial allocation
for ($i = 0; $i < sizeof($device_final); $i++) {
    if ($i < sizeof($device1object)) {
        $device_final[$i]->allocation = $i + 1;
    } else {
        $device_final[$i]->allocation = 0;
    }
}
//echo "after initial:";
//print_r($device_final);
//algorithm starts here

for ($i = sizeof($device1object); $i < sizeof($device_final); $i++) {
    for ($c = 1; $c <= $channel_count; $c++) {
        $device_final[$i]->allocation = $c;
        $D = array();
        
        //making a set of those device which are allocated to same channel
        for ($x = 0; $x < sizeof($device_final); $x++) {
            if ($device_final[$x]->allocation == $c) {
                array_push($D, (new device($device_final[$x]->dev_id, $device_final[$x]->data_rate,$device_final[$x]->x_cod,$device_final[$x]->y_cod, $device_final[$x]->distance, $device_final[$x]->tollerence, $device_final[$x]->allocation,$device_final[$x]->data_size,$device_final[$x]->priority)));
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
            if ($sum > $D[$x]->tollerence) {
                $device_final[$i]->allocation = 0;
                break;
            }
            else{
                $snr=1/$sum;
                $log=log(($snr+1),2);
                $data_rate_given = 20 * $log;
            }
        }
        if ($device_final[$i]->allocation == $c)
            break;
    }
}
//echo "After algo";
//print_r($device_final);
// for($i=0;$i<sizeof($device_final);$i++){
//     $x=strval($device_final[$i]->allocation);
//     echo nl2br("\n");
//     echo " ".$x;
//     $y= strval($device_final[$i]->dev_id);
//     echo nl2br("\n");  
//     echo " ".$y;
//     // $sqlupdate="UPDATE device SET allocation = $x  WHERE dev_id ='$y'";
//     // mysqli_query( $conn, $sqlupdate);
// }


//end algo

//print_r($device_final);
//chart start
$x = [];
$y = [];
$x1 = [];
$y1 = [];

for ($i = 0; $i < $device_count; $i++) 
{
    if ($device_final[$i]->allocation > 0) {
        array_push($x, $device_final[$i]->x_cod);
        array_push($y, $device_final[$i]->y_cod);
    }  //allocated to chart
    else {
        array_push($x1, $device_final[$i]->x_cod);
        array_push($y1, $device_final[$i]->y_cod);
    }//requested to chart
}

//inserting device into data base
// for ($i = 0; $i < $device_count; $i++) {
//     //$a = $device_final[$i]->data_rate;
//     //$b=$device_final[$i]->x_cod ;
//     //$c=$device_final[$i]->y_cod;
//     //$d = $device_final[$i]->distance;
//     //$e = $device_final[$i]->tollerence;
//     //$f = $device_final[$i]->allocation;

//     $sql = "INSERT INTO device ( data_rate, distance, tollerence, allocation) 
//     VALUES ('$a','$d','$e','$f')";

//     if ($conn->query($sql) === true) { ?>
<!-- //         <script type="text/javascript">
//             //alert('Devices Placed!');
//             //window.location.href = "device.html";
//         </script> -->
<!-- // <#?php } else { -->
<!-- //         echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')</script>"; -->
<!-- //     }
// }
// ?> -->

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
    <!--<meta http-equiv="refresh" content="5">-->
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
    <!-- <script>
        window.setTimeout(function() {
            window.location.reload();
        }, 5000);
    </script> -->

</body>

</html>