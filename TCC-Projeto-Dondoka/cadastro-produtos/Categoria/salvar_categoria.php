<?php
include __DIR__ . '/../conexao-produtos.php';

$nome = $_POST['nome'] ?? '';

if ($nome) {
    try {
        $sql = "INSERT INTO categoria (nome) VALUES (:nome) ON CONFLICT (nome) DO NOTHING";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':nome' => $nome]);

        echo "Categoria cadastrada com sucesso!<br>";
        echo "<a href='form_categoria.php'>Cadastrar outra</a> | ";
        echo "<a href='../index.php'>Voltar ao Painel</a>";
    } catch (PDOException $e) {
        die("Erro ao cadastrar categoria: " . $e->getMessage());
    }
} else {
    echo "O nome da categoria é obrigatório!";
}
