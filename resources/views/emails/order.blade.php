<!DOCTYPE html>
<html>
<head>
    <title>Your Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .content h1 {
            color: #333333;
        }
        .order-details {
            margin-top: 20px;
        }
        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-details th, .order-details td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        .order-details th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #777777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Order Confirmation</h1>
        </div>
        <div class="content">
            <h1>Thank you for your order, {{ $user->full_name }}!</h1>
            <p>We are excited to let you know that your order has been successfully placed. Below are the details of your order:</p>

            <div class="order-details">
                <h2>Order Details</h2>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->order_date }}</p>
                <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>

                <h3>Item(s) Ordered:</h3>
                <p><strong>Sneaker:</strong> {{ $sneaker->name }}</p>
                <p><strong>Color:</strong> {{ $sneaker_variant->color }}</p>
                <p><strong>Size:</strong> {{ $sneaker_variant->size }}</p>
                <p><strong>Brand:</strong> {{ $sneaker->brand }}</p>
                <p><strong>Quantity:</strong> {{ $order->quantity }}</p>

                <h3>Order Summary:</h3>
                <p><strong>Unit price:</strong> {{ $order->unit_price }}</p>
                <p><strong>Discount:</strong> {{ $sneaker->discount }}%</p>
                <p><strong>Total:</strong> {{ $order->quantity_price }}</p>
            </div>

            <p>If you have any questions or need further assistance, please don't hesitate to contact our customer support.</p>
            <p>Thank you for shopping with us!</p>

            <p>Best regards,<br>Godrine Mayanja <br>CEO, The SneakerRealm </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} SneakerRealm. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
