<?php
// Se houver um filtro de setor, adiciona à consulta
$setorFiltro = $_GET['setor_id'] ?? '';

// Consulta para buscar setores
$setores = $conn->query("SELECT * FROM setores");

// Consulta para buscar produtos, aplicando filtro se necessário
$sql = "SELECT p.id, p.nome, p.descricao, p.quantidade, s.nome AS setor 
        FROM produtos p 
        JOIN setores s ON p.setor_id = s.id";

if (!empty($setorFiltro)) {
    $sql .= " WHERE p.setor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $setorFiltro);
    $stmt->execute();
    $produtos = $stmt->get_result();
} else {
    $produtos = $conn->query($sql);
}
?>

<div class="container">
    <div class="topo">
        <form method="GET">
        <label style="color: black" for="setorFiltro">Filtrar por Setor:</label>
        <select id="setorFiltro" class="filtro" onchange="filtrarPorSetor(this.value)">
            <option value="">Todos</option>
            <?php while ($setor = $setores->fetch_assoc()): ?>
                <option value="<?= $setor['id']; ?>" <?= ($setorFiltro == $setor['id']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($setor['nome']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        </form>
        <div style="display:flex; justify-content: flex-end; gap: 8px">
            <button class="botao" onclick="inserirSetor()">Gerenciar Setor</button>
            <button class="botao" onclick="inserirProduto()">Inserir Produto</button>
        </div>

    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Setor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
    <?php if ($produtos->num_rows > 0): ?>
        <?php while ($produto = $produtos->fetch_assoc()): ?>
            <tr>
                <td style="color: black"><?php echo $produto['id']; ?></td>
                <td style="color: black"><?php echo htmlspecialchars($produto['nome']); ?></td>
                <td style="color: black"><?php echo htmlspecialchars($produto['descricao']); ?></td>
                <td style="color: black"><?php echo $produto['quantidade']; ?></td>
                <td style="color: black"><?php echo htmlspecialchars($produto['setor']); ?></td>
                <td class="botoes">
                    <a onclick="editar(<?php echo $produto['id']; ?>)" class="btn editar">Editar</a>
                    <a onclick="excluir(<?php echo $produto['id']; ?>)" class="btn excluir">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; color:rgb(71, 71, 71);">Nenhum produto em estoque</td>
        </tr>
    <?php endif; ?>
</tbody>



    </table>
</div>

<script>

    function inserirSetor() {
        window.location.href = "index.php?pagina=gerenciar_setor";
    }

    function inserirProduto() {
        window.location.href = "index.php?pagina=inserir_produto";
    }

    function editar(id) {
        window.location.href = "index.php?pagina=editar_produto&id=" + id;
    }

    function excluir(id) {
        if (confirm("Tem certeza que deseja excluir este produto?")) {
        fetch(`ajax/excluir_produto.php?id=${id}`)
            .then(response => response.text())
            .then(data => {
                // Mostra o retorno do PHP (mensagem de sucesso ou erro)
                alert(data);
                location.reload();
            })
            .catch(error => {
                console.error("Erro ao excluir o produto:", error);
                alert("Houve um erro ao excluir o produto.");
            });
    }
    }

    function filtrarPorSetor(setorId) {
    const url = new URL(window.location.href);
    url.searchParams.set('pagina', 'gerenciar');

    if (setorId) {
        url.searchParams.set('setor_id', setorId);
    } else {
        url.searchParams.delete('setor_id');
    }

    window.location.href = url.toString();
}
</script>