<?php
require 'config/database.php';

$sql = "SELECT s.nome AS setor, COALESCE(COUNT(p.id), 0) AS total FROM setores s
        LEFT JOIN produtos p ON s.id = p.setor_id
        GROUP BY s.id";
$result = $conn->query($sql);

$setores = $result->fetch_all(MYSQLI_ASSOC);

// vai carregar a funcionalidade home quando carregar o index
$pagina = $_GET['pagina'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Estoque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Gestão de Estoque</h1>
        <div class="menu">
            <a href="index.php?pagina=home" class="botao">Tela Inicial</a>
            <a href="index.php?pagina=inserir" class="botao">Inserir Produto</a>
            <a href="index.php?pagina=visualizar" class="botao">Visualizar Produto</a>
            <a href="index.php?pagina=editar" class="botao">Editar Produto</a>
            <a href="index.php?pagina=excluir" class="botao">Excluir Produto</a>
        </div>

        <div class="conteudo">
            <?php
            if ($pagina == 'home') {
                echo "<h2>Resumo do Estoque</h2>";
                if (empty($setores)) {
                    echo "<p style='color: black'>Nenhum setor encontrado!</p>";
                } else {
                    echo "<div class='estoque'>";
                    foreach ($setores as $setor) {

                        if($setor['total'] == 1) {
                            $total_itens = $setor['total'] . ' Item';
                        } else { 
                            $total_itens = $setor['total'] . ' Itens';
                        }
                        echo "<div class='setor'>
                                <h2>" . htmlspecialchars($setor['setor']) . "</h2>
                                <p>$total_itens</p>
                              </div>";
                    }
                    echo "</div>";
                }
            } else {
                $arquivo = "includes/{$pagina}.php";
                if (file_exists($arquivo)) {
                    include $arquivo;
                } else {
                    echo "<p>Página não encontrada!</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
