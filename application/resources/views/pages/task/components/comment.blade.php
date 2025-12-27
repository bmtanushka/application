@foreach($comments as $comment)
<!-- <div class="display-flex flex-row comment-row {{($comment->commentparent_id==$comment->comment_id && $comment->commentparent_id!=$comment->commentsuperparent_id) ? 'reply-comment':''}} {{($comment->commentparent_id!=$comment->comment_id && $comment->commentparent_id!=$comment->commentsuperparent_id) ? 'reply-reply-comment':''}}" id="card_comment_{{ $comment->comment_id }}"> -->
<div class="display-flex flex-row comment-row" id="card_comment_{{ $comment->comment_id }}">    
    <div class="p-2 comment-avatar">
        <img src="{{ getUsersAvatar($comment->avatar_directory, $comment->avatar_filename) }}" class="img-circle"
            alt="{{ $comment->first_name ?? runtimeUnkownUser() }}" width="40">
    </div>
    <div class="comment-text w-100 js-hover-actions">
        @if($comment->pcmttxt != null && $comment->pcmtid != $comment->comment_id)
        <div class="x-text opacity-5" onclick="document.getElementById('card_comment_{{ $comment->pcmtid }}').scrollIntoView();flashRed(document.getElementById('card_comment_{{ $comment->pcmtid }}'));">
            {!! _clean(substr(strip_tags($comment->pcmttxt), 0, 50)) !!}.. click for more
        </div>
        @endif
        <div class="row">
            <div class="col-sm-6 x-name">{{ $comment->first_name ?? runtimeUnkownUser() }}</div>
            <div class="col-sm-6 x-meta text-right">
                <!--meta-->
                <span class="x-date"><small>{{-- runtimeDateAgo($comment->comment_created) --}} {{ date('m-d-Y H:i:s', strtotime($comment->comment_created)) }}</small></span>
                <!--actions: delete-->
                @if($comment->permission_delete_comment)
                <span class="comment-actions"> |
                    <a href="javascript:void(0)" class="js-delete-ux-confirm confirm-action-danger text-danger"
                        data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-ajax-type="DELETE" data-parent-container="card_comment_{{ $comment->comment_id }}"
                        data-progress-bar="hidden"
                        data-url="{{ urlResource('/tasks/delete-comment/'.$comment->comment_id) }}">
                        <small>{{ cleanLang(__('lang.delete')) }}</small>
                    </a>
                </span>
                @endif
            </div>
        </div>
        <div class="p-t-4">{!! clean($comment->comment_text) !!}</div>
        @if($comment->commentparent_id!=$comment->comment_id && $comment->commentparent_id!=$comment->commentsuperparent_id)
            <a href="javascript:void(0)" onclick="letsReply2(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right btn-sm" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment->commentparent_id }}" data-comment-text="{{ mb_strimwidth($comment->comment_text, 0, 97, '...') }}">Reply</a>
        @else
            <a href="javascript:void(0)" onclick="letsReply2(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right btn-sm" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment->comment_id }}" data-comment-text="{{ mb_strimwidth($comment->comment_text, 0, 97, '...') }}">Reply</a>
        @endif
        @if($comment->comment_creatorid==auth()->user()['id'])
        <a href="javascript:void(0)" onclick="letsEdit2(this)" class="mr-1 btn btn-rounded-x btn-danger waves-effect text-left replyme float-right btn-sm" data-confirm-title="Edit Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment->comment_id }}">Edit</a>
        @endif
        <button class="react-btn" data-message-id="{{ $comment->comment_id }}" style="background: #d8d8d869;border: none;border-radius: 20px;font-size: 14px;filter: grayscale(100%);">&#128512;</button>
        <div class="reactions">
            @if ($comment->emoji_data)
                @php
                    //print_r($comment->emoji_data);
                @endphp
                @foreach (explode(';', $comment->emoji_data) as $reaction)
                    @php
                        echo '<span style="font-size: 14px;">'.$reaction.'</span><br/>';
                    @endphp
                    
                @endforeach
           
            @endif
            <span style="font-size: 14px;" id="{{ $comment->comment_id }}_myEmoji"></span>
        </div>
    </div>
</div>
@endforeach
<script>
    
    function flashRed(redBox) {
      //redBox.classList.add('highlightMe');
      redBox.style.backgroundColor = "#f0d1d7";
      setTimeout(function(){
          redBox.style.backgroundColor = "#ebf2f5";
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
                "_token": "{{ csrf_token() }}",
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
</script>