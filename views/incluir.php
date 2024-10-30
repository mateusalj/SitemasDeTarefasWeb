<?php
require '../models/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $custo = $_POST['custo'];
    $data_limite = $_POST['data_limite'];

    $query = $conn->prepare("SELECT * FROM tarefas WHERE nome = ?");
    $query->execute([$nome]);

    if ($query->rowCount() > 0) {
        echo "Erro: Já existe uma tarefa com este nome.";
        exit;
    }

    $ordem = $conn->query("SELECT MAX(ordem) + 1 AS nova_ordem FROM tarefas")->fetch()['nova_ordem'] ?? 1;

    $stmt = $conn->prepare("INSERT INTO tarefas (nome, custo, data_limite, ordem) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $custo, $data_limite, $ordem]);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/estilos.css">
    <title>Incluir Tarefa</title>
</head>
<body>
    <h1>Incluir Nova Tarefa</h1>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome da Tarefa" required><br>
        <input type="number" name="custo" placeholder="Custo (R$)" step="0.01" required><br>
        <input type="date" name="data_limite" required><br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>
