<!--ALL THIRD PART JAVASCRIPTS-->
<script src="public/vendor/js/vendor.footer.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--nextloop.core.js-->
<script src="public/js/core/ajax.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--MAIN JS - AT END-->
<script src="public/js/core/boot.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--EVENTS-->
<script src="public/js/core/events.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--CORE-->
<script src="public/js/core/app.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--SEARCH-->
<script src="public/js/core/search.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--BILLING-->
<script src="public/js/core/billing.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--Emojis-->
<script src="https://cdn.jsdelivr.net/npm/emoji-button@latest/dist/index.min.js"></script>

<!--project page charts-->
<?php if(@config('visibility.projects_d3_vendor')): ?>
<script src="public/vendor/js/d3/d3.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<script src="public/vendor/js/c3-master/c3.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<?php endif; ?>

<!--form builder-->
<?php if(@config('visibility.web_form_builder')): ?>
<script src="public/vendor/js/formbuilder/form-builder.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<script src="public/js/webforms/webforms.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<?php endif; ?>

<!--export js (https://github.com/hhurz/tableExport.jquery.plugin)-->
<script src="public/js/core/export.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<script type="text/javascript" src="public/vendor/js/exportjs/libs/FileSaver/FileSaver.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<script type="text/javascript" src="public/vendor/js/exportjs/libs/js-xlsx/xlsx.core.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<script type="text/javascript" src="public/vendor/js/exportjs/tableExport.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--printing-->
<script type="text/javascript" src="public/vendor/js/printthis/printthis.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--table sorter-->
<script type="text/javascript" src="public/vendor/js/tablesorter/js/jquery.tablesorter.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<script>

window.addEventListener("load", function(){
    $("#messages-left-menu").click(function(){
        document.getElementById("message_parent_id").value = '';  
        $("#replying_text_viewer").html('');
        tinymce.activeEditor.setContent('');
        $("#replying_text_wrapper").hide();
    });
    $(".comment-text a").attr("target", "_blank");
    $( ".comment-text p img" ).each(function() {
      $( this ).after('<br/><a type="button" title="" href="'+$(this).attr('src')+'" target="_blank"><i class="sl-icon-eye"></i></a>&nbsp;&nbsp;<a type="button" class="" href="'+$(this).attr('src')+'" download=""><i class="sl-icon-cloud-download"></i></a>');
    });
    $( ".x-description p img" ).each(function() {
      $( this ).after('<br/><a type="button" title="" href="'+$(this).attr('src')+'" target="_blank"><i class="sl-icon-eye"></i></a>&nbsp;&nbsp;<a type="button" class="" href="'+$(this).attr('src')+'" download=""><i class="sl-icon-cloud-download"></i></a>');
    });
    const dynamicLinks = document.querySelectorAll('.link');
    const dynamicLinks2 = document.querySelectorAll('.comment-text.w-100.js-hover-actions');

    // Loop through each link
    dynamicLinks.forEach(link => {
        // Get the content of the link
        const linkContent = link.textContent;
    
        // Condition to determine the color based on link content
        if (linkContent.includes('Brian Barrantes')) {
            link.style.color = '#FF0000'; // Change color to red if content includes 'Some'
        } else if (linkContent.includes('David Ibanez')) {
            link.style.color = '#2ea52e'; // Change color to blue if content includes 'Text'
        } else if (linkContent.includes('Jim Vester')) {
            link.style.color = '#0000FF'; // Change color to blue if content includes 'Text'
        } else if (linkContent.includes('Mariana Gomez')) {
            link.style.color = '#c9c917'; // Change color to blue if content includes 'Text'
        } else if (linkContent.includes('Sebastian Romero')) {
            link.style.color = '#800080'; // Change color to blue if content includes 'Text'
        } else if (linkContent.includes('Tony Faulkner')) {
            link.style.color = '#FFA500'; // Change color to blue if content includes 'Text'
        } else {
            
        }
    });
    
    // Loop through each link
    dynamicLinks2.forEach(link => {
        // Get the content of the link
        const linkContent = link.textContent;
    
        // Condition to determine the color based on link content
        if (linkContent.includes('Brian')) {
            link.style.backgroundColor  = '#FF0000'; // Change color to red if content includes 'Some'
        } else if (linkContent.includes('David')) {
            link.style.backgroundColor  = '#2ea52e'; // Change color to blue if content includes 'Text'
        } else if (linkContent.includes('Jim')) {
            link.style.backgroundColor  = '#ccccff'; // Change color to blue if content includes 'Text'
        } else if (linkContent.includes('Mariana')) {
            link.style.backgroundColor  = '#c9c917'; // Change color to blue if content includes 'Text'
        } else if (linkContent.includes('Sebastian')) {
            link.style.backgroundColor  = '#800080'; // Change color to blue if content includes 'Text'
        } else if (linkContent.includes('Tony')) {
            link.style.backgroundColor  = '#FFA500'; // Change color to blue if content includes 'Text'
        } else if (linkContent.includes('Maria')) {
            link.style.backgroundColor  = '#FFB6C1'; // Change color to blue if content includes 'Text'
        } else {
            
        }
    });
});
function letsReply(element){
  var commentId = element.getAttribute('data-comment');
  var messageTxt = element.getAttribute('data-comment-text');
  $el = $('button.js-ajax-ux-request.x-submit-button');
  $el.attr("data-url", $el.attr("data-url").split('&commentedit_id=')[0].split('&commentparent_id=')[0]+ "&commentparent_id="+commentId);
  
  tinymce.activeEditor.setContent('');
  //$('textarea').val('Add a reply to selected comment here..');
  $("#replying_pjt_cmt_text_viewer").html('<b>Replying to:</b>'+messageTxt)
  $('textarea').click();
  $("#replying_pjt_cmt_wrapper").show();
  $('html, body').animate({
    scrollTop: $("#comments-wrapper").offset().top - 100
  }, 1000); 
}
function letsReply2(element){
  var commentId = element.getAttribute('data-comment');
  var messageTxt = element.getAttribute('data-comment-text');
  var cardView = document.getElementById("card-comment-tinmyce-container");
  $el2 = $('#card-comment-post-button');
  $el2.attr("data-url", $el2.attr("data-url").split('&commentedit_id=')[0].split('&commentparent_id=')[0]+ "&commentparent_id="+commentId);
  $("#replying_tsk_cmt_text_viewer").html('<b>Replying to:</b>'+messageTxt)
  $('textarea').click();
  $("#replying_tsk_cmt_wrapper").show();
  tinymce.activeEditor.setContent('');
  //$('textarea').val('Add a reply to selected comment here..');
  
  //$('#cardModal').show().scrollTop(500);
  cardView.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
function letsReply3(element){
  var commentId = element.getAttribute('data-comment');
  var messageTxt = element.getAttribute('data-comment-text');
  var cardView = document.getElementById(element.getAttribute('data-comment-field'));
  $el2 = $(element.getAttribute('data-post-button'));
  $el2.attr("data-url", $el2.attr("data-url").split('&commentedit_id=')[0].split('&commentparent_id=')[0]+ "&commentparent_id="+commentId);
  $(element.getAttribute('data-cmt-text-viewer')).html('<b>Replying to:</b>'+messageTxt)
  $('#'+element.getAttribute('data-comment-field')+' textarea').click();
  $(element.getAttribute('data-cmt-text-wrapper')).show();
  tinymce.activeEditor.setContent('');
  //$('textarea').val('Add a reply to selected comment here..');
  
  //$('#cardModal').show().scrollTop(500);
  cardView.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
function letsEdit(element){
  var commentId = element.getAttribute('data-comment');
  $el = $('button.js-ajax-ux-request.x-submit-button');
  $el.attr("data-url", $el.attr("data-url").split('&commentedit_id=')[0].split('&commentparent_id=')[0]+ "&commentedit_id="+commentId);
  
  $("#replying_pjt_cmt_text_viewer").html('<b>You are editing the comment..</b>');
  $('textarea').click();
  tinymce.activeEditor.setContent(element.parentElement.childNodes[3].innerHTML);
  $("#replying_pjt_cmt_wrapper").show();
  $('html, body').animate({
    scrollTop: $("#comments-wrapper").offset().top - 100
  }, 500); 
}
function letsEdit2(element){
  var commentId = element.getAttribute('data-comment');
  var cardView = document.getElementById("card-comment-tinmyce-container");
  $el2 = $('#card-comment-post-button');
  $el2.attr("data-url", $el2.attr("data-url").split('&commentedit_id=')[0].split('&commentparent_id=')[0]+ "&commentedit_id="+commentId);
  
  $("#replying_tsk_cmt_text_viewer").html('<b>You are editing the comment..</b>');
  $('textarea').click();
  tinymce.activeEditor.setContent(element.parentElement.childNodes[3].innerHTML);
  $("#replying_tsk_cmt_wrapper").show();
  //$('#cardModal').show().scrollTop(500);
  cardView.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
function letsEdit3(element){
  var commentId = element.getAttribute('data-comment');
  var cardView = document.getElementById(element.getAttribute('data-comment-field'));
  $el2 = $(element.getAttribute('data-post-button'));
  $el2.attr("data-url", $el2.attr("data-url").split('&commentedit_id=')[0].split('&commentparent_id=')[0]+ "&commentedit_id="+commentId);
  
  $(element.getAttribute('data-cmt-text-viewer')).html('<b>You are editing the comment..</b>');
  $('#'+element.getAttribute('data-comment-field')+' textarea').click();
  tinymce.activeEditor.setContent(element.parentElement.childNodes[3].innerHTML);
  $(element.getAttribute('data-cmt-text-wrapper')).show();
  //$('#cardModal').show().scrollTop(500);
  cardView.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
function letsReplyMessage(element){
  var messageId = element.getAttribute('data-message-id');
  var messageTxt = element.getAttribute('data-message');
  document.getElementById("message_parent_id").value = messageId;  
  $("#replying_text_viewer").html('<b>Replying to:</b>'+messageTxt+'...')
  tinymce.activeEditor.setContent('');
  tinymce.activeEditor.focus();  
  $("#replying_text_wrapper").show();
}
function replyClose(){
  document.getElementById("message_parent_id").value = '';  
  $("#replying_text_viewer").html('')
  tinymce.activeEditor.setContent('');
  tinymce.activeEditor.focus();  
  $("#replying_text_wrapper").hide();
}
function replyPjtCmtClose(){
  $el = $('button.js-ajax-ux-request.x-submit-button');
  $el.attr("data-url", $el.attr("data-url").split('&commentedit_id=')[0].split('&commentparent_id=')[0]);  
  $("#replying_pjt_cmt_text_viewer").html('')
  tinymce.activeEditor.setContent('');
  tinymce.activeEditor.focus();  
  $("#replying_pjt_cmt_wrapper").hide();
}
function replyTskCmtClose(){
  $el2 = $('#card-comment-post-button');
  $el2.attr("data-url", $el2.attr("data-url").split('&commentedit_id=')[0].split('&commentparent_id=')[0]); 
  $("#replying_tsk_cmt_text_viewer").html('')
  tinymce.activeEditor.setContent('');
  tinymce.activeEditor.focus();  
  $("#replying_tsk_cmt_wrapper").hide();
}
function replyTskCmtClosex(element){
  $el2 = $(element.getAttribute('data-post-button'));
  $el2.attr("data-url", $el2.attr("data-url").split('&commentedit_id=')[0].split('&commentparent_id=')[0]); 
  $(element.getAttribute('data-cmt-text-viewer')).html('')
  tinymce.activeEditor.setContent('');
  tinymce.activeEditor.focus();  
  $(element.getAttribute('data-cmt-text-wrapper')).hide();
}
//open fle comment editor
function openEditor(num){
    tinymce.get('file-'+num+'-comment-tinmyce').setContent('');
    $("#file-"+num+"-comment-placeholder-input-container").hide();        
    $("#file-"+num+"-comment-tinmyce-container").show();
    tinymce.execCommand('mceFocus', true, 'file-'+num+'-comment-tinmyce');
}
//close editor
function closeEditor(num){
    $("#file-"+num+"-comment-tinmyce-container").hide();
    $("#file-"+num+"-comment-placeholder-input-container").show();
}
//submit editor
function submitEditor(num,e){
    $("#file-"+num+"-comment-tinmyce-container").hide();
    $("#file-"+num+"-comment-placeholder-input-container").show();
    nxAjaxUxRequest($(e));
}
</script>
<?php /**PATH /home/wec24/public_html/application/resources/views/layout/footerjs.blade.php ENDPATH**/ ?>