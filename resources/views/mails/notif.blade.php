@extends('mails.layout.mail')

@section('content')
    <main>
        <p>Bonjour, vous avez reçu un nouveau mail de {{ $name }} {{ $firstname }}</p>
        <br>
        <p>{{ $sujet }}</p>
        <p>{{ $themessage }}</p>

        <p>En cas de besoin, vous pouvez contacter {{ $name }} {{ $firstname }} via cet email : {{ $email }} ou via le <a href="https://anthonygonzalez.website">site</a></p>
    </main>
@endsection
