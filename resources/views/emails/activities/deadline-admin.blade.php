@extends('emails.layouts.app')
@section('content')
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <tr>
            <td width="100">&nbsp;</td>
            <td width="400" align="center">
                <div>
                    <div align='center' >
                        <p>¡Hola <strong style="color: #1ab394">{{ $user->name }}!</strong>,
                            mañana <b>{{$tomorrow}}</b>
                            tienen actividades con fecha límite los siguientes usuarios:</p>
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
            <th scope="col"># Actividades</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $info)
            <tr>
                <td>{{$info['userName']}}</td>
                <td align="center">{{$info['totalAct']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop


