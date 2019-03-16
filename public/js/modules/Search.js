export default class Search
{
    static tagsPreloader = {
        show : () => {
            $('#tagsPreloader').show();
        },
        hide : () => {
            $('#tagsPreloader').hide();
        }
    }

    static preloader = {
        show : () => {
            $('#searchPreloader').show();
        },
        hide : () => {
            $('#searchPreloader').hide();
        }
    };

    static optionTags(tags) {
        let html = "<select class='custom-select'>";
        html += "<option selected value='none'>Выберите тег</option>";
        tags.forEach(tag => {
            html += `<option value='${tag.tag_id}'>${tag.tag_title}</option>`;
        });
        html += "</select>";
        return html;
    };

    static toggleSearchField()
    {
        let field = $(this).parent().next('div :first');
        if (field.hasClass('hidden')) {
            $(this).removeClass('fa-chevron-right').addClass('fa-chevron-down');
            field.slideDown().removeClass('hidden');
        } else {
            $(this).removeClass('fa-chevron-down').addClass('fa-chevron-right');
            field.slideUp().addClass('hidden');
        }
    }

    static doSearch()
    {
        let name = $('#searchName').val().trim();
        let email = $('#searchEmail').val().trim();
        let date = $('#searchDate').val().trim();
        let message = $('#searchMessage');
        let table = $('#table');
        let form = new FormData(document.querySelector('form#token'));
        if (name.length === 0 && email.length === 0 && date.length === 0) {
            return;
        }
        if (name.length !== 0) {
            form.append('name', name);
        }
        if (email.length !== 0) {
            form.append('email', email);
        }
        if (date.length === 10) {
            form.append('createdAt', date);
        }
        $.ajax({
            url : '/search',
            method : 'post',
            data : form,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : () => {
                $(message).fadeIn(200).children().first().text(``);
                Search.preloader.show();
                $(message).hide();
            },
            success : (res) => {
                Search.preloader.hide();
                if (res === "false") {
                    $(message).fadeIn(200).children().first().text(`Ничего не найдено`);
                    table.html('');
                    return;
                }
                table.html(res);
            },
            error : (error) => {
                Search.preloader.hide();
                $(message).fadeIn(200).children().first().text(`Произошла ошибка. Подробнее в консоли`);
                console.log(error);
            }
        })
    }

    static openModalSearchTags()
    {
        let content = $('#tagsModalContent');
        $.ajax({
            url : '/tags/all',
            method : 'get',
            beforeSend : () => {
                Search.tagsPreloader.show();
            },
            success : res => {
                Search.tagsPreloader.hide();
                if (res === false) {
                    content.html("Ничего не найдено").fadeIn(100);
                    return;
                }
                content.html(Search.optionTags(res)).fadeIn(100);
            },
            error : error => {
                Search.tagsPreloader.hide();
                content.html('Возникла ошибка. Подробнее в консоли').fadeIn(100);
                console.log(error);
            }
        })
    }

    static doSearchTags()
    {
        let field = $('#table');
        let content = $('#tagsModalContent');
        content.children('div').remove();
        let select = content.children('select');
        if (!select) {
            return;
        }
        let value = select.val().trim();
        if (value === "none") {
            return;
        }
        $.ajax({
            url : `/search/${value}`,
            method : 'get',
            beforeSend : () => {
                Search.tagsPreloader.show();
                content.hide();
            },
            success : res => {
                if (res === "false") {
                    Search.tagsPreloader.hide();
                    content.append(`<div class="alert alert-warning">Ничего не найдено</div>`).fadeIn(200);
                    return;
                }
                field.html(res);
                $('#modalSearchTags').modal('hide');
            },
            error : error => {
                Search.tagsPreloader.hide();
                content.append(`<div class="alert alert-danger">Возникла ошибка. Подробнее в консоли</div>`).fadeIn(200);
                console.log(error);
            }
        })
    }

    static doSearchByTagName() {
        event.preventDefault();
        window.scrollTo(0, 0);
        let tagName = $(this).text().trim();
        $.ajax({
            url : `/search/tagName/${tagName}`,
            method : 'get',
            beforeSend : () => {
                $('#modalLoading').modal('show');
                console.log("1");
            },
            success : res => {
                setTimeout(() => {
                    $('#modalLoading').modal('hide');
                }, 500);

                if (res === "false") {
                    alert("Ничего не найдено");
                    return;
                }
                $('#table').html(res);
            },
            error : error => {
                $('#modalLoading').modal('hide');
                alert("Возникла ошибка. Подробнее в консоли");
                console.log(error);
            }
        })
    }
}