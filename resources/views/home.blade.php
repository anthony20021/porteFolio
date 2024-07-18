

@extends('layouts.app')
@section('content')
<div id = "home">
    <home-page :projects="{{ json_encode($projects) }}" :codes="{{ json_encode($codes) }}" :experiences="{{ json_encode($experiences) }}" :cv="{{ json_encode($cv) }}"></home-page>
</div>

@endsection
@push('js')
<script>
        $(function(){
            new Vue({
                el: '#home',
                props : {
                    projects: [],
                    codes: [],
                    experiences: [],
                    cv: [],
                }
            });
        });


</script>
@endpush
