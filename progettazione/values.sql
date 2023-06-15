set search_path=uni;

insert into docente (email, password, nome, cognome) values 
('mario.rossi@example.com','', 'Mario', 'Rossi'), 
('luca.bianchi@example.com', '', 'Luca', 'Bianchi'),
('giulia.verdi@example.com', '', 'Giulia', 'Verdi');

insert into segreteria (indirizzo) values
('Scienze e Tecnologie'),
('Studi Umanistici');

insert into segretario (email, password, nome, cognome) values
('paolo.neri@example.com', '', 'Paolo', 'Neri', 'Scienze e Tecnologie'),
('laura.rosa@example.com', '', 'Laura', 'Rosa', 'Studi Umanistici');

insert into studente (matricola, email, password, nome, cognome, corso_laurea) values
('123456', 'giuseppe.verdi@example.com', '', 'Giuseppe', 'Verdi', 'Informatica'),
('789012', 'marco.bianchi@');