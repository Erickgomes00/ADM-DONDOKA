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

// Detecta schema
try {
    $conn->exec("SET search_path TO public, \"$user\"");
} catch (PDOException $e) {
    die("❌ Erro ao definir search_path: " . $e->getMessage());
}

// Carrega categorias e gêneros
try {
    $categorias = $conn->query("
        SELECT * FROM categoria ORDER BY 
            CASE 
                WHEN nome='Calca' THEN 1
                WHEN nome='Camisa' THEN 2
                WHEN nome='Blusa' THEN 3
                WHEN nome='Jaqueta' THEN 4
                WHEN nome='Casacos' THEN 5
                WHEN nome='Jeans' THEN 6
                WHEN nome='Vestidos' THEN 7
                WHEN nome='Shorts' THEN 8
                WHEN nome='Saias' THEN 9
                WHEN nome='Moda Praia' THEN 10
                WHEN nome='Moda Inverno' THEN 11
            END
    ")->fetchAll();

    $generos = $conn->query('SELECT * FROM genero ORDER BY nome')->fetchAll();
} catch (PDOException $e) {
    die("❌ Erro ao buscar dados: " . $e->getMessage());
}

// Processa formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $categoria_id = $_POST['categoria_id'];
    $genero_id = $_POST['genero_id'];

    // 🎨 Recebe cores (hexadecimal)
    $cores = !empty($_POST['cores']) ? explode(",", $_POST['cores']) : [];
    $cores = array_map('trim', $cores); // remove espaços
    $cores = json_encode($cores); // transforma em JSON para salvar no jsonb

    // Upload da imagem
    if (!empty($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload = $cloudinary->uploadApi()->upload(
            $_FILES['imagem']['tmp_name'],
            ["folder" => "produtos"]
        );
        $imagem_url = $upload['secure_url'];
    } else {
        die("⚠️ A imagem é obrigatória!");
    }

    // Inserção no banco já com cores
    try {
        $sql = "INSERT INTO produto (nome, descricao, preco, estoque, categoria_id, genero_id, imagem_url, cores)
                VALUES (:nome, :descricao, :preco, :estoque, :categoria_id, :genero_id, :imagem_url, :cores)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':preco' => $preco,
            ':estoque' => $estoque,
            ':categoria_id' => $categoria_id,
            ':genero_id' => $genero_id,
            ':imagem_url' => $imagem_url,
            ':cores' => $cores
        ]);

        header("Location: listar_produtos.php?sucesso=1");
        exit;

    } catch (PDOException $e) {
        die("❌ Erro ao inserir produto: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastro de Produto</title>
<style>
body { background-color: #1e1e1e; color: #fff; font-family: Arial, sans-serif; margin: 0; padding: 0; }
.container { max-width: 700px; margin: 50px auto; background-color: #2e2e2e; padding: 30px; border-radius: 10px; }
h1 { text-align: center; margin-bottom: 30px; }
label { display: block; margin-bottom: 5px; font-weight: bold; }
input[type="text"], input[type="number"], textarea, select, input[type="file"] { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: none; background-color: #3a3a3a; color: #fff; }
textarea { resize: vertical; }
button { background-color: #555; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
button:hover { background-color: #777; }
a { display: inline-block; margin-top: 15px; text-decoration: none; color: #fff; }
a:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="container">
<h1>Cadastro de Produto</h1>

<form action="" method="post" enctype="multipart/form-data">

    <label>Nome:</label>
    <input type="text" name="nome" required>

    <label>Descrição:</label>
    <textarea name="descricao" rows="4" required></textarea>

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

    <!-- 🎨 Campo de cores -->
    <label>Cores (hexadecimal, separadas por vírgula):</label>
    <input type="text" name="cores" placeholder="#000000, #FFFFFF, #FF5733">

    <label>Imagem:</label>
    <input type="file" name="imagem" accept="image/*" required>

    <button type="submit">Cadastrar Produto</button>
</form>

<a href="listar_produtos.php">← Voltar para Produtos</a>
</div>
</body>
</html>
