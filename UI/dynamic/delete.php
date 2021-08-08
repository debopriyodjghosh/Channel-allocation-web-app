<?php

include 'database.php';

function delete(){
    // $channel_number=channel number from the device 
    //find all the devices on that channel
    //sort all the devices with allocated=0 w.r.t priority
    //now calculate sum of interference for all the sorted devices one by one if sum<tolerance then allocate and break
    //else continue for all other devices
}

//how to find the time required
//find all the devices allocated in channel 1 to n
//then find sum of interference for device1
//then find snr=i/sum of interfrence
//then find 20*log2(1+snr)

?>