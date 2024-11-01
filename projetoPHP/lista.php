<?php 
    require 'db.php';
    //verificando se foi escolhido um filtro , caso n tenha escolhido usar o valor padrão
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'prioridade';
    $ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'ASC';

    $camposPermitidos = ['prazo', 'prioridade', 'nome'];
    $ordensPermitidas = ['ASC', 'DESC'];

    // Verificando se o filtro escolhido esta condito nos comapos permitidos
    if (!in_array($filtro, $camposPermitidos)) $filtro = 'prioridade';
    if (!in_array($ordem, $ordensPermitidas)) $ordem = 'ASC';

    // Consulta para obter nomes de todos os colaboradores 
    $sqli = "SELECT id, nome FROM colaboradores";
    $colaboradoresRes = mysqli_query($conn, $sqli);
    $colaboradores = [];
    while ($colaborador = mysqli_fetch_assoc($colaboradoresRes)) {
        $colaboradores[] = $colaborador;
    }

//Junta os dados da tabela colaboradores, com o Ij especifica que a junção acontece quando o colaborador_id e igual colaboradores.id
    $sql = "SELECT tarefa.id, tarefa.titulo, tarefa.descricao, tarefa.prazo, tarefa.prioridade, colaboradores.nome
            FROM tarefa
            INNER JOIN colaboradores ON tarefa.colaborador_id = colaboradores.id
            ORDER BY 
                CASE 
                    WHEN '$filtro' = 'prioridade' AND '$ordem' = 'ASC' THEN FIELD(tarefa.prioridade, 'alta', 'media', 'baixa')
                    WHEN '$filtro' = 'prioridade' AND '$ordem' = 'DESC' THEN FIELD(tarefa.prioridade, 'baixa', 'media', 'alta')
                    
                END, 
                $filtro $ordem";

    $res = mysqli_query($conn, $sql);

    // select colaborador verificando se  a variável colaborador_id foi definida na URL e Verifica se o ID do colaborador na opção atual é igual ao valor enviado na URL.
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
</head>

<body>
    <form method="GET" action="">
        <label for="filtro">Filtrar por:</label>
        <select name="filtro" id="filtro">
            <option value="prazo" <?= $filtro == 'prazo' ? 'selected' : '' ?>>Prazo</option>
            <option value="prioridade" <?= $filtro == 'prioridade' ? 'selected' : '' ?>>Prioridade</option>
            <option value="nome" <?= $filtro == 'nome' ? 'selected' : '' ?>>Colaborador</option>
        </select>


        <label for="ordem">Ordem:</label>
        <select name="ordem" id="ordem">
            <option value="ASC" <?= $ordem == 'ASC' ? 'selected' : '' ?>>Crescente</option>
            <option value="DESC" <?= $ordem == 'DESC' ? 'selected' : '' ?>>Decrescente</option>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <div>
        <table>
            <tr>
                <th>Título</th>
                <th>Descrição</th>
                <th>Prazo</th>
                <th>Prioridade</th>
                <th>Colaborador</th>
                <th>Ação</th>
            </tr>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?= $row['titulo'] ?></td>
                    <td><?= $row['descricao']?></td>
                    <td><?= date('d/m/Y', strtotime($row['prazo'])) ?></td>
                    <td><?= $row['prioridade'] ?></td>
                    <td><?= $row['nome'] ?></td>
                    <td>
                        <button><a href="atualizar.php?id=<?= $row['id'] ?>">Editar</a></button>
                        <button><a href="deletar.php?id=<?= $row['id'] ?>">Deletar</a></button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div>
        <button><a href="index.php">Voltar</a></button>
    </div>
</body>

</html>
