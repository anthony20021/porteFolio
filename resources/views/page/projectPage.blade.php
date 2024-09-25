@extends('layouts.app')
@section('content')
<div id = "project">
    <project-page-user :project='{{ json_encode($project) }}'></project-page-user>
</div>

@endsection
@push('js')
<script>
        $(function(){
            new Vue({
                el: '#project',
                props : {
                    project: {},
                }
            });
        });


</script>
@endpush
