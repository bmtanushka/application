@foreach($comments as $comment)
<!-- each comment -->
<!-- <div class="display-flex flex-row comment-row {{($comment->commentparent_id==$comment->comment_id && $comment->commentparent_id!=$comment->commentsuperparent_id) ? 'reply-comment':''}} {{($comment->commentparent_id!=$comment->comment_id && $comment->commentparent_id!=$comment->commentsuperparent_id) ? 'reply-reply-comment':''}}" id="comment_{{ $comment->comment_id }}"> -->
<div class="display-flex flex-row comment-row" id="comment_{{ $comment->comment_id }}">
    <div class="p-2">
        <img src="{{ getUsersAvatar($comment->avatar_directory, $comment->avatar_filename)  }}"
            class="img-circle" alt="user" width="40">
    </div>
    <div class="comment-text w-100 js-hover-actions">
        @if($comment->pcmttxt != null && $comment->pcmtid != $comment->comment_id)
        <div class="x-text opacity-5" onclick="document.getElementById('comment_{{ $comment->pcmtid }}').scrollIntoView({block: 'start', behavior: 'smooth'});flashRed(document.getElementById('comment_{{ $comment->pcmtid }}'));">
            {!! _clean(substr(strip_tags($comment->pcmttxt), 0, 50)) !!}.. click to view the comment replied
        </div>
        @endif
        <div class="row">
            <div class="col-sm-6 x-name">{{ $comment->first_name ?? runtimeUnkownUser() }}</div>
            <div class="col-sm-6 x-meta text-right">
                <!--actions-->
                @if($comment->permission_delete_comment)
                <span class="comment-actions js-hover-actions-target hidden">
                    <a href="javascript:void(0)" class="btn-outline-danger confirm-action-danger"
                        data-confirm-title="{{ cleanLang(__('lang.delete_comment')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-ajax-type="DELETE" data-url="{{ url('/comments/'.$comment->comment_id) }}">
                        <i class="sl-icon-trash"></i>
                    </a>
                </span>
                @endif
                <!--actions-->
                <span class="text-muted x-date"><small>{{-- runtimeDateAgo($comment->comment_created) --}}{{ date('m-d-Y H:i:s', strtotime($comment->comment_created)) }}</small></span>
            </div>
        </div>
        <div>{!! _clean($comment->comment_text) !!}</div>
        @if($comment->commentparent_id!=$comment->comment_id && $comment->commentparent_id!=$comment->commentsuperparent_id)
            <a href="javascript:void(0)" onclick="letsReply(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment->commentparent_id }}" data-comment-text="{{ mb_strimwidth($comment->comment_text, 0, 97, '...') }}">Reply</a>
        @else
            <a href="javascript:void(0)" onclick="letsReply(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment->comment_id }}" data-comment-text="{{ mb_strimwidth($comment->comment_text, 0, 97, '...') }}">Reply</a>
        @endif
        <a href="javascript:void(0)" onclick="letsEdit(this)" class="mr-1 btn btn-rounded-x btn-danger waves-effect text-left replyme float-right" data-confirm-title="Edit Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment->comment_id }}">Edit</a>

        <!-- <span class="js-hover-actions-target hidden">
            <a href="javascript:void(0)" class="btn-outline-danger replyme"
                data-confirm-title="Reply Comment" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                data-ajax-type="" data-comment="{{ $comment->comment_id }}" onclick="letsReply(this)">
                <i class="sl-icon-pencil"></i>
            </a>
        </span> -->
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
<!--each comment -->
@endforeach
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