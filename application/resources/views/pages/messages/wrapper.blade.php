@extends('layout.wrapper') @section('content')

<!--javascript-->
<script src="public/js/core/messages.js?v={{ config('system.versioning') }}"></script>

<!-- main content -->
<div class="container-fluid">
    <!-- .chat-row -->
    <div class="chat-main-box">

        <!-- .chat-left-panel -->
        @include('pages.messages.components.left-panel')

        <!-- .chat-right-panel -->
        @include('pages.messages.components.right-panel')


        <!-- file uplaod -->
        @include('pages.messages.components.file-upload')

    </div>
    <!-- .chat-right-panel -->
</div>
<!-- /.chat-row -->

</div>
<!--main content -->

<script>
    window.setInterval(function(){
        var lastLi = 0;
        $('#messages-left-menu li').each(function(){
            if($(this).find('span.messages_counter').text()>=1){
                //console.log($(this).find('span.messages_counter').text());
                //console.log(lastLi);
                if(lastLi==0)
                    $(this).insertBefore('#messages-left-menu li:eq(1)');
                lastLi=1;
            } else {
                lastLi=0;
            }
        });
    }, 2000);
</script>

@endsection