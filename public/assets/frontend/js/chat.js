var baseUrl = document.currentScript.getAttribute('data-base-url');

// var token = $('meta[name="csrf-token"]').attr('content');
var runnigLoadMore = 0;
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    getChatPersons();
    var personId = $('input[name="chatWith"]').val();
    if (personId) {
        getChatPersons(1);
        history.pushState(null, "", baseUrl + '/chat')
        // var current = $('.chat-person[data-other-id="'+personId+'"]').trigger('click');
        // var name = current.find('.getName').text();
        // var imgSrc = current.find('.getSrc').attr('src');
        // current.addClass('active');
        // getPersonChat(personId);
        // $('.setName').text(name).removeClass('d-none');
        // $('.setSrc').attr('src',imgSrc).removeClass('d-none');
        // $('.refresh-icon').removeClass('d-none');
    } else {
        getChatPersons();
    }
});

$(document).on('keyup', 'input[name="searchChat"]', function () {
    getChatPersons();
})

$(document).on('click', '.toggleChats', function () {
    var value = $('input[name="sortBy"]').val();
    $('input[name="sortBy"]').val((value == '1') ? '0' : '1');
    getChatPersons();
})

$(document).on('click', '.chat-person', function () {
    var personId = $(this).data('other-id');
    var name = $(this).find('.getName').text();
    var allowMessage = $(this).data('allow-message');
    if (allowMessage != '1')
        $('#sendMessageForm').find('input[name="message"]').val("").attr({ 'disabled': true, "placeholder": name + "is not available for message at this moment." });
    else
        $('#sendMessageForm').find('input[name="message"]').attr({ 'disabled': false, "placeholder": "Type a message..." });
    var imgSrc = $(this).find('.getSrc').attr('src');
    $('input[name="chatWith"]').val(personId);
    $('input[name="receiver_id"]').val(personId);
    $(this).addClass('active');
    $(this).parent().find('.chat-person').not(this).removeClass('active');
    getPersonChat(personId);
    $('.setName').text(name).removeClass('d-none');
    $('.setSrc').attr('src', imgSrc).removeClass('d-none');
    $('.refresh-icon').removeClass('d-none');
    readPersonChat();
    toggleDisplayChat();

})
$(document).on('click', '.refresh-icon', function () {
    $(this).addClass('rotate-icon');
    getChatPersons();
    refreshPersonChat();
    // setTimeout(function(){
    // },1000)
    // var personId = $('input[name="chatWith"]').val();
    // getPersonChat(personId);
});

$(document).on('click', '.delete-chat', function () {
    var personId = $(this).closest('.chat-person').data('other-id');
    // alert(personId);
    $('#clearChatModal input[name="personId"]').val(personId);
    $('#clearChatModal').modal('show');
});

$(document).on('click', '.clearChatConfirm', function () {
    var personId = $('#clearChatModal input[name="personId"]').val();
    clearPersonChat(personId);
});

$(document).on('change keyup', 'input[name="message"]', function () {
    var value = $(this).val();
    if (value) {
        $(this).closest('.chat-board-input').removeClass('send-hide-btn');
    } else {
        $(this).closest('.chat-board-input').addClass('send-hide-btn');
    }
});

$("#sendMessageForm").validate({
    ignore: [],
    rules: {
        message: "required",
    },
    messages: {
        message: "Message is required",
    },
    errorPlacement: function (error, element) {
        if (element.prop("type") === "checkbox") {
            error.insertAfter(element.next("label"));
        } else if (element.prop("name") === "message") {

        } else {
            error.insertAfter(element);
        }
    },
    submitHandler: function (form) {
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success: function (response) {
                var personId = $('input[name="receiver_id"]').val();
                // getPersonChat(personId);
                refreshPersonChat()
                getChatPersons()
                $('input[name="message"]').val('').trigger('change');
            }
        });
        return false;
    }
});

function toggleDisplayChat(revert = 0) {
    if (revert) {
        $('.unloaded-chat').removeClass('d-none');
        $('.loaded-chat').addClass('d-none');
        $('input[name="chatWith"]').val('');
        $('input[name="receiver_id"]').val('');
    } else {
        $('.loaded-chat').removeClass('d-none');
        $('.unloaded-chat').addClass('d-none');
    }
}

function getChatPersons(trigger = 0) {
    var personId = $('input[name="chatWith"]').val();
    var sortBy = $('input[name="sortBy"]').val();
    sortBy = (sortBy == '1') ? 'time_desc' : 'time_asc';
    var search = $('input[name="searchChat"]').val();
    $.ajax({
        url: baseUrl + '/chat/list-persons',
        type: 'POST',
        data: { sortBy: sortBy, search: search, personId: personId },
        success: function (response) {
            $('.chat-person-list').html(response);
            if (trigger) {
                $('.chat-person[data-other-id="' + personId + '"]').trigger('click');
            }
        }
    });
}

function getPersonChat(id, lastId = "", scrollTo = 1) {
    runnigLoadMore = 1
    $.ajax({
        url: baseUrl + '/chat/get-person-chat',
        type: 'POST',
        data: { person_id: id, last_id: lastId },
        success: function (response) {
            if (lastId) {
                // $('.chat-board-data').prepend(response);
                scrolltocustomheight(lastId, response)
                removeDuplicateDaystatus();
            } else {
                $('.chat-board-data').html(response);
            }
            if (scrollTo) {
                $('.chat-board-data').scrollTop($('.chat-board-data')[0].scrollHeight);
            }
            $('.refresh-icon').removeClass('rotate-icon');
            runnigLoadMore = 0
        }
    });
}

function refreshPersonChat() {
    // runnigLoadMore = 1
    var personId = $('input[name="chatWith"]').val();
    var lastId = $('.msgBox:last').data('chat-last');
    if (personId) {
        $.ajax({
            url: baseUrl + '/chat/refresh-person-chat',
            type: 'POST',
            data: { person_id: personId, last_id: lastId, refresh: 1 },
            success: function (response) {
                $('.chat-board-data').append(response);
                if (response) {
                    $('.chat-board-data').scrollTop($('.chat-board-data')[0].scrollHeight);
                }
                $('.refresh-icon').removeClass('rotate-icon');
                removeDuplicateDaystatus();
                // runnigLoadMore = 0
            }
        });
    }
}

function removeDuplicateDaystatus() {
    var lastId = '';
    $('.day-status').each(function () {
        var thisId = $(this).attr('id');
        if (lastId == thisId) {
            $(this).remove();
            // $('.day-status#'+thisId).not(':first').addClass('asdasd');
        }
        lastId = thisId
    })
}

function scrolltocustomheight(lastId, response) {
    var $current_top_element = $('.chat-board-data .msgBox[data-chat=' + lastId + ']');
    $('.chat-board-data').prepend(response);

    var previous_height = 0;
    $current_top_element.prevAll().each(function () {
        previous_height += $(this).outerHeight();
    });
    if (response) {
        $('.chat-board-data').scrollTop(previous_height + 30);
    } else {
        $('.chat-board-data').scrollTop(previous_height - 30);
    }
}

function clearPersonChat(personId) {
    $.ajax({
        url: baseUrl + '/chat/clear-chat',
        type: 'POST',
        data: { person_id: personId },
        success: function (response) {
            toastr.clear();
            toastr.options.closeButton = true;
            toastr.success(response.message);
            $('#clearChatModal').modal('hide');
            getChatPersons();
            toggleDisplayChat(1);
        }
    });
}

function readPersonChat() {
    var personId = $('input[name="chatWith"]').val();
    $.ajax({
        url: baseUrl + '/chat/read-chat',
        type: 'POST',
        data: { person_id: personId },
        success: function (response) {
            // toastr.clear();
            // toastr.options.closeButton = true;
            // toastr.success(response.message);
            // $('#clearChatModal').modal('hide');
            getChatPersons();
        }
    });
}

setInterval(function () {
    getChatPersons()
    refreshPersonChat();
    readPersonChat();
}, 10000)

// $(document).bind("DOMMouseScroll mousewheel scroll keyup",function(event){
$('.chat-board-data').scroll(function () {
    if ($(this).scrollTop() == 0) {
        var personId = $('input[name="chatWith"]').val();
        if (personId) {
            var lastId = $('.msgBox:first').data('chat');
            getPersonChat(personId, lastId, 0);
        }
    }
    // // console.log($('.chat-board-data')[0].height);
    // console.log($('.chat-board-data')[0].scrollHeight);
    // console.log($(this).scrollTop());
})