<?php
    require 'db.php';
 
    // Verifica se foi escolhido um filtro; caso contrário, usa o valor padrão
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'prioridade';
    $ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'ASC';
    $colaborador_id = isset($_GET['colaborador_id']) ? (int)$_GET['colaborador_id'] : null;
 
    $camposPermitidos = ['prazo', 'prioridade', 'nome'];
    $ordensPermitidas = ['ASC', 'DESC'];
 
    // Verifica se o filtro escolhido está entre os campos permitidos
    if (!in_array($filtro, $camposPermitidos)) $filtro = 'prioridade';
    if (!in_array($ordem, $ordensPermitidas)) $ordem = 'ASC';
 
    // Consulta para obter nomes de todos os colaboradores
    $sqli = "SELECT id, nome FROM colaboradores";
    $colaboradoresRes = mysqli_query($conn, $sqli);
    $colaboradores = [];
    while ($colaborador = mysqli_fetch_assoc($colaboradoresRes)) {
        $colaboradores[] = $colaborador;
    }
 
    // Consulta SQL principal para obter as tarefas com filtro e ordenação
    $sql = "SELECT tarefa.id, tarefa.titulo, tarefa.descricao, tarefa.prazo, tarefa.prioridade, colaboradores.nome
            FROM tarefa
            INNER JOIN colaboradores ON tarefa.colaborador_id = colaboradores.id ";
 
    // Adiciona condição de filtragem pelo colaborador selecionado, se fornecido
    if ($colaborador_id) {
        $sql .= "WHERE tarefa.colaborador_id = $colaborador_id ";
    }
 
    // Adiciona a ordenação por filtro
    $sql .= "ORDER BY
                CASE
                    WHEN '$filtro' = 'prioridade' AND '$ordem' = 'ASC' THEN FIELD(tarefa.prioridade, 'alta', 'media', 'baixa')
                    WHEN '$filtro' = 'prioridade' AND '$ordem' = 'DESC' THEN FIELD(tarefa.prioridade, 'baixa', 'media', 'alta')
                END,
                $filtro $ordem";
 
    $res = mysqli_query($conn, $sql);
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
        <label for="colaborador_id">Filtrar por Colaborador:</label>
        <select name="colaborador_id" id="colaborador_id">
            <option value="">Todos</option>
            <?php foreach ($colaboradores as $colaborador): ?>
                <option value="<?= $colaborador['id'] ?>" <?= $colaborador_id == $colaborador['id'] ? 'selected' : '' ?>>
                    <?= $colaborador['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>
 
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
 
 