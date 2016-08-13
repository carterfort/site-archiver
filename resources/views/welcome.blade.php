@extends('layouts.main')
@section('main')
<div class="title">Archive it.</div>

@if (count($errors) > 0)
    <div class="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="get" action="archive-url">
<p>
    <input name="url" placeholder="http://website.com" />
</p>
<p>
    <button>Go</button>
</p>
</form>
@stop