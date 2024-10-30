<?php
require '../models/conexao.php';

$id = $_GET['id'];
$conn->prepare("DELETE FROM tarefas WHERE id = ?")->execute([$id]);

header('Location: ../views/index.php');
