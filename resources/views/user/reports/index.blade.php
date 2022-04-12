@extends('layouts.admin.app')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <report-users></report-users>
        </div>
    </div>

    <report-users-plannedvsreal :c_users="{{json_encode($users)}}"></report-users-plannedvsreal>
    <report-users-totalhourscustomers :c_users="{{json_encode($users)}}"></report-users-totalhourscustomers>
    <report-users-totalhoursdays :c_users="{{json_encode($users)}}"></report-users-totalhoursdays>
@endsection
