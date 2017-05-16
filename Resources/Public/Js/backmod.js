$( document ).ready(function() {
	// $("tr").hover(function(){
	// 	$(this).find('div.row-actions').show();
	// });
	// $("tr").mouseout(function(){
	// 	$(this).find('div.row-actions').hide();
	// });
	$('#selectall').click(function(event) {  
        if(this.checked) { 
            $('.chk-comments').each(function() { 
                this.checked = true;  
            });
        }else{
            $('.chk-comments').each(function() { 
                this.checked = false; 
            });        
        }
    });

    $('#moveto').change(function(event) {  
        this.form.submit();
    });

    $(".comment-reply-button").on('click', function(){
        var parentCommentId = $(this).attr('btnvalue'); 
        var newsId = $(this).attr('newsvalue'); 
        var commentHTML = $('.active-comment-form').html();
        $('.active-comment-form').html('');
        $('.active-comment-form').removeClass('active-comment-form');
        $(this).parent().parent().find('.comment-form-reply_'+parentCommentId).append(commentHTML);
        $(this).parent().parent().find('.comment-form-reply_'+parentCommentId).addClass('active-comment-form');
        $('#parentId').val(parentCommentId);
        $('#newsuid').val(newsId)
    });

    $(document).on("click", "#comment-form-close-btn", function(event) {
        event.preventDefault();
        var commentHTML = $('.active-comment-form').html();
        $('.active-comment-form').html('');
        $('.active-comment-form').removeClass('active-comment-form');
        $('#form-reply-view').html(commentHTML);
        $('#form-reply-view').addClass('active-comment-form');
    });


    $(".comment-quick-edit-button").on('click', function(){
        

        var parentCommentId = $(this).attr('btnvalue'); 
        var description = $(this).parent().parent().parent().parent().find('.comment-description_'+parentCommentId).html();
        var username = $(this).parent().parent().parent().parent().find('.comment-username_'+parentCommentId).html();
        var usermail = $(this).parent().parent().parent().parent().find('.comment-usermail_'+parentCommentId).html();
        var website = $(this).parent().parent().parent().parent().find('.comment-website_'+parentCommentId).html();
        

        var newsId = $(this).attr('newsvalue'); 
        var commentHTML = $('.active-comment-quick-edit').html();
        $('.active-comment-quick-edit').html('');
        $('.active-comment-quick-edit').removeClass('active-comment-quick-edit');
        $(this).parent().parent().find('.comment-form-quick-edit_'+parentCommentId).append(commentHTML);
        $(this).parent().parent().find('.comment-form-quick-edit_'+parentCommentId).addClass('active-comment-quick-edit');
        $('#commentId').val(parentCommentId);
        $('#newsuid').val(newsId);
        $('#quick-edit-description').html('');
        $('#quick-edit-description').append($.trim(description));
        $('#quick-edit-username').val(username);
        $('#quick-edit-usermail').val(usermail);
        if(website != '')
        $('#quick-edit-website').val($.trim(website));
    });

    $(document).on("click", "#comment-quick-edit-close-btn", function(event) {
        event.preventDefault();
        var commentHTML = $('.active-comment-quick-edit').html();
        $('.active-comment-quick-edit').html('');
        $('.active-comment-quick-edit').removeClass('active-comment-quick-edit');
        $('#form-quick-edit').html(commentHTML);
        $('#form-quick-edit').addClass('active-comment-quick-edit');
    });

    $(".viewmore").on('click', function(){
        $(this).parent().next().slideDown();
        $(this).parent().hide();
        $(this).parent().next().next().show();
        $(this).parent().next().next().next().hide();
    });

    $(".hidethis").on('click', function(){
        $(this).parent().next().show();
        $(this).parent().prev().prev().show();
        $(this).parent().prev().hide();
        $(this).parent().hide();
    });

    $(document).on("click", "#newsCommentSubmit", function() {
            var flag = 1;
            var elementObj;

            $('.validatethis').each(function(i, obj) {
                elementObj = $(this);
                
                if($(this).val() == '')
                {
                    elementObj.parent().addClass('has-error');
                    flag = 0;   
                }
                else
                {
                    elementObj.parent().removeClass('has-error');
                }

            });
            if(flag == 0)
            {
                $('html, body').animate({
                    scrollTop: $('.has-error').offset().top
                }, 1000);
                return false;
            }
    });

    $(document).on("click", "#replyCommentSubmit", function() {
            var flag = 1;
            var elementObj;

            $('.replyvalidatethis').each(function(i, obj) {
                elementObj = $(this);
                
                if($(this).val() == '')
                {
                    elementObj.parent().addClass('has-error');
                    flag = 0;   
                }
                else
                {
                    elementObj.parent().removeClass('has-error');
                }

            });
            if(flag == 0)
            {
                $('html, body').animate({
                    scrollTop: $('.has-error').offset().top
                }, 1000);
                return false;
            }
    });

    $("#usermail").focusout(function(){
        elementObj = $(this);
        if(elementObj.val() != '')
        {
            if(!validateEmail(elementObj.val()))
            {
                var errormsg = '';
                elementObj.val('');
                elementObj.attr('placeholder',errormsg);
                elementObj.parent().addClass('has-error');
                $('html, body').animate({
                    scrollTop: elementObj.offset().top - 100
                }, 1000);
                flag = 0;   
            }
            else
                elementObj.parent().removeClass('has-error');
        }
    });

});

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}