$(function () {


    var $heading = $('h1').text();
    switch ($heading){
        case "About Us":
            $('#main-nav').find('li').removeClass('active');
            $('#main-nav').find('#menu-item-64').addClass('active');
            break;
        case "Conference Vetting System":
            $('#main-nav').find('li').removeClass('active');
            $('#main-nav').find('#menu-item-65').addClass('active');
            break;
        case "Ethical Charter":
            $('#main-nav').find('li').removeClass('active');
            $('#main-nav').find('#menu-item-75').addClass('active');
            break;
        case "Transparent MedTech":
            $('#main-nav').find('li').removeClass('active');
            $('#main-nav').find('#menu-item-83').addClass('active');
        default:

    }

    hideNewsletterSection();


    function hideNewsletterSection() {
        $class_name = $('#page-masterhead, #ethical-charter-login-panel');
        if($class_name.hasClass('cvs') || $class_name.hasClass('ec') || $class_name.hasClass('tm')){
            $('#subscribe_newsletter').hide();
        }
    }

    $('#ec-reg-form :checkbox').change(function() {
        if (this.checked) {
            $('#first_member_first_name, #first_member_second_name, #first_member_position, #first_member_email').removeAttr('required');
        } else {
            $('#first_member_first_name, #first_member_second_name, #first_member_position, #first_member_email').prop('required',true);
        }
    });

  

    // show modal on webnair

    $('#go_to_assessment').on('click', function (e) {
        e.preventDefault();

        $('#confirm').modal({
            backdrop: 'static',
            keyboard: false
        })
            .one('click', '#yes', function() {
                assessment_url = $('#goto_assessment').val();
                window.location.href =assessment_url;
            });

    });


    //stop watch script

    var h1 = $('#stopwatch')[0],
        start = document.getElementById('start'),
        stop = document.getElementById('stop'),
        clear = document.getElementById('clear'),
        seconds = 0, minutes = 0, hours = 0,
        t;

    function add() {
        seconds++;
        if (seconds >= 60) {
            seconds = 0;
            minutes++;
            if (minutes >= 60) {
                minutes = 0;
                hours++;
            }
        }

        h1.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);
        timer();
    }
    function timer() {
        t = setTimeout(add, 1000);
    }

    //start the clock

    if( $('#stopwatch').length) {
        timer();
    }

    //process bar

    counter = 0;

    $('input[type=radio]').change(function () {

        if($(this).prop("checked", true)){
            counter++;
        }

        if(counter>0) {
            $('.next-question').prop("disabled", false);
        }

    });


    $('.nav-question').on('click', function () {

        totalstep = $('#totalstep').val();
        currenstep_panel = $('.col-question-panel.active');
        currentstep = currenstep_panel.attr('id');

        stepno = currentstep.split('_');

        counter = 0;

        updateProgressBar(stepno);

        //disable the next button
        currenstep_panel.find('input[type=radio]').each( function () {

            if($(this).prop("checked")){
                counter++;
            }
        });

        if(counter != 0)
        {
            $('.next-question').prop("disabled", false);
        }

        counter = 0;

        if(!$(this).hasClass('prev-question')){
            $('.next-question').prop("disabled", true);
        }

    });



    function updateProgressBar(stepno) {
        //update the process bar
        $('.step').each(function (index) {

            if(index<parseInt(stepno[1])) {
                $('#step_'+index).removeClass();
                $('#step_'+index).addClass('step done');
            }
            else{
                $('#step_'+index).removeClass();
                $('#step_'+index).addClass('step unanswered');
            }
        });
    }



})