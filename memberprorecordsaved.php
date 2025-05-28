<?php
include 'connection.php';
$conn = openCon();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['name'];
    $address = $_POST['address'];
    $birthdate = $_POST['bday'];
    $sex = $_POST['sex'];
    $email = $_POST['email'];
    $date_applied = date('Y-m-d');

    if ($_POST['formSource'] === 'createmem') {
        $application_type = 'Online';
    } else {
        $application_type = $_POST['application_type']; 
    }

     $sql = "INSERT INTO member_profiles (fullname, address, birthdate, sex, email, application_type, date_applied) 
            VALUES ('$fullname', '$address', '$birthdate', '$sex', '$email', '$application_type', '$date_applied')";

    if (mysqli_query($conn, $sql)) {
        $memberid = mysqli_insert_id($conn);
        $status = "Pending"; 


        $sql_loan = "INSERT INTO loan_applications (memberid, fullname, email, status, application_type, date_applied) 
                     VALUES ('$memberid', '$fullname', '$email', '$status', '$application_type', '$date_applied')";

        mysqli_query($conn, $sql_loan);

        if ($_POST['formSource'] === 'createmem') {
            echo "<script>
                    window.location.href='createuseracc.php?name=" . urlencode($fullname) . "&email=" . urlencode($email) . "'; 
                  </script>"; 
        } else {
            echo "<script>
                    alert('Record Saved!');
                    window.location.href='memberprofile.php';  
                  </script>"; 
        }
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>