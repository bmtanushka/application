@foreach($files as $file)
<!--each row-->
<tr id="file_{{ $file->file_id }}">
    @if(config('visibility.files_col_checkboxes'))
    <td class="files_col_checkbox checkitem" id="files_col_checkbox_{{ $file->file_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-files-{{ $file->file_id }}" name="ids[{{ $file->file_uniqueid }}]"
                class="listcheckbox listcheckbox-files filled-in chk-col-light-blue"
                data-actions-container-class="files-checkbox-actions-container">
            <label for="listcheckbox-files-{{ $file->file_id }}"></label>
        </span>
    </td>
    @endif
    <td class="files_col_file" id="files_col_file_{{ $file->file_id }}">
        
        @if($file->file_type == 'image')
        <!--dynamic inline style-->
        <div>
            <a class="fancybox preview-image-thumb"
                href="storage/files/{{ $file->file_directory }}/{{ $file->file_filename  }}"
                title="{{ str_limit($file->file_filename, 60) }}" alt="{{ str_limit($file->file_filename, 60) }}">
                <img class="lists-table-thumb"
                    src="{{ url('storage/files/' . $file->file_directory .'/'. $file->file_thumbname) }}">
            </a>
            <a type="button" title="" href="https://www.task-meister.com/storage/files/{{$file->file_directory}}/{{$file->file_filename }}" target="_blank">
                <i class="sl-icon-eye"></i>
            </a>&nbsp;&nbsp;<a type="button" class="" href="files/download?file_id={{ $file->file_uniqueid }}" download>
                <i class="sl-icon-cloud-download"></i>
            </a>
            <button class="react-btn" data-message-id="{{ $file->file_id }}" style="background: #d8d8d869;border: none;border-radius: 20px;font-size: 14px;filter: grayscale(100%);">&#128512;</button>
            <div class="reactions">
                @if ($file->emoji_data)
                    @php
                        //print_r($comment->emoji_data);
                    @endphp
                    @foreach (explode(';', $file->emoji_data) as $reaction)
                        @php
                            echo '<span style="font-size: 14px;">'.$reaction.'</span><br/>';
                        @endphp
                        
                    @endforeach
               
                @endif
                <span style="font-size: 14px;" id="{{ $file->file_id }}_myEmoji"></span>
            </div>
        </div>
        @elseif($file->file_extension == 'pdf')
        <!--dynamic inline style-->
        <div>
            <embed src="https://www.task-meister.com/storage/files/{{$file->file_uniqueid}}/{{$file->file_filename }}" width="100" height="75" type="application/pdf"><br/>
            <a type="button" title="" href="https://www.task-meister.com/storage/files/{{$file->file_uniqueid}}/{{$file->file_filename }}" target="_blank">
                <i class="sl-icon-eye"></i>
            </a>&nbsp;&nbsp;<a type="button" class="" href="files/download?file_id={{ $file->file_uniqueid }}" download>
                <i class="sl-icon-cloud-download"></i>
            </a>
            <button class="react-btn" data-message-id="{{ $file->file_id }}" style="background: #d8d8d869;border: none;border-radius: 20px;font-size: 14px;filter: grayscale(100%);">&#128512;</button>
            <div class="reactions">
                @if ($file->emoji_data)
                    @php
                        //print_r($comment->emoji_data);
                    @endphp
                    @foreach (explode(';', $file->emoji_data) as $reaction)
                        @php
                            echo '<span style="font-size: 14px;">'.$reaction.'</span><br/>';
                        @endphp
                        
                    @endforeach
               
                @endif
                <span style="font-size: 14px;" id="{{ $file->file_id }}_myEmoji"></span>
            </div>
        </div>
        @else
        <div class="lists-table-thumb">
            <a class="preview-image-thumb" href="files/download?file_id={{ $file->file_uniqueid }}" target="_blank">
                {{ $file->file_extension }}
            </a>
            <a type="button" class="" style="height:15px !important;display: grid;" href="files/download?file_id={{ $file->file_uniqueid }}" download>
                <i class="sl-icon-cloud-download"></i>
            </a>
            <button class="react-btn" data-message-id="{{ $file->file_id }}" style="background: #d8d8d869;border: none;border-radius: 20px;font-size: 14px;filter: grayscale(100%);">&#128512;</button>
            <div class="reactions">
                @if ($file->emoji_data)
                    @php
                        //print_r($comment->emoji_data);
                    @endphp
                    @foreach (explode(';', $file->emoji_data) as $reaction)
                        @php
                            echo '<span style="font-size: 14px;">'.$reaction.'</span><br/>';
                        @endphp
                        
                    @endforeach
               
                @endif
                <span style="font-size: 14px;" id="{{ $file->file_id }}_myEmoji"></span>
            </div>
        </div>
        @endif
    </td>
    <td class="files_col_file_name" id="files_col_file_name_{{ $file->file_id }}">
        <a href="files/download?file_id={{ $file->file_uniqueid }}" title="{{ $file->file_filename ?? '---' }}"
            download>{{ str_limit($file->file_filename ?? '---', 25) }}</a>
    </td>
    <td class="files_col_added_by" id="files_col_added_by_{{ $file->file_id }}">
        <img src="{{ getUsersAvatar($file->avatar_directory, $file->avatar_filename) }}" alt="user"
            class="img-circle avatar-xsmall">
        {{ $file->first_name ?? runtimeUnkownUser() }}
    </td>
    <td class="files_col_size" id="files_col_size_{{ $file->file_id }}">{{ $file->file_size }}</td>
    <td class="files_col_date" id="files_col_date_{{ $file->file_id }}">
        {{-- runtimeDate($file->file_created) --}} {{ date('m-d-Y H:i:s', strtotime($file->file_created)) }}
    </td>

    <td class="files_col_tags {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
        <!--tag-->
        @if(count($file->tags ?? []) > 0)
        @foreach($file->tags->take(1) as $tag)
        <span class="label label-outline-default">{{ str_limit($tag->tag_title, 15) }}</span>
        @endforeach
        @else
        <span>---</span>
        @endif
        <!--/#tag-->

        <!--more tags (greater than tags->take(x) number above -->
        @if(count($file->tags ?? []) > 1)
        @php $tags = $file->tags; @endphp
        @include('misc.more-tags')
        @endif
        <!--more tags-->
    </td>

    <td class="files_col_action actions_column" id="files_col_action_{{ $file->file_id }}">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">

            <!--clent visibility-->
            @if(config('visibility.files_col_visibility'))
            <span class="switch switch-small" id="file_edit_visibility_{{ $file->file_id }}">
                <label>
                    <input {{ runtimePrechecked($file['file_visibility_client'] ?? '') }} type="checkbox"
                        class="js-ajax-ux-request-default" name="visible_to_client"
                        id="visible_to_client_{{ $file->file_id }}" data-url="{{ url('/files') }}/{{ $file->file_id }}"
                        data-ajax-type="PUT" data-type="form" data-form-id="file_edit_visibility_{{ $file->file_id }}"
                        data-progress-bar='hidden'>
                    <span data-toggle="tooltip" data-placement="top" title="Make visible to client" class="lever switch-col-light-blue"></span>
                </label>
            </span>
            @endif

            <!--set as cover-->
            @if(config('visibility.set_image_as_project_cover'))
            @if($file->file_type == 'image')
            <button type="button" title="@lang('lang.set_as_cover_image')"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm confirm-action-info m-t-2"
                data-confirm-title="@lang('lang.set_as_cover_image')" data-confirm-text="@lang('lang.are_you_sure')"
                data-url="{{ url('projects/'.$file->fileresource_id.'/set-cover-image?file='.$file->file_id) }}">
                <i class="sl-icon-picture"></i>
            </button>
            @else
            <span class="btn btn-outline-default btn-circle btn-sm disabled {{ runtimePlaceholdeActionsButtons() }}"
                data-toggle="tooltip" title="{{ cleanLang(__('lang.actions_not_available')) }}"><i
                    class="sl-icon-picture"></i></span>
            @endif
            @endif


            <!--copy file-->
            @if(auth()->user()->is_team)
            <button type="button"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                title="@lang('lang.copy_file')" data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('files/copy') }}" data-ajax-type="GET" data-loading-target="commonModalBody"
                data-modal-title="@lang('lang.copy_files')" data-action-type="form" data-action-form-id="main-body"
                data-action-url="{{ urlResource('files/copy?id='.$file->file_uniqueid) }}" data-action-method="POST"
                data-modal-size="modal-sm" data-action-ajax-class="ajax-request" data-button-loading-annimation="yes"
                data-action-ajax-loading-target="commonModalBody">
                <i class="mdi mdi-content-copy"></i>
            </button>
            @endif

            <!--delete-->
            @if($file->permission_delete_file)
            <button type="button" title="@lang('lang.delete')"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="@lang('lang.delete_file')" data-confirm-text="@lang('lang.are_you_sure')"
                data-ajax-type="DELETE" data-url="{{ url('/') }}/files/{{ $file->file_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @else
            <!--optionally show disabled button?-->
            <span class="btn btn-outline-default btn-circle btn-sm disabled  {{ runtimePlaceholdeActionsButtons() }}"
                data-toggle="tooltip" title="@lang('lang.actions_not_available')"><i class="sl-icon-trash"></i></span>
            @endif
            <a href="files/download?file_id={{ $file->file_uniqueid }}" title="@lang('lang.download')"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm" download>
                <i class="ti-download"></i>
            </a>

            <!--more button (team)-->
            @if($file->permission_edit_file)
            <span class="list-table-action dropdown font-size-inherit">
                <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false" title="{{ cleanLang(__('lang.more')) }}"
                    class="data-toggle-action-tooltip btn btn-outline-default-light btn-circle btn-sm">
                    <i class="ti-more"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="listTableAction">
                    <!--actions button - change category-->
                    <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" 
                        data-toggle="modal" 
                        data-target="#commonModal"
                        data-modal-title="{{ cleanLang(__('lang.edit_tags')) }}"
                        data-url="{{ urlResource('/files/'.$file->file_id.'/edit-tags') }}"
                        data-action-url="{{ urlResource('/files/'.$file->file_id.'/edit-tags') }}"
                        data-loading-target="commonModalBody" 
                        data-action-ajax-class="js-ajax-ux-request"
                        data-action-method="POST">
                        {{ cleanLang(__('lang.edit_tags')) }}</a>
                    <!--actions button - attach project -->
                </div>
            </span>
            @endif
            <!--more button-->
        </span>
        <!--action button-->
    </td>
</tr>
@endforeach
<!--each row-->
<script>
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
                "reaction_resource_type":"file",
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