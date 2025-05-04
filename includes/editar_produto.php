<?php

// Verificar se o id foi passado pela URL
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Produto não encontrado!";
    exit;
}

// buscar o produto com o id fornecido
$stmt = $conn->prepare("SELECT p.id, p.nome, p.descricao, p.quantidade, p.setor_id, s.nome AS setor_nome FROM produtos p JOIN setores s ON p.setor_id = s.id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $produto = $result->fetch_assoc();
} else {
    echo "Produto não encontrado!";
    exit;
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

    input,
    select,
    textarea {
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

    <button type="submit" class="botao" onclick="editar(<?php echo $id; ?>)">Atualizar Produto</button>
</form>


<script>

    function editar(id) {
        var nome = document.querySelector('[name="nome"]').value;
        var descricao = document.querySelector('[name="descricao"]').value;
        var quantidade = document.querySelector('[name="quantidade"]').value;
        var setorId = document.querySelector('[name="setor_id"]').value;

        var formData = new FormData();
        formData.append('id', id);
        formData.append('nome', nome);
        formData.append('descricao', descricao);
        formData.append('quantidade', quantidade);
        formData.append('setor_id', setorId);

        fetch('ajax/editar_produto.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data == "Produto editado com sucesso!") {
                    window.location.href = "index.php?pagina=gerenciar";
                }
            })
            .catch(error => {
                console.error("Erro ao enviar os dados:", error);
                alert("Houve um erro ao tentar editar o produto.");
            });

    }
</script>