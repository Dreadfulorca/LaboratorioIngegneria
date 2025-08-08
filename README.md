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
