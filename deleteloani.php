<?php
include 'connection.php';
$conn = openCon();

    $id = $_GET['id'];
    $sql = "DELETE FROM loaninterest WHERE ID = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: loani.php?message=Loan Interest Deleted Successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    closeCon($conn);
?>