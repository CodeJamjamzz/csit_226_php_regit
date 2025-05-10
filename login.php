<?php
session_start();
include 'connect.php'; // your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $query = "SELECT * FROM tbluser WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id']; // use the actual column name
        $_SESSION['username'] = $user['username'];

        header("Location: eventDashboard.php");
    } else {
        echo "<script>alert('No account found with those credentials.'); window.location.href='index.php';</script>";
    }
}
?>
