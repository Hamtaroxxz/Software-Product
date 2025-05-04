<?php

require __DIR__ . '/../config/database.php';

$id = $_POST['id'] ?? null;


if (!$id) {
    echo "Setor não encontrado!";
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM setores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
} catch (\Throwable $th) {
    echo $th;
}

if ($stmt->affected_rows > 0) {
    echo "Setor excluído com sucesso!";
} else {
    echo "Setor não encontrado ou já foi removido.";
}

$stmt->close();
?>
