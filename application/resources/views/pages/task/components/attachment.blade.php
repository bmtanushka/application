@foreach($attachments as $attachment)
@if($attachment->attachment_completed != 1)
<div class="col-sm-12" id="card_attachment_{{ $attachment->attachment_uniqiueid }}">
    <div class="file-attachment m-b-25">
        @if($attachment->attachment_type == 'image')
        <!--dynamic inline style-->
        <div class="">
            <a class="fancybox preview-image-thumb"
                href="storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}"
                title="{{ str_limit($attachment->attachment_filename, 60) }}"
                alt="{{ str_limit($attachment->attachment_filename, 60) }}" style="height: auto;">
                <img class="x-image"
                    src="{{ url('storage/files/' . $attachment->attachment_directory .'/'. $attachment->attachment_thumbname) }}">
            </a>
            <a type="button" title="" href="https://www.task-meister.com/storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}" target="_blank" style="display: flex;float: left;">
                <i class="sl-icon-eye"></i>
            </a>
            <a type="button" class="" href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}" download style="display:flex;margin-left: 25px;">
                <i class="sl-icon-cloud-download"></i>
            </a>
        </div>
        @else
        <div class="x-image">
            <a class="preview-image-thumb" href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}"
                download>
                {{ $attachment->attachment_extension }}
            </a>
            <a type="button" title="" href="https://www.task-meister.com/storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}" target="_blank" style="display: flex;float: left;">
                <i class="sl-icon-eye"></i>
            </a>
            <a type="button" class="" href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}" download style="display:flex;margin-left: 25px;">
                <i class="sl-icon-cloud-download"></i>
            </a>
        </div>
        @endif
        <div class="x-details">
            <div><span class="x-meta">{{ $attachment->first_name ?? runtimeUnkownUser() }}</span>
                [{{ date('m-d-Y H:i:s', strtotime($attachment->attachment_created)) }} {{-- runtimeDateAgo($attachment->attachment_created) --}}]</div>
            <div class="x-name"><span
                    title="{{ $attachment->attachment_filename }}">{{ str_limit($attachment->attachment_filename, 60) }}</span>
            </div>
            <div class="x-tags">
                @foreach($attachment->tags as $tag)
                <span class="x-each-tag">{{ $tag->tag_title }}</span>  
                @endforeach
            </div>
            <div class="x-actions"><strong>
                    <!--action: download-->
                    <a href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}"
                        download>{{ cleanLang(__('lang.download')) }} <span class="x-icons"><i
                                class="ti-download"></i></span></strong></a>

                <!--action: cover image-->
                @if($attachment->permission_set_cover)
                <!--add cover---->
                <span id="cover_image_add_{{ $attachment->attachment_id }}"
                    class="cover_image_buttons cover_image_buttons_add js-add-cover-image {{ runtimeCoverImageAddButton($attachment->attachment_uniqiueid, $attachment->task_cover_image_uniqueid) }}"
                    data-image-url="storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}"
                    data-progress-bar="hidden" data-add-cover-button="cover_image_add_{{ $attachment->attachment_id }}"
                    data-remove-cover-button="cover_image_remove_{{ $attachment->attachment_id }}"
                    data-cover-remove-button-url="{{ url('/tasks/'.$attachment->attachmentresource_id.'/remove-cover-image') }}"
                    data-id="{{ $attachment->attachmentresource_id }}"
                    data-url="{{ url('/tasks/'.$attachment->attachmentresource_id.'/add-cover-image?imageid='.$attachment->attachment_uniqiueid) }}">
                    |
                    <strong><a href="javascript:void(0)">@lang('lang.set_cover')</a>
                    </strong></span>
                <!--remove cover---->
                <span id="cover_image_remove_{{ $attachment->attachment_id }}"
                    class="cover_image_buttons cover_image_buttons_remove js-remove-cover-image  {{ runtimeCoverImageRemoveButton($attachment->attachment_uniqiueid, $attachment->task_cover_image_uniqueid) }}"
                    data-progress-bar="hidden" data-add-cover-button="cover_image_add_{{ $attachment->attachment_id }}"
                    data-remove-cover-button="cover_image_remove_{{ $attachment->attachment_id }}"
                    data-id="{{ $attachment->attachmentresource_id }}"
                    data-url="{{ url('/tasks/'.$attachment->attachmentresource_id.'/remove-cover-image') }}">
                    |
                    <strong><a href="javascript:void(0)">@lang('lang.remove_cover')</a>
                    </strong></span>
                @endif

                <!--action: delete-->
                @if($attachment->permission_delete_attachment)
                <span> |
                    <strong><a href="javascript:void(0)" class="text-danger js-delete-ux-confirm confirm-action-danger"
                            data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                            data-parent-container="card_attachment_{{ $attachment->attachment_uniqiueid }}"
                            data-progress-bar="hidden"
                            data-url="{{ urlResource('/tasks/delete-attachment/'.$attachment->attachment_uniqiueid) }}">{{ cleanLang(__('lang.delete')) }}</a>
                    </strong></span>
                @endif
            </div>
            <button class="atc-react-btn" data-message-id="{{ $attachment->attachment_id }}" style="background: #d8d8d869;border: none;border-radius: 20px;font-size: 14px;filter: grayscale(100%);">&#128512;</button>
            <div class="reactions">
                @if ($attachment->emoji_data)
                    @php
                        //print_r($attachment->emoji_data);
                        $p_reaction = "";
                    @endphp
                    @foreach (explode(';', $attachment->emoji_data) as $reaction)
                        @php
                            if($p_reaction != $reaction)
                                echo '<span style="font-size: 14px;">'.$reaction.'</span><br/>';
                            $p_reaction = $reaction;
                        @endphp
                        
                    @endforeach
               
                @endif
                <span style="font-size: 14px;" id="{{ $attachment->attachment_id }}_myEmoji"></span>
            </div>
        </div>
    </div>
    <div class="card-comment">
        
        <div class="post-comment" id="file-{{ $attachment->attachment_id }}-comment-form">
            <div id="file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper" class="file-replying_tsk_cmt_wrapper">
                <span type="button" class="btn-close" onclick="replyTskCmtClosex(this)" data-post-button="#file-{{ $attachment->attachment_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper" aria-label="Close">✖</span>
                <div id="file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" class=""></div>
            </div>
            <!--placeholder textbox-->
            <div class="x-message-field x-message-field-placeholder m-b-10" id="file-{{ $attachment->attachment_id }}-comment-placeholder-input-container"
                data-show-element-container="file-{{ $attachment->attachment_id }}-comment-tinmyce-container">
                <textarea class="form-control form-control-sm w-100 file-coment-placeholder-input" rows="1"
                    id="file-{{ $attachment->attachment_id }}-coment-placeholder-input" onclick="openEditor({{ $attachment->attachment_id }})">{{ cleanLang(__('lang.post_a_comment')) }}...</textarea>
            </div>
            <!--rich text editor-->
            <div class="x-message-field hidden" id="file-{{ $attachment->attachment_id }}-comment-tinmyce-container">
                <!--tinymce editor-->
                <textarea class="form-control form-control-sm w-99 file-comment-tinmyce" rows="2" id="file-{{ $attachment->attachment_id }}-comment-tinmyce"
                    name="comment_text" id="comment_text"></textarea>
                <!--close button-->
                <div class="x-button p-t-10 p-b-10 text-right">
                    <button type="button" class="btn btn-default btn-sm" onclick="closeEditor({{ $attachment->attachment_id }})" id="file-{{ $attachment->attachment_id }}-comment-close-button">
                        {{ cleanLang(__('lang.close')) }}
                    </button>
                    <!--submit button-->
                    <button type="button" class="btn btn-danger btn-sm x-submit-button" id="file-{{ $attachment->attachment_id }}-comment-post-button"
                        data-url="{{ urlResource('/tasks/'.$attachment->attachment_id.'/file-comment') }}" data-type="form" data-ajax-type="post"
                        data-form-id="file-{{ $attachment->attachment_id }}-comment-form" data-loading-target="card-coment-placeholder-input-container" 
                        onclick="submitEditor({{ $attachment->attachment_id }}, this)">
                        {{ cleanLang(__('lang.post')) }}
                    </button>
                </div>
            </div>
        </div>
        
        <div id="card-comments-container-{{ $attachment->attachment_id }}">
        
            @php
                //print_r($attachment)
            @endphp
            @if ($attachment->comments_data)
                @php
                    //print_r($attachment->comments_data);
                    $p_comment = "";
                @endphp
           
            @endif
            @if ($attachment->comments_data)
                @php
                    $comments_array = explode(';|;', $attachment->comments_data);
                    $reversed = array_reverse($comments_array);
                    $comments_array = array_unique($reversed);
                @endphp
                @foreach ($comments_array as $comment)
                @php
                    $comment_array = explode('~|~', $comment);
                @endphp
                <!-- repeating block -->
                <div class="display-flex flex-row comment-row" id="card_comment_{{ $comment_array[0] }}">    
                    <div class="p-2 comment-avatar">
                        <img src="{{ getUsersAvatar($attachment->avatar_directory, $attachment->avatar_filename) }}" class="img-circle"
                            alt="{{ $comment_array[3] ?? runtimeUnkownUser() }}" width="40">
                    </div>
                    <div class="comment-text w-100 js-hover-actions">
                        @if($comment_array[2] != null && $comment_array[4] != $comment_array[0])
                        <div class="x-text opacity-5" onclick="document.getElementById('card_comment_{{ $comment_array[4] }}').scrollIntoView();flashRed(document.getElementById('card_comment_{{ $comment_array[4] }}'));">
                            {!! _clean(substr($comment_array[2], 0, 50)) !!}.. click for more
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-sm-6 x-name">{{ $comment_array[3] ?? runtimeUnkownUser() }}</div>
                            <div class="col-sm-6 x-meta text-right">
                                <!--meta-->
                                <span class="x-date"><small>{{-- runtimeDateAgo($comment_array[6]) --}} {{ date('m-d-Y H:i:s', strtotime($comment_array[6])) }}</small></span>
                                <!--actions: delete-->
                                @if($comment_array[7]==auth()->user()['id'])
                                <span class="comment-actions"> |
                                    <a href="javascript:void(0)" class="js-delete-ux-confirm confirm-action-danger text-danger"
                                        data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                                        data-ajax-type="DELETE" data-parent-container="card_comment_{{ $comment_array[0] }}"
                                        data-progress-bar="hidden"
                                        data-url="{{ urlResource('/tasks/delete-comment/'.$comment_array[0]) }}">
                                        <small>{{ cleanLang(__('lang.delete')) }}</small>
                                    </a>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="p-t-4">{!! clean($comment_array[1]) !!}</div>
                        @if($comment_array[4] != $comment_array[0] && $comment_array[4] != $comment_array[5])
                            <a href="javascript:void(0)" onclick="letsReply3(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right btn-sm" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment_array[4] }}" data-comment-field="file-{{ $attachment->attachment_id }}-comment-tinmyce-container" data-comment-text="{{ mb_strimwidth($comment_array[1], 0, 97, '...') }}" data-post-button="#file-{{ $attachment->attachment_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper">Reply</a>
                        @else
                            <a href="javascript:void(0)" onclick="letsReply3(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right btn-sm" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment_array[0] }}" data-comment-field="file-{{ $attachment->attachment_id }}-comment-tinmyce-container" data-comment-text="{{ mb_strimwidth($comment_array[1], 0, 97, '...') }}" data-post-button="#file-{{ $attachment->attachment_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper">Reply</a>
                        @endif
                        @if($comment_array[7]==auth()->user()['id'])
                            <a href="javascript:void(0)" onclick="letsEdit3(this)" class="mr-1 btn btn-rounded-x btn-danger waves-effect text-left replyme float-right btn-sm" data-confirm-title="Edit Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment_array[0] }}" data-comment-field="file-{{ $attachment->attachment_id }}-comment-tinmyce-container" data-post-button="#file-{{ $attachment->attachment_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper">Edit</a>
                        @endif
                    </div>
                </div>
                <!-- end repeating block -->
                @endforeach
           
            @endif

        </div>
        
    </div>
    
</div>
@endif
@endforeach

<div class="x-heading"><i class="mdi mdi-cloud-download"></i>Completed Files</div>

@foreach($attachments as $attachment)
@php //print_r($attachment); @endphp
@if($attachment->attachment_completed == 1)
<div class="col-sm-12" id="card_attachment_{{ $attachment->attachment_uniqiueid }}">
    <div class="file-attachment m-b-25">
        @if($attachment->attachment_type == 'image')
        <!--dynamic inline style-->
        <div class="">
            <a class="fancybox preview-image-thumb"
                href="storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}"
                title="{{ str_limit($attachment->attachment_filename, 60) }}"
                alt="{{ str_limit($attachment->attachment_filename, 60) }}" style="height: auto;">
                <img class="x-image"
                    src="{{ url('storage/files/' . $attachment->attachment_directory .'/'. $attachment->attachment_thumbname) }}">
            </a>
            <a type="button" title="" href="https://www.task-meister.com/storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}" target="_blank" style="display: flex;float: left;">
                <i class="sl-icon-eye"></i>
            </a>
            <a type="button" class="" href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}" download style="display:flex;margin-left: 25px;">
                <i class="sl-icon-cloud-download"></i>
            </a>
        </div>
        @else
        <div class="x-image">
            <a class="preview-image-thumb" href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}"
                download>
                {{ $attachment->attachment_extension }}
            </a>
            <a type="button" title="" href="https://www.task-meister.com/storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}" target="_blank" style="display: flex;float: left;">
                <i class="sl-icon-eye"></i>
            </a>
            <a type="button" class="" href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}" download style="display:flex;margin-left: 25px;">
                <i class="sl-icon-cloud-download"></i>
            </a>
        </div>
        @endif
        <div class="x-details">
            <div><span class="x-meta">{{ $attachment->first_name ?? runtimeUnkownUser() }}</span>
                [{{ date('m-d-Y H:i:s', strtotime($attachment->attachment_created)) }}{{-- runtimeDateAgo($attachment->attachment_created) --}}]</div>
            <div class="x-name"><span
                    title="{{ $attachment->attachment_filename }}">{{ str_limit($attachment->attachment_filename, 60) }}</span>
            </div>
            <div class="x-tags">
                @foreach($attachment->tags as $tag)
                <span class="x-each-tag">{{ $tag->tag_title }}</span>  
                @endforeach
            </div>
            <div class="x-actions"><strong>
                    <!--action: download-->
                    <a href="tasks/download-attachment/{{ $attachment->attachment_uniqiueid }}"
                        download>{{ cleanLang(__('lang.download')) }} <span class="x-icons"><i
                                class="ti-download"></i></span></strong></a>

                <!--action: cover image-->
                @if($attachment->permission_set_cover)
                <!--add cover---->
                <span id="cover_image_add_{{ $attachment->attachment_id }}"
                    class="cover_image_buttons cover_image_buttons_add js-add-cover-image {{ runtimeCoverImageAddButton($attachment->attachment_uniqiueid, $attachment->task_cover_image_uniqueid) }}"
                    data-image-url="storage/files/{{ $attachment->attachment_directory }}/{{ $attachment->attachment_filename  }}"
                    data-progress-bar="hidden" data-add-cover-button="cover_image_add_{{ $attachment->attachment_id }}"
                    data-remove-cover-button="cover_image_remove_{{ $attachment->attachment_id }}"
                    data-cover-remove-button-url="{{ url('/tasks/'.$attachment->attachmentresource_id.'/remove-cover-image') }}"
                    data-id="{{ $attachment->attachmentresource_id }}"
                    data-url="{{ url('/tasks/'.$attachment->attachmentresource_id.'/add-cover-image?imageid='.$attachment->attachment_uniqiueid) }}">
                    |
                    <strong><a href="javascript:void(0)">@lang('lang.set_cover')</a>
                    </strong></span>
                <!--remove cover---->
                <span id="cover_image_remove_{{ $attachment->attachment_id }}"
                    class="cover_image_buttons cover_image_buttons_remove js-remove-cover-image  {{ runtimeCoverImageRemoveButton($attachment->attachment_uniqiueid, $attachment->task_cover_image_uniqueid) }}"
                    data-progress-bar="hidden" data-add-cover-button="cover_image_add_{{ $attachment->attachment_id }}"
                    data-remove-cover-button="cover_image_remove_{{ $attachment->attachment_id }}"
                    data-id="{{ $attachment->attachmentresource_id }}"
                    data-url="{{ url('/tasks/'.$attachment->attachmentresource_id.'/remove-cover-image') }}">
                    |
                    <strong><a href="javascript:void(0)">@lang('lang.remove_cover')</a>
                    </strong></span>
                @endif

                <!--action: delete-->
                @if($attachment->permission_delete_attachment)
                <span> |
                    <strong><a href="javascript:void(0)" class="text-danger js-delete-ux-confirm confirm-action-danger"
                            data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                            data-parent-container="card_attachment_{{ $attachment->attachment_uniqiueid }}"
                            data-progress-bar="hidden"
                            data-url="{{ urlResource('/tasks/delete-attachment/'.$attachment->attachment_uniqiueid) }}">{{ cleanLang(__('lang.delete')) }}</a>
                    </strong></span>
                @endif
            </div>
            <button class="atc-react-btn" data-message-id="{{ $attachment->attachment_id }}" style="background: #d8d8d869;border: none;border-radius: 20px;font-size: 14px;filter: grayscale(100%);">&#128512;</button>
            <div class="reactions">
                @if ($attachment->emoji_data)
                    @php
                        //print_r($attachment->emoji_data);
                    @endphp
                    @foreach (explode(';', $attachment->emoji_data) as $reaction)
                        @php
                            echo '<span style="font-size: 14px;">'.$reaction.'</span><br/>';
                        @endphp
                        
                    @endforeach
               
                @endif
                <span style="font-size: 14px;" id="{{ $attachment->attachment_id }}_myEmoji"></span>
            </div>
        </div>
    </div>
    
    <div class="card-comment">
        
        <div class="post-comment" id="file-{{ $attachment->attachment_id }}-comment-form">
            <div id="file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper" class="file-replying_tsk_cmt_wrapper">
                <span type="button" class="btn-close" onclick="replyTskCmtClosex(this)" data-post-button="#file-{{ $attachment->attachment_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper" aria-label="Close">✖</span>
                <div id="file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" class=""></div>
            </div>
            <!--placeholder textbox-->
            <div class="x-message-field x-message-field-placeholder m-b-10" id="file-{{ $attachment->attachment_id }}-comment-placeholder-input-container"
                data-show-element-container="file-{{ $attachment->attachment_id }}-comment-tinmyce-container">
                <textarea class="form-control form-control-sm w-100 file-coment-placeholder-input" rows="1"
                    id="file-{{ $attachment->attachment_id }}-coment-placeholder-input" onclick="openEditor({{ $attachment->attachment_id }})">{{ cleanLang(__('lang.post_a_comment')) }}...</textarea>
            </div>
            <!--rich text editor-->
            <div class="x-message-field hidden" id="file-{{ $attachment->attachment_id }}-comment-tinmyce-container">
                <!--tinymce editor-->
                <textarea class="form-control form-control-sm w-99 file-comment-tinmyce" rows="2" id="file-{{ $attachment->attachment_id }}-comment-tinmyce"
                    name="comment_text" id="comment_text"></textarea>
                <!--close button-->
                <div class="x-button p-t-10 p-b-10 text-right">
                    <button type="button" class="btn btn-default btn-sm" onclick="closeEditor({{ $attachment->attachment_id }})" id="file-{{ $attachment->attachment_id }}-comment-close-button">
                        {{ cleanLang(__('lang.close')) }}
                    </button>
                    <!--submit button-->
                    <button type="button" class="btn btn-danger btn-sm x-submit-button" id="file-{{ $attachment->attachment_id }}-comment-post-button"
                        data-url="{{ urlResource('/tasks/'.$attachment->attachment_id.'/file-comment') }}" data-type="form" data-ajax-type="post"
                        data-form-id="file-{{ $attachment->attachment_id }}-comment-form" data-loading-target="card-coment-placeholder-input-container" 
                        onclick="submitEditor({{ $attachment->attachment_id }}, this)">
                        {{ cleanLang(__('lang.post')) }}
                    </button>
                </div>
            </div>
        </div>
        
        <div id="card-comments-container-{{ $attachment->attachment_id }}">
            @php
                //print_r($attachment)
            @endphp
            @if ($attachment->comments_data)
                @php
                    //print_r($attachment->emoji_data);
                    $p_comment = "";
                @endphp
           
            @endif
            @if ($attachment->comments_data)
                @php
                    $comments_array = explode(';|;', $attachment->comments_data);
                    $reversed = array_reverse($comments_array);
                    $comments_array = array_unique($reversed);
                @endphp
                @foreach ($comments_array as $comment)
                @php
                    $comment_array = explode('~|~', $comment);
                @endphp
                <!-- repeating block -->
                <div class="display-flex flex-row comment-row" id="card_comment_{{ $comment_array[0] }}">    
                    <div class="p-2 comment-avatar">
                        <img src="{{ getUsersAvatar($attachment->avatar_directory, $attachment->avatar_filename) }}" class="img-circle"
                            alt="{{ $comment_array[3] ?? runtimeUnkownUser() }}" width="40">
                    </div>
                    <div class="comment-text w-100 js-hover-actions">
                        @if($comment_array[2] != null && $comment_array[4] != $comment_array[0])
                        <div class="x-text opacity-5" onclick="document.getElementById('card_comment_{{ $comment_array[4] }}').scrollIntoView();flashRed(document.getElementById('card_comment_{{ $comment_array[4] }}'));">
                            {!! _clean(substr($comment_array[2], 0, 50)) !!}.. click for more
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-sm-6 x-name">{{ $comment_array[3] ?? runtimeUnkownUser() }}</div>
                            <div class="col-sm-6 x-meta text-right">
                                <!--meta-->
                                <span class="x-date"><small>{{-- runtimeDateAgo($comment_array[6]) --}} {{ date('m-d-Y H:i:s', strtotime($comment_array[6])) }}</small></span>
                                <!--actions: delete-->
                                @if($comment_array[7]==auth()->user()['id'])
                                <span class="comment-actions"> |
                                    <a href="javascript:void(0)" class="js-delete-ux-confirm confirm-action-danger text-danger"
                                        data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}" data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                                        data-ajax-type="DELETE" data-parent-container="card_comment_{{ $comment_array[0] }}"
                                        data-progress-bar="hidden"
                                        data-url="{{ urlResource('/tasks/delete-comment/'.$comment_array[0]) }}">
                                        <small>{{ cleanLang(__('lang.delete')) }}</small>
                                    </a>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="p-t-4">{!! clean($comment_array[1]) !!}</div>
                        @if($comment_array[4] != $comment_array[0] && $comment_array[4] != $comment_array[5])
                            <a href="javascript:void(0)" onclick="letsReply3(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right btn-sm" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment_array[4] }}" data-comment-field="file-{{ $attachment->attachment_id }}-comment-tinmyce-container" data-comment-text="{{ mb_strimwidth($comment_array[1], 0, 97, '...') }}" data-post-button="#file-{{ $attachment->attachment_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper">Reply</a>
                        @else
                            <a href="javascript:void(0)" onclick="letsReply3(this)" class="btn btn-rounded-x btn-info waves-effect text-left replyme float-right btn-sm" data-confirm-title="Reply Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment_array[0] }}" data-comment-field="file-{{ $attachment->attachment_id }}-comment-tinmyce-container" data-comment-text="{{ mb_strimwidth($comment_array[1], 0, 97, '...') }}" data-post-button="#file-{{ $attachment->attachment_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper">Reply</a>
                        @endif
                        @if($comment_array[7]==auth()->user()['id'])
                            <a href="javascript:void(0)" onclick="letsEdit3(this)" class="mr-1 btn btn-rounded-x btn-danger waves-effect text-left replyme float-right btn-sm" data-confirm-title="Edit Comment" data-confirm-text="Are you sure?" data-ajax-type="" data-comment="{{ $comment_array[0] }}" data-comment-field="file-{{ $attachment->attachment_id }}-comment-tinmyce-container" data-post-button="#file-{{ $attachment->attachment_id }}-comment-post-button" data-cmt-text-viewer="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_text_viewer" data-cmt-text-wrapper="#file-{{ $attachment->attachment_id }}-replying_tsk_cmt_wrapper">Edit</a>
                        @endif
                    </div>
                </div>
                <!-- end repeating block -->
                @endforeach
           
            @endif

        </div>
        
    </div>
    
</div>
@endif
@endforeach
<script>
    document.querySelectorAll('.atc-react-btn').forEach(button => {
    const pickerx = new EmojiButton();

    button.addEventListener('click', (event) => {
        // Display the emoji picker
        pickerx.pickerVisible ? pickerx.hidePicker() : pickerx.showPicker(button);
    });

    // When an emoji is selected
    pickerx.on('emoji', emoji => {
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
                "reaction_resource_type":"attachment",
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