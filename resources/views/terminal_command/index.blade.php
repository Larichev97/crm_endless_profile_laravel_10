@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Команды'])

    <div class="container-fluid py-4">
        @include('components.alert')

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h5>Команды для терминала</h5>
                    </div>
                    <div class="card-body text-left">
                        <div class="row">
                            <h6 class="mb-2">Команды для <span class="text-gradient text-warning">Библиотек</span>:</h6>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-warning text-dark" onclick="copyToClipboard('php artisan qrs:generate')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan qrs:generate <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-faded-success-vertical text-dark" onclick="copyToClipboard('php artisan l5-swagger:generate')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan l5-swagger:generate <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="mb-2">Команды для <span class="text-gradient text-purple">Composer</span>:</h6>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-faded-purple-vertical text-dark" onclick="copyToClipboard('composer install')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    composer install <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-faded-purple-vertical text-dark" onclick="copyToClipboard('composer update')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    composer update <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-faded-purple-vertical text-dark" onclick="copyToClipboard('composer require {package name}')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    composer require {package name} <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="mb-2">Команды для <span class="text-gradient text-info">Telegram</span>:</h6>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-faded-info-vertical text-dark" onclick="copyToClipboard('php artisan telegraph:new-bot')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan telegraph:new-bot <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-faded-info-vertical text-dark" onclick="copyToClipboard('php artisan telegraph:new-chat {bot_id}')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan telegraph:new-chat {bot_id} <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-faded-info-vertical text-dark" onclick="copyToClipboard('php artisan telegraph:set-webhook {bot_id}')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan telegraph:set-webhook {bot_id} <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="mb-2">Команды для <span class="text-gradient text-danger">Laravel</span>:</h6>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-danger text-dark" onclick="copyToClipboard('php artisan migrate')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan migrate <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-danger text-dark" onclick="copyToClipboard('php artisan storage:link')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan storage:link <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-danger text-dark" onclick="copyToClipboard('php artisan optimize')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan optimize <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-danger text-dark" onclick="copyToClipboard('php artisan migrate:fresh --seed')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan migrate:fresh --seed <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
                                <button type="button" class="btn btn-lg bg-gradient-danger text-dark" onclick="copyToClipboard('php artisan key:generate')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду">
                                    php artisan key:generate <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Пример формы с выполнением Команды:
    <form id="form_generate_qr_codes" action="{{ route('terminal-commands.generate-qr-codes') }}" method="POST">
        @csrf
        @method('POST')
        <button
            type="button"
            id="submit_btn_generate_qr_codes"
            class="btn btn-lg bg-gradient-warning"
            onclick="openConfirmModal('modal-default', 'modal-title-default', 'modal-body-content', 'Подтверждение', 'Вы действительно хотите выполнить команду?')"
        >
            <i class="fas fa-qrcode"></i> Сгенерировать все QR коды
        </button>
        <p class="font-weight-bolder text-dark">
            <span>php artisan qrs:generate</span>
            <span class="fas fa-copy" style="cursor: pointer;" onclick="copyToClipboard('php artisan qrs:generate')" data-toggle="tooltip" data-placement="bottom" title="Скопировать команду"></span>
        </p>
    </form>
    --}}

    @include('components.confirm-modal', ['idFormSubmit' => 'form_generate_qr_codes'])
@endsection

@push('js')
    @include('components.copy-text-script')

    <script>
        /*
        /!**
         * @param modalId
         * @param titleId
         * @param descriptionId
         * @param titleText
         * @param descriptionText
         *!/
        function openConfirmModal(modalId, titleId, descriptionId, titleText, descriptionText) {
            document.getElementById(titleId).innerText = titleText;
            document.getElementById(descriptionId).innerHTML = descriptionText;

            let modal = new bootstrap.Modal(document.getElementById(modalId));

            modal.show();
        }
        */
    </script>
@endpush
