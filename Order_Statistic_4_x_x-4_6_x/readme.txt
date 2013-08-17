--------------------------------------------------------------------------------
- Verbesserte Bestellübersicht
- Version: 1.0  Autor: Marcel Grolms (www.d-i-s.de)                                          
--------------------------------------------------------------------------------

Beschreibung:
--------------------------------------------------------------------------------
Statistik:
  Diese Modul fügt der Übersicht eine kleine Statistik hinzu.
  Dort findet man für "Heute", "diesen Monat", "letzen Monat", "Gesamt" und
  einen Benutzerdefinierten Zeitraum, folgende Dinge auf den ersten Blick:
    - Bestellungen (Anzahl)
    - Warenwert (Brutto und Netto)
    - Versandkosten
    - Gesamt (Brutto und Netto)
    
--------------------------------------------------------------------------------

Installationshinweise:
--------------------------------------------------------------------------------

1. copy_this ins Root-Verzeichnis kopieren

2. change_this
    wenn keine Änderungen an folgenden Templates:
      - out/admin/order_overview.tpl
    gemacht wurden, kann der Ordner Direkt ins Root-Verzeichnis kopiert werden.
    
    Ansonsten müssen Sie die Templates entsprechned anpassen.  

2. Backoffice Modul eintragen:

    oxorder => mgtools/mg_admin_statistik
--------------------------------------------------------------------------------