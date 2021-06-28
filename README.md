# Configuração do sistema

## Instalando o projeto
## Procedimentos
- Procedimentos.  
- composer install
- copiar .env.exemple e alterar o nome para .env
- colocar as informações do seu banco de dados no .env
- composer require laravel/passport "~9.0"
- php artisan config:cache
- php artisan cache:clear
- php artisan migrate
- php artisan passport:install
- php artisan key:generate
- php artisan db:seed
- Obs: se ocorrer erro de key, siga os passos abaixo:
- php artisan passport:install
- composer dump-autoload
- php artisan config:cache
- php artisan cache:clear

## Parâmetros para cadastrar um usuário
**endpoint: {{caminhoserver}}/api/register**
- name (string)
- email (string)
- cpf (string)
- phone (string)
- password (string)

## Para usar todas as funções do sistema

Para criar uma conta ou fazer qualquer outro tipo de rotina no sistema, será necessário ter um usuário cadastrado no sistema. No postman, configure o header com key Authorization e Value tipo Bearer  + valor do token;

## Parâmetros para criar uma conta
**endpoint: {{caminhoserver}}/api/account**

**Tipo Company:**
- cpf_cnpj (string)
- social_reason (string)
- fantasy_name (string)
- name (string)

**Tipo Person:**
- cpf_cnpj (string)
- name (string)

## Parâmetros para Transações
**Deposito**
**endpoint: {{caminhoserver}}/api/transaction/deposit**
- value(float)
- document(string)
- description(string)

**Recarga de telefone**
**endpoint: {{caminhoserver}}/api/transaction/cell_recharge**
- value(float)
- document(string)
- description(string)
- phone(string)

**Pagamento**
**endpoint: {{caminhoserver}}/api/transaction/bill_payment**
- value(float)
- document(string)
- description(string)

**Transferência**
**endpoint: {{caminhoserver}}/api/transaction/transfer**
- cpf_cnpj(string)
- agency(string)
- number_account(string)
- value(float)
- document(string)
- description(string)

**Pagar Cartão**
**endpoint: {{caminhoserver}}/api/transaction/card_pay**
- value(float)
- document(string)
- description(string)

## Parâmetros para mostrar todas as informações do usuário
**endpoint: {{caminhoserver}}/api/users**
- name(string)
- cpf(string)

## Parâmetros para cadastrar o cartão para conta
**endpoint: {{caminhoserver}}/api/card/new_card**

## Parâmetros para usar o cartão
**endpoint: {{caminhoserver}}/api/card/card_transaction**
- value(float)
- store(string)



