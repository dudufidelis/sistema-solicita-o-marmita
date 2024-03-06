<?php
include("../includes/conexao.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);

    $sql = "SELECT id, setor FROM usuarios WHERE nome = '$usuario' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['setor'] = $row['setor'];

        if ($_SESSION['setor'] === 'Admin') {
            header("Location: admin.php");
        } else {
            header("Location: pedidos.php");
        }

        exit();
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Marmitas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="container">
    <h2>Login</h2>

    <?php if(isset($erro)) { echo "<p style='color:red;'>$erro</p>"; } ?>

    <form method="post">
        <div class="form-group">
            <label for="usuario">Usuário:</label>
            <input type="text" name="usuario" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>
        </div>
        <button type="submit" class="btn">Entrar</button>
    </form>

</div>

</body>
</html>
