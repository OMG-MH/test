(function ($) {
    "use strict";

    // ==========================================
    //      Start Document Ready function
    // ==========================================
    $(function () {
        // ============== Bootstrap Tooltip Enable Start ========
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))

        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        // =============== Bootstrap Tooltip Enable End =========

        // ============== Header Hide Click On Body Js Start ========
        $(".header-button").on("click", function () {
            $(".body-overlay").toggleClass("show");
        });

        $(".body-overlay").on("click", function () {
            $(".header-button").trigger("click");
            $(this).removeClass("show");
        });
        // =============== Header Hide Click On Body Js End =========

        // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
        $(".dropdown-item").on("click", function () {
            $(this).closest(".dropdown-menu").addClass("d-block");
        });
        // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================

        // ========================== Add Attribute For Bg Image Js Start =====================
        $(".bg-img").css("background-image", function () {
            return "url(" + $(this).data("background-image") + ")";
        });
        // ========================== Add Attribute For Bg Image Js End =====================

        // ================== Password Show Hide Js Start ==========
        $(".toggle-password").on("click", function () {
            $(this).toggleClass("ti-eye ti-eye-off")

            let input = $($(this).attr("id"))

            if (input.attr("type") === "password") {
                input.attr("type", "text")
            } else {
                input.attr("type", "password")
            }
        })
        // =============== Password Show Hide Js End =================

        // ================== Freelancer Slider Start ==========
        let freelancerSlider = $('.freelancer__slider')

        if (freelancerSlider.length) {
            freelancerSlider.slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 1500,
                prevArrow: '<button type="button" class="slick-prev"><i class="ti ti-arrow-narrow-left transform-0"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="ti ti-arrow-narrow-right transform-0"></i></button>',
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        // =============== Freelancer Slider End =================

        // ========================= Slick Slider Js Start ==============
        let testimonialSlider = $(".testimonial-txt-slider")

        if (testimonialSlider.length) {
            testimonialSlider.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 1500,
                fade: true,
                prevArrow: $('.testimonial__arrow__prev'),
                nextArrow: $('.testimonial__arrow__next'),
            });
        }
        // ========================= Slick Slider Js End ===================

        // ========================= Client Slider Js Start ===============
        let partnerSlider = $('.partner__slider')

        if (partnerSlider.length) {
            partnerSlider.bxSlider({
                minSlides: 6,
                maxSlides: 6,
                slideWidth: $(window).outerWidth() / 6,
                slideMargin: 30,
                ticker: true,
                speed: 9000,
                responsive: true
            });
        }
        // ========================= Client Slider Js End ===================

        // ========================= Account Setup Key Copy Start ==========
        $('.account-setup-key__copy').on('click', function () {
            let inputElement = $('#accountSetupKey');
            inputElement.select();
            document.execCommand('copy');

            showToasts('success', 'Link has copied');
        });
        // ========================= Account Setup Key Copy End ==========

        // ========================= Share Link Copy Start ========
        let pageUrl = window.location.href;
        $('#shareLink').val(pageUrl);

        $('.share-link__copy').on('click', function () {
            let inputElement = $('#shareLink');
            inputElement.select();
            document.execCommand('copy');

            showToasts('success', 'Link has been copied');
        });
        // ========================= Share Link Copy End ==========

        // ========================= Image Upload With Preview Start ==========
        function updatePreviewLogo(file) {
            let imgPreview = $('.image-preview');

            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;

                    imgPreview.html(img);
                    imgPreview.addClass('active');
                }

                reader.readAsDataURL(file);
            } else {
                imgPreview.html('+');
                imgPreview.removeClass('active');
            }
        }

        let imgUpload = $('#imageUpload')

        imgUpload.on('change', function () {
            updatePreviewLogo(this.files[0]);
        });

        imgUpload.on('click', '.custom-file-input-clear', function () {
            updatePreviewLogo(null);
        });
        // ========================= Image Upload With Preview End ==========

        // ========================= For Th In Small Devices Start ==========
        if ($('th').length) {
            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelector('thead') ? table.querySelectorAll('thead tr th') : null;

                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((column, i) => {
                        if (heading && heading[i]) {
                            column.setAttribute('data-label', heading[i].innerText);
                        }
                    });
                });
            });
        }
        // ========================= For Th In Small Devices End ==========

        // ========================= Odometer Counter Js End =====================
        $(".odometer").isInViewport(function (status) {
            if (status === "entered") {
                setTimeout(function () {
                    $(".odometer").each(function () {
                        $(this).html($(this).attr("data-count"));
                    });
                }, 0);
            }
        });
        // ========================= Odometer Counter Js End =====================

        // =============== Sidebar Menu Js Start ===============
        $('.has-sub').on('click', function () {
            if ($('.sidebar-link').hasClass('has-sub')) {
                $(this).toggleClass('show')
                $(this).siblings('.sidebar-dropdown-menu').slideToggle(300).parent('.sidebar-item')
                $(this).parent('.sidebar-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
                $(this).parent('.sidebar-dropdown-item').siblings().children('.sidebar-link').removeClass('show')
                $(this).parent('.sidebar-dropdown-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
            }
        })

        $(".sidebar-link").each(function () {
            let pageUrl = window.location.href.split(/[?#]/)[0];

            if (this.href === pageUrl) {
                $(this).addClass("active");
                $(this).parents(".sidebar-item").addClass("open")
            }
        });

        $(".sidebar-menu .active").parent().parents(".sidebar-dropdown-menu").show().siblings().addClass("show");

        $('.sidebar-toggler').on('click', function () {
            $('.main-sidebar').toggleClass('active');
            $('.sidebar-overlay-2').toggleClass('show');
        });

        $('.sidebar-overlay-2').on('click', function () {
            $(this).removeClass('show');
            $('.main-sidebar').removeClass('active');
        });
        // =============== Sidebar Menu Js End ===============

        // ========================= Modal Video Js Start =====================
        let videoBtn = $('.video-btn');

        if (videoBtn.length) videoBtn.modalVideo();
        // ========================= Modal Video Js End =====================

        // ========================= Circle Text Js Start =====================
        let circleTxt = $(".circle-txt")

        if (circleTxt.length) {
            circleTxt.circleText({
                glue: " — ",
                repeat: 2,
            });
        }
        // ========================= Circle Text Js End =====================

        // ========================= Nice Select Js Start ===================
        $('select').not('.select-2').niceSelect();
        // ========================= Nice Select Js End =====================

        // ========================= Select2 Js Start ===================
        let select2Tag = $(".select-2");

        if (select2Tag.length) {
            select2Tag.each(function () {
                const $this = $(this);
                const noSearch = $this.data('search') === false;
                const isMultiple = $this.prop('multiple');
                const isInsideModal = $this.parents('.modal').length > 0;

                let options = {
                    containerCssClass: ":all:",
                    minimumResultsForSearch: noSearch ? Infinity : 0,
                };

                if (isMultiple) {
                    options.tags = true;
                    options.dropdownAutoWidth = true;
                }

                if (isInsideModal) {
                    options.dropdownParent = $this.parent();
                }

                $this.select2(options);
            });
        }
        // ========================= Select2 Js End =====================

        // ========================= CK Editor Start ==========
        if ($('.ck-editor').length) {
            window.editors = {};
            document.querySelectorAll('.ck-editor').forEach((node, index) => {
                ClassicEditor
                    .create(node, {})
                    .then(newEditor => {
                        window.editors[index] = newEditor
                    });
            });
        }
        // ========================= CK Editor End ==========

        // ========================= Job List Category Filter Start ==========
        $('#allCategory').on('change', function () {
            $(this).parents('li').siblings().find('input').prop('checked', false);
        });

        $('.job-list__category input:not(#allCategory)').on('change', function () {
            $('#allCategory').prop('checked', false);
        });
        // ========================= Job List Category Filter End ==========

        // ========================= Job List Sidebar/Filter In Small Devices Start ==========
        $('.show-sidebar').on('click', function () {
            $('.job-list__sidebar').addClass('active');
            $('.sidebar-overlay').addClass('show');
            $('body, html').addClass('overflow-hidden');
        });

        $('.close-sidebar, .sidebar-overlay').on('click', function () {
            $('.job-list__sidebar').removeClass('active');
            $('.sidebar-overlay').removeClass('show');
            $('body, html').removeClass('overflow-hidden');
        });
        // ========================= Job List Sidebar/Filter In Small Devices End ==========

        // ========================= Job Chat Start =========================
        let scrollableElement = $(".job-chat__chatbox");

        scrollableElement.scrollTop(scrollableElement.prop("scrollHeight"));

        $('#messageType').on('input paste', function () {
            adjustRows($(this));
        });

        function adjustRows(textarea) {
            setTimeout(function () {
                const lines = textarea.val().split('\n').length;
                const maxRows = textarea.data('max-rows');
                textarea.prop('rows', lines > maxRows ? maxRows : lines);
            }, 0);
        }

        function attachedFile(file) {
            if (file) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    let isImage = file.type.startsWith('image/');
                    let fileExtension = file.name.split('.').pop();

                    if (isImage) {
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        $('.job-chat__attachment').html(img);
                        $('.job-chat__show-attach').addClass('active');
                    } else {
                        let iconHtml = '<i class="ti ti-file-text"></i>';
                        let fileDiv = $('<div class="file-container"></div>').html(iconHtml + ' <span class="badge badge--base py-0 px-2">.' + fileExtension + '</span>');
                        $('.job-chat__attachment').html(fileDiv);
                        $('.job-chat__show-attach').addClass('active');
                    }
                }

                reader.readAsDataURL(file);
            } else {
                $('.job-chat__show-attach').removeClass('active');
            }
        }

        $('#attachFile').on('change', function () {
            attachedFile(this.files[0]);
        });

        $('.job-chat__clear-attach').on('click', function () {
            attachedFile(null);
            $('#attachFile').val('');
        });
        // ========================= Job Chat End =========================

        // ========================= Hide Profile Tooltip In Small Devices Start =========================
        if ($(window).width() < 992) {
            $('.tooltip-disable-sm').each(function () {
                const tooltip = bootstrap.Tooltip.getInstance(this);

                if (tooltip) tooltip.disable();
            });
        }
        // ========================= Hide Profile Tooltip In Small Devices End =========================
    });
    // ==========================================
    //      End Document Ready function
    // ==========================================


    // ========================= Preloader Js Start =====================
    $(window).on("load", function () {
        $(".preloader").fadeOut();
    });
    // ========================= Preloader Js End=====================

    // ========================= Header Sticky Js Start ==============
    $(window).on("scroll", function () {
        if ($(window).scrollTop() >= 300) {
            $(".header").addClass("fixed-header");
        } else {
            $(".header").removeClass("fixed-header");
        }
    });
    // ========================= Header Sticky Js End===================

    //============================ Scroll To Top Icon Js Start =========
    let btn = $(".scroll-top");

    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 300) {
            btn.addClass("show");
        } else {
            btn.removeClass("show");
        }
    });

    btn.on("click", function (e) {
        e.preventDefault();
        $("html, body").animate({scrollTop: 0}, "300");
    });
    //========================= Scroll To Top Icon Js End ======================
})(jQuery);
