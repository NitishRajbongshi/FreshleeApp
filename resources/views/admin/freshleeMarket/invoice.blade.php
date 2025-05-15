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

        .invoice-table,
        .cust_table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th,
        .invoice-table td,
        .cust_table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .cust_table td {
            border: 1px solid white;
            padding: 0;
        }

        .total-row {
            font-weight: bold;
        }

        .bottom_table {
            border: 0px;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 10px;">
        <img src="{{ $logo }}" style="width: 150px; height: auto;">
    </div>

    <table class="cust_table" style="margin-bottom: 5px;">
        <tbody>
            <tr>
                <td style="text-transform: uppercase;"><strong>{{ $customer_name }}</strong></td>
                <td>Booking ID: <strong>{{ $booking_id }}</strong></td>
            </tr>
            <tr>
                <td>Phone : +91 {{ $customer_phone }}</td>
                <td>Date: {{ $date }}</td>
            </tr>
        </tbody>
    </table>
    <p style="font-size: 12px;">Delivery Address: Vidhi Analytica, House No. 15, Mother Teressa Road, Zoo Narengi Road,
        Guwahati, 781021</p>
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
                    <td>Rs. {{ number_format($item['price_per_kg'], 2) }} per {{ $item['price_unit'] }}</td>
                    <td>Rs. {{ number_format($item['total_price'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">Delivery Charge</td>
                <td>Rs. {{ number_format($deliveryCharge, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3">Bag Charge</td>
                <td>Rs. {{ number_format($bagCharge, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3">Total Amount</td>
                <td>Rs. {{ number_format($total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="bottom_table" style="width: 100%;">
        <tr>
            <td style="width: 90%;">
                <h5>Total amount: Rupees <span style="text-transform: capitalize;">{{ $amountInWords }} only.</span>
                </h5>
                <lable style="font-size: 13px; color: rgb(63, 62, 62);">For the farmers and with the farmers</lable><br>
                <label style="font-size: 12px; color: rgb(63, 62, 62);">With the commitment to sustainable agriculture-
                    Team
                    Vidhi</label></br>
                <label style="font-size: 12px; color: rgb(63, 62, 62);">Invoice Generated: {{ $date }}</label>
            </td>
            <td style="width: 10%;">
                <img src="{{ $qr_code }}" style="width: 100px; height: auto;">
            </td>
        </tr>

    </table>
    <div class="row col-12">
        <p
            style="font-size: 12px; color: rgb(63, 62, 62); border-top: 1px solid gray; padding-top: 4px; text-align: center;">
            An initiative of Vidhi Agrotech Private Limited with the support from Vidhi Analytica LLP and EEL-NER
            under
            ICAR, Govt. of India.
        </p>
    </div>

</body>

</html>