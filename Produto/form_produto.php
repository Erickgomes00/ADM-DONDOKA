<?php
include __DIR__ . '/../cadastro-produtos/conexao-produtos.php';

// ------------------------
// Detecta schema padrão
// ------------------------
try {
    $conn->exec("SET search_path TO public, \"$user\"");
} catch (PDOException $e) {
    die("❌ Erro ao definir search_path: " . $e->getMessage());
}

// ------------------------
// Busca categorias e gêneros
// ------------------------
try {
    $stmt = $conn->query('SELECT id, nome FROM categoria ORDER BY nome');
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->query('SELECT id, nome FROM genero ORDER BY nome');
    $generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Erro ao buscar categorias ou gêneros: " . $e->getMessage());
}

// ------------------------
// Processa envio do formulário
// ------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $estoque = $_POST['estoque'] ?? 0;
    $categoria_id = $_POST['categoria_id'] ?? null;
    $genero_id = $_POST['genero_id'] ?? null;
    $imagem_url = null;

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    if (!empty($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['imagem']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['imagem']['name']);
        $destPath = $uploadDir . $fileName;
        if (move_uploaded_file($tmpName, $destPath)) $imagem_url = 'uploads/' . $fileName;
        else die("❌ Erro ao salvar a imagem.");
    } else {
        die("⚠️ A imagem é obrigatória!");
    }

    if ($nome && $preco && $estoque && $categoria_id && $genero_id && $imagem_url) {
        try {
            $sql = "INSERT INTO produto (nome, preco, estoque, categoria_id, genero_id, imagem_url)
                    VALUES (:nome, :preco, :estoque, :categoria_id, :genero_id, :imagem_url)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':preco' => $preco,
                ':estoque' => $estoque,
                ':categoria_id' => $categoria_id,
                ':genero_id' => $genero_id,
                ':imagem_url' => $imagem_url
            ]);
            header("Location: listar_produtos.php?sucesso=1");
            exit;
        } catch (PDOException $e) {
            die("❌ Erro ao inserir produto: " . $e->getMessage());
        }
    }
    die("⚠️ Todos os campos são obrigatórios!");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastro de Produto</title>
<style>
body { background-color: #1e1e1e; color: #fff; font-family: Arial, sans-serif; }
.container { max-width: 600px; margin: 50px auto; background-color: #2e2e2e; padding: 30px; border-radius: 10px; }
h1 { text-align: center; margin-bottom: 20px; }
form label { display: block; margin-top: 15px; font-weight: bold; }
form input, form select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: none; }
button { margin-top: 20px; padding: 10px 20px; border: none; border-radius: 5px; background-color: #555; color: #fff; cursor: pointer; }
button:hover { background-color: #777; }
a.btn-voltar { display: inline-block; margin-top: 20px; color: #fff; text-decoration: none; background-color: #555; padding: 8px 15px; border-radius: 5px; }
a.btn-voltar:hover { background-color: #777; }
</style>
</head>
<body>
<div class="container">
<h1>Cadastro de Produto</h1>
<form action="" method="post" enctype="multipart/form-data">
    <label>Nome:</label>
    <input type="text" name="nome" required>

    <label>Preço:</label>
    <input type="number" name="preco" step="0.01" required>

    <label>Estoque:</label>
    <input type="number" name="estoque" required>

    <label>Categoria:</label>
    <select name="categoria_id" required>
        <option value="">Selecione</option>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Gênero:</label>
    <select name="genero_id" required>
        <option value="">Selecione</option>
        <?php foreach ($generos as $gen): ?>
            <option value="<?= $gen['id'] ?>"><?= htmlspecialchars($gen['nome']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Imagem:</label>
    <input type="file" name="imagem" accept="image/*" required>

    <button type="submit">Cadastrar Produto</button>
</form>

<a href="listar_produtos.php" class="btn-voltar">← Voltar</a>
</div>
</body>
</html>
