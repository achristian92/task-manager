@extends('emails.layouts.app')
@section('content')
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <tr>
            <td width="100">&nbsp;</td>
            <td width="400" align="center">
                <div>
                    <div align='center' >
                        <p>¡Hola <strong style="color: #1ab394">{{ $user->name }}!</strong>,
                            Ayer <b>{{ $yesterday }}</b> quedaron algunas actividades pendiente de completar de los siguientes usuarios:</p>
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
            <th scope="col">Usuario</th>
            <th scope="col">Planificado</th>
            <th scope="col">Completados</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $user)
            <tr>
                <td>{{$user['name']}}</td>
                <td align="center">{{$user['totalAct']}}</td>
                <td align="center">{{$user['qtyCompleted']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop


