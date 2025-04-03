<?php
// Verificar se o id foi passado pela URL
$id = $_GET['id'] ?? null;

echo $id;

if (!$id) {
    echo "<p style='color: yellow'class='error'>Produto não encontrado!</p>";
    exit;
}

// Conectar ao banco de dados e buscar o produto com o id fornecido
$stmt = $conn->prepare("SELECT p.id, p.nome, p.descricao, p.quantidade, p.setor_id, s.nome AS setor_nome FROM produtos p JOIN setores s ON p.setor_id = s.id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $produto = $result->fetch_assoc();
} else {
    echo "<p style='color:red' class='error'>Produto não encontrado!</p>";
    exit;
}

// Atualizar o produto caso o formulário seja enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        header("Location: index.php?pagina=gerenciar");
        exit;
    } else {
        echo "<p class='error'>Preencha todos os campos obrigatórios!</p>";
    }
}

$setores = $conn->query("SELECT * FROM setores");
?>

<style>
/* Estilo do formulário, semelhante ao seu estilo anterior */
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

<h2>Editar Produto</h2>
<form method="POST">
    <label for="nome">Nome do Produto:</label>
    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($produto['nome']); ?>" required>

    <label for="descricao">Descrição:</label>
    <textarea name="descricao" id="descricao"><?= htmlspecialchars($produto['descricao']); ?></textarea>

    <label for="quantidade">Quantidade:</label>
    <input type="number" name="quantidade" id="quantidade" value="<?= $produto['quantidade']; ?>" required>

    <label for="setor_id">Setor:</label>
    <select name="setor_id" id="setor_id" required>
        <option value="">Selecione um setor</option>
        <?php while ($setor = $setores->fetch_assoc()): ?>
            <option value="<?php echo $setor['id']; ?>" <?= ($setor['id'] == $produto['setor_id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($setor['nome']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit" class="botao">Atualizar Produto</button>
</form>
