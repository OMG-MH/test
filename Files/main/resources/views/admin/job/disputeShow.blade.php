@extends('admin.layouts.master')

@section('master')
    <div class="col-lg-6">
        <div class="custom--card h-auto">
            <div class="card-header">
                <h3 class="title">@lang('Overview')</h3>
            </div>
            <div class="card-body">
                <table class="table table-flush">
                    <tbody>
                        <tr>
                            <td><strong>@lang('Job Title')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ __($job->title) }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Job Code')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $job->job_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Author')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                <p class="fw-semibold d-flex align-items-center gap-2">
                                    {{ __($dispute->userAssignedBy->fullname) }}
                                    <a href="{{ route('admin.user.details', $dispute->userAssignedBy->id) }}">
                                        <small>@</small>{{ $dispute->userAssignedBy->username }}
                                    </a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Freelancer')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                <p class="fw-semibold d-flex align-items-center gap-2">
                                    {{ __($dispute->userAssignedTo->fullname) }}
                                    <a href="{{ route('admin.user.details', $dispute->userAssignedTo->id) }}">
                                        <small>@</small>{{ $dispute->userAssignedTo->username }}
                                    </a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Job Quantity')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ $job->quantity }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Cost Per Work')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">{{ showAmount($job->rate) . ' ' . $setting->site_cur }}</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Disputant')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                <p class="fw-semibold">
                                    {{ __($dispute->disputant->fullname) }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text--danger"><strong>@lang('Dispute Reason')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                @if(strlen($dispute->dispute_reason) <= 100)
                                    {{ __($dispute->dispute_reason) }}
                                @else
                                    {{ __(strLimit($dispute->dispute_reason, 100)) }} <a href="#" class="see-more" data-reason="{{ __($dispute->dispute_reason) }}">@lang('See More')</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>@lang('Disputed On')</strong></td>
                            <td class="pe-2"><strong>:</strong></td>
                            <td class="text-start">
                                <p class="d-flex align-items-center gap-2">
                                    {{ showDateTime($dispute->disputed_at) }}
                                    <span class="text--muted">{{ diffForHumans($dispute->disputed_at) }}</span>
                                </p>
                            </td>
                        </tr>

                        @if($dispute->status == ManageStatus::ASSIGNED_JOB_DISPUTED)
                            <tr>
                                <td colspan="100">
                                    <a href="#" class="btn btn--base w-100" data-bs-toggle="modal" data-bs-target="#takeActionModal">
                                        <i class="ti ti-click"></i> @lang('Take Action')
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="custom--card h-auto">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="title">@lang('Conversation')</h3>
            </div>
            <div class="card-body">
                <div class="admin-chat">
                    <div class="admin-chat__chatbox" id="chatBox">
                        @include('admin.partials.jobConversations')
                    </div>

                    @if($dispute->status == ManageStatus::ASSIGNED_JOB_DISPUTED)
                        <div class="admin-chat__type-msg">
                            <form id="chatForm" class="row g-3" enctype="multipart/form-data">
                                <div class="col-12">
                                    <textarea id="messageInput" rows="1" name="message" data-max-rows="3" placeholder="@lang('Type your message here')..."></textarea>
                                    <div class="admin-chat__show-attach">
                                        <div class="admin-chat__attachment"></div>
                                        <button type="button" class="admin-chat__clear-attach" id="clearAttach">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <label for="attachFile" class="btn btn--sm btn--icon btn-outline--secondary">
                                        <i class="ti ti-paperclip"></i>
                                    </label>
                                    <input type="file" id="attachFile" name="file" hidden accept=".png,.jpg,.jpeg,.pdf">
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn--sm btn--base" id="sendMessageBtn">
                                            <i class="ti ti-send"></i> @lang('Send')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Dispute Reason Modal --}}
    <div class="custom--modal modal fade" id="disputeReasonModal" tabindex="-1" aria-labelledby="disputeReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="disputeReasonModalLabel">@lang('Dispute Reason')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Take Action Modal --}}
    <div class="custom--modal modal fade" id="takeActionModal" tabindex="-1" aria-labelledby="takeActionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="takeActionModalLabel">@lang('Take Action')</h2>
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="{{ route('admin.dispute.take.action', $dispute->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            @php $freelancerCompensation = $job->quantity * $job->rate @endphp

                            <div class="col-12">
                                <label class="form--label">@lang('Freelancer Compensation')</label>
                                <div class="input--group">
                                    <input type="number" class="form--control" id="compensation" value="{{ getAmount($freelancerCompensation) }}" disabled>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Amount for Job Author')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="author_amount" placeholder="@lang('Enter the amount that will be sent to the author')" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form--label required">@lang('Amount for Freelancer')</label>
                                <div class="input--group">
                                    <input type="number" step="any" min="0" class="form--control" name="freelancer_amount" placeholder="@lang('Enter the amount that will be sent to the freelancer')" required>
                                    <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--sm btn--base">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
    <style>
        .main-content .admin-chat__username {
            font-size: 1.25rem;
            line-height: 1;
            font-weight: 600;
            color: hsl(var(--black)/0.6);
        }

        .main-content .admin-chat__username i {
            font-size: 1.375rem;
            line-height: 1;
            color: hsl(var(--base-two));
        }

        .main-content .admin-chat__chatbox {
            margin-bottom: 20px;
            max-height: 400px;
            overflow: auto;
        }

        .main-content .admin-chat__chatbox::-webkit-scrollbar {
            width: 4px;
            height: 10px;
        }

        .main-content .admin-chat__chatbox::-webkit-scrollbar-thumb {
            background-color: hsl(var(--black)/0.15);
        }

        .main-content .admin-chat__chatbox::-webkit-scrollbar-track {
            background-color: hsl(var(--black)/0.1);
        }

        .main-content .admin-chat__msg {
            display: flex;
            gap: 10px;
            width: max-content;
            max-width: 70%;
            margin-bottom: 20px;
        }

        @media screen and (max-width: 479px) {
            .main-content .admin-chat__msg {
                max-width: 80%;
            }
        }

        .main-content .admin-chat__msg:last-child {
            margin-bottom: 0;
        }

        .main-content .admin-chat__msg.outgoing {
            margin-left: auto;
        }

        .main-content .admin-chat__msg.outgoing .admin-chat__msg__content {
            align-items: flex-end;
        }

        .main-content .admin-chat__msg.outgoing .admin-chat__msg__icon {
            color: hsl(var(--base-d-200));
        }

        .main-content .admin-chat__msg.outgoing .admin-chat__msg__username {
            text-align: right;
        }

        .main-content .admin-chat__msg.outgoing .admin-chat__msg__txt {
            background: hsl(var(--base-d-200));
            color: hsl(var(--white));
            border-radius: 5px 0 5px 5px;
        }

        .main-content .admin-chat__msg.outgoing .admin-chat__msg__attachment {
            background: hsl(var(--base-two-l-900));
            color: hsl(var(--base-d-200));
            font-weight: 600;
        }

        .main-content .admin-chat__msg.outgoing .admin-chat__msg__date {
            text-align: right;
        }

        .main-content .admin-chat__msg__icon {
            display: block;
            width: 30px;
            font-size: 1.3125rem;
            line-height: 1;
            color: hsl(var(--base-two-d-100));
            margin-top: 10px;
        }

        .main-content .admin-chat__msg__content {
            max-width: calc(100% - 40px);
            display: flex;
            flex-direction: column;
        }

        .main-content .admin-chat__msg__username {
            display: block;
            font-size: 0.8125rem;
            line-height: 1;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .main-content .admin-chat__msg__txt {
            width: max-content;
            max-width: 100%;
            background: hsl(var(--base-two));
            padding: 5px 10px;
            border-radius: 0 5px 5px 5px;
            margin-bottom: 5px;
            font-size: 0.875rem;
            color: hsl(var(--white));
        }

        .main-content .admin-chat__msg__attachment {
            background: hsl(var(--base));
            color: hsl(var(--white));
            font-size: 0.8125rem;
            line-height: 1;
            font-weight: 500;
            padding: 2px 7px 5px;
            border-radius: 3px;
        }

        .main-content .admin-chat__msg__attachment i {
            font-size: 1.2em;
        }

        .main-content .admin-chat__msg__date {
            display: block;
            font-size: 0.8125rem;
            line-height: 1;
        }

        .main-content .admin-chat__type-msg {
            border: 1px solid hsl(var(--black)/0.1);
            border-radius: 5px;
            padding: 10px;
            transition: 0.3s;
        }

        .main-content .admin-chat__type-msg:focus-within {
            border-color: hsl(var(--base));
        }

        .main-content .admin-chat__type-msg textarea {
            width: 100%;
            height: 50px;
            resize: none;
            border: 0;
            outline: none;
        }

        .main-content .admin-chat__type-msg textarea::-webkit-scrollbar {
            width: 4px;
            height: 10px;
        }

        .main-content .admin-chat__type-msg textarea::-webkit-scrollbar-thumb {
            background-color: hsl(var(--black)/0.15);
        }

        .main-content .admin-chat__type-msg textarea::-webkit-scrollbar-track {
            background-color: hsl(var(--black)/0.1);
        }

        .main-content .admin-chat__show-attach {
            width: 70px;
            height: 70px;
            border-radius: 5px;
            background-size: cover;
            background: hsl(var(--black)/0.05) no-repeat center center;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
            display: none;
            position: relative;
        }

        .main-content .admin-chat__show-attach.active {
            display: block;
        }

        .main-content .admin-chat__show-attach img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 5px;
        }

        .main-content .admin-chat__attachment {
            width: 100%;
            height: 100%;
        }

        .main-content .admin-chat__attachment .file-container {
            width: 100%;
            height: 100%;
            display: flex;
            gap: 5px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: hsl(var(--black)/0.5);
        }

        .main-content .admin-chat__attachment .file-container i {
            font-size: 1.875rem;
            transform: translateY(0);
        }

        .main-content .admin-chat__attachment .file-container .badge {
            font-size: 0.75rem;
            padding: 3px 5px;
        }

        .main-content .admin-chat__clear-attach {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 18px;
            height: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: red;
            color: hsl(var(--white));
            border-radius: 50%;
            font-size: 0.625rem;
        }

        .main-content .admin-chat__clear-attach i {
            transform: translate(0);
        }
    </style>
@endpush

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                // dispute reason
                $('.see-more').on('click', function (e) {
                    e.preventDefault()

                    let disputeReasonModal = $('#disputeReasonModal')

                    disputeReasonModal.find('p').text($(this).data('reason'))
                    disputeReasonModal.modal('show')
                })

                // dispute conversation
                scrollToBottom()

                function attachedFile(file) {
                    if (file) {
                        let reader = new FileReader()

                        reader.onload = function (e) {
                            let isImage = file.type.startsWith('image/')
                            let fileExtension = file.name.split('.').pop()

                            if (isImage) {
                                let img = document.createElement('img')
                                img.src = e.target.result

                                $('.admin-chat__attachment').html(img)
                                $('.admin-chat__show-attach').addClass('active')
                            } else {
                                let iconHtml = '<i class="ti ti-file-text"></i>'
                                let fileDiv = $('<div class="file-container"></div>').html(iconHtml + ' <span class="badge bg--base">.' + fileExtension + '</span>')

                                $('.admin-chat__attachment').html(fileDiv)
                                $('.admin-chat__show-attach').addClass('active')
                            }
                        }

                        reader.readAsDataURL(file)
                    } else {
                        $('.admin-chat__show-attach').removeClass('active')
                    }
                }

                $('#attachFile').on('change', function () {
                    attachedFile(this.files[0])
                })

                $('.admin-chat__clear-attach').on('click', function () {
                    attachedFile(null)
                    $('#attachFile').val('')
                })

                let isJobDisputed = @json($dispute->status == ManageStatus::ASSIGNED_JOB_DISPUTED);

                // send message
                $('#sendMessageBtn').on('click', function () {
                    let formData = new FormData($('#chatForm')[0])
                    formData.append('id', {{ $dispute->id }})

                    $.ajax({
                        url: "{{ route('admin.dispute.send.message') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $('#chatBox').html(response.html)
                            scrollToBottom()

                            $('#messageInput').val('')
                            $('#clearAttach').trigger('click')
                            $('#attachFile').val('')
                        },
                        error: function (response) {
                            if (response.status === 404) {
                                showToasts('error', response.responseJSON.error)
                            } else {
                                if (response.responseJSON.errors) {
                                    $.each(response.responseJSON.errors, function (key, errors) {
                                        errors.forEach(function (error) {
                                            showToasts('error', error)
                                        })
                                    })
                                } else {
                                    showToasts('error', response.responseJSON.error)
                                }
                            }
                        }
                    })
                })

                function fetchMessage() {
                    let id = {{ $dispute->id }};
                    let isAtBottom = isUserAtBottom($('.admin-chat__chatbox'))

                    $.get("{{ route('admin.dispute.fetch.message') }}", { id: id })
                        .done(function (response) {
                            $('#chatBox').html(response.html)

                            if (isAtBottom) scrollToBottom()
                        })
                        .fail(function (jqXHR) {
                            showToasts('error', jqXHR.responseJSON.error)
                        })
                }

                function isUserAtBottom(scrollableElement) {
                    const threshold = 100
                    const position = scrollableElement.scrollTop() + scrollableElement.innerHeight()
                    const height = scrollableElement.prop('scrollHeight')

                    return height - position <= threshold
                }

                function scrollToBottom() {
                    let scrollableElement = $(".admin-chat__chatbox")
                    scrollableElement.scrollTop(scrollableElement.prop("scrollHeight"))
                }

                if (isJobDisputed) setInterval(fetchMessage, 15000)
            })
        })(jQuery)
    </script>
@endpush
