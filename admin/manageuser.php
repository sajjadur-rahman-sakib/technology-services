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

?>

<?php
include 'config.php';


if (isset($_POST['deletebtn'])) {
    $selectedId = $_POST['selectedId'];
    $sql = "DELETE FROM reservation WHERE id = $selectedId";
    mysqli_query($conn, $sql);
}

// Handle tracking update
if (isset($_POST['updatetrackingbtn'])) {
    $trackingId = $_POST['trackingId'];
    $trackingValue = mysqli_real_escape_string($conn, $_POST['trackingValue']);
    $sql = "UPDATE reservation SET tracking = '$trackingValue' WHERE id = $trackingId";
    mysqli_query($conn, $sql);
    // Optional: reload to show updated value
    echo "<meta http-equiv='refresh' content='0'>";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
    <link rel="stylesheet" href="approval.css">
    <style>
        /* Payment Status Styles */
        .payment-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            min-width: 70px;
            text-align: center;
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
        
        /* Table responsive adjustments */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        /* Make email column smaller */
        .content th:nth-child(4), 
        .content td:nth-child(4) {
            width: 150px;
            max-width: 150px;
            word-wrap: break-word;
        }
        
        /* Center align payment status column */
        .content th:nth-child(8), 
        .content td:nth-child(8) {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="contaner">
        <div class="navbar item">
            <div class="blank">
                <a href="./admin.php" class="btnhome"></a>
            </div>
            <h1>Manage User</h1>
            <div class="logout">
                <a href="">Logout</a>
            </div>
        </div>
        <div class="content item">
            <table>
                <thead>
                    <tr>
                        <th>User No.</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Problem</th>
                        <th>Tracking</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
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
                    
                    $result = mysqli_query($conn,$sql);
                    $no=1;
                    while($row=mysqli_fetch_assoc($result))
                    {
                        // Determine payment status display
                        $payment_status = $row['payment_status'] ? $row['payment_status'] : 'Not Paid';
                        
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
                        <td>'.$no.'</td>
                        <td>'.$row['name'].'</td>
                        <td>'.$row['phone'].'</td>
                        <td>'.$row['email'].'</td>
                        <td>'.$row['address'].'</td>
                        <td>'.$row['problem'].'</td>
                        <td>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="trackingId" value="'.$row['id'].'">
                                <select name="trackingValue" style="width:130px;">
                                    <option value="Order placed"'.($row['tracking']==='Order placed'?' selected':'').'>Order placed</option>
                                    <option value="In progress"'.($row['tracking']==='In progress'?' selected':'').'>In progress</option>
                                    <option value="Serviceing"'.($row['tracking']==='Serviceing'?' selected':'').'>Serviceing</option>
                                    <option value="Service complete"'.($row['tracking']==='Service complete'?' selected':'').'>Service complete</option>
                                    <option value="Ready for delivery"'.($row['tracking']==='Ready for delivery'?' selected':'').'>Ready for delivery</option>
                                    <option value="Delivered"'.($row['tracking']==='Delivered'?' selected':'').'>Delivered</option>
                                    
                                </select>
                                <button type="submit" name="updatetrackingbtn" style="background-color: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Update</button>
                            </form>
                        </td>
                        <td><span class="payment-status '.$status_class.'">'.htmlspecialchars($payment_status).'</span></td>
                        <td class="actions">
                                <form method="post" action="">
                                    <input type="hidden" name="selectedId" value="'.$row['id'].'">
                                    <button class="delete btn" name="deletebtn"  onclick="return confirmDelete(\''.$row['name'].'\')">Delete User</button>
                                </form>
                            </td>
                    </tr>';
                    $no++;
                    }
                    
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-right">
      </div>
    <script>
        function confirmDelete(name) {
            return confirm("Are you sure you want to delete user " + name + "?");
        }
    </script>
</body>
</html>
