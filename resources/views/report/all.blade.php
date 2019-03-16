@extends('layouts.app')
@section('title')
    <title>Все сообщения</title>
@endsection
@section('content')
    @include('layouts.head')
    <div class="row m-3">
        <i id="toggleSearch" class="fa fa-chevron-right" aria-hidden="true"> Поиск</i>
    </div>
    <div class="row m-2 hidden" style="display: none;">
        <form action="{{ url('/search') }}" id="token">
            {{ csrf_field() }}
        </form>
        <div class="col-sm-3 p-1">
            <input class="form-control w-100" id="searchName" type="text" placeholder="Имя пользователя">
        </div>
        <div class="col-sm-3 p-1">
            <input class="form-control w-100" id="searchEmail" type="text" placeholder="Email">
        </div>
        <div class="col-sm-3 p-1">
            <input class="form-control w-100" id="searchDate" type="date" placeholder="Дата">
        </div>
        <div class="col-sm-3 p-1">
            <button class="btn btn-primary w-100" id="doSearch">Искать</button>
        </div>
        <div class="row w-100">
            <small class="w-100 text-right"><a class="text-muted text-decoration-none m-1 p-1" href="#" data-toggle="modal" data-target="#modalSearchTags" id="showTagsModalSearch">Искать по тегу</a></small>
        </div>
    </div>

    <div class="row">
        <div class="w-100 text-center" id="searchPreloader" style="display: none">
            <img src="{{ asset('/images/preloader.gif') }}" alt="Заугрузка" style="width:150px;">
        </div>
        <div class="w-100 text-center" id="searchMessage" style="display: none">
            <div class="alert alert-warning">

            </div>
        </div>
    </div>
    <div class="row" id="table">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Имя пользователя</th>
                    <th scope="col">Email</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Тэги</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td>
                        <a href="user/{{$report->url}}">
                            {{$report->userName}}
                        </a>
                    </td>
                    <td> {{$report->email}} </td>
                    <td> {{$report->createdAt}} </td>
                    <td>
                        @if (count($report->tags) === 0)
                            Нет тегов
                        @else
                            <div class="row">
                                @foreach( $report->tags as $tag)
                                    <a href="#" class="col-sm-4 btn btn-light" id="doSearchByTagName">
                                        {{ $tag->tagName }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td> <a href="/report/{{$report->report_id}}">Подробнее</a> </td>
                    <td><i class="fa fa-trash" data-id="{{$report->report_id}}" aria-hidden="true"></i></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@include('modal.addNewReport')
@include('modal.searchByTags')
@include('modal.loading')