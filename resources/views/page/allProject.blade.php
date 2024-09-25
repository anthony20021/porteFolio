

@extends('layouts.app')
@section('content')
<div id = "projects">
    <all-project :projects='{{ json_encode($projects) }}'></all-project>
</div>

@endsection
@push('js')
<script>
        $(function(){
            new Vue({
                el: '#projects',
                props : {
                    projects: {},
                }
            });
        });


</script>
@endpush
