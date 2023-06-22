set search_path=uni;
-- la password di ogni utente è bitnami

insert into docente (email, password, nome, cognome) values 
('mario.rossi@example.com','6a4dc9133d5f3b6d9fff778aff361961', 'Mario', 'Rossi'), 
('luca.bianchi@example.com', '6a4dc9133d5f3b6d9fff778aff361961', 'Luca', 'Bianchi'),
('giulia.verdi@example.com', '6a4dc9133d5f3b6d9fff778aff361961', 'Giulia', 'Verdi');

insert into segreteria (indirizzo) values
('Scienze e Tecnologie'),
('Studi Umanistici');

insert into segretario (email, password, nome, cognome, segreteria) values
('paolo.neri@example.com', '6a4dc9133d5f3b6d9fff778aff361961', 'Paolo', 'Neri', 'Scienze e Tecnologie'),
('laura.rosa@example.com', '6a4dc9133d5f3b6d9fff778aff361961', 'Laura', 'Rosa', 'Studi Umanistici');

insert into corso_laurea (nome, tipologia, segreteria) values 
('Informatica', 'Triennale', 'Scienze e Tecnologie'),
('Matematica', 'Magistrale', 'Scienze e Tecnologie');

insert into studente (matricola, email, password, nome, cognome, corso_laurea) values
('123456', 'giuseppe.verdi@example.com', '6a4dc9133d5f3b6d9fff778aff361961', 'Giuseppe', 'Verdi', 'Informatica'),
('789012', 'marco.bianchi@example.com', '6a4dc9133d5f3b6d9fff778aff361961', 'Marco', 'Bianchi', 'Informatica'),
('345678', 'sara.rossi@example.com', '6a4dc9133d5f3b6d9fff778aff361961', 'Sara', 'Rossi', 'Matematica');

insert into insegnamento (corso_laurea, codice, nome, anno, descrizione, responsabile) values 
('Informatica', '001', 'Programmazione', '1', 'Corso introduttivo alla programmazione', 'mario.rossi@example.com'),
('Informatica', '002', 'Basi di Dati', '2', 'Intorduzione alle basi di dati', 'luca.bianchi@example.com'),
('Matematica', '001', 'Analisi Matematica', '1', 'Corso di analisi matematica avanzata', 'giulia.verdi@example.com');

insert into appello (corso_laurea, codice, data) values
('Informatica', '001', '2023-06-15'),
('Informatica', '001', '2023-06-30'),
('Informatica', '002', '2023-07-10');

insert into sostiene (studente, corso_laurea, codice, data, voto) values
('123456', 'Informatica', '001', '2023-06-15', 28),
('123456', 'Informatica', '001', '2023-06-30', 30),
('789012', 'Informatica', '001', '2023-06-30', 25),
('789012', 'Informatica', '002', '2023-07-10', 27);

insert into propedeuticita (corso_is, codice_is, corso_has, codice_has) values 
('Informatica', '001', 'Informatica', '002');

-- inserimenti per controllo trigger gestione_iscrizione_esami
insert into sostiene (studente, corso_laurea, codice, data, voto) values 
('123456', 'Informatica', '002', '2023-07-10', 18);

insert into sostiene (studente, corso_laurea, codice, data, voto) values 
('789012', 'Informatica', '003', '2023-07-03', 20);

-- inserimenti per il controllo trigger gestione_appelli_esami
insert into insegnamento (corso_laurea, codice, nome, anno, descrizione, responsabile) values 
('Informatica', '003', 'Analisi del continuo', '1', 'Corso introduttivo di analisi matematica', 'giulia.verdi@example.com');

insert into appello (corso_laurea, codice, data) values 
('Informatica', '003', '2023-07-03');

insert into appello (corso_laurea, codice, data) values 
('Informatica', '003', '2023-06-30');

insert into sostiene (studente, corso_laurea, codice, data, voto) values 
('789012', 'Informatica', '003', '2023-07-03', 20);
