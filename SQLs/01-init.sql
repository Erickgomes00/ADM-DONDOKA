-- =====================================
-- 👤 Tabela: usuario
-- =====================================
CREATE TABLE IF NOT EXISTS usuario (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo VARCHAR(50) DEFAULT 'cliente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================
-- 🎨 Tabela: genero
-- =====================================
CREATE TABLE IF NOT EXISTS genero (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL
);

-- =====================================
-- 🏷️ Tabela: categoria
-- =====================================
CREATE TABLE IF NOT EXISTS categoria (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL
);

-- =====================================
-- 👕 Tabela: produto
-- =====================================
CREATE TABLE IF NOT EXISTS produto (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco NUMERIC(10,2) NOT NULL,
    estoque INT NOT NULL,
    imagem_url VARCHAR(255),
    ativo BOOLEAN DEFAULT TRUE,
    categoria_id INT NOT NULL,
    genero_id INT NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categoria(id) ON DELETE CASCADE,
    FOREIGN KEY (genero_id) REFERENCES genero(id) ON DELETE CASCADE
);

-- =====================================
-- 🏠 Tabela: endereco_usuario
-- =====================================
CREATE TABLE IF NOT EXISTS endereco_usuario (
    id SERIAL PRIMARY KEY,
    usuario_id INT NOT NULL,
    rua VARCHAR(255),
    numero VARCHAR(10),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    cep VARCHAR(20),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- =====================================
-- 🛒 Tabela: carrinho
-- =====================================
CREATE TABLE IF NOT EXISTS carrinho (
    id SERIAL PRIMARY KEY,
    usuario_id INT,
    status TEXT CHECK (status IN ('Ativo', 'Finalizado', 'Cancelado')) DEFAULT 'Ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
        ON DELETE SET NULL
);

-- =====================================
-- 📦 Tabela: item_carrinho
-- =====================================
CREATE TABLE IF NOT EXISTS item_carrinho (
    id SERIAL PRIMARY KEY,
    carrinho_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0),
    preco_unitario NUMERIC(10,2),
    subtotal NUMERIC(10,2),
    FOREIGN KEY (carrinho_id) REFERENCES carrinho(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produto(id) ON DELETE RESTRICT
);
