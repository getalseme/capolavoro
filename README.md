# TrovaPersoneRisorse

## Descrizione del Progetto

TrovaPersoneRisorse è un progetto di informatica scolastico che mira a fornire un sistema per la gestione degli orari scolastici e la localizzazione delle risorse all'interno di un istituto.

## Struttura delle Cartelle

- **client_web**: Contiene il codice HTML e JavaScript per l'interfaccia web che effettua richieste al server REST.
- **db_conversion**: Contiene un file PHP utilizzato per popolare il database con i dati degli orari scolastici.
- **doc**: Contiene documentazione relativa alla progettazione del database, requisiti minimi e specifica API in formato OpenAPI (api.yaml).
- **server_rest**: Contiene il server REST scritto in PHP utilizzando il framework Fat Free Framework. Include cartelle per le librerie (vendor), classi (class), e viste (views).
- **sql**: Contiene il file SQL per la creazione e popolamento del database.

## Utilizzo

1. Eseguire il file SQL per creare e popolare il database con i dati degli orari scolastici.
2. Inserire l'intera repository nella directory `htdocs` di XAMPP. Assicurarsi che il nome della cartella principale sia "P002_TrovaPersoneRisorse".
3. Accedere all'URL "http://localhost/P002_TrovaPersoneRisorse/client_web/login.html" per effettuare il login con le credenziali fornite.
4. Una volta effettuato l'accesso, è possibile navigare tra le pagine del sito per:
   - Trovare la posizione di un docente in un determinato momento.
   - Visualizzare l'orario settimanale di un docente o studente.
   - Prenotare aule per attività (solo per docenti).
   - Controllare le attività a cui si partecipa e altre funzionalità.

## Note

- Assicurarsi che il server web (ad esempio Apache) e il server MySQL siano in esecuzione su XAMPP prima di utilizzare l'applicazione.
- Le credenziali di accesso saranno fornite agli utilizzatori per consentire l'accesso alle funzionalità del sistema.
