-- ======================================================
-- CRIAR O BANCO DE DADOS
-- ======================================================

CREATE DATABASE IF NOT EXISTS sci3d_db;
USE sci3d_db;

-- ======================================================
-- APAGAR AS TABELAS CASO JÁ EXISTAM
-- (isso evita erro quando você rodar mais de uma vez)
-- ======================================================

DROP TABLE IF EXISTS resetasenha;
DROP TABLE IF EXISTS usuarios;

-- ======================================================
-- TABELA: usuarios
-- essa é a tabela principal do sistema (login/cadastro)
-- ======================================================

CREATE TABLE usuarios (

  -- ID do usuário (gerado automaticamente)
  id INT AUTO_INCREMENT PRIMARY KEY,

  -- Nome do usuário
  nome VARCHAR(100) NOT NULL,

  -- Email (não pode repetir)
  email VARCHAR(100) NOT NULL UNIQUE,

  -- Senha criptografada (bcrypt no Java)
  senha VARCHAR(255) NOT NULL,

  -- Data em que o usuário foi criado
  data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  -- Código usado quando o usuário esquece a senha
  codigo_recuperacao VARCHAR(6) DEFAULT NULL,

  -- Data em que o código de recuperação expira
  codigo_expira DATETIME DEFAULT NULL,

  -- Tipo da foto (ex: image/png, image/jpeg)
  photo_type VARCHAR(50) DEFAULT NULL,

  -- Foto do usuário salva direto no banco
  photo LONGBLOB DEFAULT NULL,

  -- Descrição do usuário (bio/perfil)
  description TEXT DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- TABELA: resetasenha
-- usada para criar token quando o usuário esquece a senha
-- ======================================================

CREATE TABLE resetasenha (

  -- ID do reset
  id INT AUTO_INCREMENT PRIMARY KEY,

  -- ID do usuário que pediu recuperação
  usuario_id INT NOT NULL,

  -- Token gerado pelo sistema (UUID ou string aleatória)
  token VARCHAR(255) NOT NULL,

  -- Data em que o token deixa de funcionar
  expira_em DATETIME NOT NULL,

  -- Liga essa tabela com a tabela usuarios
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================================================
-- USUÁRIO DE TESTE
-- senha = 123456 (já criptografada com bcrypt)
-- ======================================================

INSERT INTO usuarios (
  nome,
  email,
  senha,
  description
) VALUES (
  'brayan',
  'brayan@gmail.com',
  '$2y$10$56sHJsMm3I0HJPnGzBcgrOTEHGxbS1As.1OA0rbeATBjhvc3bcCq2',
  'Usuário de teste criado para login no sistema'
);