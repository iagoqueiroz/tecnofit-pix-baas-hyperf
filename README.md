# 🚀 HyperF - Pix Baas (Teste Técnico Tecnofit)

Este projeto utiliza o **[Hyperf](https://hyperf.io/)** como base, rodando em um ambiente **Docker** com suporte a PHP, MySQL e Mailhog (para envio e testes de e-mails).

---

## 📦 Pré-requisitos

Antes de começar, você precisa ter instalado em sua máquina:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Composer](https://getcomposer.org/)
- PHP >= 8.2

## ⚙️ Instalação do Projeto

### 1. Clone o repositório
```bash
git clone https://github.com/iagoqueiroz/tecnofit-pix-baas-hyperf.git
cd tecnofit-pix-baas-hyperf
```

### 2. Estrutura do Projeto e Execução

#### 2.1. Estrutura de Diretórios

O projeto segue a estrutura padrão do HyperF, separando a lógica em Services e também fazendo o uso de DTOs, Enums e ValueObjects para melhor representação dos dados e validação.

#### 2.2. Inicialização do Ambiente

O projeto é totalmente dockerizado. Para iniciar o ambiente, execute os seguintes comandos na raiz do projeto:

```bash
# Copiar variáveis de ambiente
cp .env.example .env

# 1. Instalar dependencias do composer
composer install --ignore-platform-req=ext-redis

# 2. Construir e iniciar os containers (app, mysql, mailhog)
docker compose up -d --build

# 3. Rodar as migrations para criar as tabelas no MySQL
docker compose exec app php bin/hyperf.php migrate:fresh

# 4. Inserir uma conta de teste (Exemplo: UUID ef0001c3-c9cc-41a3-aa7a-b08b8a818108 com R$ 500,00)
docker compose exec app php bin/hyperf.php db:seed
# (O seeder precisa ser criado, mas para fins de teste manual, pode-se usar o comando SQL)
# Exemplo de SQL:
# INSERT INTO `account` (`id`, `name`, `balance`, `created_at`, `updated_at`) VALUES ('ef0001c3-c9cc-41a3-aa7a-b08b8a818108', 'John Doe', 500.00, NOW(), NOW());
```

### 3. Endpoints

O serviço estará disponível na porta  do container .

| Método | URL | Descrição |
| :--- | :--- | :--- |
| POST | `/account/{accountId}/balance/withdraw` | Realiza o saque PIX (imediato ou agendado). |

**Exemplo de Requisição (Saque Imediato):**

```json
POST /account/ef0001c3-c9cc-41a3-aa7a-b08b8a818108/balance/withdraw
{
    "method": "PIX",
    "amount": 150.00,
    "schedule": null,
    "pix": {
        "type": "email",
        "key": "fulano@email.com"
    }
}
```

**Exemplo de Requisição (Saque Agendado):**

```json
POST /account/ef0001c3-c9cc-41a3-aa7a-b08b8a818108/balance/withdraw
{
    "method": "PIX",
    "amount": 150.00,
    "schedule": "2025-11-12 15:00",
    "pix": {
        "type": "email",
        "key": "fulano@email.com"
    }
}
```

### 4. Processamento de Saques Agendados

O processamento é feito por um comando de console configurado no Crontab do Hyperf para rodar a cada minuto.
Você também pode executar manualmente com o comando abaixo:

```bash
# Comando executado pelo Crontab
docker compose exec app php bin/hyperf.php withdraw:process-scheduled
```

### 5. Ferramentas (Mailhog)

Os e-mails de notificação de saque serão capturados pelo Mailhog, que pode ser acessado em `http://localhost:8025`.
