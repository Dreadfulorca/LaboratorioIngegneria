# Analisi del repository `LaboratorioIngegneria`

## 1) Scopo del progetto
Applicazione Symfony 6.4 per la gestione di **movimenti finanziari** (entrate/uscite), con:
- CRUD dei movimenti,
- filtro per categoria nella lista,
- regole di validazione dominio (incluse regole cross-field).

## 2) Stack tecnico
- **Backend**: PHP 8.1+, Symfony 6.4.
- **Persistenza**: Doctrine ORM/DBAL + migrazioni Doctrine.
- **Template UI**: Twig + Bootstrap 5 (asset mapper).
- **Testing**: PHPUnit (unit + integration), Mockery disponibile.
- **Qualità codice**: PHPStan, php-cs-fixer.

## 3) Struttura ad alto livello
- `files/src/Controller`: controller HTTP.
- `files/src/Entity`: modello dominio (`Movement`, `Category`).
- `files/src/Form`: form Symfony (`MovementType`).
- `files/src/Repository`: query DB.
- `files/src/Validator/Constraints`: validazioni custom.
- `files/templates`: viste Twig.
- `files/tests`: test unitari e integrazione.

## 4) Regole business centrali
- Se `type=EXPENSE`, la categoria è obbligatoria.
- Se `type=INCOME`, la categoria è vietata.

## 5) Flusso applicativo
- Lista movimenti: `GET /movimenti/`
- Nuovo movimento: `GET|POST /movimenti/nuovo`
- Modifica: `GET|POST /movimenti/{id}/modifica`
- Eliminazione: `POST /movimenti/{id}`

## 6) Test
- Unit test validator custom.
- Integration test validazione entity.
- Integration test repository.

## 7) Ambienti
- Dev/Prod: PostgreSQL
- Test: SQLite (`files/.env.test`)

Questa analisi serve come base per README tecnico-funzionale.
