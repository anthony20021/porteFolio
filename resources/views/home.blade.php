

@extends('layouts.app')
@section('content')
<div id = "home">
    <home-page :projects="{{ json_encode($projects) }}" :codes="{{ json_encode($codes) }}"></home-page>
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
                }
            });
        });


</script>
@endpush
