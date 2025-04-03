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
            <label style='color: black' for="setorFiltro">Filtrar por Setor:</label>
            <select name="setor_id" id="setorFiltro" class="filtro" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php while ($setor = $setores->fetch_assoc()): ?>
                    <option value="<?= $setor['id']; ?>" <?= ($setorFiltro == $setor['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($setor['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
        <div style="display:flex; justify-content: flex-end; gap: 8px">
            <button class="botao" onclick="inserirSetor()">Inserir Setor</button>
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
                    <a href="excluir.php?id=<?php echo $produto['id']; ?>" 
                       class="btn excluir" 
                       onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
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
        window.location.href = "index.php?pagina=inserir_setor";
    }

    function inserirProduto() {
        window.location.href = "index.php?pagina=inserir_produto";
    }


    function editar(id) {
        window.location.href = "index.php?pagina=editar_produto&id=" + id;
    }

    function excluir(id) {
        if (confirm("Tem certeza que deseja excluir este produto?")) {
            window.location.href = "excluir_produto.php?id=" + id;
        }
    }
</script>