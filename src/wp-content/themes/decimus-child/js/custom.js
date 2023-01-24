jQuery(document).ready(function ($) {
    var fbBtn = $("#follow-facebook-btn");
    var ttBtn = $("#timetable-btn");
    var maxWidth = 400;

    // change button size in the hero section
    function changeBtnSize(width, maxWidthExlusive) {
        if (width < maxWidthExlusive) {
            fbBtn.addClass("btn-sm").removeClass("me-2");
            ttBtn.css("display", "none");
        } else {
            fbBtn.removeClass("btn-sm").addClass("me-2");
            ttBtn.css("display", "initial");
        }
    }

    // change profile photo size on smaller screens
    function changePhotoSize(width, maxWidthExlusive) {
        var ph = $("#profile-photo");
        if (width < maxWidthExlusive) {
            ph.css("object-fit", "cover")
                .css("max-height", "280px")
                .css("margin", "0 auto")
                .css("text-align", "center")
                .addClass("rounded");
        } else {
            ph.removeClass("rounded");
        }
    }

    // initialize
    changeBtnSize($(window).width(), maxWidth);
    changePhotoSize($(window).width(), 576);

    // call functions on resize event
    $(window).on("resize", function () {
        var width = $(this).width();
        changeBtnSize(width, maxWidth);
        changePhotoSize(width, 576);

        if (width < 538) {
            ttBtn.css("display", "none");
        } else {
            ttBtn.css("display", "initial");
        }
    });

    $("button[data-bs-target='#offcanvas-navbar']").on("click", function () {
        //$("#offcanvas-navbar").addClass("bg-dark");
    });
    $(document).on("hidden.bs.offcanvas", function () {
        //$("#offcanvas-navbar").removeClass("bg-dark");
    });

    // change button, title styling for products
    $(".wp-block-button__link").each(function () {
        $(this).addClass("btn btn-primary");
    });

    // make products look like cards
    $(".wc-block-grid__product").each(function () {
        $(this).addClass("card");
    });

    $(".wp-block-button__link.add_to_cart_button.ajax_add_to_cart").each(
        function () {
            var addToCartBtn = $(this);
            addToCartBtn.addClass("btn btn-primary single_add_to_cart_button");
            addToCartBtn
                .attr("name", "add-to-cart")
                .attr("data-bs-toggle", "offcanvas")
                .attr("data-bs-target", "#offcanvas-cart");


        }
    );

    // change price tag color
    $(".wc-block-grid__product-price.price").each(function () {
        $(this).addClass("text-light");
    });

    $("nav.breadcrumb").each(function () {
        $(this).removeClass("bg-light");
    });

    try {
        // popup to show subsidy certificate enlarged
        var showEnlargedSubsidyCertificateModal = new bootstrap.Modal(
            document.getElementById("subsidy-modal"),
            {keyboard: false}
        );
        $("#subsidy-certificate").on("click", function () {
            showEnlargedSubsidyCertificateModal.show();
        });

        // popup to show the newest Facebook events
        var showFbEventsModal = new bootstrap.Modal(
            document.getElementById("events-modal"),
            {keyboard: false}
        );
        $("#show-fb-events-btn").on("click", function () {
            showFbEventsModal.show();
        });
    } catch (error) {
    }

    var lastScrollTop = 0;
    $(window).on("scroll", function (event) {
        var st = $(this).scrollTop();
        var topNav = $("#top-fix");
        if (st > lastScrollTop) {
            // down scroll
            topNav.css("height", "0px");
        } else {
            // up scroll code
            topNav.css("height", "0px");
        }
        lastScrollTop = st;
    });


    $(".front-slider").slick({
        arrows: true,
        dots: true,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1
    });

});
