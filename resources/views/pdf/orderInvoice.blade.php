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

        table.items {
            border: 0.1mm solid #000000;
        }

        td {
            vertical-align: top;
        }
        .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        table thead td { background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
        }
        .items td.blanktotal {
            background-color: #EEEEEE;
            border: 0.1mm solid #000000;
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
        }
        .items td.cost {
            text-align: "." center;
        }
    </style>
</head>
<body>
<!--mpdf
<htmlpageheader name="myheader">
<table width="100%"><tr>
<td width="50%" style="color:#0000BB; "><span style="font-weight: bold; font-size: 14pt;">Acme Trading Co.</span><br />123 Anystreet<br />Your City<br />GD12 4LP<br /><span style="font-family:dejavusanscondensed;">&#9742;</span> 01777 123 567</td>
<td width="50%" style="text-align: right;">Invoice No.<br /><span style="font-weight: bold; font-size: 12pt;">0012345</span></td>
</tr></table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
Page {PAGENO} of {nb}
</div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div style="text-align: right">Data: {{ $date }}</div>
<table width="100%" style="font-family: serif;" cellpadding="10"><tr>
    <td width="45%" style="border: 0.1mm solid #888888; ">345 Anotherstreet<br />Little Village<br />Their City<br />CB22 6SO</td>
    <td width="55%">&nbsp;</td>
</tr></table>
<br />
<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
        <td width="15%">Lp</td>
        <td width="45%">Nazwa</td>
        <td width="10%">Cena</td>
        <td width="15%">Ilość</td>
        <td width="15%">Wartość</td>
    </tr>
    </thead>
    <tbody>

    @foreach($positions as $position)
        <tr>
            <td align="center">{{$loop->index+1}}</td>
            <td>{{$position->name}}</td>
            <td align="center" class="cost">{{$position->price}}</td>
            <td align="center">{{$position->quantity}}</td>
            <td class="cost">{{$position->price * $position->quantity}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
