#Laborationsrapport

##Steg 1. Säkerhetsbrister

###Inloggning
####Username och password tar emot taggar och whitespaces.<br/>

<strong>Lösning:</strong> Jag gjorde en strip_tags på både username och password som tar bort taggar om det finns en öppningstag, finns det bara en stängtag
så tas inte den bort.<br/>

<strong>Risk:</strong> När man kan skriva in javascript oc sql - injektions så kan en ond användare länka till andra sidor från din sida och hämta ut cookies
och sådant.

#### Man kan logga in med vilka värden som helst.<br/>

<strong>Lösning:</strong> I check.php så var isUser tvungen till att returnera true eller false så i sec.php oc isUser functionen returnerade jag false om
det inte finns något resultat och true om det finns.<br/>

<strong>Risk:</strong> Det som risken är att man undviker hela säkerheten med login om man kan logga in med vad som helst.

#### Man kan få tillgång till applikationen genom att bara skriva mess.php i urlen.<br/>

<strong>Lösning:</strong> Implementerade in if - sats som kollar om det finns en session med username i eller inte, finns det inte någon sådan session så
kommer man tillbaka till inoggnings - sidan annars får man tillgång till applikationen.<br/>

<strong>Risk:</strong> Kan man undgå att logga in så skulle hela applikationen bli en öppen chatt och då har man tappat hela iden med att ha en login.

#### Värdena läggs in direkt i SQL - frågan som parametrar vilket resulterar i att man är sårbar för SQL - injektions.<br/>

<strong>Lösning:</strong> Jag paramatiserade SQL frågan i isUser till att ta emot frågetecken som värde sedan när man hämtade resultatet med de inmatade värdena
så la jag till false om det inte fanns någon användare med det username och password annars så loggas man in.<br/>

<strong>Risk:</strong> Risken är att man kan få sin databas förstörd om en ond användare skulle få för sig att testa sql frågor och
förstöra databasen.

#### Lösenorden sparas i databasen i klartext.<br/>

<strong>Lösning:</strong> Det jag gjorde för att lösa detta var att jag gjorde en password_hash för att hasha och kryptera lösenordet i databasen.<br/>

<strong>Risk:</strong> När man har lösenord i klartext i databasen så om databsen skulle läcka ut så skulle någon få alla användare och deras lösenord och
kunna logga in med vilken som och ange sig att vara den personen.

###Skriva meddelanden
#### Både namnfältet och meddelandefältet tar emot taggar och whitespaces.<br/>

<strong>Lösning:</strong> För att ta bort taggar och whitespaces använder jag strip_tags och trim för att ta bort både taggar och whitespaces.<br/>

<strong>Risk:</strong> När man kan skriva in javascript oc sql - injektions så kan en ond användare länka till andra sidor från din sida och hämta ut cookies
och sådant.

#### Inserten till databasen är inte skyddad mot sql injections.<br/>

<strong>Lösning:</strong> Jag paramatiserar SQL - frågan så att man inte skriver in parametrarna direkt i SQL - frågan utan gör PDO och gör en array som
jag executar.

<strong>Risk:</strong> Risken är att man kan få sin databas förstörd om en ond användare skulle få för sig att testa sql frågor och
förstöra databasen.

###Databas
#### Databasen går att ladda ner om man har tillgång till URL.<br/>

<strong>Risk:</strong> Riskerna med detta är att man kan se allt man har i databasen om man har rätt program för att göra så.

<strong>Lösning:</strong> Det jag gör är att kryptera lösenord i databasen så går det inte att använda sig av ett rainbowtable för
att veta vilka lösenord en användare har. Jag har inte hittat något på mitt webbhotell där man kan filtrera ut vissa filer som inte
ska gå att ladda ner men det är en sådan lösning man skulle behöva ha för att lösa så att den inte går att ladda ner över huvud taget.

###CSRF
#### Det går att utföra en CSRF attack.<br/>

<strong>Lösning:</strong> Det jag har gjort är att jag gör en random token som är ett slumpat id som jag sedan sparar i en session varje
gång man går in på chatt sidan. Sedan gämför jag token som finns på klienten med den som finns i sessionen och om de stämmer
överens så lagras meddelandet i databsen annars händer ingenting.

<strong>Risk:</strong> Risken med detta är att man kan skriva meddelanden som en användare och det blir väldigt svårt för just den
användaren att bevisa att det inte var den som skrev det.

## Steg - 2 Optimering
1. Det jag har gjort är att flytta all css som låg i index.php och i mess.php till en egen fil och då kan den filen cachas och på så
sätt få ner laddningstiderna när denna applikation är publicerad på nätet. Man märker inget lokalt för att det går så snabbt i vilket
fall som helst så man märker inte om den är cachad eller ej.
2. Jag använder den inkluderade jwuery.mini filen istället för den vanliga jquery och på så sätt får jag bort onödiga whitespaces och
den blir svårläst för användare med och så borde den vara snabbare att läsa in på grund av att allt står på en enda rad och inte
innehåller whitespaces och sådant.
3. Jag har också en minifierad bootstrap css som också kommer att vara snabbare än den vaniga för att den inte behöver ta in alla
whitespaces och sådant.

###Mätningar
1. <strong>Login - sida:</strong> På login - sidan när jag gör mätningar så tar det totalt längre tid på den modifierade applikationen
gentemot orginal applikationen men det beror på att bilden b.jpg tar 204 millisekunder att ladda in och är på 142kb och då tar det
längre tid och det blir mer att ladda in på den modifierade applikationen än på orginal applikationen. Så tar man bort bilden som
laddas in på index på den modifierade applikationen så blir den snabbare än orginal applikationen.
2. <strong>Meddelande - sida:</strong> När man har loggat in och kommer till själva chatten så är det modifierade systemet snabbare än
vad orginalsystemet är. Detta beror mest på att i orginal applikationen så försöker den att ladda in ett javascript som inte finns
och det är på ungefär 200 millisekunder innan den inser att den inte finns. Så när man har loggat in tar det modifierade systemet
850 millisekunder och orginal systemet 1 sekund, och jag har även tagit ner mängden som laddas med ungefär 60kb.
3. <strong>Logout:</strong> När man trycker på logout och loggas ut så är återigen det modifierade systemet lite långsammare än
orginal systemet och det beror återigen på att jag läser in en bild som inte orginal systemet gör och då blir mitt ungefär 150 millisekunder
långsammare och det blir 120kb mer att ladda in.

## Steg - 3 Longpolling
Longpollingen är gjord som så att jag har en oändlig loop i MessageBoard.js som kallar på sig själv varje sekund, för om jag inte hade
någon timout på den så skickade den request hela tiden och då så buggade själva longpollingen ur och ibland fick man ut ett meddelande
och ibland inte. Sedan i get.php så har jag en while loop som lever i 20 sekunder och om den hittar någon förändring i databasen så
kommer man ur loopen och det som fanns i databasen skrivs ut, annars kör den bara om loopen ända till en förändring sker.<br/>

Nackdelen med long polling är att man hela tiden håller en anslutning öppen och då kan jag tänka mig att det tar en del resurser att
göra så.<br/>

Fördelen med denna implementation är att man kan göra en realtidskonversation och slipper att uppdatera sidan när någon har skrivit ett
meddelande.

###Optimering long polling
Optimeringen jag har gjort är att jag bara kör en sql fråga istället för som jag hade innan, då hade jag 2 stycken sql frågor och då borde det
ta mer kraft att köra 2 sql frågor än 1.

##Publicering
Länk till publicerad version: http://martinfohlin.se/1DV449_laboration2/1DV449_L02/index.php