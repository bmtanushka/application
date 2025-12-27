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
            {!! _clean(substr($comment->pcmttxt, 0, 50)) !!}.. click for more
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
            <a href="javascript:void(0)" onclick="letsReply3(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right btn-sm" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment->comment_id }}" data-comment-field="file-{{ $comment->commentresource_id }}-comment-tinmyce-container" data-comment-text="{{ mb_strimwidth($comment->comment_text, 0, 97, '...') }}" data-post-button="#file-{{ $comment->commentresource_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $comment->commentresource_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $comment->commentresource_id }}-replying_tsk_cmt_wrapper">Reply</a>
        @else
            <a href="javascript:void(0)" onclick="letsReply3(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right btn-sm" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment->comment_id }}" data-comment-field="file-{{ $comment->commentresource_id }}-comment-tinmyce-container" data-comment-text="{{ mb_strimwidth($comment->comment_text, 0, 97, '...') }}" data-post-button="#file-{{ $comment->commentresource_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $comment->commentresource_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $comment->commentresource_id }}-replying_tsk_cmt_wrapper">Reply</a>
        @endif
            <a href="javascript:void(0)" onclick="letsEdit3(this)" class="mr-1 btn btn-rounded-x btn-danger waves-effect text-left replyme float-right btn-sm" data-confirm-title="Edit Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment->comment_id }}" data-comment-field="file-{{ $comment->commentresource_id }}-comment-tinmyce-container" data-post-button="#file-{{ $comment->commentresource_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $comment->commentresource_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $comment->commentresource_id }}-replying_tsk_cmt_wrapper">Edit</a>
    </div>
</div>
@endforeach