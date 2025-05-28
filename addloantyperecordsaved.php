<?php
    include 'connection.php';
    $conn = openCon();

    if(isset($_POST['btnSubmit']))
    {
        
        $loantypename = $_POST['loantypename'];
        $description = $_POST['description'];

        $sql = "INSERT INTO loan_types
         (loantypename, description) 
        VALUES 
        ('$loantypename','$description')";

        if(mysqli_query($conn,$sql)){
            echo "<script>
                alert('Record Saved Successfully!');
                window.location.href='loant.php'; 
              </script>"; 
        }
        else{
            echo 'Error'. mysqli_error($conn);
        }
        mysqli_close($conn);
}
?>

   