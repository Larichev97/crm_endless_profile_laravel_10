<div class="modal fade" id="{{ $idSuccessModal }}" tabindex="-1" role="dialog" aria-labelledby="{{ $idSuccessModal }}" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                @if(!empty($titleSuccessModal))
                    <h6 class="modal-title" id="modal-title-notification">{{ $titleSuccessModal }}</h6>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3 text-center">
                    <i class="fa fa-check-circle text-gradient text-success" style="font-size: 80px;"></i>
                    <h3 class="text-gradient text-success mt-4">Успіх!</h3>
                    <p>{{ $descriptionSuccessModal }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark w-100" data-bs-dismiss="modal">Продовжити</button>
            </div>
        </div>
    </div>
</div>
