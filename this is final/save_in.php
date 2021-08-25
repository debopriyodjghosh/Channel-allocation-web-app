<?php 
include 'database.php';

//device count and channel count
if (isset($_POST)) {
    $base_x=$_POST['base_x'];
    $base_y=$_POST['base_y'];
    $channel_count = $_POST['channel_count'];
   
}
 //clear base id
 $sql1="DELETE FROM base limit 1";
 if ($conn->query($sql1))
 {}
 //delete devices
 $sql1="DELETE FROM device";
 mysqli_query($conn,$sql1);
 $sql1="ALTER TABLE device AUTO_INCREMENT = 1";
 if ($conn->query($sql1))
 {}
 
//insert new
$sql= "INSERT INTO base (x_cod, y_cod, channel_count) VALUES ('$base_x', '$base_y', '$channel_count')";
if ($conn->query($sql) === true) { ?>

<script type="text/javascript">
            //alert('Devices Placed!');
            window.location.href = "client.html";
        </script>
    
<?php } else {
        echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error')</script>";
    }


    ?>