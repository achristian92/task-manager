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

    <workplans-user-import></workplans-user-import>
    <workplans-user-mass-delete></workplans-user-mass-delete>
    <workplans-user-duplicate></workplans-user-duplicate>
@endsection
