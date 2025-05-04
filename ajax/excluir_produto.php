<?php

require __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? null;


if (!$id) {
    echo "Produto não encontrado!";
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
} catch (\Throwable $th) {
    echo $th;
}

if ($stmt->affected_rows > 0) {
    echo "Produto excluído com sucesso!";
} else {
    echo "Produto não encontrado ou já foi removido.";
}

$stmt->close();
?>
