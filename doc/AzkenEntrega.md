# Azken entregarako egin beharreko atalak:

- [x] Galderak ezabatu ahal izatea.

> Ariketa hau egiteko, Reviewing Quizes atalean,
galdera bat editatzeko aukeratzean, botoi bat agertzen da,
galdera hori ezabatzeko.

> Behar izandako denbora: 15 minutu.

- [x] **One-play**: Kautotu gabeko erabiltzaileei ausaz galdera
bat erakutsi, eta erantzun ondoren ondo ala gaizki erantzun
duen esan.

> Ariketa hau egiteko, lehenengo galdera bat ausaz lortzen duen MySQL
kontsulta  sortu dugu `ORDER BY RAND() ASC LIMIT 1` erabiliz,
galderak auzas ordenatzeko, eta lehenengoa hartzeko. Ondoren,
behin galdera lortu dugula, erantzun zuzena beti posizio berean ager
ez dadin, PHP-ren array batean sartu ditugu erantzun zuzena eta okerrak,
eta `shuffle($array)` funtzioa erabili dugu ordena ausaz aukeratzeko.
Galderaren identifikadorea gordetzeko formulario barruan
`<input type="hidden">` elementu bat erabili dugu. Erantzuna ondo dagoen
ala ez egiaztatzeko, AJAX bitartez eskaera egiten diogu zerbitzariari,
eta erantzuna zuzena bada, _Ondo_ katea erantzuten du. Galdera berdinak
agertu ez daitezen, galdera bat zuzen erantzutean `$_SESSION` aldagaian
gordetzen dugu horren _id_-a, eta MySQL-ren kontsultan _id_ horiek
baztertzen ditugu.

> Erabiltzaileek goitizen bat jarri dezakete, eta zuzen erantzutean,
goitizena ez bada hutsa, DB-an gordeko da ondo erantzun duela, Top 10-ean
erakutsi ahal izateko

- [x] **Playing-by-subject**: Jokalariak gai bat aukeratu ondoren,
gai horretako 3 galdera aurkeztuko zaizkio, eta erantzun
ondoren asmatutakoen kopurua eta bataz besteko zailtasuna
bistaratuko dira.

> Ariketa hau egiteko, lehenengo gai desberdin guztiak zerrendatu ditugu,
eta web orrian gehitu ditugu, testu eremuan idatzi ahala gai posibleak
bete daitezen. Ondoren, gaia aukeratzeko testu eremua aldatzen denean,
zerbitzariari 3 galdera eskatzen zaizkio, AJAX bitartez. Ez badago
galderarik gai horrekin, emaitza hutsa egongo da, eta hortaz ez da
ezer pantaiaratuko. Galderarik baldin badago, galdera horien ausaz gehienez
hiru bueltatuko ditu zerbitzariak `ORDER BY RAND() LIMIT 3` erabiliz.
Galdera horiek gero pantaian erakutsiko dira jQuery erabiliz, kate
moduan ditugun elementu batzuk DOM-ean gehitzeko. Azkenik, galderak
erantzun ondoren, botoia sakatzean, AJAX bitartez zerbitzariari galderak
eta erantzunak bidaltzen zaizkio, eta honek DB-an erantzunak egiaztatzen
ditu, eta erantzun zuzenak eta bataz besteko zailtasunak bueltatzen ditu,
eta horiek bezeroak pantaiaratzen ditu.

> Erabiltzaileek goitizen bat jarri dezakete, eta galderetako bat gutxienez
zuzen erantzutean, goitizena ez bada hutsa, DB-an gordeko da ondo
erantzun duela, Top 10-ean erakutsi ahal izateko.

- [x] **Top 10 quizers - Global ranking**: Erabiltzaile anonimoek
goitizen bat erabili dezakete. Hori egitean, beraien asmatze
kopuruaren arabera sailkatuko dira, eta 10 hoberenak `layout.php`
orrialdean agertuko dira.

> Aurreko ariketetan, goitizenak gorde ditugu DB-an. Horretarako bi taula
erabili ditugu: Bata (_Nicks_ izenekoa), goitizen bakoitzari ID bat emateko,
eta bestea (_Nicks2Questions_ izenekoa), goitizen bakoitzaren ID-ak zuzen
erantzutako galderekin lotzeko (Dena taula batean gorde zitekeen, baina
goitizen luzeekin espazioa aurrezten dugu honela). Taula hauen datuak
erabiliz, eta MySQL-ren kontsulta luze baina bakar batekin, nahi ditugun
datuak lortu ditzakegu, `COUNT`, `JOIN`, `GROUP BY`, `ORDER BY` eta `LIMIT`
klausulak erabilita. Azkenik lortzen ditugun hamar horiek taula batean
erakusten ditugu.