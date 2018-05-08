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
})