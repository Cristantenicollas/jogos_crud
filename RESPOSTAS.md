# Respostas das Atividades — MSEP

> Observação: o enunciado cita `Cliente` e "PHP + MySQL", mas o projeto implementado é de **Jogos** e usa **PostgreSQL** (`pgsql:` em `config/Database.php`). As respostas abaixo seguem o código real; onde o enunciado fala `Cliente`, leia-se `Jogo`.

---

## 1. Exercícios de apropriação

### 1.1 Onde ocorre o CRUD

O CRUD é dividido em duas camadas: **as telas** (recebem a requisição e mostram o HTML) e o **repository** (executa o SQL).

| Operação | Tela (entrada/saída) | Método SQL |
|---|---|---|
| **C**reate | `public/criar.php` — formulário + `POST` | `JogoRepository::criar()` → `INSERT INTO jogos` |
| **R**ead | `public/index.php` — listagem<br>`public/editar.php` — carrega um registro | `JogoRepository::listar()` → `SELECT * ... ORDER BY id`<br>`JogoRepository::buscarPorID()` → `SELECT ... WHERE id = :id` |
| **U**pdate | `public/editar.php` — formulário preenchido + `POST` | `JogoRepository::atualizar()` → `UPDATE jogos SET ... WHERE id = :id` |
| **D**elete | `public/excluir.php` — link com confirmação | `JogoRepository::excluir()` → `DELETE FROM jogos WHERE id = :id` |

O contrato do CRUD está em `interfaces/RepositoryInterface.php` (`criar`, `listar`, `buscarPorID`, `atualizar`, `excluir`), e a conexão PDO fica em `config/Database.php`.

### 1.2 Onde está a regra de negócio

Está na **entidade**, em `models/jogo.php`:

- `Jogo::validar($dados)` — título, gênero e plataforma obrigatórios e com no máximo 255 caracteres; ano inteiro entre 1950 e o ano atual + 1.
- `Jogo::validarId($id)` — garante que o ID recebido pela URL/POST é inteiro e maior ou igual a 1.

As telas apenas **orquestram**: recebem os dados, chamam `Jogo::validar()`, criam o objeto `Jogo`, chamam o repository e redirecionam. O repository **só persiste** — não decide se um dado é válido. Exemplo em `public/criar.php`:

```php
$erros = Jogo::validar($_POST);
if (!$erros) {
    $jogo = new Jogo(trim($_POST['titulo']), ...);
    (new JogoRepository())->criar($jogo);
}
```

### 1.3 Por que a classe `Cliente`/`Jogo` não acessa o banco diretamente

1. **Separação de responsabilidades:** a entidade conhece seus dados e suas regras; o repository conhece SQL; a tela conhece HTTP/HTML. Cada classe tem um único motivo para mudar.
2. **Ponto único de manutenção:** se o banco mudar (MySQL → PostgreSQL, troca de host, tabela renomeada), altera-se apenas o repository/`Database`, sem tocar na entidade nem nas telas.
3. **Reutilização:** a mesma entidade e o mesmo repository servem para qualquer tela ou parte do sistema, sem duplicar SQL.
4. **Testabilidade:** é possível validar as regras (`Jogo::validar()`) sem abrir conexão com o banco.
5. **Segurança:** o SQL fica concentrado no repository, com *prepared statements* (`:id`, `:t`...), evitando SQL Injection e evitando que cada tela invente sua própria consulta.
6. **Baixo acoplamento:** a tela depende do contrato `RepositoryInterface`, não de detalhes de conexão (base do padrão Repository).

Se a entidade abrisse conexão, ela misturaria regra de negócio com persistência, repetiria código de conexão e ficaria impossível de testar ou trocar de banco sem reescrevê-la.

---

## 2. Exercícios de aplicação

### 2.1 Tela de edição — `public/editar.php`

Fluxo implementado:

1. **GET** `editar.php?id=X`: valida o ID com `Jogo::validarId()`; se inválido, mostra "ID inválido" e link de volta.
2. Busca o registro com `buscarPorID()`; se não existir, mostra "Jogo não encontrado".
3. Renderiza o formulário já preenchido, com o ID em campo `hidden`.
4. **POST**: valida os campos com `Jogo::validar()` **e** valida o ID; se houver erro, exibe a lista de erros e **mantém o que o usuário digitou** (formulário "sticky").
5. Sem erros: monta o objeto `Jogo`, chama `atualizar()` e redireciona para `index.php`.
6. `PDOException` é capturada e vira mensagem amigável, sem expor detalhes do banco.

Proteções presentes: `htmlspecialchars()` em todos os valores exibidos (XSS) e *prepared statements* no repository.

### 2.2 Tela de exclusão — `public/excluir.php`

1. Valida o ID recebido (`Jogo::validarId()`); inválido → mensagem e link de volta.
2. Chama `excluir($id)` dentro de `try/catch`.
3. Sucesso → `header('Location: index.php')`; falha → mensagem amigável.
4. Em `index.php`, o link de exclusão pede confirmação com `confirm('Deseja excluir?')`.

**Melhorias recomendadas (nível profissional):**
- Exclusão por **POST + token CSRF**, não por link GET (links GET podem ser disparados por prefetch, crawlers ou histórico do navegador).
- Usar `rowCount()` para avisar quando o ID não existe (hoje um ID inexistente "exclui com sucesso" sem excluir nada).
- Registrar/logar a exclusão (auditoria).

### 2.3 Teste de erros e validação (executado)

Todos os arquivos passaram em `php -l` (sem erro de sintaxe) e a validação foi testada na prática:

| Caso testado | Resultado |
|---|---|
| Dados válidos (`God of War`, `Acao`, `PS5`, `2018`) | **OK** — nenhum erro |
| Todos os campos vazios | 4 erros: título, gênero, plataforma e ano obrigatórios |
| Ano `1800` | Erro: ano deve estar entre 1950 e 2027 |
| Ano `"abc"` | Erro: ano deve ser inteiro |
| Título com 256 caracteres | Erro: máximo 255 caracteres |
| ID `5` / `0` / `"abc"` | válido / inválido / inválido |

Teste de integração com o banco real (`crud_jogo`, PostgreSQL em `192.168.10.37`) — registro de teste criado e removido ao final:

```
1) criar: OK
2) listar/buscar: OK (id=4)
   buscarPorID: OK
3) atualizar: OK
4) excluir: OK
5) dados inválidos bloqueados: OK (4 erros)
   nada foi gravado: OK
```

Conclusão dos testes: o CRUD funciona de ponta a ponta, dados inválidos são bloqueados **antes** de chegar ao banco e o formulário devolve os erros ao usuário.

---

## 3. Desafio profissional

> "O sistema será ampliado para **Clientes**" (ou outra entidade).

### 3.1 O Repository pode ser reutilizado?

**O contrato e o padrão, sim. A classe concreta, não.**

- `RepositoryInterface` é reutilizável: qualquer entidade que tenha CRUD pode implementá-la.
- `Database::conectar()` é reutilizável: a mesma conexão PDO serve para todos os repositories.
- `JogoRepository` **não** é reutilizável para `Cliente`, porque o SQL é específico (tabela `jogos`, colunas `titulo/genero/plataforma/ano`).

O correto é criar `ClienteRepository implements RepositoryInterface`, mantendo o mesmo contrato. Uma evolução possível é extrair um `AbstractRepository` com o código repetido de `prepare/execute/fetch` e deixar cada repository só com o SQL específico.

### 3.2 O que muda?

1. **Nova tabela** no banco (`clientes`), com suas colunas.
2. **Nova entidade** `models/cliente.php`: atributos, construtor, getters e `validar()` com as regras do cliente (ex.: nome obrigatório, e-mail válido e único, CPF etc.).
3. **Novo repository** `repositories/ClienteRepository.php` implementando `RepositoryInterface`, com o SQL da tabela `clientes`.
4. **Novas telas** (ou telas genéricas): `criar`, `editar`, `excluir` e listagem para clientes, além do item no menu.
5. **Regras de negócio específicas** (validação de e-mail, CPF, duplicidade), que não existem em `Jogo`.
6. Se houver relação (ex.: cliente possui jogos), surgem **chave estrangeira** e consultas com `JOIN`.

### 3.3 O que permanece igual?

- A **arquitetura em camadas** (tela → entidade → repository → banco).
- O **padrão Repository** e a **interface** `RepositoryInterface`.
- O **PDO** e o **singleton de conexão** em `config/Database.php`.
- O **fluxo das telas**: listar → criar → editar → excluir, com validação, mensagens de erro, *sticky form*, `htmlspecialchars` e redirecionamento.
- As **boas práticas de segurança**: *prepared statements*, validação de ID, tratamento de `PDOException`.
- A separação de responsabilidades: entidade não acessa o banco; repository não valida regra de negócio; tela não contém SQL.

**Resumo:** a estrutura permanece; muda apenas a entidade (model), o repository concreto, a tabela e as telas específicas. É exatamente o ganho do padrão Repository: **a arquitetura é reutilizada, o código específico é que muda**.

---

## 4. Quadro-resumo

| Pergunta | Resposta curta |
|---|---|
| Onde ocorre o CRUD? | Telas em `public/` chamam os métodos de `JogoRepository`, que executa o SQL. |
| Onde está a regra de negócio? | `models/jogo.php` — `validar()` e `validarId()`. |
| Por que a entidade não acessa o banco? | Separação de responsabilidades, reutilização, testabilidade, segurança e baixo acoplamento. |
| Edição implementada? | Sim — `public/editar.php`, com validação e formulário preenchido. |
| Exclusão implementada? | Sim — `public/excluir.php`, com validação de ID e confirmação na listagem. |
| Erros testados? | Sim — sintaxe, campos vazios, ano inválido, título longo, IDs inválidos e CRUD real no banco. |
| O Repository pode ser reutilizado? | O contrato/sim; a classe concreta/não — cria-se um repository por entidade. |
| O que muda ao ampliar? | Tabela, model, repository concreto, telas e regras específicas. |
| O que permanece igual? | Camadas, interface, PDO, fluxo das telas, segurança e boas práticas. |
