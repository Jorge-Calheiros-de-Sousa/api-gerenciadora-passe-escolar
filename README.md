# api-gerenciadora-passe-escolar
Api que gerencia as viagens e recargas do passe escolar apenas usando PHP.

### Requisitos
- Composer
- versão  do php >=7
- Docker

### Passos necessários

- Clone o repositório
- Execute o comando `composer update`
- Mude o nome do arquivo `env.example` para `.env`
- Execute o comando `docker-compose up -d`
- Preencha as variaveis do arquivo `.env` de acordo com as intruções abaixo:


    |Variavel  |Valor  |
    |---------|---------|
    |DB_HOST     |(rost do banco)         |
    |DB_NAME    |banco         |
    |DB_USER     |root         |
    |DB_PASSWORD     |123         |
- Crie o banco de dados acessando o phpmyadmin por [http://localhost:8000](http://localhost:8000) e insira as informações `Ultilizador: root` e `Senha: 123`
- Importe os comandos sql dentro da pasta `bd` do repositorio para o phpmyadmin.
- Pronto agora a aplicação já esta executando

## Portas da aplicação


|Porta  |Descrição  |
|---------|---------|
|8001    |aplicação(http)      |
|8000     |phpmyadmin         |


## Comandos do docker

|Comando |Descrição |
|---------|---------|
|docker-compose up -d    |Baixa o container ps: apenas executar uma vez         |
|docker-compose start     | executa o container        |
|docker-compose stop     | para o container        |
|docker ps    |mostra todos os containers executados no momento         |

