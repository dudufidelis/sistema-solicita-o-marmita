<?php
include("../includes/conexao.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    $setor = mysqli_real_escape_string($conn, $_POST['setor']);

    $sql = "INSERT INTO usuarios (nome, senha, setor) VALUES ('$usuario', '$senha', '$setor')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit();
    } else {
        $erro = "Erro no registro: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Marmitas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="container">
    <h2>Registro</h2>
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
        <div class="form-group">
            <label for="setor">Setor:</label>
            <input type="text" name="setor" required>
        </div>
        <button type="submit" class="btn">Registrar</button>
    </form>
    <p>Já tem uma conta? <a href="login.php">Login</a></p>
</div>

</body>
</html>
