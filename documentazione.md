# Documentazione del progetto

<!-- Documento che contiene le scelte implementative adottate -->

## Progettazione concettuale

### Modello ER

![Modello ER](./img/ermodel.png)

La relazione tra appello e studente modella sia l'iscrizione di uno studente ad un appello che il sostenimento di una appello da parte dello studente. Inoltre l'associzione ha un attributo `voto` che se nullo indica che uno studente si è iscritto ad un appello ma la sua prova non è ancora stata valutata.

L'entità **insegnamento** è un entità debole perché, come riportato nelle specifiche, ha un attributo `codice` che è identificato univocamente all'interno del corso di laurea.

Anche l'entità **appello** è un entità debole perché ogni un appello dipende dall'insegnamento di cui sarà la prova. L'attributo data e la relazione con l'entità insegnamento formano l'indentificatore esterno di questa entità.

L'entità **segretario** permette di avere più persone fisiche identificate dalla propria `email` che lavorano presso una **segreteria**. Quest'ultima è caratterizzata da una chiave `indirizzo` come _scienze e tecnologie_, _medicina e chilurgia_, _studi umanistici_ e _scienze motorie_.
Inoltre l'entità segreteria è responsabile, come richiesto dalla specifiche di definire i **corsi di laurea** per questo motivo ho scelto di rappresentare queste entità nel modello relazionale.

Infine la gerarchia utente, studente, docente e segretario ha come scopo quello di rappresentare le caratteristiche in comune alle tre entità che sono `email, nome, cognome e password`. La mail e la password servono per l'accesso e nome e cognome per descrivere l'utente.
La tipologia di gerarichia è totale ed esclusciva, $(T, E)$ perché dato ogni utente esso apparitene ad una e una sola delle classi figlie.

### Vincoli extra schema
L'attributo `anno` appartiene ad $\{1, 2, 3 \}$ se `tipologia` = _triennale_.
Invece `anno` appartiene ad $\{1, 2 \}$ se `tipologia` = _magistrale_.

L'attributo `voto` è _null_ se lo studente è iscritto al appello ma non ha ancora sotenuto la prova d'esame. Invece dato un appello con voto $v$ se lo studente ha voto $0 \le v < 18$ allora l'appello è insufficiente invece se $18 \le v \le 30$ l'appello è sufficiente.

## Progettazione logica

### Ristrutturazione del modello ER

![ER Ristrutturato](./img/erristrutturato.png)

L'associazione `crea` è ridondante perché riesco a ottenere gli appelli di un docente tramite gli insegnamenti di cui è responsabile, per questo motivo ho scelto di cancellare questa associazione dal modello ER ristrutturato.

Ho scelto di ristrutturare la gerarchia presente nel modello ER accorpando gli attributi dell'entità padre, **utente**, nelle figlie dal momento che la gerarchia è di tipo totale ed esclusivo sopratutto perché ci sono associazioni che coninvolgo le varie entità figlie come evidenziato nel modello ER.

Inoltre ho scelto come identificatore dell'entità **studente** l'attributo `matricola` perché occupa uno spazio in memoria minore rispetto a `mail`.

### Traduzione del modello logico
Si nota che ho utilizzato la <u>sottolineatura</u> per indicare la chiave della relazione, * per attributi potenzialmente nulli e il _corsivo_ per le chiavi esterne.
Traduzione delle entitità con identificatore interno:

**docente**(<u>email</u>, password, nome, cognome)

**studente**(<u>matricola</u>, email, password, nome, cognome, _corso_laurea_)

**segretario**(<u>email</u>, password, nome, cognome, _segreteria_)

**segreteria**(<u>indirizzo</u>)

**corso_laurea**(<u>nome</u>, tipologia, _segreteria_)

Traduzione delle entità con identificatore esterno:

**insegnamento**(<u>corso_laurea, codice</u>, nome, anno, descrizione, _responsabile_)

**appello**(<u>corso_laurea, codice, data</u>)

Traduzione delle associazioni molti a molti:

**sostiene**(<u>studente, corso, codice, data</u>, voto*)

**propedeuticità**(<u>corso_is, codice_is, corso_has, codice_has</u>)

Traduzione delle associazioni uno a molti:

- aggiunta alla relazione insegnamento la chiave primaria di docente notando che il numero di insegnamento per ogni docente non è arbitrariamente grande ma minore a tre,
- aggiunto alla relazione studente un attributo _corso_laurea_ che contiene il nome del corso di laurea a cui è iscritto,
- aggiunto alla relazione corso_laurea l'attributo segreteria che rappresenta l'id della segrateria che l'ha definita,
- aggiunto alla relazione segretario l'attributo segreteria che rappresenta l'ide della segreteria presso cui lavora.

### Normalizzazione
Oltre alle dipendenze funzionali banali ovvero quelle che ogni attributo ha con la chiave delle propria tabella eccetto la chiave stessa.

## Progettazione fisica
Procedo ora a realizzare lo schema fisico da quello logico utilizzando i comandi SQL di DDL, data definition language.

```sql
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
	-- se un indirizzo della segreteria è aggiornato allora le modifiche si ripercuotono sui segretari
	-- invece se un indirizzo della segreteria è cancellato i record riferiti in questa tabella sono settati a null	
);

create table corso_laurea (
	nome varchar primary key,
	tipologia char(10) not null,
	segreteria varchar references segreteria (indirizzo) on update cascade on delete no action
	-- se un indirizzo della segreteria è aggiornato allora le modifiche si ripercuotono sulla segreteria che ha definito il corso di laurea
	-- se un indirizzo della segreteria è cancellato la cancellazione della riga non è propagata
);

create table studente (
	matricola char(6) primary key,
	email varchar not null unique,
	password varchar not null,
	nome varchar(50) not null,
	cognome varchar(50) not null,
	corso_laurea varchar references corso_laurea (nome) on update cascade on delete no action
	-- se un corso di laurea è modificato queste modifiche sono propagate al corso di laurea dello studente
	-- se un corso di laurea è cancellato allora 
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
```

### Scelta dei tipi di dato

### Politche di reazione di integrità referenziale
Per il vincolo di integrità referenziale sulla tabella studente dal momento che è necessario gestire la cancellazione di studenti in tabelle di storico quindi non è possibile applicare politiche diverse rispetto a quella `no action`.
Invece per quanto riguarda l'aggiornamento delle informazioni di un corso di laurea decido di propagare la modifiche.

Per il vincolo di integrità refernziale della tabella segretario, dal momento che le specifiche non applica particolari restrizioni, decido di propagare le modifiche e annullare le cancellazioni che coinvolgono gli indirizzi della segreteria.

Infine per quanto riguarda il vincolo di integrtità referenziale del corso di laurea decido di propagare le modifiche alle tuple del indirizzo della segreteria e di bloccare le cancellazioni.