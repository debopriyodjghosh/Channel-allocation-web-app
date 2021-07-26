<?php
include 'config.php';

$x_cod=rand(0,200);
$y_cod=rand(0,200);
$data_rate=rand(20,30);


$temp=$data_rate/20;
$tollerence=1/(pow(2, $temp)-1);

$allocation=0;


$distance=0;// print base co ordinate
//distance between base and device



    //echo $priority_device." ".$channel_count." ". $device_count." ".$base_x ." ".$base_y ;
    $sql = "INSERT INTO device (device_count, channel_count, priority_device, base_x, base_y ) 
            VALUES ($device_count, $channel_count, $priority_device, $base_x, $base_y)";
    if ($conn->query($sql)=== true) { ?>
       
        <script type="text/javascript"> 
                alert('Device Placed!'); 
                window.location.href = "#";
                </script>
        
   <?php } else {
        echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')javascript:history.go(-1);</script>";
    }

    
//$conn->close();
?>