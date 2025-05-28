<?php
include 'connection.php';
$conn = openCon();

    $id = $_GET['id'];
    $sql = "DELETE FROM ownerusers WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: usermanagement.php?message=Account Deleted Successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    closeCon($conn);
?>