<!-- Contact form modal -->
<div class="modal fade" id="{{ $idContactFormModal }}" tabindex="-1" role="dialog" aria-labelledby="{{ $idContactFormModal }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h4 class="font-weight-bolder text-info text-gradient text-center">Форма зворотного зв'язку</h4>
                    </div>
                    <div class="card-body">
                        <form role="form" class="text-left" id="{{ $idContactForm }}">
                            <input hidden name="id_status" value="1">

                            <div class="row">
                                <label>Номер телефону <span style="color: red">*</span></label>
                                <div class="input-group mb-3 js--phone_number">
                                    <input type="text" id="phone_number" class="form-control" name="phone_number" placeholder="+380 (__) ___-__-__" aria-label="Номер телефону" aria-describedby="basic-addon1">
                                </div>

                                <label>Email <span style="color: red">*</span></label>
                                <div class="input-group mb-3 js--email">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Введіть email..." aria-label="Email" aria-describedby="email-addon">
                                </div>

                                <label>Ім'я <span style="color: red">*</span></label>
                                <div class="input-group mb-3 js--firstname">
                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Введіть ім'я..." aria-label="Ім'я" aria-describedby="basic-addon1">
                                </div>

                                <label>Прізвище</label>
                                <div class="input-group mb-3 js--lastname">
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Введіть прізвище..." aria-label="Прізвище" aria-describedby="basic-addon1">
                                </div>

                                <label>Коментар</label>
                                <div class="input-group mb-3 js--comment">
                                    <textarea class="form-control" rows="4" id="comment" name="comment" placeholder="Введіть коментар..." aria-label="Коментар" aria-describedby="basic-addon1"></textarea>
                                </div>

                                <span id="modal_error_message_block" style="color: red"></span>

                                <div class="text-center">
                                    <button type="button" id="contactFormSubmitBtn" class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">Відправити</button>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-round bg-gradient-danger btn-lg w-100 mt-4 mb-0" data-bs-dismiss="modal">Закрити</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.success-modal',
    [
        'idSuccessModal' => 'success_modal_contact_form',
        'descriptionSuccessModal' => 'Форму зворотного зв\'язку надіслано.',
        'titleSuccessModal' => '',
    ]
)

@push('js')
    <script>
        /* Применяем маску к input для номера телефона ------------- */
        const phoneNumberInput = $('#phone_number');

        $(document).ready(function() {
            $(phoneNumberInput).inputmask("+380 (99) 999-99-99");
        });
        /* --------------------------------------------------------- */

        $(document).ready(function() {
            $('#contactFormSubmitBtn').click(function(e) {
                e.preventDefault();

                // Удаляем все сообщения об ошибках с предыдущей отправки
                $('#{{ $idContactForm }}').find('.invalid-validation').remove();
                $('#{{ $idContactForm }} .is-invalid').removeClass('is-invalid');
                $('#{{ $idContactForm }} .input-group').addClass('mb-3');

                // Получаем данные формы
                let formData = $('#{{ $idContactForm }}').serialize();

                // Отправка AJAX запроса
                $.ajax({
                    url: '{{ route('contact-forms.ajax-store') }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        // Обработка ответа сервера
                        if (data.success) {
                            $('#{{ $idContactFormModal }}').modal('hide');

                            cleanFormFieldsValues();

                            $('#success_modal_contact_form').modal('show');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorSpan = $("#modal_error_message_block");

                        if (errorSpan) {
                            errorSpan.hide();
                            errorSpan.text('');
                        }

                        // Too Many Requests:
                        if (xhr.status === 429 && errorSpan) {
                            errorSpan.text('Нещодавно Ви вже надіслали запит. Будь ласка, спробуйте пізніше.');
                            errorSpan.show();
                        }

                        if (xhr.responseJSON && xhr.responseJSON.errors && Object.keys(xhr.responseJSON.errors).length) {
                            // Добавляем сообщения об ошибках под каждым полем ввода
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                let input = $('#{{ $idContactForm }}').find('[name="' + field + '"]');
                                let inputBlock = $('.js--' + field);

                                input.addClass('is-invalid'); // Добавляем класс для подсветки поля

                                if (inputBlock && messages && Object.keys(messages).length) {
                                    $.each(messages, function(keyError, messageError) {
                                        // Добавляем сообщение об ошибке
                                        if (messageError) {
                                            inputBlock.removeClass('mb-3');
                                            inputBlock.after('<span class="invalid-validation col-12 mb-3" style="color: red; margin-top: 5px; font-size: 14px;">' + messageError + '</span>');
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            });
        });

        function cleanFormFieldsValues() {
            $('#phone_number').val('');
            $('#email').val('');
            $('#firstname').val('');
            $('#lastname').val('');
            $('#comment').val('');

            let errorSpan = $("#modal_error_message_block");

            if(errorSpan) {
                errorSpan.hide();
                errorSpan.text('');
            }
        }
    </script>
@endpush
