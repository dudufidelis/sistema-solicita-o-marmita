<?php
    $host = "10.201.2.7";
    $usuario = "root";
    $senha = "20db@sql";
    $banco = "sistema_marmitas";

    $conn = new mysqli($host, $usuario, $senha, $banco);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }
?>
