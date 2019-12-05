# Setup und Benützung des Projekts

## Benötigte Programme:   
* [PHP 7.2.x](http://php.net/downloads.php)  
* [Composer](https://getcomposer.org/download/)  
* [Git](https://git-scm.com/download/) + [Github Account](https://github.com/)  
* [MySQL](https://dev.mysql.com/downloads/mysql/)
* [Node.js und npm](https://nodejs.org/de/download/)

## Setup:
1. Den Projektordner als lokales repository erstellen:  
    `git clone https://github.com/Steinschnueffler/MyMathematik.git`  
2. In den Projektordner navigieren:  
    `cd MyMathematik/`  
3. .env.local erstellen und folgendes Eintragen:  
    `APP_ENV=` #here dev or prod  
    `APP_SECRET=` #here any app secret, for example 3ed8e27d1e5129d918e8bd2b62a0e40f  
    `DATABASE_URL=` #database connection: schema://user:password@ip:port/name, see point 5. example: mysql://standard:Pa44w0rd@127.0.0.1:3306/mymathematik  
4. Die Projektabhängigkeiten installieren (Muss bei neuen Abhängigkeiten auch während der Entwicklung wiederhohlt werden):  
    `composer install`
5. Den Sql Server einrichten und einen Benutzernamen und Passwort in .env.local eintragen, dann  
    `php bin/console doctrine:database:create`  
    `php bin/console doctrine:migrations:migrate`  
    Siehe Anleitung [hier](https://symfony.com/doc/current/doctrine.html)

6. Die Frontend Abhängigkeiten installieren  
    `npm install`  
    `npm run dev`

## Benützen des Projektes:
* PHPs eingebauter Webserver (einfach zu benützen aber sehr langsam), erreichbar im Browser über localhost:8000. Zum starten (Benötigt symfony binary):  
    `symfony serve`
* Vollwertiger Webserver (z.b. Apache oder Nginx), erreichbar im Browser über localhost, localhost:80 oder IP-Adresse des Computers
Public/ Ordner entspricht "Server/DocumentRoot,"  alle anderen Ordner dürfen nicht erreichbar sein!
Darin enthaltenes index.php File muss mit einem PHP-Mod geladen werden  

## Entwicklung:

### Grundsätzliche Befehle

* Frontend bauen (muss nach jeder änderung in .js oder .scss gemacht werden)  
    `npm run dev`
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
