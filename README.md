# 🎮 Sistema de Cadastro de Jogos — CRUD

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Database-4169E1?style=for-the-badge&logo=postgresql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

## 📌 Sobre o projeto

O **Sistema de Cadastro de Jogos** é uma aplicação web desenvolvida em PHP, utilizando PostgreSQL para armazenar e gerenciar informações sobre jogos eletrônicos.

O projeto implementa as quatro operações fundamentais de um CRUD (*Create, Read, Update e Delete*), permitindo cadastrar, visualizar, atualizar e excluir jogos.

A aplicação também utiliza conceitos de **Programação Orientada a Objetos (POO)** e o padrão de projeto **Repository**, organizando o código em diferentes camadas.

## 🚀 Funcionalidades

O sistema oferece as seguintes funcionalidades:

- ✅ **Cadastrar jogos:** adicionar novos jogos ao banco de dados.
- ✅ **Listar jogos:** visualizar todos os jogos cadastrados.
- ✅ **Editar jogos:** atualizar informações dos registros existentes.
- ✅ **Excluir jogos:** remover jogos cadastrados.
- ✅ **Validar informações:** verificar os dados antes de armazená-los.
- ✅ **Integração com PostgreSQL:** realizar operações no banco utilizando PDO.
- ✅ **Tratamento de erros:** apresentar mensagens quando os dados forem inválidos ou ocorrerem falhas.

## 🛠️ Tecnologias utilizadas

| Tecnologia | Utilização |
|---|---|
| PHP | Desenvolvimento da aplicação e regras de negócio |
| PostgreSQL | Armazenamento dos dados |
| PDO | Conexão e execução de consultas SQL |
| HTML5 | Estrutura das páginas |
| CSS3 | Estilização da interface |
| SQL | Manipulação dos registros |
| Git e GitHub | Versionamento do projeto |

## 📂 Estrutura do projeto

O código está organizado da seguinte maneira:

```text
jogos_crud/
│
├── config/
│   └── Database.php
│
├── interfaces/
│   └── RepositoryInterface.php
│
├── models/
│   └── jogo.php
│
├── repositories/
│   └── JogoRepository.php
│
├── public/
│   ├── index.php
│   ├── criar.php
│   ├── editar.php
│   └── excluir.php
│
├── CREATE TABLE jogos(.pgsql
└── RESPOSTAS.md
```

### Descrição das pastas

- **config:** responsável pela configuração e conexão com o banco de dados.
- **interfaces:** define os métodos obrigatórios para os repositórios.
- **models:** contém a classe Jogo e suas validações.
- **repositories:** executa as consultas SQL e as operações CRUD.
- **public:** contém as páginas acessadas pelo usuário.

O arquivo `RESPOSTAS.md` apresenta explicações sobre os conceitos e atividades relacionados ao desenvolvimento do sistema.

## 🗄️ Banco de dados

O projeto utiliza o **PostgreSQL**, com um banco de dados chamado `crud_jogo`.

A tabela `jogos` possui a seguinte estrutura:

```sql
CREATE TABLE jogos (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    genero VARCHAR(255) NOT NULL,
    plataforma VARCHAR(255) NOT NULL,
    ano INTEGER NOT NULL
);
```

### Campos da tabela

| Campo | Tipo | Descrição |
|---|---|---|
| id | SERIAL | Identificador único do jogo |
| titulo | VARCHAR(255) | Nome do jogo |
| genero | VARCHAR(255) | Gênero do jogo |
| plataforma | VARCHAR(255) | Plataforma disponível |
| ano | INTEGER | Ano de lançamento |

## 💻 Como executar o projeto

### 1. Pré-requisitos

Antes de executar o sistema, tenha instalado:

- PHP 8 ou superior, preferencialmente PHP 8.3;
- PostgreSQL;
- Extensão `pdo_pgsql` habilitada no PHP;
- Git;
- Um editor de código, como o VS Code;
- Um navegador web.

### 2. Clonar o repositório

Abra o terminal e execute:

```bash
git clone https://github.com/Cristantenicollas/jogos_crud.git
```

Entre na pasta:

```bash
cd jogos_crud
```

### 3. Configurar o banco de dados

No PostgreSQL, crie um banco de dados chamado:

```sql
CREATE DATABASE crud_jogo;
```

Depois, conecte-se ao banco `crud_jogo` e execute o script de criação da tabela, disponível no repositório.

### 4. Configurar a conexão

Abra o arquivo:

```text
config/Database.php
```

Atualize as informações de conexão conforme seu ambiente:

- Host do PostgreSQL;
- Porta do servidor;
- Nome do banco de dados;
- Usuário;
- Senha.

**Importante:** evite publicar credenciais reais no GitHub. Prefira utilizar variáveis de ambiente para armazenar informações sensíveis.

### 5. Iniciar o servidor PHP

No terminal, entre na pasta pública:

```bash
cd public
```

Inicie o servidor:

```bash
php -S localhost:8000
```

### 6. Acessar a aplicação

Abra o navegador e acesse:

http://localhost:8000

A página inicial apresentará a listagem de jogos cadastrados e as opções de gerenciamento.

## 🔄 Operações CRUD

O sistema implementa as quatro operações principais:

| Operação | Descrição | Arquivo |
|---|---|---|
| Create | Cadastrar um jogo | `criar.php` |
| Read | Listar jogos cadastrados | `index.php` |
| Update | Editar informações | `editar.php` |
| Delete | Excluir um jogo | `excluir.php` |

As operações SQL são executadas pela classe `JogoRepository`, responsável pela comunicação com o banco de dados.

## 🧠 Conceitos aplicados

Durante o desenvolvimento, foram utilizados conceitos importantes de programação:

- **Programação Orientada a Objetos:** organização do sistema utilizando classes e métodos.
- **Encapsulamento:** controle do acesso aos atributos e comportamentos dos objetos.
- **Interfaces:** definição de contratos para implementar os métodos CRUD.
- **Padrão Repository:** separação das operações de acesso ao banco de dados.
- **PDO:** utilização de uma interface para comunicação com o PostgreSQL.
- **Validação de dados:** verificação das informações enviadas pelos formulários.
- **Separação de responsabilidades:** divisão entre interface, regras de negócio e persistência dos dados.

## 🔒 Validação e segurança

O projeto implementa medidas para melhorar a integridade e a segurança das informações:

- Validação de campos obrigatórios;
- Verificação dos dados antes de salvar;
- Validação dos identificadores dos jogos;
- Utilização de consultas preparadas com PDO;
- Tratamento de exceções durante operações no banco;
- Utilização de `htmlspecialchars()` para exibir informações nos formulários e tabelas.

Essas medidas ajudam a evitar dados inválidos e reduzem riscos comuns em aplicações web.

## 🎯 Objetivo educacional

Este projeto foi desenvolvido com o objetivo de praticar o desenvolvimento de aplicações web com PHP e PostgreSQL, aplicando conceitos de programação orientada a objetos, organização em camadas e manipulação de dados.

A atividade também permite compreender como implementar um CRUD organizado, reutilizável e de fácil manutenção.

## 👨‍💻 Desenvolvedor

**Nícollas Cristante Gonçalves**

GitHub: [@Cristantenicollas](https://github.com/Cristantenicollas)

Repositório: [jogos_crud](https://github.com/Cristantenicollas/jogos_crud)

---

⭐ Projeto desenvolvido para fins educacionais e aprimoramento dos conhecimentos em desenvolvimento web, PHP e banco de dados.
