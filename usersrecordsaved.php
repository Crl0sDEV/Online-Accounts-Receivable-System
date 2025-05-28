<?php
    include 'connection.php';
    $conn = openCon();

    if(isset($_POST['btnSubmit']))
    {
        $fullname = $_POST['name'];
        $username = $_POST['uname'];
        $password = $_POST['pass'];
        $email = $_POST['email'];

         $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if (isset($_POST['formSource']) && $_POST['formSource'] === 'createuseracc') {
        $role = 'member'; 
        } else {
            $role = $_POST['role']; 
        }

        $sql = "INSERT INTO ownerusers (fullname, username, password, email, role) 
        VALUES 
        ('$fullname','$username','$password','$email', '$role')";

    if (mysqli_query($conn, $sql)) {

    if (isset($_POST['formSource']) && $_POST['formSource'] === 'createuseracc'){
                echo "<script>
                        window.location.href='index.php'; 
                    </script>"; 
            } 
            else{
                echo "<script>
                        alert('Record Saved!');
                        window.location.href='useraccounts.php'; 
                    </script>"; 
            }
        } 
        else{
            echo 'Error: ' . mysqli_error($conn);
        }
            
    mysqli_close($conn);
 }
?>