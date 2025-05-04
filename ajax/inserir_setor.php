<?php
require __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'] ?? '';

    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO setores (nome) VALUES (?)");
        $stmt->bind_param("s", $nome); 
        $stmt->execute();
        $stmt->close();

        echo "Setor inserido com sucesso!";
        exit;
    } else {
        echo "Preencha todos os campos obrigatórios!";
    }
} else {
    echo "Método não permitido.";
}
?>
