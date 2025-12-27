<!--chat left -->
@if($message->message_creatorid != auth()->user()->id)
<li id="{{ messageUniqueID($message->message_unique_id) }}" class="messages_message">
    <div class="chat-img"><img src="{{ getUsersAvatar($message->avatar_directory, $message->avatar_filename)  }}"
            alt="user" />
    </div>
    <div class="chat-content">
        <h5>
            @if($message->first_name)
            {{  $message->first_name  .' '. $message->last_name }}
            @else
            runtimeUnkownUser()
            @endif
        </h5>
        <div class="box message-content-box {{ 'message_type_'.$message->message_file_type }} bg-light-info">
            <div class="message-content">
                <!--text-->
                @if($message->message_type == 'text')
                    @if($message->pmsgtxt != null)
                    <div class="x-text opacity-5" onclick="document.getElementById('{{ messageUniqueID($message->pmsgtxtui) }}').scrollIntoView();">
                        {!! _clean(substr($message->pmsgtxt, 0, 50)) !!}.. click for more
                    </div>
                    @endif
                    @if($message->pmsgtxt == null && $message->pmsgtxtui != null)
                    <div class="x-text opacity-5" onclick="document.getElementById('{{ messageUniqueID($message->pmsgtxtui) }}').scrollIntoView();">
                        click to see initial message..
                    </div>
                    @endif
                    <div class="x-text">
                        {!! _clean($message->message_text) !!}
                    </div>
                    @php
                        $pcontent = _clean(substr($message->message_text, 0, 50));
                    @endphp
                @endif
                <!--file-->
                @if($message->message_type == 'file' && $message->message_file_type == 'file')
                <div class="x-file">
                    <a href="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_name) }}"
                        target="_blank">
                        <i class="sl-icon-paper-clip"></i> <span>{{ $message->message_file_name }}</span>
                    </a>
                </div>
                @php
                    $pcontent = 'File message';
                @endphp
                @endif
                <!--image-->
                @if($message->message_type == 'file' && $message->message_file_type == 'image')
                <div class="x-image">
                    <a href="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_name) }}"
                        target="_blank">
                        <img src="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_thumb_name) }}"
                            alt="{{ $message->message_file_name }}" />
                    </a>
                </div>
                @php
                    $pcontent = 'File message';
                @endphp
                @endif
            </div>
                
            
            <!--meta-->
            <div class="x-meta">
                <!--delete button-->
                @if(auth()->user()->is_admin)
                <span id="message_delete_button_{{ $message->message_id }}"
                    class="text-danger messages_delete_button hidden x-left-side"
                    data-message-id="{{ messageUniqueID($message->message_unique_id) }}" data-progress-bar="hidden"
                    data-ajax-type="DELETE" data-url="{{ url('/messages/'.$message->message_unique_id) }}">
                    <i class="sl-icon-trash"></i>
                </span>
                @endif
                <span class="time">{{ date('m-d-Y H:i:s', strtotime($message->message_created)) }} {{-- runtimeDateAgo($message->message_created) --}}</span>
                <button class="react-btn" data-message-id="{{ $message->message_unique_id }}" style="background: #d8d8d869;border: none;border-radius: 20px;font-size: 14px;filter: grayscale(100%);">&#128512;</button>
                
                <div class="reactions">
                    @if ($message->emoji_data)
                        @php
                            //print_r($message->emoji_data);
                        @endphp
                        @foreach (explode(';', $message->emoji_data) as $reaction)
                            @php
                                echo '<span style="font-size: 14px;">'.$reaction.'</span><br/>';
                            @endphp
                            
                        @endforeach
                    @endif
                    <span style="font-size: 14px;" id="{{ $message->message_unique_id }}_myEmoji"></span>
                </div>
            </div>
            
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" onclick="letsReplyMessage(this)" role="button" data-message="{{ $pcontent }}" data-message-id="{{ $message->message_id }}" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16">
                <path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.7 8.7 0 0 0-1.921-.306 7 7 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254l-.042-.028a.147.147 0 0 1 0-.252l.042-.028zM7.8 10.386q.103 0 .223.006c.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96z"/>
            </svg>
    </div>        
    
</li>
@endif

<!--chat right -->
@if($message->message_creatorid == auth()->user()->id)
<li class="reverse" id="{{ messageUniqueID($message->message_unique_id) }}">
    <div class="chat-content my-chat-content">
        <div class="box message-content-box bg-light-inverse {{ 'message_type_'.$message->message_file_type }}">
            <div class="message-content">
                <!--text-->
                @if($message->message_type == 'text')
                    @if($message->pmsgtxt != null)
                    <div class="x-text opacity-5" onclick="document.getElementById('{{ messageUniqueID($message->pmsgtxtui) }}').scrollIntoView();">
                        {!! _clean(substr($message->pmsgtxt, 0, 50)) !!}.. click for more
                    </div>
                    @endif
                    @if($message->pmsgtxt == null && $message->pmsgtxtui != null)
                    <div class="x-text opacity-5" onclick="document.getElementById('{{ messageUniqueID($message->pmsgtxtui) }}').scrollIntoView();">
                        click to see initial message..
                    </div>
                    @endif
                    <div class="x-text">
                        {!! _clean($message->message_text) !!}
                    </div>
                @endif
                <!--file-->
                @if($message->message_type == 'file' && $message->message_file_type == 'file')
                <div class="x-file">
                    <a href="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_name) }}"
                        target="_blank">
                        <i class="sl-icon-paper-clip"></i> <span>{{ $message->message_file_name }}</span>
                    </a>
                </div>
                @endif
                <!--image-->
                @if($message->message_type == 'file' && $message->message_file_type == 'image')
                <div class="x-image">
                    <a href="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_name) }}"
                        target="_blank">
                        <img src="{{ url('storage/files/'.$message->message_file_directory.'/'.$message->message_file_thumb_name) }}"
                            alt="{{ $message->message_file_name }}" />
                    </a>
                </div>
                @endif
            </div>
            <!--meta-->
            <div class="x-meta">
                <!--delete button-->
                <span id="message_delete_button_{{ $message->message_id }}"
                    class="text-danger messages_delete_button hidden x-right-side"
                    data-message-id="{{ messageUniqueID($message->message_unique_id) }}" data-progress-bar="hidden"
                    data-ajax-type="DELETE" data-url="{{ url('/messages/'.$message->message_unique_id) }}">
                    <i class="sl-icon-trash"></i>
                </span>
                <span class="time">{{ date('m-d-Y H:i:s', strtotime($message->message_created)) }} {{-- runtimeDateAgo($message->message_created) --}}</span>
                <button class="react-btn" data-message-id="{{ $message->message_unique_id }}" style="background: #d8d8d869;border: none;border-radius: 20px;font-size: 14px;filter: grayscale(100%);">&#128512;</button>
                
                <div class="reactions">
                    @if ($message->emoji_data)
                        @php
                            //print_r($message->emoji_data);
                        @endphp
                        @foreach (explode(';', $message->emoji_data) as $reaction)
                            @php
                                echo '<span style="font-size: 14px;">'.$reaction.'</span><br/>';
                            @endphp
                            
                        @endforeach
                   
                    @endif
                    <span style="font-size: 14px;" id="{{ $message->message_unique_id }}_myEmoji"></span>
                </div>
            </div>
        </div>
    </div>
</li>
@endif
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
                "reaction_resource_type":"message",
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