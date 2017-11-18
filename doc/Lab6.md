# Ataza 1

Ataza hau burutzeko, aurreko ataletan egin behar izan dugun
web zerbitzu baten antzekoa egin behar genuen. Kasu honetan, aldiz,
emaitzaren mota, mota konplexu bat da.

Hau ahalbidetzeko, `NuSOAP` liburutegiak eskaintzen duen mota
konplexuak eraikitzeko `addComplexType` funtzioa erabili dugu,
ondorengo parametroak pasaz.

```php
$server->wsdl->addComplexType(
    'QuestionData', // Izena
    'complexType',
    'struct', // Mota
    'all',
    '',
    array( // Gorputza
        'testua' => 'xsd:string',
        'zuzena' => 'xsd:string',
        'zailtasuna' => 'xsd:string'
    )
);
```

Honek `QuestionData` mota konplexua deklaratzen du. Orain, datu mota hori
erabiliko dugu, gure funtzioaren emaitza moduan, `tns` _namespace_-a
erabiliz.

```php
$server->register(
    'getQuestion', // Funtzio izena
    array('id' => 'xsd:integer'), // Parametroak
    array('return' => 'tns:QuestionData'), // Emaitza
    'http://soapinterop.org/' // Namespace
); 
```

Behin zerbitzua eraikita dagoela, bezero bat sortu behar genuen. Honetarako,
aurreko ataletan bezala kontsumatu dugu:

```php
function get_question_data($id)
{
    $bezeroa = new SoapClient("https://$_SERVER[HTTP_HOST]/getQuestionWZ.php?wsdl");
    $emaitza = $bezeroa->getQuestion($id);
    return $emaitza;
}
```

Ondoren, funtzio horren emaitzatik, adibidez, galderaren enuntziatua lortzeko,
honela egin dezakegu.

```php
$galdera = get_question_data($_GET["id"]);
$enuntziatua = $galdera->testua;
```


# Ataza 2

Ataza honetan geolokalizazio web zerbitzu bat kontsumatu behar genuen.
Guk aukeratutako web zerbitzua [http://ip-api.com/](http://ip-api.com/) da.
Zerbitzu honek, IP helbide bat emanda, helbide horren kokapena bueltatzen
du. Zerbitzu honek bai JSON bai XML formatuetan eman ditzake emaitzak, baina
JSON datu gutxiago okupatzen duenez, hori erabili dugu. Web zerbitzu hau,
gainera, REST API baten bitartez atzitzen da. Horretarako, PHP-ren _cURL_
funtzioak erabili ditugu.

API-ari deia egiteko, lehenengo bezeroaren IP helbidea lortu dugu,
`$_SERVER["REMOTE_ADDR"]` aldagaiarekin, eta web zerbitzuaren url-ari gehitu
diogu. Azkenik web zerbitzua deituz. Gainera, errorerik gertatu den 
begiratzen dugu.


```php
function get_location()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/" . $_SERVER["REMOTE_ADDR"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($output, true);

    if ($data["status"] === "success") {
        return $data;
    } else {
        return null;
    }
}
```

Emaitza JSON formatuan ematen digute, eta honelako itxura du:

```json
{
    "as":"AS12338 Euskaltel S.A.",
    "city":"San Sebastián de La Gomera",
    "country":"Spain",
    "countryCode":"ES",
    "isp":"Euskaltel S.A.",
    "lat":28.0916,
    "lon":-17.1133,
    "org":"Euskaltel S.A.",
    "query":"85.86.248.34",
    "region":"CN",
    "regionName":"Canary Islands",
    "status":"success",
    "timezone":"Atlantic/Canary",
    "zip":"38800"
}
```

Errorerik emanez gero, honakoa da irteera:

```json
{  
    "message":"invalid query",
    "query":"HauEzDaIPHelbideBat",
    "status":"fail"
}
```

Guk erabili ditugun datuak 3 dira:
- `status`: Errorerik egon den jakiteko
- `lat`: IP helbidearen kokapenaren latitudea.
- `lon`: IP helbidearen kokapenaren longitudea.

Latitudea eta longitudea oso zenbaki itsusiak direnez, hauek ondo ikus
daitezen Google Maps-en API-a erabili dugu mapa batean erakusteko.

Honetarako, Google-en web orrialdean dagoen adibide sinple bat hartu dugu,
eta gure koordenatuetan markagailu bat gehitu dugu, honela:

```javascript
function init_map() {
    // Maparen konfigurazioa
    var myOptions = {
        zoom: 16,
        center: new google.maps.LatLng(<?= $lat ?>, <?= $lon ?>),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };
    map = new google.maps.Map(document.getElementById('map'), myOptions);
    
    marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(<?= $lat ?>, <?= $lon ?>)
    });
}

google.maps.event.addDomListener(window, 'load', init_map);
```

Ikusi daiteke nola `$lat` eta `$lon` aldagaiak sartu ditugun kokapena ezartzen
duten funtzioen deietan.
