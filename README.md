# 🦎 Exotic Pets Emporium

![Badge em Desenvolvimento](http://img.shields.io/static/v1?label=STATUS&message=EM%20DESENVOLVIMENTO&color=GREEN&style=for-the-badge)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)
![TypeScript](https://img.shields.io/badge/typescript-%23007ACC.svg?style=for-the-badge&logo=typescript&logoColor=white)
![Bootstrap](https://img.shields.io/badge/bootstrap-%23563D7C.svg?style=for-the-badge&logo=bootstrap&logoColor=white)

## 📝 Descrição

O **Exotic Pets Emporium** é uma plataforma de e-commerce desenvolvida em PHP puro utilizando a arquitetura **MVC (Model-View-Controller)**. O sistema simula a adoção de animais exóticos, oferecendo uma experiência completa desde o catálogo de produtos até o checkout com integração de pagamentos.

O projeto conta com uma área administrativa robusta para gestão de estoque, visualização de indicadores financeiros através de gráficos interativos e controle de mensagens de contato.

## 🚀 Funcionalidades

* **Arquitetura MVC:** Estrutura organizada em Models, Views e Controllers.
* **Autenticação de Usuários:** Sistema de Login e Registro com diferenciação de níveis de acesso (Admin/Cliente).
* **Catálogo de Produtos:** Visualização de animais com detalhes, fotos e preços.
* **Carrinho de Compras:** Adição, remoção e atualização de itens no cesto de adoção.
* **Checkout e Pagamentos:**
    * Simulação de Cartão de Crédito, Boleto e PIX.
    * **Integração com Mercado Pago SDK**.
* **Dashboard Administrativo:**
    * Gráficos interativos (Chart.js) para total de adoções, usuários e animais.
    * Filtros de data para relatórios.
    * Listagem de atividades recentes.
* **Banco de Dados Avançado:** Utilização de Procedures, Triggers e Functions para auditoria e lógica de negócio.

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP 8.2+
* **Banco de Dados:** MySQL
* **Frontend:** HTML5, CSS3, Bootstrap 5
* **Scripts:** TypeScript (compilado para JS), Chart.js
* **Gerenciamento de Dependências:** Composer (PHP), NPM (Node.js)

## 📦 Instalação e Configuração

### Pré-requisitos
* Servidor Web (Apache/Nginx)
* PHP >= 8.2
* MySQL
* Composer
* Node.js & NPM

### Passo a Passo

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/seu-usuario/e-commerce.git](https://github.com/seu-usuario/e-commerce.git)
    cd e-commerce
    ```

2.  **Instale as dependências do Backend:**
    ```bash
    cd public
    composer install
    ```

3.  **Instale as dependências do Frontend:**
    ```bash
    npm install
    ```
    *(Nota: O TypeScript é compilado para a pasta `public/js` conforme configurado no `tsconfig.json`)*.

4.  **Configuração do Banco de Dados:**
    * Crie um banco de dados chamado `e-comercce`.
    * Configure as credenciais no arquivo `config/database.php`.
    * **Importante:** Execute os scripts SQL abaixo para criar a estrutura necessária.

### 🗄️ Scripts SQL (Setup do Banco)

Execute os comandos abaixo no seu gerenciador de banco de dados (ex: PHPMyAdmin ou DBeaver):

<details>
<summary><strong>1. Criação das Tabelas</strong> (Clique para expandir)</summary>

```sql
CREATE TABLE `usuarios` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `nome` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) NOT NULL DEFAULT 'cliente',
    `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `animais` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `especie` VARCHAR(100) NOT NULL,
    `origem` VARCHAR(100),
    `data_nascimento` DATE,
    `preco` DECIMAL(10, 2) NOT NULL,
    `estoque` INT NOT NULL DEFAULT 0,
    `descricao` TEXT,
    `imagem_url` VARCHAR(255),
    `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `adocoes` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `usuario_id` INT NOT NULL,
    `valor_total` DECIMAL(10, 2) NOT NULL,
    `data_adocao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `endereco_logradouro` VARCHAR(255) DEFAULT NULL,
    `endereco_numero` VARCHAR(50) DEFAULT NULL,
    `endereco_complemento` VARCHAR(100) DEFAULT NULL,
    `endereco_bairro` VARCHAR(100) DEFAULT NULL,
    `endereco_cidade` VARCHAR(100) DEFAULT NULL,
    `endereco_estado` VARCHAR(50) DEFAULT NULL,
    `endereco_cep` VARCHAR(20) DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `adocao_itens` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `adocao_id` INT NOT NULL,
    `animal_id` INT NOT NULL,
    `quantidade` INT NOT NULL DEFAULT 1,
    `preco_unitario` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (adocao_id) REFERENCES adocoes (id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES animais (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pagamentos` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `adocao_id` INT NOT NULL,
    `metodo_pagamento` VARCHAR(50) NOT NULL,
    `status_pagamento` VARCHAR(50) NOT NULL,
    `transacao_id` VARCHAR(255) DEFAULT NULL,
    `nome_cartao` VARCHAR(255) DEFAULT NULL,
    `numero_cartao_final` VARCHAR(4) DEFAULT NULL,
    `validade_cartao` VARCHAR(7) DEFAULT NULL,
    `data_pagamento` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (adocao_id) REFERENCES adocoes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `contato_mensagens` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `assunto` VARCHAR(255) NOT NULL,
    `mensagem` TEXT NOT NULL,
    `data_envio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `lido` TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `auditoria_precos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `animal_id` INT NOT NULL,
    `preco_antigo` DECIMAL(10, 2),
    `preco_novo` DECIMAL(10, 2),
    `usuario_modificacao` VARCHAR(255),
    `data_modificacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES animais(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
</details>

<details> <summary><strong>2. Índices e Otimização</strong></summary>

SQL

CREATE INDEX idx_animais_busca ON animais(especie, origem);
CREATE INDEX idx_adocoes_usuario_id ON adocoes(usuario_id);
CREATE INDEX idx_adocao_itens_adocao_id ON adocao_itens(adocao_id);
CREATE INDEX idx_adocao_itens_animal_id ON adocao_itens(animal_id);
CREATE INDEX idx_pagamentos_adocao_id ON pagamentos(adocao_id);
</details>

<details> <summary><strong>3. Triggers, Functions e Procedures</strong> (Copiar um bloco por vez)</summary>

SQL

DELIMITER $$
CREATE FUNCTION `fn_verifica_estoque`(
    p_animal_id INT,
    p_quantidade_desejada INT
) RETURNS BOOLEAN
READS SQL DATA
BEGIN
    DECLARE v_estoque_atual INT;
    SELECT estoque INTO v_estoque_atual FROM animais WHERE id = p_animal_id;
    IF v_estoque_atual >= p_quantidade_desejada THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END$$
DELIMITER ;
SQL

DELIMITER $$
CREATE TRIGGER `trg_auditoria_preco_update`
BEFORE UPDATE ON `animais`
FOR EACH ROW
BEGIN
    IF OLD.preco <> NEW.preco THEN
        INSERT INTO auditoria_precos (animal_id, preco_antigo, preco_novo, usuario_modificacao)
        VALUES (OLD.id, OLD.preco, NEW.preco, USER()); 
    END IF;
END$$
DELIMITER ;
SQL

DELIMITER $$
CREATE PROCEDURE `sp_insere_animais_massa`(IN p_quantidade_inserir INT)
BEGIN
    DECLARE i INT DEFAULT 1;
    WHILE i <= p_quantidade_inserir DO
        INSERT INTO `animais` (especie, origem, preco, estoque, descricao, data_nascimento, data_cadastro)
        VALUES (CONCAT('Animal de Teste ', i), 'Origem de Teste', RAND() * 1000 + 50, 10, 'Descrição de teste.', '2025-01-01', NOW());
        SET i = i + 1;
    END WHILE;
END$$
DELIMITER ;
</details>

<details> <summary><strong>4. Dados Iniciais (Admin)</strong></summary>

SQL

-- Senha padrão: admin
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `role`) VALUES
('Admin', 'admin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
</details>

👨‍💻 Autores
Lucas de Fiori Viudes

Vitto Lorenzo Barboza Legnani

Lucas Gozer Lopes
