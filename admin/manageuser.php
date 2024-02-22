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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
    <link rel="stylesheet" href="approval.css">
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    include 'config.php';

                    $sql = "SELECT * FROM reservation";
                    
                    $result = mysqli_query($conn,$sql);
                    $no=1;
                    while($row=mysqli_fetch_assoc($result))
                    {
                        echo '<tr>
                        <td>'.$no.'</td>
                        <td>'.$row['name'].'</td>
                        <td>'.$row['phone'].'</td>
                        <td>'.$row['email'].'</td>
                        <td>'.$row['address'].'</td>
                        <td>'.$row['problem'].'</td>
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