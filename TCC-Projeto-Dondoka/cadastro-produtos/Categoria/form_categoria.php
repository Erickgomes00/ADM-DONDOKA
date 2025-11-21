<?php
include __DIR__ . '/../conexao-produtos.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Categoria</title>
</head>
<body>
    <h2>Nova Categoria</h2>
    <form action="salvar_categoria.php" method="post">
        <label for="nome">Nome da Categoria:</label>
        <input type="text" name="nome" id="nome" required><br><br>

        <label for="genero">Gênero:</label>
        <select name="genero" id="genero">
            <option value="Unissex">Unissex</option>
            <option value="Feminino">Feminino</option>
            <option value="Masculino">Masculino</option>
        </select><br><br>

        <button type="submit">Salvar</button>
    </form>
</body>
</html>
