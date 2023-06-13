create database universita;

create schema uni;

set search_path=uni;

create table docente (
	email varchar primary key,
	password varchar not null,
	nome varchar(50) not null,
	cognome varchar(50) not null
);

create table segreteria (
	indirizzo varchar primary key
);

create table segretario (
	email varchar primary key,
	password varchar not null,
	nome varchar(50) not null,
	cognome varchar(50) not null,
	segreteria varchar references segreteria (indirizzo) on update cascade on delete set null (segreteria)
);

create table corso_laurea (
	nome varchar primary key,
	tipologia char(10) not null,
	segreteria varchar references segreteria (indirizzo) on update cascade on delete no action
);

create table studente (
	matricola char(6) primary key,
	email varchar not null unique,
	password varchar not null,
	nome varchar(50) not null,
	cognome varchar(50) not null,
	corso_laurea varchar references corso_laurea (nome) on update cascade on delete no action
);

create table insegnamento (
	corso_laurea varchar,
	codice char(3),
	nome varchar not null,
	anno char(1) not null,
	descrizione text not null,
	responsabile varchar not null references docente (mail) on ,
	primary key(corso_laurea, codice)
);

create table appello (
	corso_laurea varchar,
	codice char(3),
	data date,
	primary key(corso_laurea, codice, data)
);

create table sostiene (
	studente char(6),
	corso_laurea varchar,
	codice char(3),
	data date,
	voto smallint,
	primary key(studente, corso_laurea, codice, data)
);

crate table propeudicita (
	corso_is varchar,
	codice_is char(3),
	corso_has varchar,
	codice_has char(3),
	primary key(codice_is, codice_is, corso_has, codice_has)
);