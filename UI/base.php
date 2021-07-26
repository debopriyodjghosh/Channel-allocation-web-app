<?php
include 'config.php';

$base_x=rand(0,200);
$base_y=rand(0,200);

if (isset($_POST)) {
    $device_count =  $_POST['device_count'];
    $channel_count = $_POST['channel_count'];
    $priority_device =  $_POST['priority_device'];
    //echo $priority_device." ".$channel_count." ". $device_count." ".$base_x ." ".$base_y ;
    //inser or update, if exists then update, if not then insert base
    $sql = "INSERT INTO base (device_count, channel_count, priority_device, base_x, base_y ) 
            VALUES ($device_count, $channel_count, $priority_device, $base_x, $base_y)";
    if ($conn->query($sql)=== true) { ?>
       
        <script type="text/javascript"> 
                alert('Base Station Placed!'); 
                window.location.href = "device.html";
                </script>;
        
   <?php } else {
        echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')javascript:history.go(-1);</script>";
    }
}
    
//$conn->close();
?>