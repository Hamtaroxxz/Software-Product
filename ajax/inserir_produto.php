<?php
require __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 0;
    $setor_id = $_POST['setor_id'] ?? null;

    // Verifica se os dados necessários foram preenchidos
    if (!empty($nome) && !empty($setor_id)) {
        try {
            $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, quantidade, setor_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $nome, $descricao, $quantidade, $setor_id);
            $stmt->execute();
            $stmt->close();

            echo "Produto inserido com sucesso!";
        } catch (Exception $e) {
            echo "Erro ao inserir o produto: " . $e->getMessage();
        }
    } else {
        echo "Preencha todos os campos obrigatórios!";
    }
} else {
    echo "Método não permitido.";
}
?>
