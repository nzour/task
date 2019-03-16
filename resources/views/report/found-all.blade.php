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
            <td> <a href="/report/{{$report->id}}">Подробнее</a> </td>
            <td><i class="fa fa-trash" data-id="{{$report->id}}" aria-hidden="true"></i></td>
        </tr>
    @endforeach
    </tbody>
</table>