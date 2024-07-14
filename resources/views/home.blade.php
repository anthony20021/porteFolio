

@extends('layouts.app')
@section('content')
<div id = "home">
    <home-page :projects="{{ json_encode($projects) }}"></home-page>
</div>

@endsection
@push('js')
<script>
        $(function(){
            new Vue({
                el: '#home',
                props : {
                    projects: [],
                }
            });
        });


</script>
@endpush
