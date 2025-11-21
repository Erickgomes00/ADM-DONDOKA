-- Inserts de genero
INSERT INTO genero (nome)
VALUES 
('Masculino'),
('Feminino'),
('Unissex')
ON CONFLICT (nome) DO NOTHING;

-- Inserts de categoria
INSERT INTO categoria (nome)
VALUES
('Calca'),
('Camisa'),
('Blusa'),
('Jaqueta'),
('Casacos'),
('Jeans'),
('Vestidos'),
('Saias e Shorts'),
('Moda Praia'),
('Moda Inverno')
ON CONFLICT (nome) DO NOTHING;
