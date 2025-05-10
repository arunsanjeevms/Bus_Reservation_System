<?php
    require '_functions.php';
    $conn = db_connect();    

    if(!$conn) 
        die("Connection Failed");

    if(isset($_GET["pnr"]))
    {
        $pnr = $_GET["pnr"];

        $route_id = get_from_table($conn, "bookings", "booking_id", $pnr, "route_id");

        $customer_id = get_from_table($conn, "bookings", "booking_id", $pnr, "customer_id");

        $customer_name = get_from_table($conn, "customers", "customer_id", $customer_id, "customer_name");

        $customer_phone = get_from_table($conn, "customers", "customer_id", $customer_id, "customer_phone");
        
        $customer_route = get_from_table($conn, "bookings", "booking_id", $pnr, "customer_route");

        $booked_amount = get_from_table($conn, "bookings", "booking_id", $pnr, "booked_amount");

        $booked_seat = get_from_table($conn, "bookings", "booking_id", $pnr, "booked_seat");

        $booked_timing = get_from_table($conn, "bookings", "booking_id", $pnr, "booking_created");

        $dep_date = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_date");

        $dep_time = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_time");

        $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <title>Document</title>
    <style>
        li{
            list-style-type: none;
            padding-left: 0;
        }
    </style>
</head>

<body>
<!-- <li>
                            <strong>PNR : </strong>
                            <?php echo $pnr; ?>
                        </li>
                        <li>
                            <strong>Customer Name : </strong>
                            <?php echo $customer_name; ?>
                        </li>
                        <li>
                            <strong>Customer Phone : </strong>
                            <?php echo $customer_phone; ?>
                        </li>
                        <li>
                            <strong>Route : </strong>
                            <?php echo $customer_route; ?>
                        </li>
                        <li>
                            <strong>Bus Number : </strong>
                            <?php echo $bus_no; ?>
                        </li>
                        <li>
                            <strong>Booked Seat Number : </strong>
                            <?php echo $booked_seat; ?>
                        </li>
                        <li>
                            <strong>Departure Date : </strong>
                            <?php echo $dep_date; ?>
                        </li>
                        <li>
                            <strong>Departure Time : </strong>
                            <?php echo $dep_time; ?>
                        </li>
                        <li>
                            <strong>Booked Timing : </strong>
                            <?php echo $booked_timing; ?>
                        </li> -->
                        


<div class="container">
    <div class="row">
        <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <address>
                        <strong>S B T B S</strong>
                        <br>
                        Detroit, Mi 4646
                        
                    </address>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                   
                    <p>
                        <em><b>Booked On:</b> <br><?php echo $booked_timing; ?></em>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="text-center">
                    <h4><b>PNR CODE:</b> <?php echo $pnr; ?></h5>
                    <h2>Ticket Details</h2>
                </div>
                </span>
                <table class="table table-hover table-striped">
                    <!-- <thead>
                        <tr>
                            <td>   </td>
                            <td>   </td>
                            <th></th>
                           
                            <th class="text-center"></th>
            
                        </tr>
                    </thead> -->
                    <tbody>
                        <tr>
                            
                            <td class="col-md-6"><em><b>Customer Name:</b></em></h4></td>
                           
                            <td class="col-md-6 text-center"><?php echo $customer_name; ?></td>
                          
                        </tr>
                        <tr>
                          
                            <td class="col-md-6"><em><b>Contact Details:</b></em></h4></td>
                    
                            <td class="col-md-6 text-center"><?php echo $customer_phone; ?></td>
                           
                        </tr>

                        <tr>
                            
                            <td class="col-md-6"><em><b>Route:</b></em></h4></td>
                    
                            <td class="col-md-6 text-center"><?php echo $customer_route; ?></td>
                           
                        </tr>

                        
                        <tr>
                           
                            <td class="col-md-6"><em><b>Bus Number:</b></em></h4></td>
                    
                            <td class="col-md-6 text-center"><?php echo $bus_no; ?></td>
                           
                        </tr>

                        <tr>
                           
                            <td class="col-md-6"><em><b>Seat Number:</b></em></h4></td>
                    
                            <td class="col-md-6 text-center"><?php echo $booked_seat; ?></td>
                           
                        </tr>

                        <tr>
                           
                            <td class="col-md-6"><em><b>Departure Date:</b></em></h4></td>
                    
                            <td class="col-md-6 text-center"><?php echo $dep_date; ?></td>
                           
                        </tr>

                        <tr>
                            
                            <td class="col-md-6"><em><b>Departure Time:</b></em></h4></td>
                    
                            <td class="col-md-6 text-center"><?php echo $dep_time; ?></td>
                           
                        </tr>

                    </tbody>
                </table>
                <!-- <div>
                    <h1 style="text-align:center;">
                        Thank you for your order.
                    </h1>
                    
                </div> -->

                
            </div>
        </div>
    </div>

    <div style="margin-top: 3rem;">    
        <button class="btn btn-primary">Print</button>
    </div>

<script>
const printBtn = document.querySelector("button");

printBtn.addEventListener("click", () => window.print());
</script>

<style>
    body {
    margin-top: 20px;
}
</style>
</body>
</html>