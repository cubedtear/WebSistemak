# Ataza 1

Ataza honetan 20 segunduro freskatzen den galderen kontagailu
bat gehitu behar genuen. Honek bi zenbaki erakutsi behar zituen:
orain kautotuta dagoen erabiltzaileak sartutako galderen kopurua,
eta guztira dauden galderen kopurua. Honetarako AJAX erabili behar zen.

Javascript fitxategian, 20 segunduro koderen bat exekutatzeko
`setInterval` funtzioa erabili dugu. Honek behin eta berriro exekutatu
beharreko funtzio bat, eta periodoa milisegundotan hartzen du.

Ondoren, jQuery-ren `$.get` funtzioa erabili dugu zerbitzariari
AJAX bitartez eskaera bat egiteko. Eskaera kautotuta dauden
erabiltzaileek soilik egin dezaketenez, saioa mantentzeko erabiltzen
dugun _token_-a ere bidali dugu. Azkenik, emaitza lortzean, orrian
dagokion tokian sartu dugu.


```javascript
setInterval(function () {
    $.get('handlingQuizes.php?myquestions&token=' + token).done(function (result) {
        $("#question_count").html(result);
    });
}, 20000)
```

# Ataza 2

Ataza honetan aurreko ataleko kontagailuaren antzeko bat gehitu behar
genuen, baina kasu honetan aldi berean web orrian kautotuta dauden
erabiltzaileen kopurua erakutsi behar zuen. Hau ere 20 segunduro
freskatu dugu, `setInterval` funtzioa erabiliz.

XML fitxategi batean gordeko dugu uneoro dagoen erabiltzaileen kopurua.
Honela, erabiltzaile berri bat kautotzean, bat gehituko diogu balioari,
eta erabiltzaileak irten ahala, bat kenduko diogu.

XML fitxategia maneiatzeko, PHP-ren `SimpleXML` erabili dugu, aurreko
laborategian bezala.

Hau ez da oso modu eraginkorra, gure sesioen inplementazioa ez duelako
PHP-renak bezain beste funtzionalitate, baina suposatuta erabiltzaileek
saioa ixten dutela web orritik irtetzean, kontagailua ondo egon beharko luke.

### Kautotzean
```php
$xml = new SimpleXMLElement(file_get_contents("xml/counter.xml"));
$xml[0] = intval($xml[0])+1;
$xml->asXML("xml/counter.xml");
```

### Irtetzean
```php
$xml = new SimpleXMLElement(file_get_contents("xml/counter.xml"));
$xml[0] = intval($xml[0])-1;
$xml->asXML("xml/counter.xml");
```

### Datua ematen duen PHP atala
```php
if (isset($_GET["user_count"])) {
    $xml = new SimpleXMLElement(file_get_contents("xml/counter.xml"));
    echo $xml[0];
    die();
}
```

### Datua eskatzen duen Javascript kodea
```javascript
$.get('handlingQuizes.php?user_count&token=' + token).done(function (result) {
    $("#user_count").html(result);
});
```