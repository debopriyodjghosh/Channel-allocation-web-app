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
                //window.location.href = "device.html";
                </script>
        
   <?php } else {
        echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')javascript:history.go(-1);</script>";
    }
}

for ($i=0; $i<$priority_device; $i++) { //priority device
    $x_cod=rand(0, 200);
    $y_cod=rand(0, 200);
    $data_rate=rand(21, 30);
    
    $temp=$data_rate/20;
    $tollerence=1/(pow(2, $temp)-1);
    
    $allocation=0;
    $x3=pow(($x_cod-$base_x), 2); //x2-x1 sqr
    $y3=pow(($y_cod-$base_y), 2); //y2-y1 sqr
    $distance=sqrt($x3+$y3);

    //#echo $x_cod;
    #echo "<br>";
    #echo $y_cod;
    #echo "<br>";
    #echo $data_rate;
    #echo "<br>";
    #echo $tollerence;
    #echo "<br>";
    #echo $allocation;
    #echo "<br>";
    #echo $distance;
    #echo "<br>";

    $sql1 = "INSERT INTO device ( data_rate, x_cod, y_cod, distance, tollerence, allocation ) 
    VALUES ($data_rate, $x_cod, $y_cod, $distance, $tollerence, $allocation)";
    if ($conn->query($sql1)=== true) { ?>


<?php } else {
        echo "<script>alert('Error: ' . $sql1 . '<br>' . $conn->error')javascript:history.go(-1);</script>";
    }
}

//<script type="text/javascript"> 
#alert('Device Placed!');
//window.location.href = "device.html";
//</script>

for ($j=0; $j< ($device_count -$priority_device); $j++) { //non priority device
    $x_cod=rand(0, 200);
    $y_cod=rand(0, 200);
    $data_rate=rand(1, 20);
    
    $temp=$data_rate/20;
    $tollerence=1/(pow(2, $temp)-1);
    
    $allocation=0;
    $x3=pow(($x_cod-$base_x), 2); //x2-x1 sqr
    $y3=pow(($y_cod-$base_y), 2); //y2-y1 sqr
    $distance=sqrt($x3+$y3);
    
    $sql1 = "INSERT INTO device ( data_rate, x_cod, y_cod, distance, tollerence, allocation ) 
    VALUES ($data_rate, $x_cod, $y_cod, $distance, $tollerence, $allocation)";
    if ($conn->query($sql1)=== true) { ?>


<?php } else {
        echo "<script>alert('Error: ' . $sql1 . '<br>' . $conn->error')javascript:history.go(-1);</script>";
    }
}

//$conn->close();
?>