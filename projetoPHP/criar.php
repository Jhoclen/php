<?php 
    
    require'db.php';

     
     $sqli = "SELECT id, nome FROM colaboradores";
     $colaboradoresRes = mysqli_query($conn, $sqli);
     $colaboradores = [];
     while ($colaborador = mysqli_fetch_assoc($colaboradoresRes)) {
        $colaboradores[] = $colaborador;
     }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $prazo = $_POST['prazo'];
        $prioridade = $_POST['prioridade'];
        $colaborador_id = (int)$_POST['colaborador_id'];
        

        $sql = "INSERT INTO tarefa ( titulo, descricao, prazo, prioridade, colaborador_id) VALUES ('$titulo','$descricao','$prazo','$prioridade',$colaborador_id)";

        if(mysqli_query($conn, $sql)){
            header("location: index.php");
            
        }else{
            echo "erro". mysqli_error($conn);
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nova tarefa</title>
</head>

<body>
    <div>
        <form method="post" action="criar.php">
            <label for="titulo">titulo da tarefa</label>
            <input type="text" name="titulo" id="titulo" required>
            <label for="descricao">descrição</label>
            <input type="text" name="descricao" id="descricao" required>
            <label for="prazo">prazo</label>
            <input type="date" name="prazo" id="prazo" min="<?= date('Y-m-d') ?>" required>
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