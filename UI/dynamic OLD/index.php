<!DOCTYPE html>
<html>

<head>
    <title>Channel Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
    <div style="margin: 8em auto auto 27em;width: 40%;">
        <div class="heading">
        <center><h1><b>Dynamic Channel Allocation </b></h1></center>
        </div>
        <hr>
        <div class="heading">
        <center><h3>Give Base Station Details to start the system</center></h3>
            <br>
        </div>
        <div class="heading">
            <form action="save_in.php" method="post">
            <div class="form-group">
                <label for="channel">Enter Number of Channels </label>
                <input type="number" name="channel_count" class="form-control" placeholder="Enter Number of Channels" required>
            </div>
                <!--<div class="row">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                </div> -->     <?php
                    //Base station coordinate
                    $base_x = rand(0, 200);
                    $base_y = rand(0, 200);
                    ?>
                <div class="form-group">
                <label for="channel">Base Location Coordinate </label>
                        <input class="form-control" type="text" placeholder="            ...............................     <?php echo $base_x ?> , <?php echo $base_y ?>     ..........................." readonly>
                        <input type="hidden" name="base_x" value="<?php echo $base_x ?>">
                        <input type="hidden" name="base_y" value="<?php echo $base_y ?>">
                    
                </div>
                <hr>
                <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg" name="submit">Next</button>
                </div>
            </form>
      
        </div>
    </div>
    <hr>
</body>

</html>