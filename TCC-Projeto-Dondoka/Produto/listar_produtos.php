<?php
include __DIR__ . '/../cadastro-produtos/conexao-produtos.php';

// ========================
// EXCLUIR PRODUTO
// ========================
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $stmt = $conn->prepare("DELETE FROM produto WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: listar_produtos.php");
    exit;
}

// ========================
// ATUALIZAR PRODUTO
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {

    $id = intval($_POST['id']);
    $nome = $_POST['nome'];
    $preco = floatval($_POST['preco']);
    $estoque = intval($_POST['estoque']);
    $categoria_id = intval($_POST['categoria_id']);
    $genero_id = intval($_POST['genero_id']);

    $sql = "UPDATE produto 
            SET nome = :nome, preco = :preco, estoque = :estoque, 
                categoria_id = :categoria_id, genero_id = :genero_id
            WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':preco' => $preco,
        ':estoque' => $estoque,
        ':categoria_id' => $categoria_id,
        ':genero_id' => $genero_id,
        ':id' => $id
    ]);

    header("Location: listar_produtos.php");
    exit;
}

// ========================
// BUSCAR PRODUTO PARA EDIÇÃO
// ========================
$produtoEditar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $conn->prepare("SELECT * FROM produto WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $produtoEditar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ========================
// BUSCAR CATEGORIAS
// ========================
$stmt = $conn->query("SELECT * FROM categoria ORDER BY nome");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ========================
// BUSCAR GÊNEROS
// ========================
$stmt = $conn->query("SELECT * FROM genero ORDER BY nome");
$generos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ========================
// BUSCAR TODOS PRODUTOS
// ========================
$sql = "SELECT p.id, p.nome, p.preco, p.estoque, p.imagem_url,
               c.nome AS categoria, g.nome AS genero
        FROM produto p
        LEFT JOIN categoria c ON p.categoria_id = c.id
        LEFT JOIN genero g ON p.genero_id = g.id
        ORDER BY p.id ASC";

$stmt = $conn->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Listar Produtos</title>
<style>
body { background-color: #1e1e1e; color: #fff; font-family: Arial, sans-serif; }
.container { max-width: 900px; margin: 50px auto; background-color: #2e2e2e; padding: 30px; border-radius: 10px; }
h1 { text-align: center; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #555; padding: 10px; text-align: center; }
th { background-color: #3a3a3a; }
tr:nth-child(even) { background-color: #2a2a2a; }
tr:hover { background-color: #3e3e3e; }
a, button { color: #fff; text-decoration: none; padding: 5px 10px; border-radius: 5px; background-color: #555; border: none; cursor: pointer; }
a:hover, button:hover { background-color: #777; }
img { max-width: 100px; }
.form-editar input, .form-editar select { width: 100%; padding: 8px; margin: 5px 0; border-radius: 5px; border: none; }
</style>
</head>
<body>
<div class="container">
<h1>Produtos Cadastrados</h1>
<a href="form_produto.php">Cadastrar Novo Produto</a>

<table>
<thead>
<tr>
<th>ID</th>
<th>Nome</th>
<th>Preço</th>
<th>Estoque</th>
<th>Categoria</th>
<th>Gênero</th>
<th>Imagem</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
<?php foreach ($produtos as $prod): ?>
<tr>
<td><?= $prod['id'] ?></td>
<td><?= htmlspecialchars($prod['nome']) ?></td>
<td><?= number_format($prod['preco'], 2, ',', '.') ?></td>
<td><?= $prod['estoque'] ?></td>
<td><?= htmlspecialchars($prod['categoria']) ?></td>
<td><?= htmlspecialchars($prod['genero']) ?></td>
<td>
<?php if($prod['imagem_url']): ?>
<img src="<?= htmlspecialchars($prod['imagem_url']) ?>" alt="">
<?php endif; ?>
</td>
<td>
<a href="listar_produtos.php?editar=<?= $prod['id'] ?>">Editar</a> |
<a href="listar_produtos.php?excluir=<?= $prod['id'] ?>" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php if ($produtoEditar): ?>
<div class="form-editar">
<h2>Editar Produto ID <?= $produtoEditar['id'] ?></h2>
<form method="POST" action="listar_produtos.php">
<input type="hidden" name="id" value="<?= $produtoEditar['id'] ?>">

<label>Nome:</label>
<input type="text" name="nome" value="<?= htmlspecialchars($produtoEditar['nome']) ?>" required>

<label>Preço:</label>
<input type="number" name="preco" step="0.01" value="<?= $produtoEditar['preco'] ?>" required>

<label>Estoque:</label>
<input type="number" name="estoque" value="<?= $produtoEditar['estoque'] ?>" required>

<label>Categoria:</label>
<select name="categoria_id" required>
<?php foreach ($categorias as $cat): ?>
<option value="<?= $cat['id'] ?>" 
    <?= $produtoEditar['categoria_id'] == $cat['id'] ? 'selected' : '' ?>>
    <?= htmlspecialchars($cat['nome']) ?>
</option>
<?php endforeach; ?>
</select>

<label>Gênero:</label>
<select name="genero_id" required>
<?php foreach ($generos as $gen): ?>
<option value="<?= $gen['id'] ?>" 
    <?= $produtoEditar['genero_id'] == $gen['id'] ? 'selected' : '' ?>>
    <?= htmlspecialchars($gen['nome']) ?>
</option>
<?php endforeach; ?>
</select>

<button type="submit" name="atualizar">Atualizar Produto</button>
</form>
</div>
<?php endif; ?>

<a href="../index.php" class="btn-voltar">← Voltar ao Painel</a>
</div>
</body>
</html>

<?php $conn = null; ?>
