create database baumer;

use baumer;
create table colaborador (
id serial not null primary key,
nome text,
cpf text,
email text
);

use baumer;
create table tarefa (
    id serial not null primary key,
    descricao text,
    responsavel bigint unsigned, /*garantindo que esse campo tenha o mesmo tipo do id da tabela responsável */ 
    prioridade text,
    data_cadastro datetime,
    data_execucao datetime,
    data_limite datetime,
    foreign key (responsavel) references colaborador(id)
);
