<?php 
    
    require'db.php';
    //verificando se o id existe e se de fato é um número
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = $_GET['id'];

        $sql = "DELETE FROM tarefa WHERE id = $id";
    
        if(mysqli_query($conn, $sql)){
            header("location: lista.php");
        }else{
            echo "erro: " .mysqli_error($conn);
        }   
    }
       

?>