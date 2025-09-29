<br><br><br><br><br><br><br>
<table>
    <thead>
    <tr>
        <th></th>
        <th>CLIENTES \ MESES</th>
        @foreach($months_range as $month)
            <th>{{$month}}</th>
        @endforeach
        <th>ENCARGADO</th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td></td>
            <td>{{$customer['name']}}</td>
            @foreach($customer['hoursMonths'] as $value)
                <td>{{$value}}</td>
            @endforeach
            <td>{{ $customer['customerUserName'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
