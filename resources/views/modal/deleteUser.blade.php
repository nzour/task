<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="preloader">
                <img src="{{ URL::asset('images/preloader.gif') }}" alt="Загрузка...">
            </div>
            <form id="modalDeleteForm" class="text-center" action="{{ url('/') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="userId" value="{{ $user->id}}">
                <h4> Вы действительно хотите удалить пользователя <b>{{$user->name}}</b>? </h4>
                <div class="text-muted"> Удалятся все добавленные этим пользователем записи </div>
                <div id="modalMessage" class="alert alert-danger" style="display: none"></div>
                <div>
                    <button class="btn btn-danger m-1" id="doDeleteUserModal">Удалить</button>
                    <button class="btn btn-light m-1" data-dismiss="modal">Отмена</button>
                </div>
            </form>
        </div>
    </div>
</div>