@extends('layouts.app')

@section('title')
    <title> Сообщение №{{ $report->report_id }}: {{ $report->userName }} - {{ $report->email }} </title>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="w-25 font-italic m-3 p-2">
                <h5>
                    <a href="{{ url('/') }}" >На главную</a>
                </h5>
            </div>
        </div>
        <div class="col-sm-8 offset-sm-2">
            <form action="" class="">
                {{ csrf_field() }}
                <input type="hidden" id="reportId" name="reportId" value="{{ $report->report_id }}">
                <div class="m-1">
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
                    <textarea id="text" class="alert alert-light w-100 border" rows="15" >{{ $report->text }}</textarea>
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
                <div class="row">
                    <div class="col-sm-3">
                        Тэги:
                    </div>
                    <div class="col-sm-9 text-right">
                        <a href="#" id="showAddNewTag">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            Добавить тэг
                        </a>
                    </div>
                    <div class="col-sm-12 " id="addTagInput"></div>
                    <div class="col-sm-12" id="tagsField">
                        @foreach($report->tags as $tag)
                            <span data-tag-name="{{ $tag->tagName }}" style="background-color: lightgrey; border-radius: 5px; padding:5px">
                                {{ $tag->tagName }}
                                <i class="fa fa-times" style="cursor: pointer" aria-hidden="true"></i>
                            </span>
                        @endforeach
                    </div>
                </div>
                <div id="message" class="alert alert-danger"></div>
                <div class="row p-2">
                    <div class="col-sm-6 text-center p-2">
                        <button class="btn btn-primary" id="doUpdateReport"> Сохранить </button>
                    </div>
                    <div class="col-sm-6 text-center p-2">
                        <button class="btn btn-light" id="doCancelUpdateReport"> Отменить </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection