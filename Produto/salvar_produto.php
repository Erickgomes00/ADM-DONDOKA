<?php
// Conexão PDO
include __DIR__ . '/../cadastro-produtos/conexao-produtos.php';

// Cloudinary
require __DIR__ . '/vendor/autoload.php';
use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'dwjygfm9n',
        'api_key'    => '617625774942954',
        'api_secret' => 'wNrArR3fpVyhfy-vI65PPfOK_uM'
    ]
]);

// ========================
// Coleta dados do formulário
// ========================
$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$preco = $_POST['preco'] ?? 0;
$estoque = $_POST['estoque'] ?? 0;
$categoria_id = $_POST['categoria_id'] ?? null;
$genero_id = $_POST['genero_id'] ?? null;

// ========================
// Valida campos obrigatórios
// ========================
if (!$nome || !$descricao || !$preco || !$estoque || !$categoria_id || !$genero_id) {
    die("⚠️ Todos os campos são obrigatórios!");
}

// ========================
// Upload de imagem para Cloudinary
// ========================
if (!empty($_FILES['imagem']['tmp_name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    try {
        $upload = $cloudinary->uploadApi()->upload(
            $_FILES['imagem']['tmp_name'],
            ["folder" => "produtos"]
        );
        $imagem_url = $upload['secure_url'];
    } catch (Exception $e) {
        die("❌ Erro ao enviar imagem para Cloudinary: " . $e->getMessage());
    }
} else {
    die("⚠️ A imagem é obrigatória!");
}

// ========================
// Inserir produto no banco
// ========================
try {
    $sql = "INSERT INTO produto (nome, descricao, preco, estoque, categoria_id, genero_id, imagem_url)
            VALUES (:nome, :descricao, :preco, :estoque, :categoria_id, :genero_id, :imagem_url)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':preco' => $preco,
        ':estoque' => $estoque,
        ':categoria_id' => $categoria_id,
        ':genero_id' => $genero_id,
        ':imagem_url' => $imagem_url
    ]);

    // Redireciona para lista de produtos com mensagem de sucesso
    header("Location: listar_produtos.php?sucesso=1");
    exit;

} catch (PDOException $e) {
    die("❌ Erro ao inserir produto: " . $e->getMessage());
}
?>
