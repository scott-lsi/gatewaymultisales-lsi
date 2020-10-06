<table>
    <thead>
        <tr>
            <th>orderid</th>
            <th>placed_by</th>
            <th>customer_number</th>
            <th>date</th>
            <th>qty</th>
            <th>total</th>
            <th>item</th>
        </tr>
    </thead>
    <tbody>
        @foreach($per_product as $row)
        <tr>
            <td>{{ $row['orderid'] }}</td>
            <td>{{ $row['placed_by'] }}</td>
            <td>{{ $row['customer_number'] }}</td>
            <td>{{ $row['date'] }}</td>
            <td>{{ number_format($row['qty'], 0) }}</td>
            <td>{{ number_format($row['total'], 2) }}</td>
            <td>{{ $row['item'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
