@extends('layouts.admin.app')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <report-users></report-users>
        </div>
        <div class="col-md-4">
            <report-customers></report-customers>
        </div>
        <div class="col-md-4">
            <report-activities></report-activities>
        </div>
    </div>

    <report-users-plannedvsreal :p_users="{{json_encode($users)}}"></report-users-plannedvsreal>
    <report-users-totalhourscustomers :p_users="{{json_encode($users)}}"></report-users-totalhourscustomers>
    <report-users-totalhoursdays :p_users="{{json_encode($users)}}"></report-users-totalhoursdays>

    <report-customers-totalhoursdays></report-customers-totalhoursdays>
    <report-customers-listusers :p_customers="{{json_encode($customers)}}"></report-customers-listusers>
    <report-customers-history></report-customers-history>
    <report-customers-tag :p_customers="{{json_encode($customers)}}"></report-customers-tag>

    <report-activities-list></report-activities-list>
@endsection
