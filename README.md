xlsx2ucm
========

_Rīks, ar ko var sūtīt dokumentus uz UCM pēc Excel tabulas_

---

## Biznesa vajadzība

Sendigo sūtīs dokumentus un Excel (2007) failu uz FTP, un no turienes vajadzēs sūtīt dokumentus uz UCM, ņemot arī metadatus no Excel faila kolonnām.

---

## Apraksts

* ~~Pieslēgties pie SFP~~ Manuāli ielejuplādēt failus no servera un palaist skriptu (lokāli)
* Salasīt metadatus un faila nosaukumus no Excel 2007 (.xlsx) faila
* Sūtīt uz UCM PDF failus (kas ir pedejā kolonnā) ar metadatiem uz UCM
* Aizvietot faila nosaukumu (pedējo kollonnu; faila nosaukumi var atkārtoties - sūtīt tikai pirmo) ar UCM tiešo linku uz failu
* Saglabāt failu
  * Kā Excel 2007
  * Kā CSV (priekš Excel - bes iekavām)
* Aizsūtīt atskaiti pie CC&B

---

Piemērs:

* 14:30 - saņēmu e-pastu (izlasīju)
* 14:33 - nokopēju datus no FTP
* 14:35 - palaidīju processu uz UCM...
```
ajanso05@KVP-18233:~/Documents/UCM/Sendigo> php -f xlsx2ucm.php dev files/TEST1_29.09_7070/TEST1_29.09.xlsx
9999200000.pdf.
[2014-10-07T12:35:21+01:00] CheckIn a document...done:Successfully checked in content item '00000032685'.
...
9491200000.pdf.
[2014-10-07T12:45:53+01:00] CheckIn a document...done:Successfully checked in content item '00000033765'.
[2014-10-07T12:45:55+01:00] File has been created files/TEST1_29.09_7070/TEST1_29.09.xlsx
```
* 14:50 - aizsūtīju izejošo xlsx uz CC&B 

---

## Vērsiju izmaiņas

- [YYYY.MM.DD H:i:s] v1.0: Pirma parbāudīta versija

## Konfigurācija

Konfigurācija atrodas ```config.php``` failā.

### Mapping

| UCM | Type | Sendigo | Sample | Notes
|---- |----- |-------- | -------|-------
| dDocTitle | VARCHAR2(255) | CustomerName / Account No / Contact Date | RIINA SOIDLA / 0839200000 / 2014.09.10 |  
| dDocAuthor | VARCHAR2(255) | &quot;sendigo_user&quot; | sendigo_user | Servcie user name
| dDocName | VARCHAR2(31) | (UCM auto generated) | (auto) |  
| dSecurityGroup | VARCHAR2(255) | &quot;LE_EE&quot; or &quot;LE_LT&quot; | LE_EE |  
| primaryFile | VARCHAR2(255) | (filename) | 839200000.pdf |  
| dDocAccount | VARCHAR2(255) | &quot;Elektrum_EE&quot; or &quot;Elektrum_LT&quot; | Elektrum_EE |  
| dDocType | VARCHAR2(30) | &quot;MASSPROLONGATION&quot; | MASSPROLONGATION |  
| xldcProfile | VARCHAR2(30) | &quot;sendigo&quot; | sendigo |  
| xChannelIn |   |   | (empty) |  
| xChannelOut |   | &quot;email&quot; | email |  
| xCountry | VARCHAR2(30) | &quot;Estonia&quot; or &quot;Lithuania&quot; | Estonia | From excel
| xCustomerName | VARCHAR2(200) |   | RIINA SOIDLA | From excel
| xCustomerType | VARCHAR2(30) | &quot;Private&quot; or &quot;Business&quot; | Private | From excel
| xCustomerCode | VARCHAR2(30) |   | 45706076520 | From excel
| xCustomerEIC | VARCHAR2(30) |   | 38X-AVP-71F200E7 | From excel
| dInDate | VARCHAR2(26) | (UCM auto generated) | (auto) |  
| xCCBAccountID | VARCHAR2(250) | AccountId | 0839200000 | From excel
| xContractID | VARCHAR2(250) | AccountId | 0839200000 | From excel
| xDocModified | VARCHAR2(26) | Not filled | (auto) |  
| xContactDate |   |  | 10.09.2014 | From excel
| xOfferExpirationDate |   |  | 30.09.2014 | From excel
| xCampaignId |   |  | 1 | From excel
| xAccountManagerName |   |   | (not defined) | From excel

---

## Lietošana

Nokopēt falus ```files/``` folderī un palaist skriptu:

    php -f xlsx2ucm.php dev files/...xlsx  > files/...`date +"%Y%m%d%H%M%S"`.log

## Minimālas sistēmas prasības

* PHP

## TODO

* Automatiski lasīt SFTP saturu
* Kad paradīsies jauns folderis un xlsx fails iekš tā - palaist processu

---

## Pasūtītājs

Autortiesības (c) 2014 Artūrs Jansons, AS "Latvenergo"

Atļauja tiek piešķirta bez maksas, jebkurai personai iegūstot kopiju šo programmatūru un ar to saistītās dokumentācijas failus ( "Programmatūra"), kas nodarbojas ar programmatūras bez ierobežojumiem, tostarp bez ierobežojuma tiesības lietot, kopēt, modificēt, apvienot , publicēt, izplatīt, licencēt, un / vai pārdot Programmatūras kopijas, un ļaut personām, kurām Software ir mēbelēts to darīt, ievērojot šādus nosacījumus: 

Iepriekš paziņojums par autortiesībām, un šis paziņojums atļauju jāiekļauj visās kopijās vai būtiskām daļām Programmatūru. 

PROGRAMMATŪRA "KĀ IR", BEZ GARANTIJAS JEBKĀDA VEIDA, tiešu vai netiešu, IESKAITOT, BET NE aprobežojas ar GARANTIJAS, PIEMĒROTĪBU NOTEIKTAM MĒRĶIM UN NEPĀRKĀPŠANU. NEKĀDĀ GADĪJUMĀ autoru vai autortiesību turētāji ATBILDĪGI PAR nekādas pretenzijas, ZAUDĒJUMU vai citām saistībām, VAI NU prasību LĪGUMA, pārkāpumiem vai citiem gadījumiem, kuri radušies no vai SAKARĀ AR PROGRAMMATŪRAS VAI IZMANTOŠANAS VAI CITIEM darījumos, kas notiek SOFTWARE.

***

Copyright (c) 2014 Artūrs Jansons, AS "Latvenergo"

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.