<?php
require 'config/database.php'; // Certifique-se de que a conexão está incluída

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';

    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO setores (nome) VALUES (?)");
        $stmt->bind_param("s", $nome); 
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
        exit;
    } else {
        echo "<p class='error'>Preencha todos os campos obrigatórios!</p>";
    }
}
?>


<style>

.container {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    text-align: center;
}

.conteudo {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    margin: auto;
    width: 80%;
    max-width: 800px;
}

.estoque {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}


form {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 400px;
    margin: auto;
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

label {
    font-size: 16px;
    margin: 10px 0 5px;
    font-weight: bold;
    color: #333;
}

input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background-color: #fff;
    color: #333;
}

textarea {
    resize: vertical;
    height: 80px;
}

button.botao {
    background-color: #007bff;
    border: none;
    padding: 12px 20px;
    font-size: 18px;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
    font-weight: bold;
    color: white;
    box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
}

button.botao:hover {
    background-color: #0056b3;
}

.success {
    color: #28a745;
    font-weight: bold;
    margin-top: 10px;
}

.error {
    color: #dc3545;
    font-weight: bold;
    margin-top: 10px;
}


</style>

<h2>Inserir Novo Setor</h2>
<form method="POST">
    <label for="nome">Nome do Setor:</label>
    <input type="text" name="nome" id="nome" required>
    <button type="submit" class="botao">Inserir Setor</button>
</form>
