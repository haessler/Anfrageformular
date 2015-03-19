# Anfrageformular
Kleines Anfrageformular
Nur zwei drei Felder für Name, Email, Telefon, Firma und Textfeld. Prüft nur, ob Name, Email oder Telefon vorhanden sind. Mit Ausschluss von IP-Adressen, von denen aus gespamt wurde. 
Installation: PHP-Script in ein Verzeichnis, Formular ins Template. 
Das PHP-Script gibt ?danke oder ?missing zurück, für die Nachricht ein HTML-Element anlegen.
Die IP-Adressen von Spammern müssen in ein Array eingetragen werden. 
