<?php
require __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Atualizar o produto caso o formulário seja enviado
    $id = $_POST['id'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 0;
    $setor_id = $_POST['setor_id'] ?? null;

    if (!empty($nome) && $setor_id) {
        // Atualizar o produto no banco de dados
        $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, quantidade = ?, setor_id = ? WHERE id = ?");
        $stmt->bind_param("ssiii", $nome, $descricao, $quantidade, $setor_id, $id);
        $stmt->execute();
        $stmt->close();

        // Redirecionar após a atualização
        echo "Produto editado com sucesso!";
        exit;
    } else {
        echo "Preencha todos os campos obrigatórios!";
    }

} else {
    echo "Método não permitido.";
}
?>