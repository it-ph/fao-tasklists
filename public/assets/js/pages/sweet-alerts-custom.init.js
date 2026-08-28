function store(form) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, save it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-primary mt-2',
        cancelButtonClass: 'btn btn-danger ms-2 mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Thank you!',
                icon: 'success',
                allowOutsideClick: false
            });
            $("#" + form).submit();
        }
    });
}

function update(form) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-primary mt-2',
        cancelButtonClass: 'btn btn-danger ms-2 mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Thank you!',
                icon: 'success',
                allowOutsideClick: false
            });
            $("#" + form).submit();
        }
    });
}

function idelete(form) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-primary mt-2',
        cancelButtonClass: 'btn btn-danger ms-2 mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Thank you!',
                icon: 'success',
                allowOutsideClick: false
            });
            $("#" + form).submit();
        }
    });
}

function start(form) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, start it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-primary mt-2',
        cancelButtonClass: 'btn btn-danger ms-2 mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Thank you!',
                icon: 'success',
                allowOutsideClick: false
            });
            $("#" + form).submit();
        }
    });
}

function stop(form) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, stop it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-primary mt-2',
        cancelButtonClass: 'btn btn-danger ms-2 mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Thank you!',
                icon: 'success',
                allowOutsideClick: false
            });
            $("#" + form).submit();
        }
    });
}

function pause(form) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, pause it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-primary mt-2',
        cancelButtonClass: 'btn btn-danger ms-2 mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Thank you!',
                icon: 'success',
                allowOutsideClick: false
            });
            $("#" + form).submit();
        }
    });
}

function resume(form) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, resume it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-primary mt-2',
        cancelButtonClass: 'btn btn-danger ms-2 mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Thank you!',
                icon: 'success',
                allowOutsideClick: false
            });
            $("#" + form).submit();
        }
    });
}

function must_clock_in_first() {
    Swal.fire({
        title: 'Invalid Action',
        text: "You must Clock IN before you can create, start or resume a task!",
        icon: 'error',
        confirmButtonText: 'Okay!',
        confirmButtonClass: 'btn btn-primary mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    });
}

function shift_already_ended() {
    Swal.fire({
        title: 'Invalid Action',
        text: "Your shift for today has already been ended. You cannot create, start, or resume tasks after clocking out!",
        icon: 'error',
        confirmButtonText: 'Okay!',
        confirmButtonClass: 'btn btn-primary mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    });
}

function has_active_task() {
    Swal.fire({
        title: 'Invalid Action',
        text: "Please On Hold or Complete your current task before you can create, start or resume another task!",
        icon: 'error',
        confirmButtonText: 'Okay!',
        confirmButtonClass: 'btn btn-primary mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    });
}

function clockIO(form) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-primary mt-2',
        cancelButtonClass: 'btn btn-danger ms-2 mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Thank you!',
                icon: 'success',
                allowOutsideClick: false
            });
            $("#" + form).submit();
        }
    });
}

function removeClockOut(form) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, resume it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-primary mt-2',
        cancelButtonClass: 'btn btn-danger ms-2 mt-2',
        buttonsStyling: false,
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            Swal.fire({
                title: 'Thank you!',
                icon: 'success',
                allowOutsideClick: false
            });
            $("#" + form).submit();
        }
    });
}