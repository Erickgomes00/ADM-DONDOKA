<?php
include __DIR__ . '/../cadastro-produtos/conexao-produtos.php';

$nome = $_POST['nome'] ?? '';
$preco = $_POST['preco'] ?? 0;
$estoque = $_POST['estoque'] ?? 0;
$categoria_id = $_POST['categoria_id'] ?? null;
$genero_id = $_POST['genero_id'] ?? null;

$imagem_url = null;

// Upload de imagem
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (!empty($_FILES['imagem']['tmp_name'])) {
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

// Inserir produto
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
