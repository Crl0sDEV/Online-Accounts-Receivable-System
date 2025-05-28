<?php
include 'connection.php';
$conn = openCon();

    $id = $_GET['id'];
    $sql = "DELETE FROM loan_types WHERE ID = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: loant.php?message=Loan Type Deleted Successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    closeCon($conn);
?>