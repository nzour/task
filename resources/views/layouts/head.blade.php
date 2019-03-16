<div class="row">
    <div class="col-sm-8">
        <div class="w-25 font-italic m-3 p-2 ">
            <h5>
                <a href="{{ url('/') }}" class="text-decoration-none">На главную</a>
            </h5>
        </div>
    </div>
    <div class="col-sm-2 text-center">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle w-100 m-3" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Заполнить
            </button>
            <div class="dropdown-menu w-100 text-center" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ url('/fake/create/1') }}">Добавить 1</a>
                <a class="dropdown-item" href="{{ url('/fake/create/5') }}">Добавить 5</a>
                <a class="dropdown-item" href="{{ url('/fake/create/10') }}">Добавить 10</a>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <button class="btn btn-primary w-100 m-3 p-2" data-toggle="modal" data-target="#myModal">Добавить</button>
    </div>
</div>