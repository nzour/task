@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-10">
            <div class="w-25 font-italic m-3 p-2 ">
                <h5>
                    <a href="{{ url('/') }}" class="text-decoration-none">На главную</a>
                </h5>
            </div>
        </div>
        <div class="col-sm-8 offset-sm-2 border">
            <div class="row">
                <div class="col-sm-12 alert alert-danger" id="message"></div>
                <div class="col-sm-12 alert alert-success text-center" id="status">Успешно!</div>
            </div>
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" id="id" value="{{ $user->id }}">
            <div class="row border-bottom">
                <div class="col-sm-6 p-1 text-center">
                    <b>Имя пользователя</b>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-10 p-1" data-type="name">
                            {{ $user->name }}
                        </div>
                        <div class="col-sm-2 p-2 text-center">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-sm-6 p-1 text-center">
                    <b>Email</b>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-10 p-1" data-type="email">
                            {{ $user->email }}
                        </div>
                        <div class="col-sm-2 p-2 text-center">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-sm-6 p-1 text-center">
                    <b>Ссылка на профиль</b>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-10 p-1" data-type="url">
                            {{ $user->url }}
                        </div>
                        <div class="col-sm-2 p-2 text-center">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8 offset-2 text-right" style="font-size:2em; margin-top:30px;">
            <i class="fa fa-trash-o" data-toggle="modal" data-target="#deleteUserModal"></i>
        </div>
    </div>
@endsection
@include('modal.deleteUser')