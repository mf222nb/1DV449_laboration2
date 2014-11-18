#Laborationsrapport

##Steg 1. Säkerhetsbrister

###Inloggning
1. Username och password tar emot taggar och whitespaces.
2. Man kan logga in med vilka värden som helst.
3. Man kan få tillgång till applikationen genom att bara skriva mess.php i urlen.
4. Värdena läggs in direkt i SQL - frågan som parametrar vilket resulterar i att man är sårbar för SQL - injektions.

###Skriva meddelanden
1. Både namnfältet och meddelandefältet tar emot taggar och whitespaces.
2. Inserten till databasen är inte skyddad mot sql injections.

##Lösningar

###Inloggning
1. Jag gjorde en strip_tags på både username och password som tar bort taggar om det finns en öppningstag, finns det bara en stängtag
så tas inte den bort.
2. I check.php så var isUser tvungen till att returnera true eller false så i sec.php oc isUser functionen returnerade jag false om
det inte finns något resultat och true om det finns.
3. Implementerade in if - sats som kollar om det finns en session med username i eller inte, finns det inte någon sådan session så
kommer man tillbaka till inoggnings - sidan annars får man tillgång till applikationen.
4. Jag paramatiserade SQL frågan i isUser till att ta emot frågetecken som värde sedan när man hämtade resultatet med de inmatade värdena
så la jag till false om det inte fanns någon användare med det username och password annars så loggas man in.

###Skriva meddelanden
1. För att ta bort taggar och whitespaces använder jag strip_tags och trim för att ta bort både taggar och whitespaces.
2. Jag paramatiserar SQL - frågan så att man inte skriver in parametrarna direkt i SQL - frågan utan gör PDO och gör en array som
jag executar.

##Hur skulle dessa säkerhetshålen utnyttjas?
1. När man kunde skriva in taggar så kunde en ond användare skriva in script - taggar och på så sätt hämta ut information från sidan.
3. Om man kan gå direkt till mess.php och få tillgång till applikationen så går man förbi login delen och kan göra allt som en inloggad
användare kan.

##Vad för skada kan säkerhetsbristen göra?
1. När man kan skriva in javascript oc sql - injektions så kan en ond användare länka till andra sidor från din sida och hämta ut cookies
och sådant.
2. Kan man undgå att logga in så skulle hela applikationen bli en öppen chatt och då har man tappat hela iden med att ha en login.

