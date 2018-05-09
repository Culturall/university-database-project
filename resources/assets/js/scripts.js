$(document).ready(function () {

    // PROFILE
    $('.profile-edit-show').click(function () {
        $('#profile-edit-form').show();
        $('.profile-edit-show').hide();
    });
    $('#profile-edit-form button.cancel').click(function () {
        $('#profile-edit-form').hide();
        $('.profile-edit-show').show();
    })

    // SELECT MULTIPLE
    var last_valid_selection = null;

    $('select.custom-select').change(function (event) {

        console.log($(this).val());
        $(this).val().forEach(element => {
            if (!element) {
                $(this).val([""]);
                last_valid_selection = null;
            }
        });

        if ($(this).val().length > 3) {
            $(this).val(last_valid_selection);
        } else {
            last_valid_selection = $(this).val();
        }
    });
})