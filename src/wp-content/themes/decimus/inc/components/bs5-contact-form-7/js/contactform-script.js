jQuery(document).ready(function ($) {
    function updateEventsDataForModal() {
        var eventName = $("#offcanvas-cart .item-name > strong > a").text().trim();
        var eventDetails = $(
            "#offcanvas-cart .item-name .item-quantity > .quantity"
        ).text();

        $('form.wpcf7-form input[name="event-name"]').val(eventName);
        $('form.wpcf7-form input[name="event-details"]').val(eventDetails);
    }

    /* Validation Events for changing response CSS classes */
    document.addEventListener(
        "wpcf7invalid",
        function () {
            $(".wpcf7-response-output").addClass("alert alert-danger");
            updateEventsDataForModal();
        },
        false
    );
    document.addEventListener(
        "wpcf7spam",
        function () {
            $(".wpcf7-response-output").addClass("alert alert-warning");
            updateEventsDataForModal();
        },
        false
    );
    document.addEventListener(
        "wpcf7mailfailed",
        function () {
            $(
                ".wpcf7-response-output, .wpcf7-response-output.wpcf7-display-none.wpcf7-acceptance-missing"
            ).addClass("alert alert-warning");
            updateEventsDataForModal();
        },
        false
    );
    document.addEventListener(
        "wpcf7mailsent",
        function () {
            $(".wpcf7-response-output").addClass("alert alert-success");
            $(".wpcf7-response-output").removeClass("alert-danger");
            $("button.wpcf7-submit").attr("disabled", "disabled");
            updateEventsDataForModal();
        },
        false
    );

    // Acceptance
    if (
        !$(".wpcf7-response-output.wpcf7-display-none").hasClass(
            "wpcf7-acceptance-missing"
        )
    ) {
        $(".wpcf7-response-output.wpcf7-display-none").addClass(
            "alert alert-danger"
        );
    }

    document.addEventListener(
        "wpcf7invalid",
        function () {
            $("label.form-check.form-check-checkbox").addClass("not-valid");
        },
        false
    );
    document.addEventListener(
        "wpcf7mailsent",
        function (event) {
            $("label.form-check.form-check-checkbox").removeClass(
                "not-valid checked"
            );

            if (sessionStorage.getItem("currentUserData")) {
                sessionStorage.removeItem("currentUserData");
            }

            // save user data in session
            var currentUser = {
                name: event.detail.inputs[2].value,
                email: event.detail.inputs[3].value,
            };
            sessionStorage.setItem("currentUserData", JSON.stringify(currentUser));

            // console.log('User data saved')
            // window.location.replace("https://baratszilvifeeling.hu/penztar/");


        },
        false
    );

    $("input#gdpr").change(function () {
        if ($(this).is(":checked")) {
            $("label.form-check.form-check-checkbox").addClass("checked");
        } else {
            $("label.form-check.form-check-checkbox.not-valid").removeClass(
                "checked"
            );
        }
    });

    // Disable Send Button
    $("input#gdpr").click(function () {
        if ($("button.wpcf7-submit").is(":disabled")) {
            $("button.wpcf7-submit").removeAttr("disabled");
        } else {
            $("button.wpcf7-submit").attr("disabled", "disabled");
        }
    });
}); // jQuery End
