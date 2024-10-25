<?php

    $host = 'localhost';
    $dbname = 'crud_php';
    $user = 'root';
    $pass = '';

    $conn = mysqli_connect($host, $user, $pass, $dbname);

    if(!$conn){
        die("Erro na conexão: " . mysqli_connect_error());
    }else{
        
    }
    
?>