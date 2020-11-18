<html>
<head>
    <title>Protokół przyjęcia pojazdu {{$order->number}}</title>
    <style>
        body {
            font-size: 12px;
        }

        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 1px solid #000;
            padding: 15px;
            text-transform: uppercase;
        }

        table {
            border: 0;
        }

        .documentInfo {
            border: none;
            margin-top: 10px;
        }

        .documentInfo td {
            vertical-align: top;
        }

        h3 {
            border-bottom: 1px solid #000000;
            font-size: 13px;
            text-transform: uppercase;
        }

        .order-note {
            padding: 5px;
            border: 1px dashed #000000;
            margin-top: 10px;
            height: 150px;
        }

        .employee-signature {
            border-top: 1px solid #000000;
            text-align: center;
            padding: 10px;
        }

        .customer-signature {
            border-top: 1px solid #000000;
            text-align: center;
        }
    </style>
</head>
<body>
<!--mpdf
<htmlpageheader name="myheader">
<div></div>
</htmlpageheader>
<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
{PAGENO}/{nb}
</div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div class="header">Protokół przyjęcia pojazdu do warsztatu</div>
<table width="100%" class="documentInfo">
    <tr>
        <td style="width: 30%">Data przyjęcia [dz/m/r]</td>
        <td style="width: 20%">Numer zlecenia</td>
        <td style="width: 50%">Pieczątka serwisu</td>
    </tr>
    <tr>
        <td>
            <b>{{\Carbon\Carbon::parse($order->date_receipt_vehicle)->format("d/m/Y")}}</b>
            <br />
            Godzina przyjęcia <br />
            <b>{{\Carbon\Carbon::parse($order->date_receipt_vehicle)->format("H:i")}}</b>
        </td>
        <td><b>{{$order->number}}</b></td>
        <td style="height: 125px; border: 1px dashed #000"></td>
    </tr>
</table>
<h3>Dane klienta</h3>
<table style="width: 100%">
    <tr>
        <td colspan="2">
            Imię i nazwisko / Firma <br />
            <b>
                @if(empty($order->customer_company))
                    {{$order->customer_name}} {{$order->customer_surname}}
                @else
                    {{$order->customer_company}}
                @endif
            </b>
        </td>
    </tr>
    <tr>
        <td>
            Adres zamieszkania / Siedziby firmy <br />
            <b>{{$order->customer_address}} {{$order->customer_postcode}} {{$order->customer_city}}</b>
        </td>
        <td>
            Telefon kontaktowy <br />
            <b>{{$order->customer_phone}}</b>
        </td>
    </tr>
</table>
<h3>Dane samochodu</h3>
<table style="width: 100%">
    <tr>
        <td>
            Marka / Model <br />
            <b>{{$order->vehicle->mark}} {{$order->vehicle->model}}</b>
        </td>
        <td colspan="2">
            Nr nadwozia / VIN <br />
            <b>{{$order->vehicle->vin}}</b>
        </td>
    </tr>
    <tr>
        <td>
            Rok produkcji <br />
            <b>{{$order->vehicle->production_year}}</b>
        </td>
        <td>
            Nr rejestracyjny <br />
            <b>{{$order->vehicle->registration_number}}</b>
        </td>
        <td>
            Przebieg <br />
            <b>{{$order->vehicle_mileage}} km</b>
        </td>
    </tr>
</table>
<h3>Informacje dodatkowe</h3>
<div style="text-align: center">
    <img src="{{resource_path("views/pdf/img/car.jpg")}}" >
</div>
<h3>Zakres prac zleconych przez klienta</h3>
<div class="order-note">
    {{$order->note}}
</div>
<table width="100%" style="margin-top: 50px;">
    <tr>
        <td style="width: 30%" class="employee-signature">
            Czytelny podpis osoby przyjmującej
        </td>
        <td> </td>
        <td style="width: 30%" class="customer-signature">
            Czytelny podpis klienta
        </td>
    </tr>
</table>
</body>
</html>
