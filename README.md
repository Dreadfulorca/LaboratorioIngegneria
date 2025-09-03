## Informazione importante per l'esecuzione dei test:

## Ambienti e database

- **Sviluppo/Produzione:** PostgreSQL (configurato in `files/.env` e nel `docker-compose.yml`).
- **Test (PHPUnit):** SQLite, per evitare dipendenze da servizi esterni.

In ambiente `test` viene utilizzato `files/.env.test`, che imposta:
``dotenv
APP_ENV=test
DATABASE_URL="sqlite:///%kernel.project_dir%/var/test.db"
Doctrine crea lo schema al volo tramite SchemaTool; non sono necessarie migrazioni né un server PostgreSQL.

**Requisiti:**
- PHP 8.1+
- Composer
- **Per i test è necessario l'uso di: estensione PHP sqlite3 (es. su Ubuntu: sudo apt install php8.1-sqlite3)**

-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

# Repository di esempio per l'esame del corso di Laboratorio di Ingegneria del Software
Gli studenti possono, se lo desiderano, utilizzare questo repository come punto di partenza per il loro progetto.
Il repository contiene un ambiente di sviluppo come quello utilizzato a lezione e tutti i pacchetti Symfony utilizzati
a lezione, già installati.

**ATTENZIONE:** a eccezione di quanto sotto riportato, la configurazione dei pacchetti non è stata modificata rispetto a
quella installata da Symfony flex. La configurazione predefinita è probabilmente sufficiente per gli scopi dell'esame,
ma in caso di necessità, si rimanda alla documentazione dei relativi pacchetti.

## Modifiche alla configurazione predefinita
- Definizione dei parametri di configurazione del database dentro `files/.env`;
- Definizione dei parametri di configurazione del database di test (opzionale) dentro `files/.env.test`;
- Modifica alla configurazione di `files/.php-cs-fixer.dist.php` per consentire i nomi dei metodi di test in snake_case;
- Installazione e configurazione di Bootstrap 5 tramite Symfony asset-mapper.

## Primo avvio del progetto
1. Clonare il repository;
2. Avviare l'ambiente di sviluppo `make start`;
3. Avviare la shell all'interno del container `make shell`;
4. Installare le dipendenze `composer install`;
5. localhost:8080 dovrebbe mostrare la pagina di benvenuto di Symfony 6.4.21.

## Altre informazioni utili
Per gli argomenti trattati a lezione e relativi link alla documentazione, consultare il
[diario delle lezioni](https://github.com/RBastianini/labingsoft/blob/master/DIARIO-LEZIONI.md) nel repository
principale del corso.

Per informazioni sullo svolgimento del progetto, sulle modalità di consegna e di valutazione dello stesso, consultare
[questa pagina](https://github.com/RBastianini/labingsoft/wiki/Informazioni-sull'esame) nel repository principale del
corso.