<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></p>

<h1 align="center">Sistema de Agendamento Médico</h1>

<p align="center">
Um sistema completo para gerenciamento de pacientes e agendamentos médicos desenvolvido com Laravel e tecnologias modernas.
</p>

## Sobre o Projeto

O Sistema de Agendamento Médico é uma aplicação web completa para clínicas e consultórios médicos, permitindo o gerenciamento eficiente de pacientes, responsáveis e agendamentos. Desenvolvido com Laravel e seguindo os princípios SOLID, o sistema oferece uma interface moderna e intuitiva para facilitar o dia a dia dos profissionais de saúde.

### Principais Funcionalidades

- Cadastro e gerenciamento de pacientes
- Vinculação de responsáveis aos pacientes
- Agendamento de consultas
- Dashboard com informações relevantes
- Relatórios e estatísticas

## Requisitos do Sistema

- Docker e Docker Compose
- Git
- Node.js (para json-server)

## Configuração com Docker

O projeto está configurado para ser executado facilmente com Docker, sem necessidade de instalação local do PHP, MySQL ou outras dependências.

### 1. Clone o Repositório

```bash
git clone https://github.com/seu-usuario/medical-scheduling-system.git
cd medical-scheduling-system
```

### 2. Configure o Arquivo .env

```bash
cp .env.example .env
```

### 3. Instale as dependências do Composer

```bash
composer install
```

### 4. Instale as dependências do npm e compile os assets

```bash
npm install
npm run build
```

### 5. Inicie os Containers Docker

```bash
docker compose up --build -d
```

Este comando iniciará os seguintes serviços:
- **app**: Servidor PHP com Laravel (porta 8000)
- **db**: Servidor MySQL (porta 3310)
- **mockapi**: API mock de médicos usando json-server (porta 3000)

### 6. Execute as Migrações e Seeders

```bash
docker compose exec app php artisan migrate
```

### 7. Acesse o Sistema

Acesse o sistema em http://localhost:8000

### Mock de médicos via Docker

O serviço mockapi roda o json-server em um container Docker, expondo a API de médicos em http://localhost:3000. Os dados estão no arquivo `resources/mocks/doctors.json`. Ao alterar esse arquivo, o mock é atualizado automaticamente.

**Atenção:** Todas as cidades dos médicos cadastrados são apenas "Vitória" ou "Vila Velha".

## Comandos Docker Úteis

### Visualizar Logs

```bash
docker compose logs -f
```

### Acessar o Terminal do Container

```bash
docker compose exec app sh
```

### Parar os Containers

```bash
docker compose down
```

### Reconstruir os Containers (após alterações no Dockerfile)

```bash
docker compose up -d --build
```

## Estrutura do Projeto

O projeto segue a arquitetura MVC do Laravel com algumas adaptações para seguir os princípios SOLID:

- **Controllers**: Responsáveis por receber requisições e delegar para os serviços
- **Services**: Contêm a lógica de negócio da aplicação
- **Models**: Representam as entidades do banco de dados
- **Views**: Interface do usuário usando Blade e TailwindCSS
- **Mocks**: Dados fictícios para desenvolvimento e testes

## Configuração do json-server

O projeto utiliza json-server para simular uma API REST durante o desenvolvimento, especialmente para os dados de médicos.

Ela roda em um container Docker, expondo a API de médicos em http://localhost:3000. Os dados estão no arquivo `resources/mocks/doctors.json`. Ao alterar esse arquivo, o mock é atualizado automaticamente.

Caso queira iniciar o mock sem o docker, siga os comandos abaixo:

### Instalação do json-server

```bash
npm install -g json-server
```

Ou, se preferir instalar como dependência de desenvolvimento do projeto:

```bash
npm install --save-dev json-server
```

### Utilizando o json-server

Para iniciar o servidor mock de médicos:

```bash
json-server --watch resources/mocks/doctors.json --port 3000
```

Isso disponibilizará uma API REST completa em `http://localhost:3000` com os seguintes endpoints:

- `GET /doctors` - Lista todos os médicos
- `GET /doctors/:id` - Obtém um médico específico pelo ID
- `POST /doctors` - Cria um novo médico
- `PUT /doctors/:id` - Atualiza um médico existente
- `DELETE /doctors/:id` - Remove um médico

### Estrutura do mock de médicos

Cada objeto médico contém:

```json
{
  "id": "doc101",
  "nome": "Dra. Ana Silva",
  "especialidade": "Clínico Geral",
  "crm": "SP123456",
  "cidade": "Vitória" ou "Vila Velha",
  "disponibilidade": [...],
  "avaliacao_media": 4.8
}
```

## Fluxo do usuário

1. **Cadastro de usuário:**
   - O usuário realiza seu registro para acessar o sistema.
2. **Cadastro de paciente e responsável:**
   - Após o login, o usuário pode cadastrar um paciente e associar um responsável (caso necessário).
3. **Criação de consulta:**
   - Com paciente e responsável cadastrados, é possível agendar uma consulta médica para o paciente.

Esse fluxo garante que apenas usuários autenticados possam cadastrar pacientes, responsáveis e criar consultas, mantendo a organização e segurança dos dados.

## Diagrama de Entidades
```
+----------------+           +---------------------+
|   Pacientes    |<----------|    Responsaveis     |
+----------------+ 1      N  +---------------------+
| id (PK)        |           | id (PK)             |
| nome           |           | paciente_id (FK)    |
| cpf (UNIQUE)   |           | nome                |
| data_nascimento|           | cpf (UNIQUE)        |
| idade          |           | grau_parentesco     |
| cep            |           +---------------------+
| logradouro     |
| numero         |
| bairro         |
| cidade         |
| uf             |
+----------------+
        |
        | 1
        |    
        | N
+---------------------+
|   Agendamentos      |
+---------------------+
| id (PK)             |
| paciente_id (FK)    |
| nome_medico         |
| especialidade       |
| data_hora           |
| status              | <- agendado, cancelado, realizado
+---------------------+

+---------------------+
|   LogIntegracao     |
+---------------------+
| id (PK)             |
| endpoint            |
| metodo              |
| payload             |
| resposta            |
| status_http         |
| erro (nullable)     |
| criado_em           |
+---------------------+
```
