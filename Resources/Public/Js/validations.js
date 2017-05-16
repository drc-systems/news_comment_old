jQuery(document).ready(function () {
    $(document).on("click", "#newsCommentSubmit", function () {
        var flag = 1;
        var elementObj;

        $('.validatethis').each(function (i, obj) {
            elementObj = $(this);
            if ($(this).val() == '') {
                elementObj.parent().addClass('has-error');
                flag = 0;
            } else {
                elementObj.parent().removeClass('has-error');
            }
        });
        if (flag == 0) {
            $('html, body').animate({
                scrollTop: $('.has-error').offset().top
            }, 1000);
            return false;
        }
    });

    $("#usermail").focusout(function () {
        elementObj = $(this);
        if (elementObj.val() != '') {
            if (!validateEmail(elementObj.val())) {
                var errormsg = '';
                elementObj.val('');
                elementObj.attr('placeholder',errormsg);
                elementObj.parent().addClass('has-error');
                $('html, body').animate({
                    scrollTop: elementObj.offset().top - 100
                }, 1000);
                flag = 0;
            } else {
                elementObj.parent().removeClass('has-error');
            }
        }
    });
})

function validateEmail($email)
{
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test($email);
}