$(function () {

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-bottom-right",
        timeOut: "3500"
    };

    if (window.successMessage) {
        toastr.success(window.successMessage);
    }

    if (window.errorMessage) {
        toastr.error(window.errorMessage);
    }
    function showToastErrors() {
        if (window.errors) {
            if (!$('.modal.show').length) {
                window.errors.forEach(function (error) {
                    toastr.error(error);
                });
            }
        }
    }

    setTimeout(function () {
        showToastErrors();
    }, 200);

});
