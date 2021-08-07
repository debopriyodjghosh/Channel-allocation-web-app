<?php
	include 'database.php';
    $q = "select channel_count FROM base LIMIT 1";
    $r = mysqli_query($conn,$q);
    while ($i = mysqli_fetch_array($r)) {
        $channel_count = $i['channel_count'];
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
echo "after initial:";
print_r($device_final);
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
echo "Aftre algo";
print_r($device_final);
for($i=0;$i<sizeof($device_final);$i++){
    $x=strval($device_final[$i]->allocation);
    echo nl2br("\n");
    echo " ".$x;
    $y= strval($device_final[$i]->dev_id);
    echo nl2br("\n");  
    echo " ".$y;
    // $sqlupdate="UPDATE device SET allocation = $x  WHERE dev_id ='$y'";
    // mysqli_query( $conn, $sqlupdate);
}
function delete(){

}
?>