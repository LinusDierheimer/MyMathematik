# Setup und Benützung des Projekts

## Benötigte Programme:   
* [PHP 7.x.x](http://php.net/downloads.php)  
* [Composer](https://getcomposer.org/download/)  
* [Git](https://git-scm.com/download/) + [Github Account](https://github.com/)  

## Setup:
1. Den Projektordner als lokales repository erstellen:  
    `git clone https://github.com/Steinschnueffler/MyMathematik.git`  
2. In den Projektordner navigieren:  
    `cd MyMathematik/`  
3. Die Projektabhängigkeiten installieren (Muss bei neuen Abhängigkeiten auch während der Entwicklung wiederhohlt werden):  
    `composer self-update`  
    `composer install`  
    `composer update`  

## Benützen des Projektes:
* PHPs eingebauter Webserver (einfach zu benützen aber sehr langsam), erreichbar im Browser über localhost:8000. Zum starten:  
    `php bin/console server:run`  
* Vollwertiger Webserver (z.b. Apache oder Nginx), erreichbar im Browser über localhost, localhost:80 oder IP-Adresse des Computers
Public/ Ordner entspricht "Server/DocumentRoot,"  alle anderen Ordner dürfen nicht erreichbar sein!
Darin enthaltenes index.php File muss mit einem PHP-Mod geladen werden  

## Entwicklung:

### Grundsätzliche Befehle

* Aktuelle Version Herunterladen:  
    `git pull`  
* Aktuellen Änderungen ansehen:  
    `git status`  
* Aktuellen Änderungen zu den gewünschten Änderungen hinzufügen:  
    `git add <file/folder/.(für alles)>`  
* Gewünschte Änderungen übernehemen:  
    `git commit -m "Was man gemacht hat"`  
* Verision mit Hauptversion in der Cloud zusammenführen:  
    `git push`  
* Auf den letzten commit zurücksetzen:  
    `git reset --hart HEAD`  
### Empfohlene Werkzeuge: 
* [Visual Studio Code](https://code.visualstudio.com/Download) mit empfohlen Plugins als IDE