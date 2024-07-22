@extends('layouts.app')
@section('content')
<div id = "cv">
    <cv-page-user :cv='{{ json_encode($cv) }}'></cv-page-user>
</div>

@endsection
@push('js')
<script>
        $(function(){
            new Vue({
                el: '#cv',
                props : {
                    cv: {},
                }
            });
        });


</script>
@endpush
