<?php
require '../models/conexao.php';

$id = $_GET['id'];
$direcao = $_GET['direcao'];

try {
    
    $conn->beginTransaction();

    // Buscar a tarefa atual
    $stmt = $conn->prepare("SELECT * FROM tarefas WHERE id = ?");
    $stmt->execute([$id]);
    $tarefa = $stmt->fetch();

    // Determinar a nova ordem
    $novaOrdem = ($direcao === 'subir') ? $tarefa['ordem'] - 1 : $tarefa['ordem'] + 1;

    // Verificar se existe uma tarefa na nova ordem
    $stmt = $conn->prepare("SELECT * FROM tarefas WHERE ordem = ?");
    $stmt->execute([$novaOrdem]);
    $tarefaAdjacente = $stmt->fetch();

    if ($tarefaAdjacente) {
        // Atribuir uma ordem temporária para evitar conflito
        $stmt = $conn->prepare("UPDATE tarefas SET ordem = -1 WHERE id = ?");
        $stmt->execute([$tarefaAdjacente['id']]);

        // Atualizar a ordem da tarefa atual
        $stmt = $conn->prepare("UPDATE tarefas SET ordem = ? WHERE id = ?");
        $stmt->execute([$novaOrdem, $tarefa['id']]);

        // Atualizar a tarefa que tinha a ordem conflitante para a nova posição
        $stmt = $conn->prepare("UPDATE tarefas SET ordem = ? WHERE id = ?");
        $stmt->execute([$tarefa['ordem'], $tarefaAdjacente['id']]);
    }

    
    $conn->commit();

} catch (Exception $e) {

    $conn->rollBack();
    echo "Erro ao reordenar tarefas: " . $e->getMessage();
    exit;
}

// Redirecionar 
header('Location: ../index.php');
exit;