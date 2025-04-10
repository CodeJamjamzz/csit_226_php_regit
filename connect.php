<?php
    $connection = new mysqli('localhost', 'root','','dbf2pinca');
   
    if (!$connection){
        die (mysqli_error($mysqli));
    }
       
?>