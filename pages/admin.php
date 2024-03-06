<?php
include("../includes/conexao.php");

session_start();

// Verificar se o usuário está logado e é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['setor'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Lógica de filtrar por data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data_filtro = mysqli_real_escape_string($conn, $_POST['data']);
    $sql_pedidos = "SELECT nome_pedido, DATE_FORMAT(data_solicitacao, '%d/%m/%Y') AS data_solicitacao_formatada, refeicao, setor 
                    FROM pedidos 
                    INNER JOIN usuarios ON pedidos.usuario_id = usuarios.id 
                    WHERE DATE_FORMAT(data_solicitacao, '%Y-%m-%d') = '$data_filtro'
                    ORDER BY data_solicitacao DESC";
} else {
    // Lógica de exibir todos os pedidos
    $sql_pedidos = "SELECT nome_pedido, DATE_FORMAT(data_solicitacao, '%d/%m/%Y') AS data_solicitacao_formatada, refeicao, setor 
                    FROM pedidos 
                    INNER JOIN usuarios ON pedidos.usuario_id = usuarios.id 
                    ORDER BY data_solicitacao DESC";
}

$result_pedidos = $conn->query($sql_pedidos);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Sistema de Marmitas (Admin)</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="container">
    <h2>Pedidos (Admin)</h2>

    <h3>Filtrar por Data:</h3>
    <form method="post">
        <label for="data">Selecione a Data:</label>
        <input type="date" name="data" id="data">
        <button type="submit">Filtrar</button>
    </form>

    <?php if ($result_pedidos->num_rows > 0) : ?>
        <?php
        $last_date = '';
        $last_refeicao = '';
        echo "<div class='column-container'>";
        while ($row = $result_pedidos->fetch_assoc()) :
            $current_date = $row["data_solicitacao_formatada"];
            $refeicao_info = $row["refeicao"] === "Almoço" ? "Almoço" : "Janta";

            if ($last_date != $current_date) {
                if ($last_date != '') {
                    echo "</div>"; // Fechar a div anterior se não for a primeira data
                }
                echo "<div class='column'>";
                echo "<h4>Data: $current_date</h4>";
                $last_refeicao = ''; // Resetar a última refeição ao mudar a data
            }

            // Se a refeição mudou, exibir a refeição
            if ($last_refeicao != $refeicao_info) {
                echo "<h5>$refeicao_info</h5>";
            }

            echo "<p><strong>Setor:</strong> {$row['setor']} | <strong>Nome:</strong> {$row['nome_pedido']}</p>";

            $last_date = $current_date;
            $last_refeicao = $refeicao_info;
        endwhile;
        echo "</div>"; // Fechar a última div
        ?>
    <?php else : ?>
        <p>Nenhum pedido encontrado.</p>
    <?php endif; ?>

    <p><a href="logout.php">Sair</a></p>
</div>

</body>
</html>
