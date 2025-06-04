<?php
require 'config/database.php';

$sql = "SELECT s.nome AS setor, COALESCE(COUNT(p.id), 0) AS total FROM setores s
        LEFT JOIN produtos p ON s.id = p.setor_id
        GROUP BY s.id";
$result = $conn->query($sql);

$setores = $result->fetch_all(MYSQLI_ASSOC);

// vai carregar a funcionalidade home quando carregar o index
$pagina = $_GET['pagina'] ?? 'home';
$id_produto = $_GET['id'] ?? '';
?>

<?php include "includes/header.php" ?>

<!-- Inclui Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
    <div class="container">
        <h1>Gestão de Estoque</h1>
        <div class="menu">
            <a href="index.php?pagina=home" class="botao">Tela Inicial</a>
            <a href="index.php?pagina=gerenciar" class="botao">Gerenciar Produtos</a>
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
                        $total_itens = $setor['total'] == 1 ? '1 Item' : $setor['total'] . ' Itens';

                        echo "<div class='setor'>
                                <h2>" . htmlspecialchars($setor['setor']) . "</h2>
                                <p>$total_itens</p>
                              </div>";
                    }
                    echo "</div>";

                    echo "<h2 style='margin-top: 40px;'>Distribuição por Setor</h2>";
                   echo "<div class='grafico-container'>
                        <canvas id='graficoEstoque'></canvas>
                    </div>";

                    $cores = [];
                    foreach ($setores as $_) {
                        $cores[] = 'rgba(' . rand(0,255) . ',' . rand(0,255) . ',' . rand(0,255) . ', 0.7)';
                    }

                    echo "<script>
                            const setores = " . json_encode(array_column($setores, 'setor')) . ";
                            const totais = " . json_encode(array_column($setores, 'total')) . ";
                            const cores = " . json_encode($cores) . ";

                            const ctx = document.getElementById('graficoEstoque').getContext('2d');
                            const graficoEstoque = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: setores,
                                    datasets: [{
                                        data: totais,
                                        backgroundColor: cores,
                                        borderColor: '#ffffff',
                                        borderWidth: 2
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'right'
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const label = context.label || '';
                                                    const value = context.parsed;
                                                    return label + ': ' + value + ' item' + (value === 1 ? '' : 's');
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                          </script>";
                }
            } else {
                $arquivo = "includes/{$pagina}.php";

                if (file_exists($arquivo)) {
                    if ($id_produto) {
                        $_GET['id'] = $id_produto;
                    }
                    include $arquivo;
                } else {
                    echo "<p style='color: black'>Página não encontrada!</p>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>
