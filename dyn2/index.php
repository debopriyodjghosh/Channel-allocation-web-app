<!DOCTYPE html>
<html>

<head>
    <title>Channel Page</title>
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <div class="login">
        <div class="heading">
            <h2>Dynamic Channel Allocation </h2>
        </div>
        <hr>
        <div class="heading">
            <h3>Give Base Station Details to start the system</h3>
        </div>
        <div class="heading">
            <form action="save_in.php" method="post">
              
                <div class="row">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="number" name="channel_count" class="form-control" placeholder="Enter Number of Channels" required>
                    </div>
                </div>      <?php
                    //Base station coordinate
                    $base_x = rand(0, 200);
                    $base_y = rand(0, 200);
                    ?>
                <div class="row">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input class="form-control" type="text" placeholder="Base Location Coordinate [<?php echo $base_x ?> ,  <?php echo $base_y ?>]" readonly>
                        <input type="hidden" name="base_x" value="<?php echo $base_x ?>">
                        <input type="hidden" name="base_y" value="<?php echo $base_y ?>">
                    </div>
                </div>
                <button type="submit" name="submit" class="float">Next</button>

            </form>
      
        </div>
    </div>
    <hr>
</body>

</html>