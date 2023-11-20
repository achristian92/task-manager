@extends('emails.layouts.app')
@section('content')
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <tr>
            <td width="100">&nbsp;</td>
            <td width="400" align="center">
                <div>
                    <div align='center' >
                        <p>¡Hola <strong style="color: #1ab394">{{ $user->name }}!</strong>,
                            Los siguientes clientes superaron el límite de horas del mes <strong>{{ $month }}</strong>:</p>
                    </div>
                </div>
            </td>
            <td width="100">&nbsp;</td>
        </tr>
    </table>
    <br>
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <thead>
        <tr>
            <th scope="col">Cliente</th>
            <th scope="col">Limite</th>
            <th scope="col">Total Tiempo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $customer)
            <tr>
                <td>{{$customer['name']}}</td>
                <td align="center">{{$customer['limit']}}</td>
                <td align="center">{{$customer['time']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop


