@extends('layouts.admin.app')
@section('content')
    <workplans-user-show
        :c_customers   = "{{json_encode($customers)}}"
        :c_type_status = "{{json_encode($status)}}">
    </workplans-user-show>


    <activity-form
        :c_customers  = "{{json_encode($customers)}}"
        :c_tags       = "{{json_encode($tags)}}">
    </activity-form>

{{--    <import-work-plan-component></import-work-plan-component>--}}
{{--    <duplicate-work-plan-component></duplicate-work-plan-component>--}}
{{--    <mass-destroy-work-plan-component></mass-destroy-work-plan-component>--}}
@endsection
