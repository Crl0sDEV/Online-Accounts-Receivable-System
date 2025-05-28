<?php
include 'connection.php';
$conn = openCon();

if (isset($_POST['approve'])) {
    $id = $_POST['id'];

    $query = "SELECT memberid FROM loan_applications WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $memberid = $row['memberid'];

    $updateLoan = "UPDATE loan_applications SET status = 'approved' WHERE id = $id";
    mysqli_query($conn, $updateLoan);

    $updateMember = "UPDATE member_profiles SET status = 'approved' WHERE memberid = $memberid";
    mysqli_query($conn, $updateMember);

    header("Location: memberapplication.php");
    exit();
}

closeCon($conn);
