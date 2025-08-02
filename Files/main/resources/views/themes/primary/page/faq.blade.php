@extends($activeTheme . 'layouts.frontend')

@section('frontend')
    <div class="faq py-120 section-bg">
        <div class="container">
            <div class="section-heading" >
                <p class="section-heading__subtitle">{{ __(@$faqContent->data_info->section_heading_subtitle) }}</p>
                <h2 class="section-heading__title">{{ __(@$faqContent->data_info->section_heading_title) }}</h2>
            </div>

            @if(count($faqElements))
                <div class="accordion custom--accordion" id="faqAccordion">
                    @foreach($faqElements as $faq)
                        <div class="accordion-item" >
                            <h2 class="accordion-header">
                                <button type="button" @class(['accordion-button', 'collapsed' => !$loop->first]) data-bs-toggle="collapse" data-bs-target="{{ '#collapse' . $loop->iteration }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ 'collapse' . $loop->iteration }}">
                                    {{ __(@$faq->data_info->question) }}
                                </button>
                            </h2>
                            <div id="{{ 'collapse' . $loop->iteration }}" @class(['accordion-collapse collapse', 'show' => $loop->first]) data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    @php echo @$faq->data_info->answer @endphp
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                @include($activeTheme . 'partials.basicNoData')
            @endif
        </div>
    </div>

    @include($activeTheme . 'sections.whyChooseUs')
    @include($activeTheme . 'sections.counters')
@endsection
