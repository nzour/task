let notValid = false;

export default class Report
{
    static addReport(event)
    {
        event.preventDefault();
        let message = $('#message');
        let preloader = $('#preloader');
        Report.doValidated();
        if (notValid) {
            return;
        }
        let captcha = grecaptcha.getResponse();
        if (!captcha.length) {
            message.text('Необходимо пройти каптчу').fadeIn(100);
            return;
        }

        let form = new FormData(document.querySelector("form"));

        form.set('userName', form.get('userName').trim());
        form.set('email', $('#email').val().trim());
        form.set('url', $('#url').val().trim());
        form.set('text', form.get('text').trim());
        form.append('captcha', captcha);

        // Получение тегов
        let tags = $('#tagsField').children('span');
        let ajaxTags = [];
        for (let i = 0; i < tags.length; i++) {
            let tag = $(tags[i]).data('tag-name');
            ajaxTags.push(tag);
        }
        if (ajaxTags.length !== 0) {
            form.append('tags', JSON.stringify(ajaxTags));
        }
        if (form.get('url').length !== 0) {
            $.ajax({
                url: `/url/exists/${form.get('url')}`,
                method: 'get',
                success: res => {
                    if (res !== false && $('#url').attr('disabled') === undefined) {
                        message.text('Данный url уже занят').fadeIn(100);
                    } else {
                        message.text('').fadeOut(100);
                        $.ajax({
                            url: '/modify/add',
                            method: 'post',
                            data: form,
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: () => {
                                preloader.fadeIn(300);
                            },
                            success: res => {
                                preloader.fadeOut(300);
                                console.log(res);
                                if (res === true) {
                                    window.location.href = "/";
                                } else {
                                    if (res === "CAPTCHA_ERROR") {
                                        message.text('Ошибка с каптчей. Убедитесь, что вы её прошли');
                                        return;
                                    }
                                    message.text("Возникла ошибка. Подробнее в консоль").fadeIn(200);
                                }
                            },
                            error: error => {
                                preloader.fadeIn(300);
                                alert("Возникла непредвиденная ошибка. Смотреть в консоль");
                                console.log(error);
                            }
                        });
                    }
                }
            });
        } else {
            message.text('').fadeOut(100);
            $.ajax({
                url: '/modify/add',
                method: 'post',
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: () => {
                    preloader.fadeIn(300);
                },
                success: res => {
                    preloader.fadeOut(300);
                    console.log(res);
                    if (res === true) {
                        window.location.href = "/";
                    } else {
                        if (res === "CAPTCHA_ERROR") {
                            message.text('Ошибка с каптчей. Убедитесь, что вы её прошли');
                            return;
                        }
                        message.text("Возникла ошибка. Подробнее в консоль").fadeIn(200);
                    }
                },
                error: error => {
                    preloader.fadeIn(300);
                    alert("Возникла непредвиденная ошибка. Смотреть в консоль");
                    console.log(error);
                }
            });
        }

    }

    static updateReport(event)
    {
        event.preventDefault();

        let message = $('#message');
        let form = new FormData(document.querySelector('form'));
        let text = $('#text').val().trim();
        if (text.length === 0) {
            message.text('Пожалуйста, заполните поле сообщения').fadeIn(100);
            return;
        }
        message.fadeOut(100);
        form.set('text', text);
        // Получение тегов
        let tags = $('#tagsField').children('span');
        let ajaxTags = [];
        for (let i = 0; i < tags.length; i++) {
            let tag = $(tags[i]).data('tag-name');
            ajaxTags.push(tag);
        }
        if (ajaxTags.length !== 0) {
            form.append('tags', JSON.stringify(ajaxTags));
        }

        $.ajax({
            url : '/modify/update',
            method : 'post',
            data : form,
            cache: false,
            contentType : false,
            processData : false,
            success : (res) => {
                if (res === true) {
                    Report.switchOffUpdate(event);
                } else {
                    message.text("Возникла ошибка").fadeIn(100);
                }
            },
            error : (error) => {
                message.text(error).fadeIn(100);
            }
        })
    }

    static switchOnUpdate(event)
    {
        event.preventDefault();

        let reportId = $('#reportId').val().trim();
        $.ajax({
            url : `/report/${reportId}/change`,
            method : 'get',
            success : res => {
                $('body').html(res);
            }
        })
    }

    static switchOffUpdate(event)
    {
        event.preventDefault();

        let reportId = $('#reportId').val().trim();
        $.ajax({
            url : `/report/${reportId}`,
            method : 'get',
            success : res => {
                $('body').html(res);
            }
        })
    }

    static deleteReport(event)
    {
        event.preventDefault();
        let form = new FormData(document.querySelector('form#modalDeleteForm'));
        let message = $('#message');
        message.text('').fadeOut(100);
        $.ajax({
            url : '/modify/delete',
            method: 'post',
            data : form,
            cache: false,
            contentType : false,
            processData : false,
            success : res => {
                if (res === true) {
                    window.location.href = '/';
                } else {
                    message.text('Возникла ошибка').fadeIn(100);
                }
            },
            error : (error) => {
                message.text('Возникла ошибка. Подробнее в консоли').fadeIn(100);
                console.log(error);
            }
        })
    }

    static deleteReportFromMainPage(event)
    {
        event.preventDefault();
        let token = $('input[name=_token]').val().trim();
        let id = $(this).data('id');
        let form = new FormData();
        form.append('reportId', id);
        form.append('_token', token);
        $.ajax({
            url : '/modify/delete',
            method: 'post',
            data : form,
            cache: false,
            contentType : false,
            processData : false,
            beforeSend: () => {
                $('#modalLoading').modal('show');
            },
            success : res => {
                if (res === true) {
                    $('#modalLoading').modal('hide');
                    $.ajax({
                        url : "/",
                        method : "get",
                        cache: false,
                        contentType : false,
                        processData : false,
                        success : res => {
                            $('body').html(res);
                        },
                        error : () => {
                            window.location.reload();
                        }
                    });
                } else {
                    alert("Возникла ошибка. Возможно данной записи уже не существует. Рекомендуем обновить страницу");
                    $('#modalLoading').modal('hide');
                }
            },
            error : (error) => {
                alert("Возникла ошибка. Подробнее в консоли");
                $('#modalLoading').modal('hide');
                console.log(error);
            }
        })
    }

    static doValidated()
    {
        let message = $('#message');
        let userName = $('#userName').val().trim();
        let email = $('#email').val().trim();
        let url = $('#url').val().trim();
        let text = $('#text').val().trim();

        if (userName.length === 0) {
            message.text('Пожалуйста, заполните имя').fadeIn(100);
            notValid = true;
            return;
        }

        if (email.length === 0) {
            message.text('Пожалуйста, заполните Email').fadeIn(100);
            notValid = true;
            return;
        }

        if (!/[@]/.test(email) || /[А-яЁё\s]/.test(email)) {
            message.text('Некорректный формат Email').fadeIn(100);
            notValid = true;
            return;
        }
        if (text.length === 0) {
            message.text('Пожалуйста, заполните поле с сообщением').fadeIn(100);
            notValid = true;
            return;
        }

        if (url.length !== 0) {
            if (!/^[a-zA-Z0-9_]+$/.test(url)) {
                message.text('Недопустимые символы в ссылке на профиль пользователя').fadeIn(100);
                notValid = true;
                return;
            }
            if (url.length < 5) {
                message.text('Ссылка на профиль пользователя должна быть длиннее 5 символов').fadeIn(100);
                notValid = true;
                return;
            }
        } else {
            message.text('').fadeOut(100);
            notValid = false
        }
    }

    static isUserExists()
    {
        let userName = $('#userName').val().trim();
        let email = $('#email');
        let url = $('#url');
        if (userName.length === 0) {
            return;
        }
        $.ajax({
            url : `/user/get/${userName}`,
            method : 'get',
            success : res => {
                if (res !== false) {
                    email.attr('disabled', ' ').val(res.email);
                    url.attr('disabled', ' ').val(res.url);
                } else {
                    email.removeAttr('disabled').val('');
                    url.removeAttr('disabled').val('');
                }
            }
        })
    }

}