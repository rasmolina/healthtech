create database healthtechdatabase;

use healthtechdatabase;

create table supervisor(
id int AUTO_INCREMENT,
classeUsuario varchar (3),
login varchar(255),
senha binary(60),
nomeCompleto varchar(300),
cpf varchar(14),
dataNascimento date,
logradouro varchar(300),
numero int,
telefone varchar(14),
email varchar(80),
CONSTRAINT supervisor_pk PRIMARY KEY (id),
CONSTRAINT supervisor_uk1 UNIQUE (cpf),
CONSTRAINT supervisor_uk2 UNIQUE (login),
CONSTRAINT supervisor_uk3 UNIQUE (email),
CONSTRAINT supervisor_uk4 UNIQUE (telefone)
);

create table medico(
id int AUTO_INCREMENT,
classeUsuario varchar (3),
login varchar(255),
senha binary(60),
nomeCompleto varchar(300),
cpf varchar(14),
crm varchar(20),
permite_req boolean,
especialidade varchar(30),
dataNascimento date,
logradouro varchar(300),
numero int,
telefone varchar(14),
email varchar(80),
CONSTRAINT medico_pk PRIMARY KEY (id),
CONSTRAINT medico_uk1 UNIQUE (cpf),
CONSTRAINT medico_uk2 UNIQUE (crm),
CONSTRAINT medico_uk3 UNIQUE (login),
CONSTRAINT medico_uk4 UNIQUE (email),
CONSTRAINT medico_uk5 UNIQUE (telefone)
);

create table analista(
id int AUTO_INCREMENT,
classeUsuario varchar (3),
login varchar(255),
senha binary(60),
nomeCompleto varchar(300),
cpf varchar(14),
dataNascimento date,
logradouro varchar(300),
numero int,
telefone varchar(14),
email varchar(80),
CONSTRAINT analista_pk PRIMARY KEY (id),
CONSTRAINT analista_uk1 UNIQUE (cpf),
CONSTRAINT analista_uk2 UNIQUE (login),
CONSTRAINT analista_uk3 UNIQUE (email),
CONSTRAINT analista_uk4 UNIQUE (telefone)
);

create table paciente(
id int AUTO_INCREMENT,
classeUsuario varchar (3),
login varchar(255),
senha binary(60),
nomeCompleto varchar(300),
cpf varchar(14),
dataNascimento date,
logradouro varchar(300),
numero int,
telefone varchar(14),
email varchar(80),
sexo char,
prontuario int,
CONSTRAINT paciente_pk PRIMARY KEY (id),
CONSTRAINT paciente_uk1 UNIQUE (cpf),
CONSTRAINT paciente_uk2 UNIQUE (login),
CONSTRAINT paciente_uk3 UNIQUE (email),
CONSTRAINT paciente_uk4 UNIQUE (telefone),
CONSTRAINT paciente_uk5 UNIQUE (prontuario)
);

create table exame(
id int AUTO_INCREMENT,
nome varchar(200),
altocusto boolean,
CONSTRAINT exame_pk PRIMARY KEY (id)
);

create table pedido(
id int AUTO_INCREMENT,
hipotese varchar(400),
dataSolicitacao datetime,
idMedico int, 
idPaciente int,
CONSTRAINT pedido_pk PRIMARY KEY (id),
CONSTRAINT pedido_fk1 FOREIGN KEY (idMedico) REFERENCES medico (id),
CONSTRAINT pedido_fk2 FOREIGN KEY (idPaciente) REFERENCES paciente (id)
);

create table examespedido(
idPedido int,
idExame int,    
idAnalista int,    
situacao varchar(30),
laudo varchar(800),    
dataHoraRealizacao datetime,
dataHoraVisualizacao datetime,
CONSTRAINT examesPedido_fk1 FOREIGN KEY (idPedido) REFERENCES pedido (id),
CONSTRAINT examesPedido_fk2 FOREIGN KEY (idExame) REFERENCES exame (id),
CONSTRAINT examesPedido_fk3 FOREIGN KEY (idAnalista) REFERENCES analista (id)    
);