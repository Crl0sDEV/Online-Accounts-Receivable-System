<?php
include 'connection.php';
$conn = openCon();

if (isset($_GET['id'])) {
    $loanId = intval($_GET['id']);

    $getMemberIdQuery = "SELECT memberid FROM loan_applications WHERE id = $loanId";
    $result = mysqli_query($conn, $getMemberIdQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $memberId = intval($row['memberid']);

        if (!$memberId) {
            echo "Error: Loan record found, but member ID is missing or null.";
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

            header("Location: registered.php?message=Member and all related data deleted successfully.");
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Error deleting data: " . $e->getMessage();
        }
    } else {
        echo "Member ID not found for loan application ID: $loanId";
    }

    closeCon($conn);
} else {
    echo "Invalid request.";
}
?>
