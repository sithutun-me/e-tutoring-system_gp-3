$(document).ready(function() {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };

    if (window.errors) {
        window.errors.forEach(function(error) {
            toastr.error(error);
        });
    }

    if (window.successMessage) {
        toastr.success(window.successMessage);
    }

    if (window.errorMessage) {
        toastr.error(window.errorMessage);
    }
});
