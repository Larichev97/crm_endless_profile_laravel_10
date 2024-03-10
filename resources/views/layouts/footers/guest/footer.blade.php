<footer class="footer pt-5">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-12 mb-lg-0 mb-4 mt-4 text-center">
                <button type="button" class="btn btn-block mb-3 @if(Agent::isDesktop() || Agent::isTablet()) btn-lg @else btn-xl @endif btn-dark text-uppercase" data-bs-toggle="modal" data-bs-target="#contact_form_modal">Замовити QR-профіль</button>
            </div>
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="copyright text-center text-md text-muted">
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
