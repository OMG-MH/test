@push('user-panel-modal')
    {{-- Accept Confirmation Modal --}}
    <div class="custom--modal modal fade" id="acceptConfirmationModal" tabindex="-1" aria-labelledby="acceptConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="acceptConfirmationModalLabel">@lang('Accept Application')</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @lang('Are you sure you want to accept this applicant?')
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Confirmation Modal --}}
    <div class="custom--modal modal fade" id="rejectConfirmationModal" tabindex="-1" aria-labelledby="rejectConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="rejectConfirmationModalLabel">@lang('Reject Application')</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @lang('Are you sure you want to reject this applicant?')
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--sm btn--base">@lang('Yes')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                $('.btn-accept').on('click', function (event) {
                    event.preventDefault()
                    let acceptModal = $('#acceptConfirmationModal')

                    acceptModal.find('form').attr('action', $(this).data('url'))
                    acceptModal.modal('show')
                })

                $('.btn-reject').on('click', function (event) {
                    event.preventDefault()
                    let rejectModal = $('#rejectConfirmationModal')

                    rejectModal.find('form').attr('action', $(this).data('url'))
                    rejectModal.modal('show')
                })
            })
        })(jQuery)
    </script>
@endpush
