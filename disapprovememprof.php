<?php
include 'connection.php';
$conn = openCon();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "SELECT memberid FROM loan_applications WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $memberid = $row['memberid'];
        
        $updateLoan = "UPDATE loan_applications SET status = 'disapproved' WHERE id = $id";
        $loanSuccess = mysqli_query($conn, $updateLoan);
        
        $updateMember = "UPDATE member_profiles SET status = 'disapproved' WHERE memberid = $memberid";
        $memberSuccess = mysqli_query($conn, $updateMember);
        
        if ($loanSuccess && $memberSuccess) {
            header("Location: memberapplication.php?message=Disapproved+Successfully");
            exit();
        } else {
            echo "Error updating records: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Could not find the loan application or member.";
    }
} else {
    echo "Error: No ID specified.";
}

closeCon($conn);
?>