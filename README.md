# 🦎 Exotic Pets Emporium

## 📝 Descrição

O **Exotic Pets Emporium** é uma plataforma de e-commerce desenvolvida em PHP puro utilizando a arquitetura **MVC (Model-View-Controller)**. O sistema simula a adoção de animais exóticos, oferecendo uma experiência completa desde o catálogo de produtos até o checkout com integração de pagamentos.

O projeto conta com uma área administrativa robusta para gestão de estoque, visualização de indicadores financeiros através de gráficos interativos e controle de mensagens de contato.

## 🚀 Funcionalidades

* **Arquitetura MVC:** Estrutura organizada em Models, Views e Controllers para fácil manutenção.
* **Autenticação de Usuários:** Sistema de Login e Registro com diferenciação de níveis de acesso (Admin/Cliente).
* **Catálogo de Produtos:** Visualização de animais com detalhes, fotos e preços.
* **Carrinho de Compras:** Adição, remoção e atualização de itens no cesto de adoção.
* **Checkout e Pagamentos:**
    * Simulação de Cartão de Crédito, Boleto e PIX.
    * **Integração com Mercado Pago SDK** para processamento de pagamentos.
* **Dashboard Administrativo:**
    * Gráficos interativos (Chart.js) para total de adoções, usuários e animais.
    * Filtros de data para relatórios.
    * Listagem de atividades recentes.
* **Banco de Dados Avançado:** Utilização de Procedures, Triggers e Functions para auditoria e lógica de negócio diretamente no banco.

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
    *(Nota: O TypeScript é compilado para a pasta `public/js` conforme configurado no `tsconfig.json`)*

4.  **Configuração do Banco de Dados:**
    * Crie um banco de dados chamado `e-comercce` no seu MySQL.
    * Verifique as credenciais no arquivo `config/database.php` e ajuste se necessário.
    * **Importante:** Execute os scripts SQL abaixo para criar a estrutura necessária.

### 🗄️ Scripts SQL (Setup do Banco)

Execute os comandos abaixo no seu gerenciador de banco de dados (ex: PHPMyAdmin, DBeaver ou Workbench) na ordem apresentada:

**1. Criação das Tabelas**

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
