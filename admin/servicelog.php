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
                        $sql = "SELECT * FROM reservation";

                        $result = mysqli_query($conn, $sql);
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        
                            echo '<tr>
                            <td>' . $no . '</td>
                            <td>
                                <p>User-Id: <span style="color: blue;">' . $row['id'] . '</span> 
                                User-Name: <span style="color: blue;"> ' . $row['name'] . '</span> 
                                Phone: <span style="color: blue;"> ' . $row['phone'] . '</span> 
                                Email: <span style="color: blue;"> ' . $row['email'] . '</span> 
                                Problem: <span style="color: blue;"> ' . $row['problem'] . '</span> requested a service.
                                </p>
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