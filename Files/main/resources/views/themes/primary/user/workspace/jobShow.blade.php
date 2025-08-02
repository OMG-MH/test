@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row g-4">
        <div class="col-md-6">
            <div class="custom--card h-auto">
                <div class="card-header">
                    <h3 class="title">@lang('Basic Info')</h3>
                </div>
                <div class="card-body">
                    @php $job = $assignedJob->job @endphp

                    <div class="job-request-details__job-info">
                        <div class="job-request-details__job-info__img">
                            <img src="{{ getImage(getFilePath('job') . '/' . $job->image, getFileSize('job')) }}" alt="@lang('Image')">
                        </div>
                        <div class="job-request-details__job-info__txt">
                            <h3 class="job-request-details__job-info__title">{{ __(strLimit($job->title, 50)) }}</h3>
                            <ul class="job-request-details__job-info__list">
                                <li><span class="fw-bold">@lang('Category'):</span> {{ __($job->category->name) }}</li>
                                <li><span class="fw-bold">@lang('Subcategory'):</span> {{ __($job->subcategory->name) }}</li>
                                <li><span class="fw-bold">@lang('Job Code'):</span> {{ $job->job_code }}</li>
                                <li><span class="fw-bold">@lang('Job Quantity'):</span> {{ $job->quantity }}</li>
                                <li><span class="fw-bold">@lang('Cost Per Work'):</span> {{ $setting->cur_sym . showAmount($job->rate) }}</li>
                                <li><span class="fw-bold">@lang('Assigned Date'):</span> {{ showDateTime($assignedJob->created_at, 'M d, Y - h:i A') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="custom--card h-auto">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="title">{{ trans('Chat with') . ' ' . __($assignedJob->userAssignedBy->fullname) }}</h3>
                </div>
                <div class="card-body">
                    <div class="job-chat">
                        <div class="job-chat__chatbox" id="chatBox">
                            @include($activeTheme . 'partials.jobConversations')
                        </div>

                        @if($assignedJob->status == ManageStatus::ASSIGNED_JOB_IN_PROGRESS || $assignedJob->status == ManageStatus::ASSIGNED_JOB_DISPUTED)
                            <div class="job-chat__type-msg">
                                <form id="chatForm" class="row g-3" enctype="multipart/form-data">
                                    <div class="col-12">
                                        <textarea id="messageInput" rows="2" name="message" data-max-rows="3" placeholder="@lang('Type your message here')..."></textarea>
                                        <div class="job-chat__show-attach">
                                            <div class="job-chat__attachment"></div>
                                            <button type="button" class="job-chat__clear-attach" id="clearAttach">
                                                <i class="ti ti-x fz-0 transform-0"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <label for="attachFile" class="btn btn--icon btn--sm btn-outline--secondary">
                                            <i class="ti ti-paperclip transform-0"></i>
                                        </label>
                                        <input type="file" id="attachFile" name="file" hidden accept=".png,.jpg,.jpeg,.pdf">
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn--sm btn--base py-1" id="sendMessageBtn">
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
    </div>
@endsection

@push('page-script')
    <script>
        (function ($) {
            'use strict'

            $(function () {
                let isJobInProgressOrDisputed = @json(
                    $assignedJob->status == ManageStatus::ASSIGNED_JOB_IN_PROGRESS ||
                    $assignedJob->status == ManageStatus::ASSIGNED_JOB_DISPUTED
                );

                $('#sendMessageBtn').on('click', function () {
                    let formData = new FormData($('#chatForm')[0])
                    formData.append('id', {{ $assignedJob->id }})

                    $.ajax({
                        url: "{{ route('user.job.send.message') }}",
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
                            if (response.status === 404 || response.status === 401) {
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
                    let id = {{ $assignedJob->id }};
                    let isAtBottom = isUserAtBottom($('.job-chat__chatbox'))

                    $.get("{{ route('user.job.fetch.message') }}", { id: id })
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
                    let scrollableElement = $('.job-chat__chatbox')
                    scrollableElement.scrollTop(scrollableElement.prop('scrollHeight'))
                }

                if (isJobInProgressOrDisputed) setInterval(fetchMessage, 15000)
            })
        })(jQuery)
    </script>
@endpush
