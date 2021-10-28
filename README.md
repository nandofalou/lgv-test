# Teste prático de PHP

## [LojaVirtual.com.br](https://www.lojavirtual.com.br/) - Teste prático de PHP

Este teste foi desenvolvido com o framework Codeigniter 4.1.4

## Como executar

O Codeigniter 4 necessita da extensão php-intl e php-mbstring. Verificar se está instalado.

Passos
- baixe o repositório para um local válido (Linux Ubuntu /var/www/html/)
- Copie env para.env e adapte para o seu aplicativo, especificamente as configurações de banco de dados.
```
Para MySQL:
# para uso do MySQL
database.default.hostname = localhost
database.default.database = database_test
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
# database.default.DBPrefix =

Para sqLite
# para uso do sqLite
database.default.database = ../writable/database.db
database.default.DBDriver = SQLite3
# database.default.DBPrefix =
```
- Dê permissão de escrita no diretório writable
- No diretório da aplicação, execute o servidor interno do codeigniter, digitando 
```
$ php spark serve
```
- ou configure o DocumentRoot Apache / Nginx para o diretório public do projeto (o mod-rewrite precisa estar ativo)
- Abra o browser em http://localhost:8080 para executar as migrations


## Consumindo a API

Como não foi informado o tipo de autenticação, adicionei a autenticação mais simples, Basic Auth.
- username: lojavirtual
- password: AAAAC3NzaC1lZDI1NTE5AAAAIAFGivCtTMXmFiPbd5GLHNChKn+MCVrvwjB5GK2APQti
```
 GET /users HTTP/1.1
> Host: localhost:8080
> User-Agent: insomnia/2021.4.1
> Authorization: Basic bG9qYXZpcnR1YWw6QUFBQUMzTnphQzFsWkRJMU5URTVBQUFBSUFGR2l2Q3RUTVhtRmlQYmQ1R0xITkNoS24rTUNWcnZ3akI1R0syQVBRdGk=
> Accept: */*
```

## Testes

- GET http://localhost:8080/users
- GET http://localhost:8080/users/1
- POST http://localhost:8080/users
```
{
	"name": "Fernando",
	"email": "nando.falou@gmail.com",
	"birthdate": "1980-01-01",
	"cpf": "78585878787",
	"phone": "7199999999",
	"zipcode": "41830620"
}
```
- PUT http://localhost:8080/users/1 
```
{
	"name": "Fernando",
	"email": "nando.falou@gmail.com",
	"birthdate": "1980-01-01",
	"cpf": "78585878787",
	"phone": "7199999999",
	"zipcode": "41830620"
}
```
- DELETE http://localhost:8080/users/1

Extra
- GET http://localhost:8080/zipcode/40330200

## + info
- Os CEPs são persistidos em cada consulta, minimizando o uso da API do Via CEP
- A API aceita os formatos json e multipart/form-data, informando o Content-Type
