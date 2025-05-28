<?php
    include 'connection.php';
    $conn = openCon();

    if(isset($_POST['btnSubmit']))
    {
        $memberid = $_POST['memberid'];
        $fullname = $_POST['name'];
        $email = $_POST['email'];
        $status = "Pending";
        $application = $_POST['application_type'];
        $date_applied = date('Y-m-d');

        $sql = "INSERT INTO loan_applications (memberid, fullname, email, status, application_type, date_applied) 
        VALUES 
        ('$memberid','$fullname','$email','$status', '$application_type', '$date_applied')";

if (mysqli_query($conn, $sql)) {

    echo "<script>
            window.location.href='loanapplication.php'; 
          </script>"; 
} 
} else {
echo 'Error: ' . mysqli_error($conn);
}

mysqli_close($conn);
?>