<?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!-- each comment -->
<!-- <div class="display-flex flex-row comment-row <?php echo e(($comment->commentparent_id==$comment->comment_id && $comment->commentparent_id!=$comment->commentsuperparent_id) ? 'reply-comment':''); ?> <?php echo e(($comment->commentparent_id!=$comment->comment_id && $comment->commentparent_id!=$comment->commentsuperparent_id) ? 'reply-reply-comment':''); ?>" id="comment_<?php echo e($comment->comment_id); ?>"> -->
<div class="display-flex flex-row comment-row" id="comment_<?php echo e($comment->comment_id); ?>">
    <div class="p-2">
        <img src="<?php echo e(getUsersAvatar($comment->avatar_directory, $comment->avatar_filename)); ?>"
            class="img-circle" alt="user" width="40">
    </div>
    <div class="comment-text w-100 js-hover-actions">
        <?php if($comment->pcmttxt != null && $comment->pcmtid != $comment->comment_id): ?>
        <div class="x-text opacity-5" onclick="document.getElementById('comment_<?php echo e($comment->pcmtid); ?>').scrollIntoView({block: 'start', behavior: 'smooth'});flashRed(document.getElementById('comment_<?php echo e($comment->pcmtid); ?>'));">
            <?php echo _clean(substr(strip_tags($comment->pcmttxt), 0, 50)); ?>.. click to view the comment replied
        </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-sm-6 x-name"><?php echo e($comment->first_name ?? runtimeUnkownUser()); ?></div>
            <div class="col-sm-6 x-meta text-right">
                <!--actions-->
                <?php if($comment->permission_delete_comment): ?>
                <span class="comment-actions js-hover-actions-target hidden">
                    <a href="javascript:void(0)" class="btn-outline-danger confirm-action-danger"
                        data-confirm-title="<?php echo e(cleanLang(__('lang.delete_comment'))); ?>" data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                        data-ajax-type="DELETE" data-url="<?php echo e(url('/comments/'.$comment->comment_id)); ?>">
                        <i class="sl-icon-trash"></i>
                    </a>
                </span>
                <?php endif; ?>
                <!--actions-->
                <span class="text-muted x-date"><small><?php echo e(date('m-d-Y H:i:s', strtotime($comment->comment_created))); ?></small></span>
            </div>
        </div>
        <div><?php echo _clean($comment->comment_text); ?></div>
        <?php if($comment->commentparent_id!=$comment->comment_id && $comment->commentparent_id!=$comment->commentsuperparent_id): ?>
            <a href="javascript:void(0)" onclick="letsReply(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="<?php echo e($comment->commentparent_id); ?>" data-comment-text="<?php echo e(mb_strimwidth($comment->comment_text, 0, 97, '...')); ?>">Reply</a>
        <?php else: ?>
            <a href="javascript:void(0)" onclick="letsReply(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="<?php echo e($comment->comment_id); ?>" data-comment-text="<?php echo e(mb_strimwidth($comment->comment_text, 0, 97, '...')); ?>">Reply</a>
        <?php endif; ?>
        <a href="javascript:void(0)" onclick="letsEdit(this)" class="mr-1 btn btn-rounded-x btn-danger waves-effect text-left replyme float-right" data-confirm-title="Edit Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="<?php echo e($comment->comment_id); ?>">Edit</a>

        <!-- <span class="js-hover-actions-target hidden">
            <a href="javascript:void(0)" class="btn-outline-danger replyme"
                data-confirm-title="Reply Comment" data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                data-ajax-type="" data-comment="<?php echo e($comment->comment_id); ?>" onclick="letsReply(this)">
                <i class="sl-icon-pencil"></i>
            </a>
        </span> -->
        <button class="react-btn" data-message-id="<?php echo e($comment->comment_id); ?>" style="background: #d8d8d869;border: none;border-radius: 20px;font-size: 14px;filter: grayscale(100%);">&#128512;</button>
        <div class="reactions">
            <?php if($comment->emoji_data): ?>
                <?php
                    //print_r($comment->emoji_data);
                ?>
                <?php $__currentLoopData = explode(';', $comment->emoji_data); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        echo '<span style="font-size: 14px;">'.$reaction.'</span><br/>';
                    ?>
                    
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
           
            <?php endif; ?>
            <span style="font-size: 14px;" id="<?php echo e($comment->comment_id); ?>_myEmoji"></span>
        </div>
    </div>
</div>
<!--each comment -->
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<script>
    function flashRed(redBox) {
      //redBox.classList.add('highlightMe');
      redBox.style.backgroundColor = "#f0d1d7";
      setTimeout(function(){
          redBox.style.backgroundColor = "";
          //redBox.classList.remove('highlightMe');
      }, 2000)
    }
    document.querySelectorAll('.react-btn').forEach(button => {
    const picker = new EmojiButton();

    button.addEventListener('click', (event) => {
        // Display the emoji picker
        picker.pickerVisible ? picker.hidePicker() : picker.showPicker(button);
    });

    // When an emoji is selected
    picker.on('emoji', emoji => {
        const reactionDiv = button.nextElementSibling; // Get the .reactions div
        //const emojiSpan = document.createElement('span');
        const emojiSpan = document.getElementById(button.dataset.messageId+'_myEmoji');
        emojiSpan.textContent = emoji;
        reactionDiv.appendChild(emojiSpan); // Append emoji to reactions
        //alert(button.dataset.messageId);
        $.ajax({
               type:'POST',
               url:'/reaction',
               data: {
                "_token": "<?php echo e(csrf_token()); ?>",
                "reaction": emoji,
                "reaction_resource_type":"comment",
                "reaction_resource_id": button.dataset.messageId
                },
               success:function(data) {
                   console.log(data.msg);
               }
            });
        //alert(emoji);
        // You can also send this emoji to your server here
    });
});
</script><?php /**PATH /home/wec24/public_html/application/resources/views/pages/comments/components/ajax.blade.php ENDPATH**/ ?>