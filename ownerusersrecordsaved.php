<?php
    include 'connection.php';
    $conn = openCon();

    if(isset($_POST['btnSubmit']))
    {
        $fullname = $_POST['name'];
        $username = $_POST['uname'];
        $password = $_POST['pass'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO ownerusers (fullname, username, password, email, role) 
        VALUES 
        ('$fullname','$username','$password','$email', '$role')";

if (mysqli_query($conn, $sql)) {

    echo "<script>
            window.location.href='usermanagement.php'; 
          </script>"; 
} 
} else {
echo 'Error: ' . mysqli_error($conn);
}

mysqli_close($conn);
?>