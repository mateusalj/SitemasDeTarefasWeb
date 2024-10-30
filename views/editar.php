<?php
require '../models/conexao.php';

$id = $_GET['id'];
$tarefa = $conn->query("SELECT * FROM tarefas WHERE id = $id")->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $custo = $_POST['custo'];
    $data_limite = $_POST['data_limite'];

    $query = $conn->prepare("SELECT * FROM tarefas WHERE nome = ? AND id != ?");
    $query->execute([$nome, $id]);

    if ($query->rowCount() > 0) {
        echo "Erro: Já existe uma tarefa com este nome.";
        exit;
    }

    $stmt = $conn->prepare("UPDATE tarefas SET nome = ?, custo = ?, data_limite = ? WHERE id = ?");
    $stmt->execute([$nome, $custo, $data_limite, $id]);

    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/estilos.css">
    <title>Editar Tarefa</title>
</head>
<body>
    <h1>Editar Tarefa</h1>
    <form method="POST">
        <input type="text" name="nome" value="<?= $tarefa['nome'] ?>" required><br>
        <input type="number" name="custo" value="<?= $tarefa['custo'] ?>" step="0.01" required><br>
        <input type="date" name="data_limite" value="<?= $tarefa['data_limite'] ?>" required><br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>
