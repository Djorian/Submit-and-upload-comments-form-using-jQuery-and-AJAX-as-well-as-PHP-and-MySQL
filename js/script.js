// После события загрузки всего DOMa выполнить функцию
$(document).ready(function () {
    showComments();
});

// Функция отправки комментария
$("#send-comment-form input, #send-comment-form textarea").jqBootstrapValidation({
    preventSubmit: true,
    submitError: function (form, event, errors) {
        // Дополнительные сообщения об ошибках
    },
    submitSuccess: function (form, event) {

        event.preventDefault();

        $("#send-comment-form-message-button").prop("disabled", true);

        $.ajax({
            url: "php/add-comment.php",
            type: "POST",
            data: {
                'name': $("#username").val(),
                'email': $("#email").val(),
                'comment': $("#comment").val(),
            },
            success: function (data) {
                switch (data) {
                    case '0':
                        sendingMessage('Ошибка сервера! Повторите позже!', 'danger');
                        break;
                    case '1':
                        sendingMessage('Комментарий отправлен!', 'success');
                        $('#send-comment-form').trigger("reset");
                        showComments();
                        break;
                    case '2':
                        sendingMessage('Заполните необходимые поля!', 'danger');
                        break;
                }
            },
            error: function () {
                sendingMessage('Извините, произошла ошибка. Пожалуйста, повторите отправку позже!', 'danger');
                $('#send-comment-form').trigger("reset");
            },
            complete: function () {
                setTimeout(function () {
                    $("#send-comment-form-message-button").prop("disabled", false);
                }, 1000);
            }
        });
    },
});

// Функция загрузки комментариев на страницу
function showComments() {

    var commentsContainer = ``;

    $.ajax({
        url: "php/show-comments.php",
        type: "POST",
        data: 'show-comments',
        dataType: 'json',
        success: function (data) {
            if (data === 0) {
                commentsContainer = '<h3 class="m-auto mt-2 text-danger">Извините, что-то пошло не так.</h3>';
                $('#comments-container').html(commentsContainer);
                return false;
            }
            if (data.length === 0) {
                commentsContainer = '<h3 class="m-auto mt-2 text-danger">Комментариев пока нет.</h3>';
                $('#comments-container').html(commentsContainer);
                return false;
            }
            else {
                $.each(data, function (key, value) {
                    if ((key % 2) == 0) {
                        commentsContainer += `<div class="col-lg-4 text-center"><h3 class="comments-username-green">${value.name}</h3><div class="comments-wrap-email-comment-green"><p class="email-green font-weight-bold">${value.email}</p><p class="comment">${value.comment}</p></div></div>`;
                    } else {
                        commentsContainer += `<div class="col-lg-4 text-center"><h3 class="comments-username-gray">${value.name}</h3><div class="comments-wrap-email-comment-gray"><p class="email-gray font-weight-bold">${value.email}</p><p class="comment">${value.comment}</p></div></div>`;
                    }
                });
                $('#comments-container').html(commentsContainer);
            }
        },
        error: function () {
            $('#comments-container').html('<h3 class="m-auto mt-2 text-danger">Извините, что-то пошло не так.</h3>');
        },
    });
}

// Функция для отображения сообщений об отправки формы
function sendingMessage(message, alertType) {
    $('#send-comment-form-message-block').html("<div class='alert alert-" + alertType + "'>");
    $('#send-comment-form-message-block> .alert-' + alertType).html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
        .append("</button>");
    $('#send-comment-form-message-block> .alert-' + alertType)
        .append('<strong>' + message + '</strong>');
    $('#send-comment-form-message-block> .alert-' + alertType).append('</div>');
    $(".alert").delay(2000).fadeOut(300);
}