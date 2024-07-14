@extends('layouts.app')

@section('content')
    <div id = "dashboard">
        <dashboard-page :codes="{{ json_encode($codes) }}" :experiences="{{ json_encode($experiences) }}" :projects="{{ json_encode($projects) }}" :cv="{{ json_encode($cv) }}"></dashboard-page>
    </div>
@endsection

@push('js')
<script>
        $(function(){
            new Vue({
                el: '#dashboard',
                props : {
                    projects: [],
                    experiences: [],
                    codes:[],
                    cv: [],
                }
            });
        });


</script>
@endpush