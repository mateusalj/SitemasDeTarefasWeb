<?php
require '../models/conexao.php';

$query = $conn->query("SELECT * FROM tarefas ORDER BY ordem");
$tarefas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Lista de Tarefas</h1>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Custo (R$)</th>
                <th>Data Limite</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tarefas as $tarefa): ?>
                <tr style="background-color: <?= $tarefa['custo'] >= 1000 ? 'yellow' : 'white'; ?>">
                    <td><?= htmlspecialchars($tarefa['nome']) ?></td>
                    <td><?= number_format($tarefa['custo'], 2, ',', '.') ?></td>
                    <td><?= date('d/m/Y', strtotime($tarefa['data_limite'])) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $tarefa['id'] ?>">✏️</a>
                        <a href="#" onclick="confirmarExclusao(<?= $tarefa['id'] ?>)">🗑️</a>
                        <a href="../controllers/reordenar.php?id=<?= $tarefa['id'] ?>&direcao=subir">↑</a>
                        <a href="../controllers/reordenar.php?id=<?= $tarefa['id'] ?>&direcao=descer">↓</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button onclick="window.location.href='incluir.php'">Incluir Tarefa</button>

    <script>
        function confirmarExclusao(id) {
            if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
                window.location.href = `../controllers/excluir.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
