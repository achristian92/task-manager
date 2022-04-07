@extends('layouts.admin.app')
@section('content')

    <workplans-show
        :c_users       = "{{json_encode($assignedUsers)}}"
        :c_type_status = "{{json_encode($statuses)}}">
    </workplans-show>

    <activity-form
        :c_users      = "{{json_encode($usersAll)}}"
        :c_customers  = "{{json_encode($customers)}}"
        :c_tags       = "{{json_encode($tags)}}">
    </activity-form>


@endsection
