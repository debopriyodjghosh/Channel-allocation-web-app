<?php
header("Refresh:1");
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
//print_r($device_array);
$device1 = array();
$device2 = array();

for ($i = 0; $i < sizeof($device_array); $i++) {
    if ($device_array[$i]['data_rate'] > 20)
        array_push($device1, $device_array[$i]);
    else
        array_push($device2, $device_array[$i]);
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

    function __construct($d_id, $d_rate, $x_cod, $y_cod, $dis, $tol, $allo, $data_size, $priority, $data_rate_given, $time_required)
    {
        $this->dev_id = $d_id;
        $this->data_rate = $d_rate;
        $this->x_cod = $x_cod;
        $this->y_cod = $y_cod;
        $this->distance = $dis;
        $this->tollerence = $tol;
        $this->allocation = $allo;
        $this->data_size = $data_size;
        $this->priority = $priority;
        $this->data_rate_given = $data_rate_given;
        $this->time_required = $time_required;
    }
}

$device1object = array();
$device2object = array();

for ($i = 0; $i < sizeof($device1); $i++) {
    $device1object[$i] =
        new device(
            $device1[$i]['dev_id'],
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
for ($i = 0; $i < sizeof($device2); $i++) {
    $device2object[$i] =
        new device(
            $device2[$i]['dev_id'],
            $device2[$i]['data_rate'],
            $device2[$i]['x_cod'],
            $device2[$i]['y_cod'],
            $device2[$i]['distance'],
            $device2[$i]['tollerence'],
            $device2[$i]['allocation'],
            $device2[$i]['data_size'],
            $device2[$i]['priority'],
            $device2[$i]['data_rate_given'],
            $device2[$i]['time_required']
        );
}
//compare object data rate and sort
function comparator($object1, $object2)
{
    return $object1->data_rate > $object2->data_rate;
}
//compare function for priority
function comparator2($object1, $object2)
{
    return $object1->priority < $object2->priority;
}

function In($dis1, $dis2)
{ //function for calculating interference

    $value = $dis1 / $dis2;
    return pow($value, 4);
}


usort($device2object, 'comparator');
$device_final = array_merge($device1object, $device2object);
// echo "Initially";
// print_r($device_final);

$flag = 0; //to check that algo has not run a single time
for ($m = 0; $m < sizeof($device_final); $m++) {
    if ($device_final[$m]->allocation > 0) {
        $flag = 1;
        break;
    }
}
if(sizeof($device_final)<20 and $flag==0 ){
    echo "waiting for more devices to join............";
}
else{
    echo "Allocation started check chart.....";
}

if ($flag == 0 and sizeof($device_final) == 20) {
    //initial allocation
    for ($i = 0; $i < sizeof($device_final); $i++) {
        if ($i < sizeof($device1object) and ($i+1)<=$channel_count) {
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
                    $device_final[$i]->priority = $device_final[$i]->priority + 1; //check
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
$flag2 = 0; //to check that algo has not run a single time
for ($m = 0; $m < sizeof($device_final); $m++) {
    if ($device_final[$m]->allocation > 0) {
        $flag2 = 1;
        break;
    }
}
if($flag2>0){
for ($j = 1; $j <= $channel_count; $j++) {
    $xyz = array();
    //making a set of those device which are allocated to same channel
    for ($x = 0; $x < sizeof($device_final); $x++) {
        if ($device_final[$x]->allocation == $j) {
            array_push($xyz, (new device(
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
    $sum = 0;
    for ($x = 0; $x < sizeof($xyz); $x++) {
        for ($y = 0; $y < sizeof($xyz); $y++) {
            if ($x == $y) {
                $sum = $sum + 0.0;
            } else {
                $sum = $sum + In($xyz[$x]->distance, $xyz[$y]->distance);
            }
        }
        if ($sum == 0) {
            $xyz[$x]->data_rate_given = $xyz[$x]->data_rate;
        } else {
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
    if ($device_final[$i]->allocation > 0) {
        if ($device_final[$i]->time_required == 1000) {
            $device_final[$i]->time_required = ceil(($device_final[$i]->data_size) / ($device_final[$i]->data_rate_given)); 
        }
        // $pok = $device_final[$i]->time_required;
        $device_final[$i]->time_required =$device_final[$i]->time_required - 1;
        //echo "$pok";

    }
}

//echo "After algo";
//print_r($device_final);


//deleting a device using time required
for ($i = 0; $i < sizeof($device_final); $i++) {
    if ($device_final[$i]->time_required == 0) {
        //delete er query hobe
        $ch_no = $device_final[$i]->allocation;
        //allocation 0 hobe
        $device_final[$i]->allocation = 0;

        $alloc = array();
        //making a set of those device which are allocated to that channel
        for ($x = 0; $x < sizeof($device_final); $x++) {
            if ($device_final[$x]->allocation == $ch_no) {

                array_push($alloc, (new device(
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


        $id = $device_final[$i]->dev_id;
        $sql2 = "DELETE from device where dev_id='$id'";

        if ($conn->query($sql2) === true) {
        } else {
            echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')</script>";
        }

        unset($device_final[$i]); // remove item at index 0
        $device_final = array_values($device_final);


        $pri = array();
        //making a set of those device which are allocated to 0
        for ($x = 0; $x < sizeof($device_final); $x++) {
            if ($device_final[$x]->allocation == 0) {
                array_push($pri, (new device(
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

        usort($pri, 'comparator');
        $sum2 = 0;
        // print_r($alloc);
        for ($x = 0; $x < sizeof($pri); $x++) {
            for ($y = 0; $y < sizeof($alloc); $y++) {
                $sum2 = $sum2 + In($pri[$x]->distance, $alloc[$y]->distance);
            }
            echo $sum2;
            //allocation change next time
            if ($sum2 < $pri[$x]->tollerence) //check
            {
                $pri[$x]->allocation = $ch_no;
                // $id2 = $pri[$x]->dev_id;
                for ($z = 0; $z < sizeof($device_final); $z++){
                    if($device_final[$z]->dev_id==$pri[$x]->dev_id){
                        $device_final[$z]->allocation=$ch_no;
                    }
                }
                // $sql3 = "UPDATE device set allocation='$ch_no' where dev_id='$id2'";
                // if ($conn->query($sql) === true) {
                // } else {
                //     echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')</script>";
                // }
            }
        }
    }
}
//Updating database
for ($i = 0; $i < sizeof($device_final); $i++) {
    $d = $device_final[$i]->data_rate_given; //to be given
    $a = $device_final[$i]->allocation;
    $t = $device_final[$i]->time_required; //to be done
    $p = $device_final[$i]->priority; //to be done
    $id = $device_final[$i]->dev_id;

    $sql = "UPDATE `device` SET `allocation`='$a', `priority`='$p',`data_rate_given`='$d',`time_required`='$t' WHERE dev_id='$id' ";

    if ($conn->query($sql) === true) {
    } else {
        echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')</script>";
    }
}
}
?>