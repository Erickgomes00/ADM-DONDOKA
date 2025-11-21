<?php
include '../cadastro-produtos/conexao-produtos.php';
?>

<form action="salvar_usuario.php" method="post">
    <label>Nome:</label>
    <input type="text" name="nome" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" required><br><br>

    <label>Senha:</label>
    <input type="password" name="senha" required><br><br>

    <label>Tipo:</label>
    <select name="tipo">
        <option value="Cliente">Cliente</option>
        <option value="Administrador">Administrador</option>
    </select><br><br>

    <button type="submit">Cadastrar Usuário</button>
</form>
