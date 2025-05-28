<?php
include 'connection.php';
$conn = openCon();

if (isset($_GET['memberid'])) {
    $memberId = intval($_GET['memberid']);

    if (!$memberId) {
        echo "Error: Invalid member ID.";
        exit();
    }

    mysqli_begin_transaction($conn);

    try {
        mysqli_query($conn, "DELETE FROM savings_transactions WHERE memberid = $memberId");

        mysqli_query($conn, "DELETE FROM fixed_transactions WHERE memberid = $memberId");
         
        mysqli_query($conn, "DELETE FROM time_deposit_transactions WHERE memberid = $memberId");

        mysqli_query($conn, "DELETE FROM savings_accounts WHERE memberid = $memberId");

        mysqli_query($conn, "DELETE FROM fixed_accounts WHERE memberid = $memberId");

        mysqli_query($conn, "DELETE FROM time_deposit_accounts WHERE memberid = $memberId");

        mysqli_query($conn, "DELETE FROM loan_applications WHERE memberid = $memberId");

        mysqli_query($conn, "DELETE FROM member_profiles WHERE memberid = $memberId");

        mysqli_commit($conn);

        header("Location: memberprofile.php?message=Member and all related data deleted successfully.");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error deleting data: " . $e->getMessage();
    }

    closeCon($conn);
} else {
    echo "Invalid request.";
}
?>