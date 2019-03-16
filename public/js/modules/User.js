export default class User
{
    static current = {
        name : '',
        email : '',
        url : ''
    };

    static status = {
        success : () => {
            let field = $('#status');
            field.text('Успешно').fadeIn();
            setTimeout(() => {field.text('').fadeOut()}, 3000);
        },
        none : () => {
            let field = $('#status');
            field.html('').fadeOut();

        },
        loading : () => {
            let field = $('#status');
            field.html(`<img src="/images/preloader.gif" alt="Загрузка" style="width:150px">`).fadeIn();
        }

    };

    static switchOnUpdate()
    {
        let icon = $(this).parent();
        let prev = $(this).parent().prev();
        let type = prev.data('type');
        let otherIcons = `
        <i class="fa fa-check" aria-hidden="true"></i>
        <i class="fa fa-times" aria-hidden="true"></i>
        `;
        icon.html(otherIcons);
        switch (type) {
            case 'name' :
                User.current.name = prev.text().trim();
                prev.html(`<input class="form-control" type="text" name="name" value="${User.current.name}">`);
                prev.children().focus();
                break;
            case 'email' :
                User.current.email = prev.text().trim();
                prev.html(`<input class="form-control" type="text" name="email" value="${User.current.email}">`);
                prev.children().focus();
                break;
            case 'url' :
                User.current.url = prev.text().trim();
                prev.html(`<input class="form-control" type="text" name="url" value="${User.current.url}">`);
                prev.children().focus();
                break;
            default :
                console.log("Произошла ошибка");
                break;
        }
    }

    static switchOffUpdate()
    {
        let icon = $(this).parent();
        let prev = $(this).parent().prev();
        let type = prev.data('type');
        let message = $('#message');

        let otherIcons = `
        <i class="fa fa-pencil" aria-hidden="true">
        `;
        icon.html(otherIcons);

        switch (type) {
            case 'name' :
                message.text('').fadeOut(100);
                prev.text(User.current.name);
                break;
            case 'email' :
                message.text('').fadeOut(100);
                prev.text(User.current.email);
                break;
            case 'url' :
                message.text('').fadeOut(100);
                prev.text(User.current.url);
                break;
            default :
                console.log("Произошла ошибка");
                break;
        }
    }

    static saveChanges()
    {
        let icon = $(this).parent();
        let prev = $(this).parent().prev();
        let type = prev.data('type');
        let input = prev.children();
        let value = input.val().trim();
        let message = $('#message');
        let url = `/user/update/${type}`;

        if (value.length === 0) {
            return;
        }

        let otherIcons = `
        <i class="fa fa-pencil" aria-hidden="true">
        `;

        let form = new FormData();
        let id = $('#id').val().trim();
        let token = $('#_token').val().trim();
        form.append('_token', token);
        form.append('id', id);
        form.append(type, input.val().trim());
        message.text('').fadeOut(100);

        if (type === "name") {
            if (value === User.current.name) {
                prev.text(value);
                icon.html(otherIcons);
                return;
            }
            $.ajax({
                url : `/user/get/${value}`,
                method : 'get',
                beforeSend : () => {
                    User.status.loading();
                },
                success : res => {
                    if (res !== false) {
                        User.status.none();
                        message.text('Данное имя уже существует. Попробуйте другое').fadeIn(100);
                    } else {
                        $.ajax({
                            url : url,
                            method : 'post',
                            data : form,
                            cache: false,
                            contentType : false,
                            processData : false,
                            success : res => {
                                if (res === false) {
                                    User.status.none();
                                    message('Прозошла ошибка').fadeIn(100);
                                    window.location.reload();
                                } else {
                                    icon.html(otherIcons);
                                    User.status.success();
                                    prev.text(res);
                                }
                            },
                            error : () => {
                                User.status.none();
                            }
                        })
                    }
                }
            })
        }

        if (type === "url") {
            if (value.length < 5) {
                message.text('Ссылка на профиль пользователя должна быть длиннее 5 символов').fadeIn(100);
                return;
            }
            if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                message.text('Недопустимые символы в ссылке на профиль пользователя').fadeIn(100);
                return;
            }
            if (value === User.current.url) {
                prev.text(value);
                icon.html(otherIcons);
                return;
            }
            $.ajax({
                url : `/url/exists/${value}`,
                method : 'get',
                beforeSend : () => {
                    User.status.loading();
                },
                success : res => {
                    if (res !== false) {
                        User.status.none();
                        message.text('Данный url уже существует. Попробуйте другой').fadeIn(100);
                    } else {

                        $.ajax({
                            url : url,
                            method : 'post',
                            data : form,
                            cache: false,
                            contentType : false,
                            processData : false,
                            success : res => {
                                if (res === false) {
                                    User.status.none();
                                    message('Прозошла ошибка').fadeIn(100);
                                    window.location.reload();
                                } else {
                                    icon.html(otherIcons);
                                    User.status.success();
                                    window.location.href = `/user/${res}`;
                                }
                            },
                            error : () => {
                                User.status.none();
                            }
                        })
                    }
                }
            })
        }

        if (type === "email") {
            if (!/[@]/.test(value) || /[А-яЁё\s]/.test(value)) {
                message.text('Некорректный формат Email').fadeIn(100);
                return;
            }
            if (value === User.current.email) {
                prev.text(value);
                icon.html(otherIcons);
                return;
            }
            $.ajax({
                url : url,
                method : 'post',
                data : form,
                cache: false,
                contentType : false,
                processData : false,
                beforeSend : () => {
                    User.status.loading();
                },
                success : res => {
                    if (res === false) {
                        User.status.none();
                        message('Прозошла ошибка').fadeIn(100);
                        window.location.reload();
                    } else {
                        icon.html(otherIcons);
                        User.status.success();
                        prev.text(res);
                    }
                },
                error : () => {
                    User.status.none();
                }
            })
        }

    }

    static delete()
    {
        event.preventDefault();
        let form = new FormData(document.querySelector('form#modalDeleteForm'));
        let preloader = $('#preloader');
        let message = $('#modalMessage');
        $.ajax({
            url : '/user/modify/delete',
            method : 'post',
            data : form,
            cache: false,
            contentType : false,
            processData : false,
            beforeSend : () => {
                message.text('').fadeOut(100);
                preloader.fadeIn(100);
            },
            success : res => {
                if (res === true) {
                    preloader.fadeOut(100);
                    window.location.href = "/"
                } else {
                    preloader.fadeOut(100);
                    message.text('Произошла ошибка').fadeIn(100);
                }
            },
            error : error => {
                preloader.fadeOut(100);
                message.text('Произошла ошибка. Подробнее в консоли').fadeIn(100);
            }
        })

    }
}