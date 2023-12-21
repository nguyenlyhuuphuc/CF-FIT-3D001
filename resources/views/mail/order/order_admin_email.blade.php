<h2>Customer Name: {{ $order->user->name }}</h2>
<h2>Customer Phone: {{ $order->user->phone }}</h2>
<h2>Order Addres: {{ $order->address }}</h2>
<h2>Order Notes: {{ $order->notes }}</h2>

<h2>Purchase date : {{ $order->created_at->format('d-m-Y H:i:s') }}</h2>

<table border="1">
    <tr>
        <th>STT</th>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
    </tr>
    @foreach ($order->order_items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->name }}</td>    
            <td>{{ $item->price }}</td>    
            <td>{{ $item->qty }}</td>    
            <td>{{ number_format($item->price * $item->qty, 2) }}</td>    
        </tr>
    @endforeach    
    <tr>
        <td colspan="4">Total : {{ $order->total }}</td>
    </tr>
</table>