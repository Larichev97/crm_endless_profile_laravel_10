<footer class="footer pt-4">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-12 mb-lg-0 mb-4 mt-4 text-center">
                <button type="button" class="btn btn-block mb-3 btn-xl btn-dark text-uppercase" data-bs-toggle="modal" data-bs-target="#contact_form_modal">Замовити QR-профіль</button>
            </div>
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    ©
                    <script>
                        document.write(new Date().getFullYear())
                    </script>,
                    Endless Profile team.
                </div>
            </div>
        </div>
    </div>

    @include('components.contact-form-modal', ['idContactFormModal' => 'contact_form_modal', 'idContactForm' => 'contact_form',])
</footer>
