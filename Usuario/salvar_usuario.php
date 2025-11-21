<?php

include '../cadastro-produtos/conexao-produtos.php';

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$tipo = $_POST['tipo'] ?? 'Cliente';

if ($nome && $email && $senha) {
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuario (nome, email, senha, tipo) 
                VALUES (:nome, :email, :senha, :tipo)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senhaHash,
            ':tipo' => $tipo
        ]);

        echo "Usuário cadastrado com sucesso!<br>";
        echo "<a href='form_usuario.php'>Cadastrar outro</a> | ";
        echo "<a href='../index.php'>Voltar ao Painel</a>";
    } catch (PDOException $e) {
        die("Erro ao cadastrar usuário: " . $e->getMessage());
    }
} else {
    echo "Todos os campos são obrigatórios!";
}
