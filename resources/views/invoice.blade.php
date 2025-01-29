<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .header {
            text-align: center;
        }

        .header p {
            font-size: 14px;
            font-weight: bold;
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
    <div class="header">
        <h2>Freshlee</h2>
        <p><strong>Customer Name:</strong> {{ $customer_name }}</p>
        <p><strong>Booking ID:</strong> {{ $booking_id }}</p>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Item Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }} {{ $item['qty_unit'] }}</td>
                    <td>Rs. {{ number_format($item['price_per_kg'], 2) }} per {{ $item['qty_unit'] }}</td>
                    <td>Rs. {{ number_format($item['total_price'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">Total Amount</td>
                <td>Rs. {{ number_format($total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <h5>Total amount: <span style="text-transform: capitalize;">{{ $amountInWords }} Rupees only.</span></h5>
    <p style="font-size: 12px; color: rgb(63, 62, 62);">Thank you for shopping with Freshlee! Visit Again.</p>
    <p style="font-size: 12px; color: rgb(63, 62, 62);">Invoice Generated: {{ $date }}</p>
</body>

</html>
