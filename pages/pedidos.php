<?php
include("../includes/conexao.php");

session_start();

// Verificar se o usuário está logado e não é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['setor'] === 'Admin') {
    header("Location: login.php");
    exit();
}

// Lógica de adicionar pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_pedido = mysqli_real_escape_string($conn, $_POST['nome_pedido']);
    $refeicao = mysqli_real_escape_string($conn, $_POST['refeicao']);

    // Converter a data para o formato 'yyyy-mm-dd'
    $data_pedido = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['data_pedido'])));

    // Verificar se a refeição está dentro dos valores permitidos
    if (!in_array($refeicao, ['Almoço', 'Janta'])) {
        $erro = "Valor inválido para refeição.";
    } else {
        $usuario_id = $_SESSION['usuario_id'];

        $sql = "INSERT INTO pedidos (usuario_id, nome_pedido, data_solicitacao, refeicao) 
                VALUES ('$usuario_id', '$nome_pedido', '$data_pedido', '$refeicao')";

        if ($conn->query($sql) === TRUE) {
            $mensagem = "Pedido feito com sucesso!";
        } else {
            $erro = "Erro ao fazer o pedido: " . $conn->error;
        }
    }
}

// Lógica de exibir pedidos
$sql_pedidos = "SELECT nome_pedido, DATE_FORMAT(data_solicitacao, '%d/%m/%Y') AS data_solicitacao_formatada, refeicao 
                FROM pedidos 
                WHERE usuario_id = {$_SESSION['usuario_id']}";
$result_pedidos = $conn->query($sql_pedidos);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Sistema de Marmitas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="container">
    <h2>Pedidos</h2>

    <?php if(isset($mensagem)) { echo "<p style='color:green;'>$mensagem</p>"; } ?>
    <?php if(isset($erro)) { echo "<p style='color:red;'>$erro</p>"; } ?>

    <form method="post">
        <div class="form-group">
            <label for="nome_pedido">Nome para quem será a marmita:</label>
            <input type="text" name="nome_pedido" required>
        </div>
        <div class="form-group">
            <label for="refeicao">Refeição:</label>
            <select name="refeicao" required>
                <option value="Almoço">Almoço</option>
                <option value="Janta">Janta</option>
            </select>
        </div>
        <div class="form-group">
            <label for="data_pedido">Data do Pedido:</label>
            <input type="date" name="data_pedido" placeholder="Digite a data no formato dd/mm/yyyy" required>
        </div>
        <button type="submit" class="btn">Fazer Pedido</button>
    </form>

    <h3>Seus Pedidos:</h3>
    <?php if ($result_pedidos->num_rows > 0) : ?>
        <ul>
            <?php while ($row = $result_pedidos->fetch_assoc()) : ?>
                <li><?php echo "Data: " . $row["data_solicitacao_formatada"] . " | Refeição: " . $row["refeicao"] . " | Nome: " . $row["nome_pedido"]; ?></li>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p>Você ainda não fez nenhum pedido.</p>
    <?php endif; ?>
    
    <p><a href="logout.php">Sair</a></p>
</div>

</body>
</html>
