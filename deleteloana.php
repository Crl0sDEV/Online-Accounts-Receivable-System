<?php
include 'connection.php';
$conn = openCon();

    $id = $_GET['id'];
    $sql = "DELETE FROM loanamortization WHERE ID = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: loana.php?message=Deleted Successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    closeCon($conn);
?>