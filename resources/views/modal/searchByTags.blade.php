<div class="modal fade" id="modalSearchTags" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="text-center" id="tagsPreloader" >
                <img src="{{ URL::asset('images/preloader.gif') }}" alt="Загрузка...">
            </div>
            <div class="text-center" id="tagsModalContent" style="display: none">
            </div>
            <div class="text-center">
                <button class="btn btn-primary m-1" id="doSearchTags">Искать</button>
                <button class="btn btn-light m-1" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>