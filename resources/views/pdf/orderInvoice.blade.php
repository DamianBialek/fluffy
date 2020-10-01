<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        p {
            margin: 0pt;
        }

        table {
            border: 0.1mm solid #000000;
        }

        td, th {
            vertical-align: top;
        }
        table td, table th {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        table thead td { background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
        }
        table .blanktotal {
            background-color: #EEEEEE;
            border: 0.1mm solid #000000;
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        table td.totals, table th.totals {
            text-align: right;
        }
        table td.cost, table th {
            text-align: "." center;
        }
    </style>
    <title>Faktura VAT nr {{$order->invoice_number}}</title>
</head>
<body>
<!--mpdf
<htmlpageheader name="myheader">
<table width="100%" style="border: 0"><tr>
<td width="50%" style="color:#0000BB; border: 0"></td>
<td width="50%" style="text-align: right; border: 0"><span style="font-weight: bold; font-size: 12pt;">Faktura VAT nr {{$order->invoice_number}}</span><br />dla <b>{{ $order->number  }}</b></td>
</tr></table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
{PAGENO}/{nb}
</div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div style="text-align: right">Data: {{ \Carbon\Carbon::parse($order->invoice_date)->format("d-m-Y") }}</div>
<table width="100%" style="font-family: serif; border: 0" cellpadding="10"><tr>
    <td width="45%" style="border: 0.1mm solid #888888; ">{{$order->customer_name}} {{$order->customer_surname}} <br /> {{$order->customer_address}} <br /> {{$order->customer_postcode}} {{$order->customer_city}}</td>
    <td width="55%" style="border: 0">&nbsp;</td>
</tr></table>
<br />
<table width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
        <td width="15%">Marka</td>
        <td width="35%">Model</td>
        <td width="20%">Vin</td>
        <td width="15%">Numer rejestracyjny</td>
        <td width="15%">Przebieg</td>
    </tr>
    </thead>
    <tbody>

    <tr>
        <td align="center">{{$order->vehicle->mark}}</td>
        <td>{{$order->vehicle->model}}</td>
        <td align="center" class="cost">{{$order->vehicle->vin}}</td>
        <td align="center">{{$order->vehicle->registration_number}}</td>
        <td align="center">{{$order->vehicle_mileage}}</td>
    </tr>
    </tbody>
</table>
<h4>Części</h4>
<table width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
        <td width="5%">Lp</td>
        <td width="30%">Nazwa</td>
        <td width="15%">Cena brutto</td>
        <td width="5%">VAT<br />[%]</td>
        <td width="5%">Ilość</td>
        <td width="15%">Wartość netto</td>
        <td width="15%">Kwota vat</td>
        <td width="15%">Wartość brutto</td>
    </tr>
    </thead>
    <tbody>
    @if($parts->isNotEmpty())
        @foreach($parts as $position)
            <tr>
                <td align="center">{{$loop->index+1}}</td>
                <td>{{$position->name}}</td>
                <td align="right" class="cost">{{$position->price}}</td>
                <td align="center">23</td>
                <td align="center">{{$position->quantity}}</td>
                <td class="cost totals">{{$position->price * $position->quantity}}</td>
                <td class="cost totals">{{$position->price * $position->quantity}}</td>
                <td class="cost totals">{{$position->price * $position->quantity}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td style="text-align: center" colspan="8">Brak</td>
        </tr>
    @endif
    </tbody>
</table>
<h4>Robocizna</h4>
<table width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
        <td width="5%">Lp</td>
        <td width="30%">Nazwa</td>
        <td width="15%">Cena brutto</td>
        <td width="5%">VAT<br />[%]</td>
        <td width="5%">Ilość</td>
        <td width="15%">Wartość netto</td>
        <td width="15%">Kwota vat</td>
        <td width="15%">Wartość brutto</td>
    </tr>
    </thead>
    <tbody>
    @if($services->isNotEmpty())
        @foreach($services as $position)
            <tr>
                <td align="center">{{$loop->index+1}}</td>
                <td>{{$position->name}}</td>
                <td align="right" class="cost">{{$position->price}}</td>
                <td align="center">23</td>
                <td align="center">{{$position->quantity}}</td>
                <td class="cost totals">{{$position->price * $position->quantity}}</td>
                <td class="cost totals">{{$position->price * $position->quantity}}</td>
                <td class="cost totals">{{$position->price * $position->quantity}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td style="text-align: center" colspan="8">Brak</td>
        </tr>
    @endif
    </tbody>
</table>
<br />
<br />
<table style="font-size: 9pt; border-collapse: collapse; margin-left: auto" cellpadding="8">
    <thead>
    <tr>
        <td>Nazwa</td>
        <td>Wartość brutto</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Części</td>
        <td class="cost totals">{{$order->getPartsTotalSum()}}</td>
    </tr>
    <tr>
        <td>Robocizna</td>
        <td class="cost totals">{{$order->getServicesTotalSum()}}</td>
    </tr>
    <tr style="border: 0.1mm solid #000000;">
        <th style="font-size: 12pt;">Razem do zapłaty</th>
        <th class="cost totals" style="font-size: 12pt;">{{$order->getTotalSum()}}</th>
    </tr>
    </tbody>
</table>
</body>
</html>
