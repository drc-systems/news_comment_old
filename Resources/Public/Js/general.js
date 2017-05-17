jQuery(document).ready(function () {
    $("#orderby").change(function () {
        elementObj = $('#searchterm');
        var text = elementObj.attr('placeholder');
        var inputvalue = elementObj.val();               // you need to collect this anyways
        if (text === inputvalue) {
            elementObj.val('');
        }
        this.form.submit();
    });

    $(document).on("click", ".comment-form-close-btn", function () {
        var commentHTML = $('.active-comment-form').html();
        $('.active-comment-form').html('');
        $('.active-comment-form').removeClass('active-comment-form');
        $('#form-comment-view').html(commentHTML);
        $('#form-comment-view').addClass('active-comment-form');
        $('.comment-form-close-btn').hide();
    });

    $(".comment-reply-button").on('click', function () {
        var parentCommentId = $(this).attr('btnvalue');
        var commentHTML = $('.active-comment-form').html();
        $('.active-comment-form').html('');
        $('.active-comment-form').removeClass('active-comment-form');
        $(this).parent().parent().parent().parent().find('.comment-form-reply_'+parentCommentId).append(commentHTML);
        $(this).parent().parent().parent().parent().find('.comment-form-reply_'+parentCommentId).addClass('active-comment-form');
        $('.comment-form-close-btn').show();
        
        $('#parentId').val(parentCommentId);
        $('#comment-description').focus();
    });

    $(".viewmore").on('click', function () {
        $(this).parent().next().slideDown();

        $(this).parent().hide();
    });

    $(".hidethis").on('click', function () {
        $(this).parent().prev().show();
        $(this).parent().hide();
    });
});


function highlightStar(obj, id)
{
    removeHighlight(id);
    $('.demo-table #tutorial-'+id+' li').each(function (index) {
        $(this).addClass('highlight');
        if (index == $('.demo-table #tutorial-'+id+' li').index(obj)) {
            return false;
        }
    });
}

function removeHighlight(id)
{
    $('.demo-table #tutorial-'+id+' li').removeClass('selected');
    $('.demo-table #tutorial-'+id+' li').removeClass('highlight');
}

function addRating(obj,id)
{
    $('.demo-table #tutorial-'+id+' li').each(function (index) {
        $(this).addClass('selected');
        $('#tutorial-'+id+' #rating').val((index+1));
        if (index == $('.demo-table #tutorial-'+id+' li').index(obj)) {
            return false;
        }
    });
    submitRating($('#tutorial-'+id+' #rating').val(),id);
}

function requireLogin()
{
    $("#ad-login").show();
    $('html, body').animate({
        scrollTop: $("#ad-login").offset().top
    }, 2000);
    setTimeout(function () {
        $("#ad-login").fadeOut("slow");
    }, 5000);
}

function notAllowRating()
{
    
    $("#ad-notallowed").show();
    $('html, body').animate({
        scrollTop: $("#ad-notallowed").offset().top
    }, 2000);
    setTimeout(function () {
        $("#ad-notallowed").fadeOut("slow");
    }, 5000);
}

function resetRating(id)
{
    if ($('#tutorial-'+id+' #rating').val() != 0) {
        $('.demo-table #tutorial-'+id+' li').each(function (index) {
            $(this).addClass('selected');
            if ((index+1) == $('#tutorial-'+id+' #rating').val()) {
                return false;
            }
        });
    }
}

function submitRating(rate,commentid)
{
    var pageId = $('#pagid').val();
    $.ajax({
        async: 'true',
        url: 'index.php',
        type: 'GET',
        dataType: 'json',
        data: {
            eID: "Newscommentajax",
            id:pageId,
            param:{
                rate:rate,
                commentid:commentid,
            }
        },
       
        success:function (data) {
            if (data.succ == 1) {
                $("#ad-success").show();
                $('html, body').animate({
                    scrollTop: $("#ad-success").offset().top
                }, 100);
                setTimeout(function () {
                    $("#ad-success").fadeOut("slow");
                }, 1500);
                setTimeout(function () {
                    //location.reload();
                }, 2000);
            }
        }
    });
}
