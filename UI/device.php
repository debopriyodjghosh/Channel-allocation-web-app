<?php
include 'config.php';

$arr_dev_id=[];
$arr_data_rate=[];
$arr_x_cod=[];
$arr_y_cod=[];
$arr_distance=[];
$arr_tollerence=[];
$arr_allocation=[];
$arr=[];

  $sql =mysqli_query($conn,"SELECT * from device where data_rate < '20' order by data_rate");
  
      /* while($res=mysqli_fetch_assoc($sql)){
       
        array_push($arr_dev_id,$res['dev_id']);
        array_push($arr_data_rate,$res['data_rate']);
        array_push($arr_x_cod,$res['x_cod']);
        array_push($arr_y_cod,$res['y_cod']);
        array_push($arr_distance,$res['distance']);
        array_push($arr_tollerence,$res['tollerence']);
        array_push($arr_allocation,$res['allocation']);
        


       }*/
       
     
//foreach 

/*
print_r($arr_dev_id);
print_r($arr_data_rate);
print_r($arr_x_cod);
print_r($arr_y_cod);
print_r($arr_distance);
print_r($arr_tollerence);
print_r($arr_allocation);
*/

?>