@extends('layouts.app')

@section('title')
    <title> Сообщение №{{ $report->report_id }}: {{ $report->userName }} - {{ $report->email }} </title>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="w-25 font-italic m-3 p-2 ">
                <h5>
                    <a href="{{ url('/') }}" class="text-decoration-none">На главную</a>
                </h5>
            </div>
        </div>
        <div class="col-sm-8 offset-sm-2">
            <div class="m-1">
                <input type="hidden" id="reportId" name="reportId" value="{{ $report->report_id }}">
                <b>Имя пользователя</b>:
                <a href="/user/{{ $report->url }}">{{ $report->userName }}</a>
            </div>
            <div class="m-1">
                <b>Email - адрес</b>:
                {{ $report->email }}
            </div>
            <div class="m-1">
                <b>Сообщение</b>:
            </div>
            <div class="m-1">
                <div class="alert alert-secondary">
                    {{ $report->text }}
                </div>
            </div>

            <div>
                <small class="m-1 text-muted font-italic">
                    <b>Опубликовано</b>:
                    {{ $report->createdAt }}
                </small>
            </div>
            @if(!empty($report->updatedAt))
                <div>
                    <small class="m-1 text-muted font-italic">
                        <b>Последнее изменение</b>:
                        {{ $report->updatedAt}}
                    </small>
                </div>
            @endif
            @if (count($report->tags) > 0)
                <div>
                    <b>Тэги:</b>
                </div>
                <div class="m-1">
                    @foreach($report->tags as $tag)
                        <span class="alert-secondary p-1 m-1" style="border-radius: 3px"> {{ $tag->tagName }} </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="row p-2">
        <div class="col-sm-6 text-center p-2">
            <button class="btn btn-info" id="switchOnUpdateReport"> Изменить </button>
        </div>
        <div class="col-sm-6 text-center p-2">
            <button class="btn btn-warning" data-toggle="modal" data-target="#myModal"> Удалить </button>
        </div>
    </div>
    @include('modal.deleteReport')

@endsection()