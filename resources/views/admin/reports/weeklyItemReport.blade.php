<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .total-row {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div>
        <h4>Freshlee</h4>
        <h6 style="font-size: 14px;">{{ $header }}</h6>
        <p style="font-size: 12px;">{{ $subheader }}</p>
    </div>
    <table class="invoice-table">
        <thead>
            <tr>
                <th>Serial No.</th>
                <th>Item Name</th>
                <th>Item Qantity</th>
            </tr>
        </thead>
        <tbody>
            @php
                $serialNumber = 1;
            @endphp
            @foreach ($itemCounts as $item)
                <tr class="text-center">
                    <td>{{ $serialNumber++ }}</td>
                    <td class="text-start">{{ $item->item_name }}</td>
                    <td class="text-end">{{ $item->total_quantity }} {{ $item->item_price_in }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p style="font-size: 10px; color: rgb(63, 62, 62);">Report generated at : {{ $date }}</p>
</body>

</html>
