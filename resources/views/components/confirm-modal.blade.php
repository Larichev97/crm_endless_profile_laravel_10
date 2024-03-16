<div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-title-default" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content"></div>
            <div class="modal-footer">
                <button id="modal_submit_btn" type="button" class="btn bg-gradient-primary" onclick="executeSubmitButton('{{ (string) $idFormSubmit }}')">Да</button>
                <button type="button" class="btn btn-link ml-auto" data-bs-dismiss="modal">Нет</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        /**
         *
         * @param idSubmitForm ID формы для отправки
         */
        function executeSubmitButton(idSubmitForm) {
            // Выполнить submit формы
            document.getElementById(idSubmitForm).submit();
        }
    </script>
@endpush
