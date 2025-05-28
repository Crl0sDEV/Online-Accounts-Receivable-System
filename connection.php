<?php
function OpenCon(){
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "multidb";

    $con= new mysqli ($server, $user, $pass, $db)
    or die("Connection Failed");
    return $con;
}
function closeCon($con){
    $con -> close();
}
?>