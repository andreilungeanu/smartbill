# Smartbill API Documentation

This file provides a comprehensive overview of all the available API endpoints and their required parameters, based on the official Smartbill Postman collection.

---

## Facturi

### Emitere factura firma neplatitoare de TVA
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Pentru o firma neplatitoare de TVA nu este necesara transmiterea parametrilor care au legatura cu calculul TVA. TVA nu va fi calculat si nu va fi afisat pe factura.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere factura ciorna
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

La facturare exista posibilitatea emiterii unei facturi ***ciorna***.  
In acest caz, factura nu va fi efectiv emisa si nu va primi un numar pe serie.  
Aceasta factura poate fi ulterior vizualizata si salvata (emisa) din contul de Cloud din sectiunea ***Facturi*** a meniului ***Rapoarte***.

Pentru emiterea unei facturi ciorna, trebuie ca parametrul **isDraft** sa fie **true**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": true,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Emitere factura simpla - produse fara cod
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Acesta e un exemplu de emiterea a unei facturi cu minim de optiuni pentru o firma care nu utilizeaza coduri de produse.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere factura simpla - produse cu cod
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Acesta e un exemplu de emiterea a unei facturi cu minim de optiuni pentru o firma care utilizeaza coduri de produse.

Mai exact, apare parametrul **"code"** in cadrul produsului facturat.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
      {
        "name": "Produs 1",
        "code": "codProdus1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere factura - produse cu descriere
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Acesta e un exemplu de emiterea a unei facturi cu minim de optiuni pentru o firma care utilizeaza descrieri ale produsului.

Mai exact, apare parametrul **"productDescription"** in cadrul produsului facturat.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
      {
        "name": "Produs 1",
        "productDescription": "conform contract nr. 10/2022 pentru luna mai",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere factura cu servicii
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Orice produs facturat poate fi de tipul produs sau serviciu.

In cazul in care se doreste facturarea de servicii, parametrul **"isService**" va avea valoarea **true**.

In acest caz, nu se mai foloseste modul de lucru cu descarcare de gestiune.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "products": [
      {
        "name": "Abonament lunar",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": true
      }
    ]
  }
```

---

### Emitere factura cu returnarea linkului public al facturii
**Method:** `Smartbill::invoices()->createV2(array $data)`

**Description:**

Orice produs facturat poate fi de tipul produs sau serviciu.

In cazul in care se doreste facturarea de servicii, parametrul **"isService**" va avea valoarea **true**.

In acest caz, nu se mai foloseste modul de lucru cu descarcare de gestiune.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "products": [
      {
        "name": "Abonament lunar",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": true
      }
    ]
  }
```

---

### Emitere factura cu CIF intracomunitar
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

In cazul in care se doreste emiterea facturii cu CIF intracomunitar, este necesara utilizarea parametrului boolean **"useIntraCif"**. Parametrul poate sa fie folosit si in cazul in care se doreste folosirea de CIF OSS.

Cand **"useIntraCif = false"**, factura se va emite cu CIF-ul standard al firmei.

Cand **"useIntraCif = true"**, factura se emite cu CIF-ul intracomunitar doar daca acesta este configurat pe firma. In cazul in care nu a fost setat un CIF intracomunitar, se va emite cu CIF-ul standard.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "useIntraCif": true,
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere factura pe baza de proforma
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Acesta este un exemplu de emitere de factura cand facturarea se face pe baza unei proforme.

Factura proforma pe baza careia se face emiterea trebuie sa fie deja emisa. Emiterea ei se poate face fie din contul SmartBill Cloud, fie prin API public, apeland metoda de emitere de proforma.

Parametrii care fac referire la factura proforma pe baza careia se face emiterea sunt:

``` json
"useEstimateDetails": true,
    "estimate": {
      "seriesName": "ppub1",
      "number": "0021"
    }

```

Pentru ca o factura sa fie generata cu succes este necesar ca:

*   parametrul **useEstimateDetails** sa fie **true** pentru a se prelua pe factura informatiile din proforma
*   **specificarea seriei si a numarului facturii proforme** pe baza careia este emisa factura.  
    In cazul in care parametrul **useStock** este **true**, se va face descarcarea din stoc a produselor facturate.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "isDraft": false,
    "issueDate": "{{todayDate}}",
    "dueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "useStock": false,
    "useEstimateDetails": true,
    "estimate": {
      "seriesName": "{{estimateSeriesName}}",
      "number": "0032"
    }
  }
 
 
```

---

### Emitere factura cu link de plata
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Exista doua modalitati prin care poti adauga un link de plata pe document.

O prima modalitate presupune generarea link-ului de plata la momentul emiterii facturii si va functiona doar in situatia in care ai configurat contul tau Netopia Payments sau cel de EuPlatesc in [Contul Meu > Integrari](https://cloud.smartbill.ro/core/integrari//).

In acest caz, la trimiterea parametrului de mai jos se va genera link-ul de plata in Netopia, respectiv EuPlatesc, si link-ul va fi asociat butonului "PLATESTE CU CARDUL" de pe factura:

``` json
  {
  "paymentUrl": "Generate URL",
  }

```

Raspuns:

``` json
 {
   "errorText": "",
   "message": "",
   "number": "0177",
   "series": "fac",
   "url": "https://secure.mobilpay.ro/qp/BiSujm14-w3g2c-DzlVa0"
 }

```

A doua modalitate prin care poti adauga link-ul de plata pe documente se aplica in situatia in care utilizezi un operator de plati diferit de cei integrati in SmartBill, spre exemplu Stripe. Astfel, va fi necesara generarea link-ului de plata in Stripe si apoi trimiterea acestuia pe parametrul paymentURL cu valoarea linkului respectiv.

``` json
  {
  "paymentUrl": "link_plata_din_stripe",
  }

```

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "paymentUrl": "Generate URL",
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere factura cu discount valoric
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

La emiterea facturii este posibila aplicarea unui discount care poate fi valoric sau procentual. Discountul se va aplica pe un numar de produse introduse deja pe factura. Numarul de produse va fi stabilit prin parametrul **numberOfItems**, care face referire la produsele si discounturile imediat anterioare discountului.

La **discountul valoric** se stabileste direct valoarea discountului. In acest caz parametrul discountType ia valoarea 1, iar valoarea discountului se transmite ca valoare negativa prin parametrul discountValue.

``` json
{
    "isDiscount": true,
    "numberOfItems": 2,
    "discountType": 1,
    "discountValue": -5
}

```

In cazul aplicarii unui discount pe un produs se urmaresc pasii urmatori:

*   Se adauga produsul/produsele pe document, parametrul isDiscount este false. Detalii in tabelul [Date produs](https://api.smartbill.ro/#DateProdus).
*   **Atentie!** Parametrul numberOfItems stabileste numarul de produse pe care se aplica discountul si reprezinta numarul de produse (produs sau discount) care sunt imediat anterioare sectiunii curente.
*   In cazul in care se doreste aplicarea a doua discounturi (spre ex, unul procentual si unul valoric) pe acelasi produs/produse, discounturile se adauga succesiv. Al doilea discount se va aplica atat pentru produs cat si pentru primul discount care a fost deja considerat.
    

**Observatii in cazul aplicarii unui discount pe mai multe produse!**

1) In cazul aplicarii unui discount pe mai multe produse, cota TVA folosita pentru discount, va fi cota TVA folosita pentru produsele pe care acest discount se aplica. Valorile parametrilor taxName si/sau taxPercentage sunt ignorate.

2) Discountul procentual poate fi aplicat pe mai multe produse doar daca acestea au aceeasi cota TVA. In cazul in care se aplica pe produse cu cote TVA diferite, va fi returnat un mesaj de eroare.

3) Discountul valoric poate fi aplicat pe mai multe produse cu cote TVA diferite. In acest caz este obligatorie specificarea cotei TVA folosite la discount prin parametrii taxPercentage (obligatoriu) si taxName (optional).

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        },
        {
            "name": "Discount valoric Produs 1",
            "isDiscount": true,
            "numberOfItems": 1,
            "measuringUnitName": "buc",
            "currency": "RON",
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "discountType": 1,
            "discountValue": -5
        }
    ]
}
```

---

### Emitere factura cu discount procentual
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

La emiterea facturii este posibila aplicarea unui discount care poate fi valoric sau procentual. Discountul se va aplica pe un numar de produse introduse deja pe factura. Numarul de produse va fi stabilit prin parametrul numberOfItems, care face referire la produsele si discounturile imediat anterioare discountului.

La **discountul procentual** se stabileste procentul care se aplica pe suma de plata pentru a obtine valoarea discountului. In acest caz parametrul discountType ia valoarea 2, iar procentul discountului se transmite ca numar (fara procent) prin parametrul discountPercentage.

``` json
{
    "isDiscount": true,
    "numberOfItems": 2,
    "discountType": 2,
    "discountPercentage": 10
}

```

In cazul aplicarii unui discount pe un produs se urmaresc pasii urmatori:

*   Se adauga produsul/produsele pe document, parametrul isDiscount este false. Detalii in tabelul [Date produs](https://api.smartbill.ro/#DateProdus).
*   **Atentie!** Parametrul numberOfItems stabileste numarul de produse pe care se aplica discountul si reprezinta numarul de produse (produs sau discount) care sunt imediat anterioare sectiunii curente.
*   In cazul in care se doreste aplicarea a doua discounturi (spre ex, unul procentual si unul valoric) pe acelasi produs/produse, discounturile se adauga succesiv. Al doilea discount se va aplica atat pentru produs cat si pentru primul discount care a fost deja considerat.
    

**Observatii in cazul aplicarii unui discount pe mai multe produse!**

1) In cazul aplicarii unui discount pe mai multe produse, cota TVA folosita pentru discount, va fi cota TVA folosita pentru produsele pe care acest discount se aplica. Valorile parametrilor taxName si/sau taxPercentage sunt ignorate.

2) Discountul procentual poate fi aplicat pe mai multe produse doar daca acestea au aceeasi cota TVA. In cazul in care se aplica pe produse cu cote TVA diferite, va fi returnat un mesaj de eroare.

3) Discountul valoric poate fi aplicat pe mai multe produse cu cote TVA diferite. In acest caz este obligatorie specificarea cotei TVA folosite la discount prin parametrii taxPercentage (obligatoriu) si taxName (optional).

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        },
        {
            "name": "Discount valoric Produs 1",
            "isDiscount": true,
            "numberOfItems": 1,
            "measuringUnitName": "buc",
            "currency": "RON",
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "discountType": 2,
            "discountPercentage": 10
        }
    ]
}
```

---

### Emiterea unei facturi incasate
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Incasarea unei facturi se poate face partial sau integral la facturare, sau ulterior prin [emiterea unei incasari](https://api.smartbill.ro/#Incasari/addPaymentPublicApi).

Parametrul type defineste tipul de incasare care poate fi Chitanta, Bon, Card, CEC, Bilet ordin, Ordin plata, Mandat postal sau Alta incasare.

In cazul in care incasarea se face cu chitanta, o chitanta va fi automat emisa si salvata in sistem. Pentru aceasta, seria chitantei trebuie specificata (serie definita in prealabil in contul Cloud, [vezi serii documente](https://api.smartbill.ro/#SeriiDocumenteCloud)).

In cazul in care sunteti conectati la o casa de marcat, incasarea se poate face prin emiterea unui bon fiscal. Pentru aceasta, se alege tipul de incasare *type = Bon* iar apoi se apeleaza metoda de [emitere bon](https://api.smartbill.ro/#Incasari/addPaymentPublicApi/Emitere_de_bon_fiscal).

Incasarea facturii la emitere se face prin introducerea sectiunii **payment** care contine parametrii referitori la incasare. Pentru detalii vezi [tabelul de parametrii pentru incasare](https://api.smartbill.ro/#DateIncasare).

``` json
 "payment": {
    "value": 10,
    "paymentSeries": "{{paymentSeries}}",
    "type": "Chitanta",
    "isCash": true
 }

```

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        }
    ],
    "payment": {
        "value": 10,
        "paymentSeries": "{{paymentSeries}}",
        "type": "Chitanta",
        "isCash": true
    }
}
```

---

### Emitere factura pe o firma cu TVA la incasare
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Stabilirea valorii TVA si afisarea ei pe factura depinde de statusul de platitor TVA al firmei. Statusul de platitor TVA se poate modifica din pagina de [Configurare > Date firma.](https://cloud.smartbill.ro/core/configurare/date-firma/)

Optiunea de emitere a facturii cu TVA la incasare se va utiliza doar in cazul in care firma este configurata ca platitoare de TVA si cand s-a ales incasarea totala sau partiala la facturare.

Pentru corectitudinea datelor inregistrate pe factura, este necesara corelarea valorilor referitoare la incasarea din sectiunea TVA la incasare cu cele din sectiunea **payment**. Aplicatia nu face verificari in aceasta privinta.

Acestia sunt parametrii necesari pentru definirea folosirii TVA la incasare sunt:

| Parametru | Value |
| --- | --- |
| paymentBase | valoare baza incasata pe baza careia se calculeaza TVA |
| colectedTax | valoare TVA incasat |
| paymentTotal | total valoare incasata = valoare baza incasata + valoare TVA incasat |

Exemplu:

``` json
{
    "usePaymentTax": true,
    "paymentBase": 20,
    "colectedTax": 0.19,
    "paymentTotal": 20,
}

```

#### Situatii

Cand **usePaymentTax** are valoarea **false** sau lipseste din request, indiferent daca restul parametrilor apar sau nu, pe factura nu va fi afisat nimic in legatura cu TVA la incasare.

Cand **usePaymentTax** are valoarea **true** si:

*   *firma este neplatitoare de TVA*:
    *   se va primi un raspuns de eroare cu mesajul: "O firma neplatitoare de tva nu poate folosi tva la incasare!"
*   *paymentBase, colectedTax si paymentTotal au valoarea 0 sau lipsesc din request*:
    *   pe factura va fi afisata mentiunea :***TVA la incasare*** fara detalii despre valoarea TVA-ului incasat deja sau ramas de incasat
*   *paymentBase, colectedTax si paymentTotal vor primi valori corespunzatoare cu incasarea care a fost facuta la facturare*:
    *   pe factura vor fi afisate valorile incasarii, a TVA-ului colectat, a sumei ramase de incasat si a TVA-ului ramas de incasat

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "usePaymentTax": true,
    "paymentBase": 20,
    "colectedTax": 0.19,
    "paymentTotal": 20,
    "products": [
      {
        "name": "Servicii consultanta",
        "code": "ccd1",
        "productDescription": "conform contract nr. 10/2021 pentru luna mai",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 2,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": true
      }
    ],
    "payment": {
      "value": 100,
      "type": "Ordin plata",
      "isCash": false
    }
  }

```

---

### Emitere factura si trimiterea ei pe email clientului ca atasament + link catre factura
**Method:** `Smartbill::invoices()->send(array $data)`

**Description:**

Trimiterea facturii pe email clientului se poate face in momentul facturarii sau dupa momentul facturarii.

Configurarea serverului propriu de email in contul SmartBill este necesara pentru trimiterea email-ului clientului si se poate face din [SmartBill Cloud > Configurare > Email](https://cloud.smartbill.ro/core/configurare_mailuri/).

Trimiterea facturii in momentul emiterii ei se face prin setarea parametrului **sendEmail** ca **true**.

Se poate adauga si sectiunea **email** care va contine parametri referitori la cine va primi factura.

``` json
 "sendEmail": true,
 "email": {
     "to": "test@firma.ro",
     "cc": "test1@firma.ro",
     "bcc": "test2@firma.ro"
 }

```

Atunci **cand parametrul "to" lipseste**, factura va fi trimisa adresei de email definita in parametrul **email** din sectiunea **client**.

Atunci **cand parametrul "to" este completat**, email-ul din acest camp va primi factura si nu cel definit in parametrul **email** din sectiunea **client**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "sendEmail": true,
    "email": {
        "cc": "",
        "bcc": ""
    },
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Emitere factura in valuta
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

O factura poate fi emisa in RON (comportament default) sau in valuta.

In cazul emiterii in valuta se folosesc urmatorii parametri:

``` json
    "currency": "EUR",
    "exchangeRate": 4.47,

```

Parametrul **exchangeRate** stabileste rata de curs valutar si este folosit in conversia automata a facturii.  
Daca acest parametru nu este specificat, se va prelua cursul valutar de pe BNR din ziua curenta.

Exista posibilitatea calcularii automate a unui curs valutar adaptat pentru toate facturile emise prin preluarea cursului din ziua curenta/precedenta si aplicarea unui adaos. Pentru configurarea acestui mod de lucru, [vezi detalii aici](https://api.smartbill.ro/#ConfigurareCursValutar).

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "currency": "EUR",
    "exchangeRate": 4.47,
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "EUR",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Emitere factura pentru produse cu pretul de referinta in valuta
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

Aplicatia SmartBill permite stabilirea de preturi de referinta in valuta pentru produsele facturate, si facturarea lor in RON sau valuta.

Pentru a efectua corect aceasta operatie este necesara configurarea contului firmei prin activarea preferintei "Am preturi cu moneda de referinta in valuta" din [Preferinte generale](https://cloud.smartbill.ro/documente/preferinte-generale/). Pentru mai multe detalii [vezi aici](https://api.smartbill.ro/#ConfigurareFacturiValuta).

La facturare, produsele pot avea preturi in monede diferite (ex: 2 produse in EUR, 1 produs in RON si 1 produs in USD). In cazul in care cursul valutar nu este specifica pe produse,cursul valutar folosit va depindede optiunile alese in cadrul sectiunii "Curs valutar preluat de pe internet" in zona [Preferinte personale](https://cloud.smartbill.ro/core/configurare/preferinte-personale/).

* * *

Parametrii specifici pentru acest mod de facturare sunt:

*   **In sectiunea date de facturare**, parametrul **currency** care defineste moneda in care se emite factura
*   In sectiunea **products**:
    *   parametrul **currency** care defineste moneda in care este specificat pretul produsului
    *   parametrul **price** care defineste pretul in moneda specificata
    *   Daca se doreste folosirea unui curs valutar diferit de cel preluat de pe internet sau cel definit in contul Cloud, se foloseste parametrul **exchangeRate** care defineste cursul valutar folosit la conversia automata a facturii.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "currency": "RON",
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
        {
            "name": "Produs 1",
            "productDescription": "produs cu pretul de referinta in EUR",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "EUR",
            "exchangeRate": 4.47,
            "quantity": 2,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "isService": false
        },
        {
            "name": "Produs 2",
            "productDescription": "produs cu pretul de referinta in USD",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "USD",
            "exchangeRate": 4.15,
            "quantity": 2,
            "price": 15,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "isService": false
        },
        {
            "name": "Produs 3",
            "productDescription": "produs cu pretul de referinta in RON",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 5,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "isService": false
        }
    ]
}
```

---

### Emitere factura cu descarcarea gestiunii
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

La emiterea unei facturi se poate alege descarcarea din gestiunea produselor facturate.

Descarcarea stocului se va face la emiterea facturii iar incarcarea stocului se va face in cazul in care factura este stearsa sau anulata.

Pentru descarcarea stocului in momentul facturarii urmatoarele sunt necesare:

*   sa aveti modulul de Gestiune activat;
*   sa existe cel putin o gestiune creata in [Nomenclatoare > Gestiuni](https://cloud.smartbill.ro/nomenclator/gestiuni/) ;
*   sa existe produse in stocul gestiunii;
*   unitatea de masura folosita pe produs la facturare sa fie aceeasi cu cea cu care s-a facut incarcarea produsului in stoc in contul Cloud.
    

Pentru a activa operarea stocului se foloseste parametrul useStock cu valoarea true.

``` json
    "useStock": true,

```

Daca se face emiterea unei facturi cu descarcare de gestiune, pe baza de proforma, parametrul useStock cu valoarea true este inclus in sectiunea **estimate**.

``` json
    "estimate": {
        [...]
        "useStock": true
    }

```

In cadrul sectiunii **products**, pentru fiecare produs facturat, este necesara specificarea numelui gestiunii din care se face vanzarea prin parametrul **warehouseName**.

> **Atentie!** Parametrul warehouseName este case sensitive.

``` json
"products": [
        {
            [...]
            "warehouseName": "Gestiune1",
        }
    ]

```

In cazul in care se doreste facturarea unui produs care nu se afla pe stoc, se foloseste parametrul **useStock** cu valoarea **false**. In acest caz, pentru a avea actualizate informatiile de gestiune, este necesar sa se faca descarcarea manuala a facturii din raportul [*Documente fara descarcare gestiune*](https://cloud.smartbill.ro/gestiune/raport/raport_documente_nedescarcate/) , odata ce produsul va deveni disponibil in stoc.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "useStock": true,
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "warehouseName": "{{warehouseName}}",
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Emitere factura in limba straina
**Method:** `Smartbill::invoices()->create(array $data)`

**Description:**

O factura este emisa in mod implicit in limba romana (RO), dar emiterea se poate face si intr-o alta limba.

Este permisa folosirea unei limbi de circulatie internationala, predefinita in SmartBill Cloud sau a unei limbi definite de administrator in contul Cloud.

Limba in care se va emite factura poate fi aleasa prin parametrul **language** din sectiunea datelor de facturare.

Pentru a include pe document denumirea produsului si a unitatii de masura folosite in limba de emitere a facturii, traducerea se va face prin folosirea parametrilor: **translatedName** si **translatedMeasuringUnit** din sectiunea **products**.

``` json
"language": "EN",
"products": [
        {
            [...]
            "translatedName": "Product 1",
            "translatedMeasuringUnit": "piece",
        }
    ]

```

Definirea unei limbi noi se face din [Configurare > Limbi](https://cloud.smartbill.ro/core/configurare/limba/).

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{invoiceSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "language": "EN",
    "products": [
        {
            "name": "Produs 1",
            "translatedName": "Product 1",
            "translatedMeasuringUnit": "piece",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Vizualizare PDF Factura
**Method:** `Smartbill::invoices()->getPdf(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta este metoda care trebuie apelata pentru descarcarea PDF-ului facturii.

In Postman, cand se apeleaza acest webservice, trebuie selectat **Send and Download.**

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{invoiceSeriesName}}
- `number`: 0025

---

### Interogare status incasare factura
**Method:** `Smartbill::invoices()->getPaymentStatus(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta metoda trebuie apelata cand e necesar sa se afle daca o factura a fost sau nu incasata.

Ca raspuns, se va primi:

*   suma facturii
*   suma incasarii
*   suma ramasa de incasat.

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{invoiceSeriesName}}
- `number`: 0025

---

### Stornare factura
**Method:** `Smartbill::invoices()->reverse(string $cif, string $seriesName, string $number, string $issueDate)`

**Description:**

Emiterea unei facturi storno este posibila doar in baza unei facturi existente in SmartBill Cloud.

Stornarea facturii este posibila doar in situatia in care pe aceasta nu exista stornari.

**Parameters:**

- `cif`: `string`
- `seriesName`: `string`
- `number`: `string`
- `issueDate`: `string`

---

### Anulare factura
**Method:** `Smartbill::invoices()->cancel(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta metoda poate fi apelata pentru orice factura care a fost deja emisa si presupune anularea facturii fara stergerea ei din baza de date.

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{invoiceSeriesName}}
- `number`: 2205

---

### Restaurare factura anulata
**Method:** `Smartbill::invoices()->restore(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta metoda trebuie apelata cand se doreste restaurarea unei facturi anulate.

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{invoiceSeriesName}}
- `number`: 2205

---

### Stergere factura
**Method:** `Smartbill::invoices()->delete(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta metoda trebuie apelata cand se doreste stergerea unei facturi.

**Doar ultima factura din serie poate fi stearsa**. Pentru celelalte facturi se poate aplica procedura de anulare.

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{invoiceSeriesName}}
- `number`: 2206

---

## Proforme

### Emitere proforma firma neplatitoare de TVA
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

Pentru o firma neplatitoare de TVA nu este necesara transmiterea parametrilor care au legatura cu calculul TVA. TVA nu va fi calculat si nu va fi afisat pe factura proforma.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "dueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Emitere proforma ciorna
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

La facturare exista posibilitatea emiterii unei proforme ***ciorna***.  
In acest caz, proforma nu va fi efectiv emisa si nu va primi un numar pe serie.  
Aceasta proforma poate fi ulterior vizualizata si salvata (emisa) din contul de Cloud din sectiunea ***Facturi*** a meniului ***Proforme***.

Pentru emiterea unei facturi ciorna, trebuie ca parametrul **isDraft** sa fie **true**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "dueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": true,
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Emitere proforma simpla - produse fara cod
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

Acesta e un exemplu de emiterea a unei proforme cu minim de optiuni pentru o firma care nu utilizeaza coduri de produse.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "dueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere proforma simpla - produse cu cod
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

Acesta e un exemplu de emiterea a unei proforme cu minim de optiuni pentru o firma care utilizeaza coduri de produse.

Mai exact, apare parametrul **"code"** in cadrul produsului facturat.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,

    "products": [
      {
        "name": "Produs 1",
        "code": "codProdus1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere proforma simpla - produse cu descriere
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

Acesta e un exemplu de emiterea a unei proforme cu minim de optiuni pentru o firma care utilizeaza descrieri ale produsului.

Mai exact, apare parametrul **"productDescription"** in cadrul produsului facturat.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "dueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "products": [
      {
        "name": "Produs 1",
        "productDescription": "conform contract nr. 10/2022 pentru luna mai",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere proforma cu servicii
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

Orice produs facturat poate fi de tipul produs sau serviciu.

In cazul in care se doreste facturarea de servicii, parametrul **"isService**" va avea valoarea **true**.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "products": [
      {
        "name": "Abonament lunar",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": true
      }
    ]
  }
```

---

### Emitere proforma cu returnarea linkului public al facturii
**Method:** `Smartbill::estimates()->createV2(array $data)`

**Description:**

Metoda este folosita cand se doreste returnarea informatiilor suplimentare:

| **Parametru** | **Semnificatie** |
| --- | --- |
| documentUrl | link-ul de editare al proformei |
| documentId | id-ul intern al proformei |
| documentViewUrl | link-ul public al proformei |

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "products": [
      {
        "name": "Abonament lunar",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": true
      }
    ]
  }
```

---

### Emitere proforma cu CIF intracomunitar
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

In cazul in care se doreste emiterea proformei cu CIF intracomunitar, este necesara utilizarea parametrului boolean **"useIntraCif"**. Parametrul poate sa fie folosit si in cazul in care se doreste folosirea de CIF OSS.

Cand **"useIntraCif = false"**, proforma se va emite cu CIF-ul standard al firmei.

Cand **"useIntraCif = true"**, proforma se emite cu CIF-ul intracomunitar doar daca acesta este configurat pe firma. In cazul in care nu a fost setat un CIF intracomunitar, se va emite cu CIF-ul standard.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "useIntraCif": true,
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere proforma cu link de plata
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

Exista doua modalitati prin care poti adauga un link de plata pe document.

O prima modalitate presupune generarea link-ului de plata la momentul emiterii proformei si va functiona doar in situatia in care ai configurat contul tau Netopia Payments sau cel de EuPlatesc in [Contul Meu > Integrari](https://cloud.smartbill.ro/core/integrari//).

In acest caz, la trimiterea parametrului de mai jos se va genera link-ul de plata in Netopia, respectiv EuPlatesc, si link-ul va fi asociat butonului "PLATESTE CU CARDUL" de pe factura:

``` json
  {
  "paymentUrl": "Generate URL",
  }

```

Raspuns:

``` json
 {
   "errorText": "",
   "message": "",
   "number": "0177",
   "series": "fac",
   "url": "https://secure.mobilpay.ro/qp/BiSujm14-w3g2c-DzlVa0"
 }

```

* * *

A doua modalitate prin care poti adauga link-ul de plata pe documente se aplica in situatia in care utilizezi un operator de plati diferit de cei integrati in SmartBill, spre exemplu Stripe. Astfel, va fi necesara generarea link-ului de plata in Stripe si apoi trimiterea acestuia pe parametrul **paymentURL** cu valoarea linkului respectiv.

``` json
  {
  "paymentUrl": "link_plata_din_stripe",
  }

```

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "paymentUrl": "Generate URL",
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ]
  }
```

---

### Emitere proforma cu discount valoric
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

La emiterea proformei este posibila aplicarea unui discount care poate fi valoric sau procentual. Discountul se va aplica pe un numar de produse introduse deja pe factura. Numarul de produse va fi stabilit prin parametrul **numberOfItems**, care face referire la produsele si discounturile imediat anterioare discountului.

La **discountul valoric** se stabileste direct valoarea discountului. In acest caz parametrul discountType ia valoarea 1, iar valoarea discountului se transmite ca valoare negativa prin parametrul discountValue.

``` json
{
    "isDiscount": true,
    "numberOfItems": 2,
    "discountType": 1,
    "discountValue": -5
}

```

In cazul aplicarii unui discount pe un produs se urmaresc pasii urmatori:

*   Se adauga produsul/produsele pe document, parametrul isDiscount este false. Detalii in tabelul [Date produs](https://api.smartbill.ro/#DateProdus).
*   **Atentie!** Parametrul numberOfItems stabileste numarul de produse pe care se aplica discountul si reprezinta numarul de produse (produs sau discount) care sunt imediat anterioare sectiunii curente.
*   In cazul in care se doreste aplicarea a doua discounturi (spre ex, unul procentual si unul valoric) pe acelasi produs/produse, discounturile se adauga succesiv. Al doilea discount se va aplica atat pentru produs cat si pentru primul discount care a fost deja considerat.
    

**Observatii in cazul aplicarii unui discount pe mai multe produse!**

1) In cazul aplicarii unui discount pe mai multe produse, cota TVA folosita pentru discount, va fi cota TVA folosita pentru produsele pe care acest discount se aplica. Valorile parametrilor taxName si/sau taxPercentage sunt ignorate.

2) Discountul procentual poate fi aplicat pe mai multe produse doar daca acestea au aceeasi cota TVA. In cazul in care se aplica pe produse cu cote TVA diferite, va fi returnat un mesaj de eroare.

3) Discountul valoric poate fi aplicat pe mai multe produse cu cote TVA diferite. In acest caz este obligatorie specificarea cotei TVA folosite la discount prin parametrii taxPercentage (obligatoriu) si taxName (optional).

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "dueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        },
        {
            "name": "Discount valoric Produs 1",
            "isDiscount": true,
            "numberOfItems": 1,
            "measuringUnitName": "buc",
            "currency": "RON",
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "discountType": 1,
            "discountValue": -5
        }
    ]
}
```

---

### Emitere proforma cu discount procentual
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

La emiterea proformei este posibila aplicarea unui discount care poate fi valoric sau procentual. Discountul se va aplica pe un numar de produse introduse deja pe factura. Numarul de produse va fi stabilit prin parametrul **numberOfItems**, care face referire la produsele si discounturile imediat anterioare discountului.

La **discountul procentual** se stabileste procentul care se aplica pe suma de plata pentru a obtine valoarea discountului. In acest caz parametrul discountType ia valoarea 2, iar procentul discountului se transmite ca numar (fara procent) prin parametrul discountPercentage.

``` json
{
    "isDiscount": true,
    "numberOfItems": 2,
    "discountType": 2,
    "discountPercentage": 10
}

```

In cazul aplicarii unui discount pe un produs se urmaresc pasii urmatori:

*   Se adauga produsul/produsele pe document, parametrul isDiscount este false. Detalii in tabelul [Date produs](https://api.smartbill.ro/#DateProdus).
*   **Atentie!** Parametrul numberOfItems stabileste numarul de produse pe care se aplica discountul si reprezinta numarul de produse (produs sau discount) care sunt imediat anterioare sectiunii curente.
*   In cazul in care se doreste aplicarea a doua discounturi (spre ex, unul procentual si unul valoric) pe acelasi produs/produse, discounturile se adauga succesiv. Al doilea discount se va aplica atat pentru produs cat si pentru primul discount care a fost deja considerat.
    

**Observatii in cazul aplicarii unui discount pe mai multe produse!**

1) In cazul aplicarii unui discount pe mai multe produse, cota TVA folosita pentru discount, va fi cota TVA folosita pentru produsele pe care acest discount se aplica. Valorile parametrilor taxName si/sau taxPercentage sunt ignorate.

2) Discountul procentual poate fi aplicat pe mai multe produse doar daca acestea au aceeasi cota TVA. In cazul in care se aplica pe produse cu cote TVA diferite, va fi returnat un mesaj de eroare.

3) Discountul valoric poate fi aplicat pe mai multe produse cu cote TVA diferite. In acest caz este obligatorie specificarea cotei TVA folosite la discount prin parametrii taxPercentage (obligatoriu) si taxName (optional).

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "dueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        },
        {
            "name": "Discount valoric Produs 1",
            "isDiscount": true,
            "numberOfItems": 1,
            "measuringUnitName": "buc",
            "currency": "RON",
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "discountType": 2,
            "discountPercentage": 10
        }
    ]
}
```

---

### Emitere proforma si trimiterea ei pe email clientului ca atasament + link catre proforma
**Method:** `Smartbill::estimates()->send(array $data)`

**Description:**

Trimiterea proformei pe email clientului se poate face in momentul facturarii sau dupa momentul facturarii.

Configurarea serverului propriu de email in contul SmartBill este necesara pentru trimiterea email-ului clientului si se poate face din [SmartBill Cloud > Configurare > Email](https://cloud.smartbill.ro/core/configurare_mailuri/).

Trimiterea facturii in momentul emiterii ei se face prin setarea parametrului **sendEmail** ca **true**.

Se poate adauga si sectiunea **email** care va contine parametri referitori la cine va primi factura.

``` json
 "sendEmail": true,
 "email": {
     "to": "test@firma.ro",
     "cc": "test1@firma.ro",
     "bcc": "test2@firma.ro"
 }

```

Atunci **cand parametrul "to" lipseste**, proforma va fi trimisa adresei de email definita in parametrul **email** din sectiunea **client**.

Atunci **cand parametrul "to" este completat**, email-ul din acest camp va primi proforma si nu cel definit in parametrul **email** din sectiunea **client**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "sendEmail": true,
    "email": {
        "cc": "",
        "bcc": ""
    },
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Emitere proforma in valuta
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

O proforma poate fi emisa in RON (comportament default) sau in valuta.

In cazul emiterii in valuta se folosesc urmatorii parametri:

``` json
    "currency": "EUR",
    "exchangeRate": 4.47,

```

Parametrul **exchangeRate** stabileste rata de curs valutar si este folosit in conversia automata a proformei.  
Daca acest parametru nu este specificat, se va prelua cursul valutar de pe BNR din ziua curenta.

Exista posibilitatea calcularii automate a unui curs valutar adaptat pentru toate proformele emise prin preluarea cursului din ziua curenta/precedenta si aplicarea unui adaos. Pentru configurarea acestui mod de lucru, [vezi detalii aici](https://api.smartbill.ro/#ConfigurareCursValutar).

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "currency": "EUR",
    "exchangeRate": 4.47,
    "products": [
        {
            "name": "Produs 1",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "EUR",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Emitere proforma pentru produse cu pretul de referinta in valuta
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

Aplicatia SmartBill permite stabilirea de preturi de referinta in valuta pentru produsele facturate, si facturarea lor in RON sau valuta.

Pentru a efectua corect aceasta operatie este necesara configurarea contului firmei prin activarea preferintei "Am preturi cu moneda de referinta in valuta" din [Preferinte generale](https://cloud.smartbill.ro/documente/preferinte-generale/). Pentru mai multe detalii [vezi aici](https://api.smartbill.ro/#ConfigurareFacturiValuta).

La facturare, produsele pot avea preturi in monede diferite (ex: 2 produse in EUR, 1 produs in RON si 1 produs in USD). In cazul in care cursul valutar nu este specifica pe produse, cursul valutar folosit va depindede optiunile alese in cadrul sectiunii "Curs valutar preluat de pe internet" in zona [Preferinte personale](https://cloud.smartbill.ro/core/configurare/preferinte-personale/).

* * *

Parametrii specifici pentru acest mod de facturare sunt:

*   **In sectiunea date de facturare**, parametrul **currency** care defineste moneda in care se emite factura
*   In sectiunea **products**:
    *   parametrul **currency** care defineste moneda in care este specificat pretul produsului
    *   parametrul **price** care defineste pretul in moneda specificata
    *   Daca se doreste folosirea unui curs valutar diferit de cel preluat de pe internet sau cel definit in contul Cloud, se foloseste parametrul **exchangeRate** care defineste cursul valutar folosit la conversia automata a facturii.

**Example Request Body:**

```json
 {
    "companyVatCode": "{{vatCode}}",
    "client": {
      "name": "{{clientName}}",
      "vatCode": "{{clientVatCode}}",
      "isTaxPayer": true,
      "address": "{{clientAddress}}",
      "city": "{{clientCity}}",
      "county": "{{clientCounty}}",
      "country": "{{clientCountry}}",
      "email": "{{clientEmail}}",
      "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "deliveryDate": "{{todayDate}}",
    "products": [
      {
        "name": "Produs 1",
        "code": "ccd1",
        "productDescription": "produs cu pretul de referinta in EUR",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "EUR",
        "exchangeRate": 4.47,
        "quantity": 2,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "isService": false
      },
      {
        "name": "Produs 2",
        "code": "ccd2",
        "productDescription": "produs cu pretul de referinta in USD",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "USD",
        "exchangeRate": 4.15,
        "quantity": 2,
        "price": 15,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "isService": false
      },
      {
        "name": "Produs 3",
        "code": "ccd3",
        "productDescription": "produs cu pretul de referinta in RON",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 5,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "isService": false
      }
    ]
  }
```

---

### Emitere estimate in limba straina
**Method:** `Smartbill::estimates()->create(array $data)`

**Description:**

O proforma este emisa in mod implicit in limba romana (RO), dar emiterea se poate face si intr-o alta limba.

Este permisa folosirea unei limbi de circulatie internationala, predefinita in SmartBill Cloud sau a unei limbi definite de administrator in contul Cloud.

Limba in care se va emite proforma poate fi aleasa prin parametrul **language** din sectiunea datelor de facturare.

Pentru a include pe document denumirea produsului si a unitatii de masura folosite in limba de emitere a proformei, traducerea se va face prin folosirea parametrilor: **translatedName** si **translatedMeasuringUnit** din sectiunea **products**.

``` json
"language": "EN",
"products": [
        {
            [...]
            "translatedName": "Product 1",
            "translatedMeasuringUnit": "piece",
        }
    ]

```

Definirea unei limbi noi se face din [Configurare > Limbi](https://cloud.smartbill.ro/core/configurare/limba/).

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{estimateSeriesName}}",
    "isDraft": false,
    "dueDate": "{{todayDate}}",
    "language": "EN",
    "products": [
        {
            "name": "Produs 1",
            "translatedName": "Product 1",
            "translatedMeasuringUnit": "piece",
            "isDiscount": false,
            "measuringUnitName": "buc",
            "currency": "RON",
            "quantity": 1,
            "price": 10,
            "isTaxIncluded": true,
            "taxName": "Normala",
            "taxPercentage": 19,
            "saveToDb": false,
            "isService": false
        }
    ]
}
```

---

### Vizualizare PDF proforma
**Method:** `Smartbill::estimates()->getPdf(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta este metoda care trebuie apelata pentru descarcarea PDF-ului proformei.

In Postman, cand se apeleaza acest webservice, trebuie selectat **Send and Download.**

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{estimateSeriesName}}
- `number`: 0023

---

### Interogare status facturare proforma
**Method:** `Smartbill::estimates()->getInvoices(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta metoda trebuie apelata cand e necesar sa se afle daca o proforma a fost sau nu facturata.

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{estimateSeriesName}}
- `number`: 0023

---

### Anulare proforma
**Method:** `Smartbill::estimates()->cancel(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta metoda poate fi apelata pentru orice proforma care a fost deja emisa si presupune anularea facturii fara stergerea ei din baza de date.

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{estimateSeriesName}}
- `number`: 0023

---

### Restaurare proforma anulata
**Method:** `Smartbill::estimates()->restore(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta metoda trebuie apelata cand se doreste restaurarea unei proforme anulate.

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{estimateSeriesName}}
- `number`: 0023

---

### Stergere proforma
**Method:** `Smartbill::estimates()->delete(string $cif, string $seriesName, string $number)`

**Description:**

Aceasta metoda trebuie apelata cand se doreste stergerea unei proforme.

**Doar ultima proforma din serie poate fi stearsa**. Pentru celelalte proforme se poate aplica procedura de anulare.

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{estimateSeriesName}}
- `number`: 0023

---

## Incasari

### Incasare cu chitanta pentru o factura deja emisa
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Aceasta metoda este folosita pentru emiterea incasarilor pentru facturile deja emise.

La emiterea incasarii cu chitanta va fi emisa automat si o chitanta.

Este necesara specificarea seriei chitantei care va fi emisa, serie deja configurata in pagina de [Configurare serii documente](https://cloud.smartbill.ro/core/configurare/serii/).

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "currency": "RON",
    "seriesName": "{{paymentSeries}}",
    "precision": 2,
    "value": 10,
    "text": "excursie in Grecia",
    "isDraft": false,
    "type": "Chitanta",
    "isCash": true,
    "useInvoiceDetails": false,
    "invoicesList": [
      {
        "seriesName": "{{invoiceSeriesName}}",
        "number": "0089"
      }
    ]
  }
```

---

### Incasare cu chitanta
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

La emiterea incasarii cu chitanta va fi emisa automat si o chitanta.

Parametrii specifici pentru emiterea de chitanta.

``` json
{  
    ...
    "seriesName": "{{paymentSeries}}",
    "text": "excursie in Grecia",
    "isDraft": false,
    "type": "Chitanta",
    "isCash": true
}

```

Parametrul **type** este obligatoriu si va avea valoarea **Chitanta**.

Parametrul **isCash** este folosit cu valoarea **true** in cazul acestei metode, indiferent de valoarea trimisa de utilizator in request.

In cazul in care se doreste, seria chitantei poate fi specifcata folosind parametrul **seriesName**. In cazul in care seria nu este specificata in apel, se va folosi seria implicita definita in pagina de [Configurare serii documente](https://cloud.smartbill.ro/core/configurare/serii/).


In cazul in care se emite o chitanta ciorna (**isDraft** este true), acesta nu va primi un numar decat in momentul in care va fi finalizata.  
Finalizarea ei poate fi facuta din contul Cloud, din pagina de [Incasari](https://cloud.smartbill.ro/raport/incasari/).  
In cazul in care se emite o chitanta finalizata, metoda va returna seria si numarul chitantei.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "seriesName": "{{paymentSeries}}",
    "precision": 2,
    "value": 14,
    "text": "excursie in Grecia",
    "isDraft": false,
    "type": "Chitanta",
    "isCash": true
}
```

---

### Incasare cu card
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Parametrul **type** este obligatoriu si va avea valoarea **Card**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "precision": 2,
    "value": 14,
    "isDraft": false,
    "type": "Card",
    "isCash": false
}
```

---

### Incasare cu cec
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Parametrul **type** este obligatoriu si va avea valoarea **Cec**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "precision": 2,
    "value": 14,
    "isDraft": false,
    "type": "Cec",
    "isCash": false
}
```

---

### Incasare cu bilet la ordin
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Parametrul **type** este obligatoriu si va avea valoarea **Bilet ordin**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "precision": 2,
    "value": 14,
    "isDraft": false,
    "type": "Bilet ordin",
    "isCash": false
}
```

---

### Incasare cu ordin de plata
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Parametrul **type** este obligatoriu si va avea valoarea **Ordin plata**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "precision": 2,
    "value": 14,
    "isDraft": false,
    "type": "Ordin plata",
    "isCash": false
}
```

---

### Incasare cu mandat postal
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Parametrul **type** este obligatoriu si va avea valoarea **Mandat postal**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "precision": 2,
    "value": 14,
    "isDraft": false,
    "type": "Mandat postal",
    "isCash": false
}
```

---

### Incasare cu alta incasare
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Parametrul **type** este obligatoriu si va avea valoarea **Alta incasare**.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "precision": 2,
    "value": 14,
    "isDraft": false,
    "type": "Alta incasare",
    "isCash": false
}
```

---

### Incasare cu datele de pe factura
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

In acest caz, o parte din parametrii necesari emiterii incasarii se preiau de pe factura incasata.

Vor fi preluate de pe factura:

*   Datele clientului;
*   Moneda in care a fost emisa factura - moneda incasarii va fi aceeasi cu moneda facturii
*   Cursul valutar folosit;
*   Limba in care a fost emisa factura -
*   Valoarea incasarii este totalul facturat.
    

In cazul in care acesti parametri sunt transmisi in apelul metodei, acestia nu vor fi luati in considerare.

Nu este posibila incasarea partiala a unei facturi.

Parametrii specifici pentru acest mod de emitere a incasarii sunt:

``` json
{
    "useInvoiceDetails": true,
    "invoicesList": {
        "seriesName": "{{invoiceSeriesName}}",
        "number": "0015"
    }
}

```

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "issueDate": "{{todayDate}}",
    "type": "Ordin plata",
    "isCash": false,
    "useInvoiceDetails": true,
    "invoicesList": {
        "seriesName": "{{invoiceSeriesName}}",
        "number": "0015"
    }
}
```

---

### Incasare cu bon fiscal cu returnarea continutului
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Pentru emiterea unui bon fiscal este necesara configurarea casei de marcat din pagina [Case de marcat](https://beta2.smartbill.ro/core/configurare/case_de_marcat/).

Metoda returneaza:

*   **id**: numar de identificare unic pentru fiecare bon emis;
*   **message**: continutul bonului codat. Acest continut trebuie inserat intr-un fisier cu numele si extensia configurate pentru casa de marcat si salvat;
*   **number**: numarul bonului fiscal.
    

In cazul in care se doreste emiterea bonului fiscal in SmartBill si tiparirea lui directa pe casa de marcat Daisy, Tremol, Partner sau Eltrade:

*   Parametrul **returnFiscalPrinterText** trebuie setat pe **true**;
*   Continutul parametrului **message** trebuie decodat din base64. Numele si extensia fisierului in care continutul decodat trebuie plasat se gaseste mai jos, impreuna cu locatia in care trebuie plasat fisierul pentru a putea fi preluat de driver-ul casei de marcat si tiparit pe casa:
    *   casa Daisy (cu driverul Fisco): bon.txt va fi plasat in folderul din driver C:\\Fisco_Daisy\\Bonuri\\
    *   casa Tremol (cu driver Server Tremol): bon.txt va fi plasat in C:\\ServerTremol\\Bonuri\\
    *   casa Partner (cu driverul Fiscal Net): bon.txt va fi plasat in C:\\FiscalNet\\Bonuri\\
    *   casa Eltrade (cu driverul Fiscal Printer): bon.bon va fi plasat in C:\\Program Files (x86)\\Eltrade\\DriverFPFile_RO\\Reciepts\\"

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "value": 10,
    "type": "Bon",
    "returnFiscalPrinterText": true,
    "observation": "obs",
    "useStock": false,
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ],
    "receivedCash": 5,
    "receivedCard": 5,
    "receivedTicheteMasa": 0,
    "receivedTicheteCadou": 0,
    "receivedOrdinDePlata": 0,
    "receivedCec": 0,
    "receivedCredit": 0,
    "receivedCupon": 0,
    "receivedPuncteDeFidelitate": 0,
    "receivedBonuriValoareFixa": 0,
    "receivedMonedaAlternativa": 0
  }

```

---

### Incasare cu bon fiscal fara returnarea continutului
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Pentru emiterea unui bon fiscal este necesara configurarea casei de marcat din pagina [Case de marcat](https://beta2.smartbill.ro/core/configurare/case_de_marcat/).

In acest caz, bonul va fi emis in SmartBill Cloud si el va fi tiparit manual pe casa de marcat. Astfel, **returnFiscalPrinterText** trebuie setat pe **false**.

Metoda returneaza:

*   **id**: numar de identificare unic pentru fiecare bon emis;
*   **number**: numarul bonului fiscal.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "value": 10,
    "type": "Bon",
    "returnFiscalPrinterText": false,
    "observation": "obs",
    "useStock": false,
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 10,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      }
    ],
    "receivedCash": 5,
    "receivedCard": 5,
    "receivedTicheteMasa": 0,
    "receivedTicheteCadou": 0,
    "receivedOrdinDePlata": 0,
    "receivedCec": 0,
    "receivedCredit": 0,
    "receivedCupon": 0,
    "receivedPuncteDeFidelitate": 0,
    "receivedBonuriValoareFixa": 0,
    "receivedMonedaAlternativa": 0
  }

```

---

### Incasare cu bon fiscal cu discount
**Method:** `Smartbill::payments()->create(array $data)`

**Description:**

Pe un bon fiscal se pot acorda discounturi.

Discountul poate fi procentual sau valoric:

*   Cand e setat paramentrul **discountPercentage** discountul va fi procentual.
*   Cand e setat paramentrul **discountValue** discountul va fi valori.
    

Acordarea unui discount valoric pe firmele platitoare de TVA:

*   Pe bonuri fiscale se afiseaza valoarea cu TVA a discountului, deci parametrul isTaxIncluded trebuie sa fie true.
*   Valoare cu TVA a discountului va fi setata pe discountValue/discountPercentage.

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "client": {
        "name": "{{clientName}}",
        "vatCode": "{{clientVatCode}}",
        "isTaxPayer": true,
        "address": "{{clientAddress}}",
        "city": "{{clientCity}}",
        "county": "{{clientCounty}}",
        "country": "{{clientCountry}}",
        "email": "{{clientEmail}}",
        "saveToDb": false
    },
    "issueDate": "{{todayDate}}",
    "value": 9,
    "type": "Bon",
    "returnFiscalPrinterText": false,
    "observation": "obs",
    "useStock": false,
    "products": [
      {
        "name": "Produs 1",
        "isDiscount": false,
        "measuringUnitName": "buc",
        "currency": "RON",
        "quantity": 1,
        "price": 355,
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "saveToDb": false,
        "isService": false
      },
      {
        "name": "Discount procentual 10%",
        "isDiscount": true,
        "numberOfItems": 1,
        "measuringUnitName": "buc",
        "currency": "RON",
        "isTaxIncluded": true,
        "taxName": "Normala",
        "taxPercentage": 19,
        "discountPercentage": 10
      }
    ],
    "receivedCash": 5,
    "receivedCard": 4,
    "receivedTicheteMasa": 0,
    "receivedTicheteCadou": 0,
    "receivedOrdinDePlata": 0,
    "receivedCec": 0,
    "receivedCredit": 0,
    "receivedCupon": 0,
    "receivedPuncteDeFidelitate": 0,
    "receivedBonuriValoareFixa": 0,
    "receivedMonedaAlternativa": 0
  }

```

---

### Returnare continut bon fiscal
**Method:** `Smartbill::payments()->getText(string $cif, string $id)`

**Description:**

Metoda permite obtinerea continutului unui bon fiscal emis, cu conditia ca ID-ul acestuia sa fie cunoscut - id-ul este returnat la emiterea bonului fiscal.

Raspunsul la apelarea metodei va contine textul bonului encodat base64. Pentru imprimare, continutul trebuie trimis la casa de marcat.

**Query Parameters:**

- `cif`: {{vatCode}}
- `id`: "1384"

---

### Stergere chitanta
**Method:** `Smartbill::payments()->deleteReceipt(string $cif, string $seriesName, string $number)`

**Description:**

Metoda permite stergerea unei incasari tip chitanta, cu precizarea ca doar ultima chitanta din serie poate fi stearsa.

**Query Parameters:**

- `cif`: {{vatCode}}
- `seriesName`: {{paymentSeries}}
- `number`: 0009

---

### Stergerea altor incasari pe baza facturii incasate
**Method:** `Smartbill::payments()->deleteByInvoice(string $cif, string $paymentType, string $invoiceSeries, string $invoiceNumber)`

**Description:**

Stergerea unei incasari pe baza facturii incasate este posibila atunci cand:

*   incasarea nu este facuta prin chitanta sau bon fiscal
*   factura in baza careia se sterge incasarea nu este stearsa sau anulata
    

Parametrul ***paymentType*** este obligatoriu si poate lua valorile: Card, CEC, Bilet ordin, Ordin plata, Mandat postal, Alta incasare.

**Query Parameters:**

- `cif`: {{vatCode}}
- `paymentType`: Ordin plata
- `invoiceSeries`: {{invoiceSeriesName}}
- `invoiceNumber`: 0046

---

### Stergerea altor incasari pe baza datelor incasarii
**Method:** `Smartbill::payments()->deleteByPayment(string $cif, string $paymentType, string $paymentDate, float $paymentValue, string $clientName, string $clientCif)`

**Description:**

Stergerea unei incasari pe baza datelor acesteia se poate face atunci cand incasarea nu este facuta prin chitanta sau bon fiscal.

Identificarea incasarii se face pe baza parametrilor paymentType, paymentDate,paymentValue, clientName sau clientCif (in functie de client persoana fizica sau firma).

Se va face stergerea primei incasari gasite in baza de date pentru clientul **clientName sau clientCif** din ziua **paymentDate**, care este de tipul **paymentType** si are valoarea **paymentValue**.

**Query Parameters:**

- `cif`: {{vatCode}}
- `paymentType`: Ordin plata
- `paymentDate`: {{todayDate}}
- `paymentValue`: 14
- `clientName`: {{clientName}}
- `clientCif`: {{clientVatCode}}

---

## Email

### Trimitere factura pe email ca atasament
**Method:** `Smartbill::invoices()->send(array $data)`

**Description:**

Metoda este folosita pentru a trimite prin e-mail, ca atasament, o factura clientului.

Pentru ca expedierea de e-mail-uri sa fie facuta cu succes, este necesara [configurarea propriului server de email](https://ajutor.smartbill.ro/article/37-email) in contul Cloud de pe care se va face trimiterea.

Parametrul **bodyText** va contine textul emailului codificat folosind varianta [Base64](https://www.ietf.org/rfc/rfc2045.txt) .

In textul mesajului SmartBill poate prelua si afisa automat informatii referitoare la document. Aceste informatii pot fi:

*   **#tip document# :** in functie de parametrul din apelarea metodei, afiseaza in email tipul documentului, mai exact "factura" sau "proforma";
*   **#serie numar document# :** afiseaza in email seria si numarul documentului;
*   **#link document# :** afiseaza in email URL-ul catre document;
*   **#data emiterii# :** afiseaza in email data emiterii documentului;
*   **#data scadentei# :** afiseaza in email data scadentei documentului
*   **#total document# :** afiseaza in email valoarea totala a documentului, in moneda in care a fost acesta emis
*   **#mentiune#**: afiseaza in email mentiunea de pe document
*   **#nume client# :** afiseaza in email numele clientului pentru care a fost emis documentul expediat;
*   **#persoana contact# :** afiseaza in email numele persoanei de contact daca ati introdus acest nume pe documentul expediat.
    

##### Un format tipic de e-mail care poate fi folosit poate fi vazut mai jos:

> Buna ziua,
> 
> Atasat va transmitem ***#tip document# #serie numar document#*** emisa in data de *#data emiterii#*, in valoare de *#total document#*.
> 
> Multumim pentru colaborare!

Acest mesaj codificat Base64 va avea forma de mai jos:

```
QnVuYSB6aXVhLCA8L2JyPjwvYnI+QXRhc2F0IHZhIHRyYW5zbWl0ZW0gPGI+PGk+I3RpcCBkb2N1bWVudCMgI3NlcmllIG51bWFyIGRvY3VtZW50IzwvaT48L2I+IGVtaXNhIGluIGRhdGEgZGUgPGk+I2RhdGEgZW1pdGVyaWkjPC9pPiwgaW4gdmFsb2FyZSBkZSA8aT4jdG90YWwgZG9jdW1lbnQjIFJPTjwvaT4uIDwvYnI+PC9icj4gTXVsdHVtaW0gcGVudHJ1IGNvbGFib3JhcmUh

```

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "seriesName": "{{invoiceSeriesName}}",
    "number": "0040",
    "type": "factura",
    "subject": "I3RpcCBkb2N1bWVudCMgI3NlcmllIG51bWFyIGRvY3VtZW50IyA=",
    "to": "{{clientEmail}}",
    "bodyText": "QnVuYSB6aXVhLCA8L2JyPjwvYnI+QXRhc2F0IHZhIHRyYW5zbWl0ZW0gPGI+PGk+I3RpcCBkb2N1bWVudCMgI3NlcmllIG51bWFyIGRvY3VtZW50IzwvaT48L2I+IGVtaXNhIGluIGRhdGEgZGUgPGk+I2RhdGEgZW1pdGVyaWkjPC9pPiwgaW4gdmFsb2FyZSBkZSA8aT4jdG90YWwgZG9jdW1lbnQjPC9pPi4gPC9icj48L2JyPiBNdWx0dW1pbSBwZW50cnUgY29sYWJvcmFyZSE="
}
```

---

### Trimitere proforma pe email ca atasament
**Method:** `Smartbill::estimates()->send(array $data)`

**Description:**

Metoda este folosita pentru a trimite prin e-mail, ca atasament, o proforma clientului.

Pentru ca expedierea de e-mail-uri sa fie facuta cu succes, este necesara [configurarea propriului server de email](https://ajutor.smartbill.ro/article/37-email) in contul Cloud de pe care se va face trimiterea.

Parametrul **bodyText** va contine textul emailului codificat folosind varianta [Base64](https://www.ietf.org/rfc/rfc2045.txt) .

In textul mesajului SmartBill poate prelua si afisa automat informatii referitoare la document. Aceste informatii pot fi:

*   **#tip document# :** in functie de parametrul din apelarea metodei, afiseaza in email tipul documentului, mai exact factura sau proforma;
*   **#serie numar document# :** afiseaza in email seria si numarul documentului;
*   **#link document# :** afiseaza in email URL-ul catre document;
*   **#data emiterii# :** afiseaza in email data emiterii documentului;
*   **#data scadentei# :** afiseaza in email data scadentei documentului
*   **#total document# :** afiseaza in email valoarea totala a documentului, in moneda in care a fost acesta emis
*   **#mentiune#**: afiseaza in email mentiunea de pe document
*   **#nume client# :** afiseaza in email numele clientului pentru care a fost emis documentul expediat;
*   **#persoana contact# :** afiseaza in email numele persoanei de contact daca ati introdus acest nume pe documentul expediat.
    

##### Un format tipic de e-mail care poate fi folosit poate fi vazut mai jos:

> Buna ziua,
> 
> Atasat va transmitem ***#tip document# #serie numar document#*** emisa in data de ***#data emiterii#***, in valoare de ***#total document#***.
> 
> Multumim pentru colaborare!

Acest mesaj codificat Base64 va avea forma de mai jos:

```
QnVuYSB6aXVhLCA8L2JyPjwvYnI+QXRhc2F0IHZhIHRyYW5zbWl0ZW0gPGI+PGk+I3RpcCBkb2N1bWVudCMgI3NlcmllIG51bWFyIGRvY3VtZW50IzwvaT48L2I+IGVtaXNhIGluIGRhdGEgZGUgPGk+I2RhdGEgZW1pdGVyaWkjPC9pPiwgaW4gdmFsb2FyZSBkZSA8aT4jdG90YWwgZG9jdW1lbnQjIFJPTjwvaT4uIDwvYnI+PC9icj4gTXVsdHVtaW0gcGVudHJ1IGNvbGFib3JhcmUh

```

**Example Request Body:**

```json
{
    "companyVatCode": "{{vatCode}}",
    "seriesName": "{{estimateSeriesName}}",
    "number": "0003",
    "type": "proforma",
    "subject": "I3RpcCBkb2N1bWVudCMgI3NlcmllIG51bWFyIGRvY3VtZW50IyA=",
    "to": "{{clientEmail}}",
    "bodyText": "QnVuYSB6aXVhLCA8L2JyPjwvYnI+QXRhc2F0IHZhIHRyYW5zbWl0ZW0gPGI+PGk+I3RpcCBkb2N1bWVudCMgI3NlcmllIG51bWFyIGRvY3VtZW50IzwvaT48L2I+IGVtaXNhIGluIGRhdGEgZGUgPGk+I2RhdGEgZW1pdGVyaWkjPC9pPiwgaW4gdmFsb2FyZSBkZSA8aT4jdG90YWwgZG9jdW1lbnQjPC9pPi4gPC9icj48L2JyPiBNdWx0dW1pbSBwZW50cnUgY29sYWJvcmFyZSE="
}
```

---
