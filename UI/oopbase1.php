<?php
include 'config.php'; // DB Connection
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
class device{
                public $dev_id;
                public $data_rate;
                public $distance;
                public $tolerance;
                public $allocation;
                function __construct($d_id, $d_rate, $dis, $tol, $allo){
                    $this->dev_id=$d_id;
                    $this->data_rate=$d_rate;
                    $this->distance=$dis;
                    $this->tolerance=$tol;
                    $this->allocation=$allo;
                }
            }
$device1=array();
$device2=array();
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
                    $dev_id=uniqid('dev');
                    $device1[$i] = new device ( $dev_id, $data_rate, $distance, $tollerence, $allocation );
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
                    $dev_id=uniqid('dev');
                    $device2[$j] = new device ( $dev_id, $data_rate, $distance, $tollerence, $allocation );
            }
function comparator($object1, $object2) {
        return $object1->data_rate > $object2->data_rate;
}  
function In($dis1,$dis2){ //function for calculating interference
    
    $value=$dis1/$dis2;
    return pow($value,4);
    
}   
usort($device2, 'comparator');      
$device_final=array_merge($device1,$device2);
//print device table before allocation to be done
// print_r($device_final);
//initial allocation
for($i=0;$i<$device_count;$i++)
   {
       if($i<$priority_device ){
        $device_final[$i]->allocation=$i+1;
       }
       else{
        $device_final[$i]->allocation=0;
       }
   }
   for($i=0;$i<$device_count;$i++){
    echo $device_final[$i]->allocation;
}
echo nl2br(" after algooooooooooooooooooooooooooooo");
for($i=$priority_device;$i<$device_count;$i++){
    for($c=1;$c<=$channel_count;$c++){
        $device_final[$i]->allocation=$c;
        $D= array();
        //making a set of those device which are allocated to same channel
        for($x=0;$x<$device_count;$x++){
            if($device_final[$x]->allocation==$c){
                array_push($D,(new device($device_final[$x]->dev_id, $device_final[$x]->data_rate, $device_final[$x]->distance,$device_final[$x]->tolerance, $device_final[$x]->allocation)));
            }
        }
        for ($x=0;$x<sizeof($D);$x++)
        {
            $sum=0.0;
            for ($y=0;$y<sizeof($D);$y++)
            {   
                if($x==$y){
                    $sum=$sum+0.0;
                }
                else{
                $sum=$sum+In($D[$x]->distance,$D[$y]->distance);
                }
            }
            //checking if sum of interference greater than tolerance
            if ($sum>$D[$x]->tolerance){
                $device_final[$i]->allocation=0;
                break;
            }
            
        }
        if($device_final[$i]->allocation==$c)
        break;
        
    }

}
for($i=0;$i<$device_count;$i++){
    echo $device_final[$i]->allocation;
}
//print device table after allocation to be done
// print_r($device_final);
?>

<?php
$conn->close();
?>

