jQuery(function () {
    $('body').on('click', '[data-toggle="modal" ]', function () {
        $($(this).data("target") + ' .modal-body').load($(this).attr("href"));
    });

    $(document).on('submit', '.form-ajax', function (e, el) {
        btn = $(this).find('input[type="submit"]')
        btn.closest('.modal-body').load($(this).attr("action"), Object.assign($(this).serializeArray()), function () {
            debugger;
        });
        e.preventDefault();
    });

})