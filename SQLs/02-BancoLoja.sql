-- ===============================
-- Inserts padrão (ignora conflitantes)
-- ===============================

-- ===============================
-- Tabela: usuarios
-- Adicionar cpf e telefone
-- ===============================

ALTER TABLE usuarios 
ADD COLUMN IF NOT EXISTS cpf VARCHAR(14) UNIQUE;

ALTER TABLE usuarios 
ADD COLUMN IF NOT EXISTS telefone VARCHAR(20);


-- ===============================
-- Tabela: endereco_usuario
-- Adicionar complemento
-- ===============================

ALTER TABLE endereco_usuario
ADD COLUMN IF NOT EXISTS complemento VARCHAR(255);


INSERT INTO genero (nome)
VALUES 
('Masculino'),
('Feminino'),
('Unissex')
ON CONFLICT (nome) DO NOTHING;

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

-- ===============================
-- Função e triggers de updated_at
-- ===============================

CREATE OR REPLACE FUNCTION update_timestamp()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- trigger usuarios
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM pg_trigger WHERE tgname = 'tg_usuarios_updated'
    ) THEN
        CREATE TRIGGER tg_usuarios_updated
        BEFORE UPDATE ON usuarios
        FOR EACH ROW
        EXECUTE FUNCTION update_timestamp();
    END IF;
END;
$$;

-- trigger endereco_usuario
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM pg_trigger WHERE tgname = 'tg_endereco_usuario_updated'
    ) THEN
        CREATE TRIGGER tg_endereco_usuario_updated
        BEFORE UPDATE ON endereco_usuario
        FOR EACH ROW
        EXECUTE FUNCTION update_timestamp();
    END IF;
END;
$$;
