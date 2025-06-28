<?php
session_start();
$username = $_SESSION['username'];

if (!isset($username)) {
    header('location:index.php');
}

if (isset($_GET['logout'])) {
    unset($username);
    session_destroy();
    header('location:index.php');
}

if (isset($_POST['submit'])){
    header('Location: logreport.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Log</title>
    <link rel="stylesheet" href="servicelog.css">
    <style>
        /* Payment Status Styles */
        .payment-status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            margin-left: 5px;
        }
        
        .status-paid {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status-unpaid {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }
    </style>
</head>
<body>
    <div class="contaner">
        <div class="navbar item">
            <div class="blank">
                <a href="./admin.php" class="backbtn"></a>
            </div>
            <h1>Service Log</h1>
            <div class="logout">
                <a href="">Logout</a>
            </div>
        </div>
        <div class="content item">

            <table>
                <thead>
                    <tr>
                        <th>Request No.</th>
                        <th>Request Info</th>
                        <th>Request Time</th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                        include 'config.php';
                        
                        // Updated query to include payment information
                        $sql = "SELECT r.*, p.transaction_id, p.amount, p.currency, p.status as payment_status, p.created_at as payment_date
                                FROM reservation r 
                                LEFT JOIN payments p ON r.id = p.reservation_id 
                                ORDER BY r.id DESC";

                        $result = mysqli_query($conn, $sql);
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        
                            // Determine payment status display
                            $payment_status = $row['payment_status'] ? $row['payment_status'] : 'Not Paid';
                            $amount = $row['amount'] ? '$' . number_format($row['amount'], 2) : 'N/A';
                            
                            // Set status class for styling
                            $status_class = '';
                            switch (strtolower($payment_status)) {
                                case 'paid':
                                case 'completed':
                                case 'success':
                                    $status_class = 'status-paid';
                                    break;
                                case 'pending':
                                    $status_class = 'status-pending';
                                    break;
                                case 'failed':
                                case 'cancelled':
                                    $status_class = 'status-failed';
                                    break;
                                default:
                                    $status_class = 'status-unpaid';
                            }
                        
                            echo '<tr>
                            <td>' . $no . '</td>
                            <td>
                                <p>User-Id: <span style="color: blue;">' . $row['id'] . '</span> 
                                User-Name: <span style="color: blue;"> ' . $row['name'] . '</span> 
                                Phone: <span style="color: blue;"> ' . $row['phone'] . '</span> 
                                Email: <span style="color: blue;"> ' . $row['email'] . '</span> 
                                Problem: <span style="color: blue;"> ' . $row['problem'] . '</span> requested a service.
                                <br><strong>Payment Status:</strong> <span class="payment-status '.$status_class.'">'.htmlspecialchars($payment_status).'</span>';
                                
                            if ($row['amount']) {
                                echo ' <strong>Amount:</strong> <span style="color: green; font-weight: bold;">'.$amount.'</span>';
                            }
                            
                            if ($row['transaction_id']) {
                                echo ' <strong>Transaction ID:</strong> <span style="color: #007bff;">'.$row['transaction_id'].'</span>';
                            }
                            
                            echo '</p>
                            </td>
                            <td>' . $row['submission_time'] . '</td>
                        </tr>';

                            $no++;
                        }
                    ?>


                </tbody>
            </table>

            <div style="width: 8%; /* Adjust width as needed */
                margin: 50px auto;">
                <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
                    <input style = "width: 90px;
                    height: 40px;
                     padding: 2px;
                      background-color: #4251aa;
                      color: #fff;
                       border-radius: 10px;
                       border: 1px solid black;" name="submit" type="submit" value="Print" class="printbtn">
                </form>
        
            </div>

        </div>
    </div>
    
</body>
</html>
