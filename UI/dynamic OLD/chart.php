<?php

include 'database.php';

//Base station coordinate fetch to be done
$algo_run_count=10;
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
//print_r($device_array);
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
    public $data_rate_given;
    public $time_required;

    function __construct($d_id, $d_rate,$x_cod,$y_cod, $dis, $tol, $allo, $data_size, $priority, $data_rate_given, $time_required)
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
        $this->data_rate_given=$data_rate_given;
        $this->time_required=$time_required;
    }
}

$device1object=array();
$device2object=array();

for($i=0;$i<sizeof($device1);$i++){
    $device1object[$i] = 
    new device($device1[$i]['dev_id'],
    $device1[$i]['data_rate'],
    $device1[$i]['x_cod'],
    $device1[$i]['y_cod'],
    $device1[$i]['distance'],
    $device1[$i]['tollerence'],
    $device1[$i]['allocation'],
    $device1[$i]['data_size'],
    $device1[$i]['priority'],
    $device1[$i]['data_rate_given'],
    $device1[$i]['time_required']
    
);
}
for($i=0;$i<sizeof($device2);$i++){
    $device2object[$i] = 
    new device($device2[$i]['dev_id'],
    $device2[$i]['data_rate'],
    $device2[$i]['x_cod'],
    $device2[$i]['y_cod'],
    $device2[$i]['distance'],
    $device2[$i]['tollerence'],
    $device2[$i]['allocation'],
    $device2[$i]['data_size'],
    $device2[$i]['priority'],
    $device2[$i]['data_rate_given'],
    $device2[$i]['time_required']);
}
//compare object data rate and sort
function comparator($object1, $object2)
{
    return $object1->data_rate > $object2->data_rate;
}

function comparator2($object1, $object2)
{
    return $object1->priority < $object2->priority;
}



usort($device2object, 'comparator');
$device_final = array_merge($device1object, $device2object);
$flag=0;
for($m=0;$m<sizeof($device_final);$m++)
{
    if($device_final[$m]->allocation!=0)
    {
        $flag=1;
        break;
    }

}
// echo "Initially";
// print_r($device_final);

function In($dis1, $dis2)
{ //function for calculating interference

    $value = $dis1 / $dis2;
    return pow($value, 4);
}
//flag for first time algo run
if ($flag==0 AND sizeof($device_final)==10) {
    //initial allocation
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
                    array_push($D, (new device(
                        $device_final[$x]->dev_id,
                        $device_final[$x]->data_rate,
                        $device_final[$x]->x_cod,
                        $device_final[$x]->y_cod,
                        $device_final[$x]->distance,
                        $device_final[$x]->tollerence,
                        $device_final[$x]->allocation,
                        $device_final[$x]->data_size,
                        $device_final[$x]->priority,
                        $device_final[$x]->data_rate_given,
                        $device_final[$x]->time_required
                    )));
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
                    $device_final[$i]->priority=$device_final[$i]->priority+1;//check
                    break;
                }
            }
            if ($device_final[$i]->allocation == $c) {
                break;
            }
        }
    }
    //end algo
}

   //for how much data_rate i am getting
   for ($j=1;$j<=$channel_count;$j++) {
       $xyz=array();
       //making a set of those device which are allocated to same channel
       for ($x=0;$x<sizeof($device_final);$x++) {
           if ($device_final[$x]->allocation==$j) {
               array_push($xyz, (new device($device_final[$x]->dev_id,
                                 $device_final[$x]->data_rate,
                                  $device_final[$x]->x_cod, 
                                  $device_final[$x]->y_cod, 
                                  $device_final[$x]->distance,
                                   $device_final[$x]->tollerence,
                                    $device_final[$x]->allocation,
                                     $device_final[$x]->data_size,
                                      $device_final[$x]->priority,
                                      $device_final[$x]->data_rate_given,
                                      $device_final[$x]->time_required)));
           }
       }
       $sum=0;
       for ($x = 0; $x < sizeof($xyz); $x++) {
           for ($y = 0; $y < sizeof($xyz); $y++) {
               if ($x == $y) {
                   $sum = $sum + 0.0;
               } else {
                   $sum = $sum + In($xyz[$x]->distance, $xyz[$y]->distance);
               }
           }
           if($sum==0)
           {
               $xyz[$x]->data_rate_given=$xyz[$x]->data_rate;
           }
           else{
               $snr = 1 / $sum;
               $log = log(($snr + 1), 2);
               $xyz[$x]->data_rate_given = 20 * $log;
           }
               $sum = 0.0;
           
       }
       for ($p = 0; $p < sizeof($xyz); $p++) {
           for ($q = 0; $q < sizeof($device_final); $q++) {
               if ($device_final[$q]->dev_id == $xyz[$p]->dev_id) {
                   $device_final[$q]->data_rate_given = $xyz[$p]->data_rate_given;
               }
           }
       }
   }
   //time required calculation

   for ($i = 0; $i < sizeof($device_final); $i++) {
    if($device_final[$i]->allocation!=0)
    {
        if($device_final[$i]->time_required==1000){
        $device_final[$i]->time_required=ceil(($device_final[$i]->data_size)/($device_final[$i]->data_rate_given));//why zero dhukche
      } 
       $pok=$device_final[$i]->time_required;
        $device_final[$i]->time_required=$pok-1;
        //echo "$pok";

    }
}

//echo "After algo";
//print_r($device_final);

//update in database replace code of insert with chnge
//inserting device into data base
for ($i = 0; $i < sizeof($device_final) ; $i++) {
    $d = $device_final[$i]->data_rate_given; //to be given
    $a= $device_final[$i]->allocation;
    $t= $device_final[$i]->time_required; //to be done
    $p=$device_final[$i]->priority; //to be done
    $id =$device_final[$i]->dev_id;

    $sql = "UPDATE `device` SET `allocation`='$a', `priority`='$p',`data_rate_given`='$d',`time_required`='$t' WHERE dev_id='$id' ";

if ($conn->query($sql) === true) {
       
} else {
        echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')</script>";
    }
 }

//print_r($device_final);
//chart start
$x = [];
$y = [];
$x1 = [];
$y1 = [];

for ($i = 0; $i < sizeof($device_final); $i++) 
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
    <meta http-equiv="refresh" content="2">
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



<?php
//deleting an device using time required
  for ($i = 0; $i < sizeof($device_final); $i++) {
    if ($device_final[$i]->time_required==0 ) {
        //delete er query hobe
        $ch_no=$device_final[$i]->allocation;
        //allocation 0 hobe
        $device_final[$i]->allocation=0;

        $alloc=array();
       //making a set of those device which are allocated to that channel
       for ($x=0;$x<sizeof($device_final);$x++) {
           if ($device_final[$x]->allocation==$ch_no) {
               
               array_push($alloc, (new device($device_final[$x]->dev_id,
                                 $device_final[$x]->data_rate,
                                  $device_final[$x]->x_cod, 
                                  $device_final[$x]->y_cod, 
                                  $device_final[$x]->distance,
                                   $device_final[$x]->tollerence,
                                    $device_final[$x]->allocation,
                                     $device_final[$x]->data_size,
                                      $device_final[$x]->priority,
                                      $device_final[$x]->data_rate_given,
                                      $device_final[$x]->time_required)));
           }
       }
       
      
       $id=$device_final[$i]->dev_id;
       $sql2="DELETE from device where dev_id='$id'";
       
        if ($conn->query($sql2) === true) {
            
        } else {
                echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')</script>";
            }

            array_splice($device_final, $i, 1);
        

       $pri=array();
       //making a set of those device which are allocated to 0
       for ($x=0;$x<sizeof($device_final);$x++) {
           if ($device_final[$x]->allocation==0) {
               array_push($pri, (new device($device_final[$x]->dev_id,
                                 $device_final[$x]->data_rate,
                                  $device_final[$x]->x_cod, 
                                  $device_final[$x]->y_cod, 
                                  $device_final[$x]->distance,
                                   $device_final[$x]->tollerence,
                                    $device_final[$x]->allocation,
                                     $device_final[$x]->data_size,
                                      $device_final[$x]->priority,
                                      $device_final[$x]->data_rate_given,
                                      $device_final[$x]->time_required)));
           }
       }
      
       usort($pri, 'comparator2');
       $sum2=0;
      // print_r($alloc);
       for ($x=0;$x<sizeof($pri);$x++) 
       {
        for ($y=0;$y<sizeof($alloc);$y++) {
           $sum2=$sum2 + In($pri[$x]->distance, $alloc[$y]->distance);
        }
        echo $sum2;
        //allocation chnge next time
        if($sum2 < $pri[$x]->tollerence)//check
        {
            $pri[$x]->allocation=$ch_no;
            $id2=$pri[$x]->dev_id;
            
            $sql3="UPDATE device set allocation='$ch_no' where dev_id='$id2'";
            if ($conn->query($sql) === true) {
      
            } else {
                    echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')</script>";
                }
            
        }
       }

      
        
    } 
}

?>