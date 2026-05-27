# Gestione Movimenti Finanziari (Symfony 6.4)

## Preambolo: cosa fa questa applicazione

Questa applicazione web serve per gestire movimenti finanziari personali in modo semplice.

In pratica ti permette di registrare:
- **Entrate** (esempio: stipendio, rimborso),
- **Uscite** (esempio: spesa, affitto, trasporti),

e poi:
- vedere la lista completa dei movimenti,
- filtrare i movimenti per categoria,
- modificare o eliminare un movimento già inserito.

L’applicazione è pensata come progetto didattico, ma usa una struttura reale da progetto professionale (Symfony + Doctrine + test automatici).

---

## Come funziona a livello strutturale (visione d’insieme)

L’app segue la classica architettura MVC tipica di Symfony:

1. **Controller**  
   Riceve le richieste web (es. aprire pagina lista, inviare form, eliminare record), coordina la logica e decide quale pagina mostrare.

2. **Entity**  
   Rappresenta i dati di dominio (es. `Movement`, `Category`) e le regole base sui campi (validazioni, tipo dati, relazioni DB).

3. **FormType**  
   Definisce i campi del form HTML (importo, data, tipo, categoria) e come i dati vengono mappati sull’Entity.

4. **Validator (anche custom)**  
   Controlla regole funzionali più avanzate prima del salvataggio (es. categoria obbligatoria per le uscite).

5. **Repository**  
   Contiene query al database (es. filtro per categoria, ordinamento per data).

6. **Twig Templates**  
   Sono le pagine HTML renderizzate per l’utente (lista movimenti, form nuovo/modifica, messaggi).

7. **Test**  
   Verificano che regole e comportamenti funzionino in modo automatico (unit + integration).

---

## 1) Funzionalità principali

- Creazione di un nuovo movimento finanziario.
- Visualizzazione elenco movimenti ordinato.
- Filtro per categoria nella pagina riepilogo.
- Modifica di movimenti esistenti.
- Eliminazione con protezione CSRF.
- Validazione automatica dei dati inseriti.

---

## 2) Regole di business importanti

Le regole centrali dell’app sono:

- Se il movimento è di tipo **Uscita**, la **categoria è obbligatoria**.
- Se il movimento è di tipo **Entrata**, la **categoria non deve essere specificata**.

Inoltre:
- l’importo deve essere maggiore di zero,
- la data non può essere nel futuro,
- la descrizione ha una lunghezza massima.

Queste regole sono applicate tramite il sistema di validazione Symfony (incluso un validator custom per la regola su entrata/uscita e categoria).

---

## 3) Stack tecnologico

- **PHP 8.1+**
- **Symfony 6.4**
- **Doctrine ORM / DBAL**
- **Twig**
- **Bootstrap 5** (via asset mapper)
- **PHPUnit** (test automatici)
- **PHPStan** e **PHP CS Fixer** per qualità codice

---

## 4) Struttura del repository

La parte applicativa è principalmente nella cartella `files/`:

- `files/src/Controller/`  
  Controller HTTP (routing, submit form, redirect, flash message).

- `files/src/Entity/`  
  Modello dominio (`Movement`, `Category`) + mapping Doctrine.

- `files/src/Form/`  
  Definizione form Symfony (`MovementType`).

- `files/src/Repository/`  
  Query personalizzate (es. filtro movimenti per categoria).

- `files/src/Validator/Constraints/`  
  Vincoli custom di business.

- `files/templates/`  
  Template Twig per l’interfaccia.

- `files/tests/`  
  Test unitari e test di integrazione.

- `files/migrations/`  
  Migrazioni del database.

---

## 5) Flusso utente (dalla UI)

### 5.1 Lista movimenti
- URL: `/movimenti/`
- Mostra tutti i movimenti.
- Possibilità di filtrare per categoria.
- Ordinamento: data decrescente (più recenti prima).

### 5.2 Nuovo movimento
- URL: `/movimenti/nuovo`
- Form con campi importo, descrizione, data, tipo, categoria.
- Su submit:
    - validazione dati,
    - salvataggio DB,
    - redirect alla lista con messaggio di successo.

### 5.3 Modifica movimento
- URL: `/movimenti/{id}/modifica`
- Carica un movimento esistente e permette l’aggiornamento.

### 5.4 Eliminazione movimento
- URL: `POST /movimenti/{id}`
- Richiede token CSRF valido.
- Rimuove il record e ritorna alla lista.

---

## 6) Database e ambienti

## Ambienti e database

- **Sviluppo/Produzione:** PostgreSQL (configurato in `files/.env` e nel `docker-compose.yml`).
- **Test (PHPUnit):** SQLite, per evitare dipendenze da servizi esterni.

In ambiente `test` viene usato `files/.env.test` con:

```dotenv
APP_ENV=test
DATABASE_URL="sqlite:///%kernel.project_dir%/var/test.db"