$(document).ready(function() {
  // EXPLORE SEARCH
  $('#searchfield').keypress(function(e) {
    if (e.which == 13) {
      window.location =
        location.protocol +
        '//' +
        location.host +
        location.pathname +
        '?search=' +
        $('#searchfield').val();
    }
  });

  // REGISTER
  $('#requester').change(function() {
    if ($(this).is(':checked')) {
      $('#skills').hide();
    } else {
      $('#skills').show();
    }
  });

  // PROFILE
  $('.profile-edit-show').click(function() {
    $('#profile-edit-form').show();
    $('.profile-edit-show').hide();
  });
  $('#profile-edit-form button.cancel').click(function() {
    $('#profile-edit-form').hide();
    $('.profile-edit-show').show();
  });

  // SELECT MULTIPLE
  var last_valid_selection = null;

  $('select.register-select').change(function(event) {
    $(this)
      .val()
      .forEach(element => {
        if (!element) {
          $(this).val(['']);
          last_valid_selection = null;
        }
      });

    if ($(this).val().length > 3) {
      $(this).val(last_valid_selection);
    } else {
      last_valid_selection = $(this).val();
    }
  });

  // CREATE CAMPAIGN CHECK ON DATES
  $('#create-campaign-form').submit(function(e) {
    var opening_date = new Date($('input[name="opening_date"]').val());
    var closing_date = new Date($('input[name="closing_date"]').val());
    var sign_in_period_open = new Date(
      $('input[name="sign_in_period_open"]').val()
    );
    var sign_in_period_close = new Date(
      $('input[name="sign_in_period_close"]').val()
    );

    if (
      opening_date > closing_date ||
      sign_in_period_open > sign_in_period_close ||
      closing_date < sign_in_period_close
    ) {
      alert("There's an error with the given dates. Please try valid periods!");
      e.preventDefault();
    }
  });

  // CREATE CAMPAIGN SLIDERS
  $('#threshold_percentage input[type="range"]').val(0);
  $('#threshold_percentage small').text(0 + ' %');
  $('#threshold_percentage input[type="range"]').on('input', function() {
    $('#threshold_percentage small').text($(this).val() + ' %');
  });

  //CREATE TASK OPTIONS
  $('#task-option-input').keypress(function(e) {
    if (event.keyCode === 13) {
      $('#task-option-button').click();
      e.stopPropagation();
      e.preventDefault();
    }
  });
  $('#task-option-button').click(function() {
    var name = $('#task-option-input').val();
    if (!name) return;
    $('#task-option-input').val('');
    var optionsValue = JSON.parse(
      $('input[type="hidden"][name="options"]').val()
    );
    optionsValue.push(name);
    $('input[type="hidden"][name="options"]').val(JSON.stringify(optionsValue));
    var option = $(`
            <li class="list-group-item d-flex justify-content-between align-items-center">
                ${name}
                <span class="badge badge-danger badge-pill" style="cursor: pointer">X</span>
            </li>
        `);
    $('#options-list').append(option);
    option.children('span').click(function() {
      var name = $(this)
        .parent()
        .clone() //clone the element
        .children() //select all the children
        .remove() //remove all the children
        .end() //again go back to selected element
        .text()
        .trim();
      $(this)
        .parent()
        .remove();
      var optionsValue = JSON.parse(
        $('input[type="hidden"][name="options"]').val()
      );
      if (optionsValue.length) {
        optionsValue.splice(optionsValue.indexOf(name), 1);
        $('input[type="hidden"][name="options"]').val(
          JSON.stringify(optionsValue)
        );
      }
    });
  });
  $('#create-task-form').submit(function(e) {
    if (
      JSON.parse($('input[type="hidden"][name="options"]').val()).length < 2
    ) {
      e.preventDefault();
      alert('Need at least 2 option');
    }
  });
});
