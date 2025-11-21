<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #2e2e2e;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #000;
        }

        h1 {
            text-align: center;
            color: #ffffff;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin: 15px 0;
        }

        a {
            display: block;
            padding: 15px;
            background-color: #555;
            color: #fff;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #777;
        }

        .image-container {
            max-width: 600px;
            margin: 30px auto;
            text-align: center;
        }

        .image-container img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #000;
        }

        @media (max-width: 500px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            a {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Painel de Administração</h1>
        <ul>
            <li><a href="Produto/form_produto.php">Cadastrar Produto</a></li>
            <li><a href="Produto/listar_produtos.php">Listar Produtos</a></li>

        </ul>
    </div>

    <!-- Imagem grande abaixo do painel -->
    <div class="image-container">
        <img src="img/LogoDondoka.png" alt="Imagem do Painel">
    </div>
</body>
</html>
