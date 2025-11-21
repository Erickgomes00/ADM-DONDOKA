<?php
// Inclui a conexão PDO
include __DIR__ . '/../cadastro-produtos/conexao-produtos.php';

// ------------------------
// Detecta schema padrão
// ------------------------
try {
    // Define o search_path para enxergar todas as tabelas
    $conn->exec("SET search_path TO public, \"$user\"");
} catch (PDOException $e) {
    die("❌ Erro ao definir search_path: " . $e->getMessage());
}

// ------------------------
// Busca categorias e gêneros
// ------------------------
try {
    $stmt = $conn->query('SELECT id, nome FROM categoria ORDER BY nome');
    $categorias = $stmt->fetchAll();

    $stmt = $conn->query('SELECT id, nome FROM genero ORDER BY nome');
    $generos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("❌ Erro ao buscar categorias ou gêneros: " . $e->getMessage() . ". Verifique se as tabelas existem no banco e no schema correto.");
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

    // Pasta de uploads
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Upload da imagem
    if (!empty($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['imagem']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['imagem']['name']);
        $destPath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $destPath)) {
            $imagem_url = 'uploads/' . $fileName;
        } else {
            die("❌ Erro ao salvar a imagem.");
        }
    } else {
        die("⚠️ A imagem é obrigatória!");
    }

    // Valida campos obrigatórios
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

<!-- ===========================
        Formulário HTML
=========================== -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
</head>
<body>
<h1>Cadastro de Produto</h1>

<form action="" method="post" enctype="multipart/form-data">
    <label>Nome:</label><br>
    <input type="text" name="nome" required><br><br>

    <label>Preço:</label><br>
    <input type="number" name="preco" step="0.01" required><br><br>

    <label>Estoque:</label><br>
    <input type="number" name="estoque" required><br><br>

    <label>Categoria:</label><br>
    <select name="categoria_id" required>
        <option value="">Selecione</option>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Gênero:</label><br>
    <select name="genero_id" required>
        <option value="">Selecione</option>
        <?php foreach ($generos as $gen): ?>
            <option value="<?= $gen['id'] ?>"><?= htmlspecialchars($gen['nome']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Imagem:</label><br>
    <input type="file" name="imagem" accept="image/*" required><br><br>

    <button type="submit">Cadastrar Produto</button>
</form>
</body>
</html>
