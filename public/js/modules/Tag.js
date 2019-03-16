export default class Tag
{

    static showAddTagWindow()
    {
        event.preventDefault();
        $('#addTagInput').html('').html(
            `<div class="input-group">
              <input type="text" class="form-control" id="newTagName">
              <div class="input-group-append btn btn-light" id="hideAddTagInput">
                <span class="input-group-text">
                <i class="fa fa-check" style="padding:0 3px"></i>
                </span>
              </div>
            </div>
            <div class="form-group">
                <small class="form-text text-muted">Для добавления тега достаточно просто убрать фокус с поля</small>
            </div>`
        ).hide().fadeIn(250);
        $('#newTagName').focus();
        $(this).hide();
    }

    static hideAddTagWindow() {
        event.preventDefault();
        $('#addTagInput').html('');
        $('#showAddNewTag').fadeIn(250);

        let tagsField = $('#tagsField');
        let allTags = tagsField.children('span');
        let newArr = [];

        for (let i = 0; i < allTags.length; i++) {
            newArr[i] = $(allTags[i]).data('tag-name');
        }

        let tagName = $(this).val().trim();
        let tagHtml = `<span data-tag-name="${tagName}" style="background-color: lightgrey; border-radius: 5px; padding:5px">
                        ${tagName}
                        <i class="fa fa-times" style="cursor: pointer" aria-hidden="true"></i>
                    </span>`;
        if (tagName.length !== 0) {
            if (!newArr.includes(tagName)) {
                tagsField.append(tagHtml);
            }
        }
    }

    static doDeleteTag() {
        event.preventDefault();
        $(this).parent().remove();
    }

}