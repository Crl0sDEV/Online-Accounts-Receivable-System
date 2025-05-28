<?php
    include 'connection.php';
    $conn = openCon();

    if(isset($_POST['btnSubmit']))
    {
        $loantypename = $_POST['loantypename'];
        $loaninterest = $_POST['loaninterest'];

        $sql = "INSERT INTO loaninterest
         (loantypename, loaninterest) 
        VALUES 
        ('$loantype','$loanterm')";

        if(mysqli_query($conn,$sql)){
            echo "<script>
                alert('Record Saved Successfully!');
                window.location.href='loani.php'; 
              </script>"; 
        }
        else{
            echo 'Error'. mysqli_error($conn);
        }
        mysqli_close($conn);
}
    ?>

   