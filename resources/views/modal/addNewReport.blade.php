<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="alert alert-danger" id="message"></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="preloader">
                <img src="{{ URL::asset('images/preloader.gif') }}" alt="Загрузка...">
            </div>
            <form action="{{ url('/modify/add') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="userName">Введите имя</label>
                    <input type="text" class="form-control" id="userName" name="userName" >
                    <small class="form-text text-muted">Обязательное поле.</small>
                </div>
                <div class="form-group">
                    <label for="email">Введите почту</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <small class="form-text text-muted">Обязательное поле</small>
                </div>
                <div class="form-group">
                    <label for="url">Введите ссылку на пользователя</label>
                    <input type="text" class="form-control" id="url" name="url">
                    <small class="form-text text-muted">Необязательное поле. Ссылка должна быть длиннее 5 символов и содержать только латинские буквы и цифры</small>
                </div>
                <div class="form-group">
                    <label for="text">Введите сообщение</label>
                    <textarea  class="form-control" id="text" name="text" rows="5"></textarea>
                    <small class="form-text text-muted">Обязательное поле</small>
                </div>
                <div class="g-recaptcha" data-sitekey="{{ env("CAPTCHA_SITE_KEY") }}"></div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3">
                            Тэги:
                        </div>
                        <div class="col-sm-8 text-right">
                            <a href="#" id="showAddNewTag">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Добавить тэг
                            </a>
                        </div>
                        <div class="col-sm-12 " id="addTagInput"></div>
                        <div class="col-sm-12" id="tagsField"></div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <div class="w-100 text-center">
                    <button type="button" class="btn btn-light w-25" data-dismiss="modal">Отменить</button>
                    <button id="doAddReport" type="button" class="btn btn-primary w-25">Добавить</button>
                </div>
            </div>
        </div>
    </div>
</div>