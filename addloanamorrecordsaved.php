<?php
    include 'connection.php';
    $conn = openCon();

    if(isset($_POST['btnSubmit']))
    {
        $loantype = $_POST['loantype'];
        $loanterm = $_POST['loanterm'];
        $loanamor = $_POST['loanamor'];

        $sql = "INSERT INTO loanamortization
         (loantype, loanterm, loanamor) 
        VALUES 
        ('$loantype','$loanterm', '$loanamor')";

        if(mysqli_query($conn,$sql)){
            echo "<script>
                alert('Record Saved Successfully!');
                window.location.href='loana.php'; 
              </script>"; 
        }
        else{
            echo 'Error'. mysqli_error($conn);
        }
        mysqli_close($conn);
}
    ?>

   