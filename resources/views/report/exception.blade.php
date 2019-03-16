@extends('layouts.app')

@section('title')
    <title>Вознилка непредвиденная ошибка</title>
@endsection()
@section('content')
@include('layouts.head')
<div class="row">
    <div class="col-sm-4 offset-sm-4 text-center">
        <h4>
            {{ $exception->getMessage() }}
        </h4>
    </div>
</div>
@endsection()
@include('modal.addNewReport')