<?php 
    
    require'db.php';
    
    $sqli = "SELECT id, nome FROM colaboradores";
    $colaboradoresRes = mysqli_query($conn, $sqli);
    $colaboradores = [];
    while ($colaborador = mysqli_fetch_assoc($colaboradoresRes)) {
        $colaboradores[] = $colaborador;
    }

    //verificando se o id existe e se de fato é um número
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = $_GET['id'];

        $sql = "SELECT * FROM tarefa WHERE id = $id";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];
            $prazo = $_POST['prazo'];
            $prioridade = $_POST['prioridade'];
            $colaborador_id = (int)$_POST['colaborador_id'];
            
            $sql = "UPDATE tarefa SET titulo= '$titulo', descricao='$descricao', prazo ='$prazo', prioridade ='$prioridade', colaborador_id = $colaborador_id WHERE id = $id";

            if(mysqli_query($conn, $sql)){
                header("location: lista.php");
            }else{
                echo "erro: " .mysqli_error($conn);
            } 
        }    
    }
        

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>atualizar tarefa</title>
</head>

<body>
    <div>
        <form method="post" action="atualizar.php?id=<?= $row['id'] ?>">
            <label for="titulo">titulo da tarefa</label>
            <input type="text" name="titulo" id="titulo" value="<?=$row['titulo'] ?>" required>
            <label for="descricao">descrição</label>
            <input type="text" name="descricao" id="descricao" value="<?=$row['descricao'] ?>" required>
            <label for="prazo">prazo</label>
            <input type="date" name="prazo" id="prazo" value="<?=$row['prazo'] ?>" min="<?= date('Y-m-d') ?>" required>
            <label for="prioridade">prioridade</label>
            <select id="prioridade" name="prioridade">
                <option value="alta">Alta</option>
                <option value="media">Média</option>
                <option value="baixa">Baixa</option>
            </select>
            <label for="col">colaborador</label>
            <select name="colaborador_id" id="col">
                <?php foreach ($colaboradores as $colaborador): ?>
                    <option value="<?= $colaborador['id'] ?>" ?>
                        <?= $colaborador['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Salvar</button>
            <button><a href="index.php">voltar</a></button>
        </form>
    </div>
</body>

</html>