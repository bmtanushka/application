<!--attachment-->
@if($event->event_item == 'attachment')
<!-- <div class="x-description"><a href="{{ url($event->event_item_content2) }}">{{ $event->event_item_content }}</a> -->
<div class="x-description">
    @php
       $extension = substr($event->event_item_content, strrpos($event->event_item_content, '.') + 1);
    @endphp
        
    @if($extension=="pdf")
        <embed src="https://www.task-meister.com/storage/files/{{substr($event->event_item_content2, strrpos($event->event_item_content2, '/') + 1)}}/{{ $event->event_item_content }}" width="150" height="100" 
     type="application/pdf">
        <br/>
        <button type="button" title="" class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal" data-target="#viewModal2" data-url="https://www.task-meister.com/" data-loading-target="commonModalBody" data-modal-title="Edit Client" data-action-url="https://www.task-meister.com/" data-action-method="GET" data-action-ajax-loading-target="" data-original-title="Edit" aria-describedby="" data-type="pdf" data-download="{{ url($event->event_item_content2) }}" data-discussion="{{ url('/tasks/v/'.$event->event_parent_id.'/'.str_slug($event->event_parent_title)) }}" data-src="https://www.task-meister.com/storage/files/{{substr($event->event_item_content2, strrpos($event->event_item_content2, '/') + 1)}}/{{ $event->event_item_content }}">
            <i class="sl-icon-eye"></i>
        </button>&nbsp;&nbsp;<a type="button" class="btn btn-outline-primary btn-circle btn-sm" href="{{ url($event->event_item_content2) }}">
            <i class="sl-icon-cloud-download"></i>
        </a>&nbsp;&nbsp;<a type="button" class="btn btn-outline-secondary btn-circle btn-sm" href="{{ url('/tasks/v/'.$event->event_parent_id.'/'.str_slug($event->event_parent_title)) }}">
            <i class="sl-icon-people"></i>
        </a>
    @elseif($extension=="jpg" || $extension=="jpeg" || $extension=="png" || $extension=="ico")
        <img src="https://www.task-meister.com/storage/files/{{substr($event->event_item_content2, strrpos($event->event_item_content2, '/') + 1)}}/{{ $event->event_item_content }}" width="150" height="100"></img>
        <br/>
        <button type="button" title="" class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal" data-target="#viewModal1" data-url="https://www.task-meister.com/" data-loading-target="commonModalBody" data-modal-title="Edit Client" data-action-url="https://www.task-meister.com/" data-action-method="GET" data-action-ajax-loading-target="" data-original-title="Edit" aria-describedby="" data-type="img" data-download="{{ url($event->event_item_content2) }}" data-discussion="{{ url('/tasks/v/'.$event->event_parent_id.'/'.str_slug($event->event_parent_title)) }}" data-src="https://www.task-meister.com/storage/files/{{substr($event->event_item_content2, strrpos($event->event_item_content2, '/') + 1)}}/{{ $event->event_item_content }}">
                <i class="sl-icon-eye"></i>
        </button>&nbsp;&nbsp;<a type="button" class="btn btn-outline-primary btn-circle btn-sm" href="{{ url($event->event_item_content2) }}">
            <i class="sl-icon-cloud-download"></i>
        </a>&nbsp;&nbsp;<a type="button" class="btn btn-outline-secondary btn-circle btn-sm" href="{{ url('/tasks/v/'.$event->event_parent_id.'/'.str_slug($event->event_parent_title)) }}">
            <i class="sl-icon-people"></i>
        </a>
    @else
        <a href="{{ url($event->event_item_content2) }}">{{ $event->event_item_content }}</a>
    @endif
    </div>
@endif

<!--comment-->
@if($event->event_item == 'comment')
<div class="x-description">{!! clean($event->event_item_content) !!}</div>
@endif

<!--status-->
@if($event->event_item == 'status')
<div class="x-description"><strong>{{ cleanLang(__('lang.new_status')) }}:</strong> {{ runtimeLang($event->event_item_content) }}
</div>
@endif

<!--file-->
@if($event->event_item == 'file')
    <div class="x-description">
    @php
       $extension = substr($event->event_item_content, strrpos($event->event_item_content, '.') + 1);
    @endphp
        
    @if($extension=="pdf")
        <embed src="https://www.task-meister.com/storage/files/{{substr($event->event_item_content2, strrpos($event->event_item_content2, '=') + 1)}}/{{ $event->event_item_content }}" width="200" height="200" 
     type="application/pdf">
        <br/>
        <button type="button" title="" class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal" data-target="#viewModal2" data-url="https://www.task-meister.com/" data-loading-target="commonModalBody" data-modal-title="Edit Client" data-action-url="https://www.task-meister.com/" data-action-method="GET" data-action-ajax-loading-target="" data-original-title="Edit" aria-describedby="" data-type="pdf" data-download="{{ url($event->event_item_content2) }}" data-discussion="{{ _url('projects/'.$event->event_parent_id.'/files') }}" data-src="https://www.task-meister.com/storage/files/{{substr($event->event_item_content2, strrpos($event->event_item_content2, '=') + 1)}}/{{ $event->event_item_content }}">
            <i class="sl-icon-eye"></i>
        </button>&nbsp;&nbsp;<a type="button" class="btn btn-outline-primary btn-circle btn-sm" href="{{ url($event->event_item_content2) }}">
            <i class="sl-icon-cloud-download"></i>
        </a>&nbsp;&nbsp;<a type="button" class="btn btn-outline-secondary btn-circle btn-sm" href="{{ _url('projects/'.$event->event_parent_id.'/files') }}">
            <i class="sl-icon-people"></i>
        </a>
    @elseif($extension=="jpg" || $extension=="jpeg" || $extension=="png" || $extension=="ico")
        <img src="https://www.task-meister.com/storage/files/{{substr($event->event_item_content2, strrpos($event->event_item_content2, '=') + 1)}}/{{ $event->event_item_content }}" width="200" height="200"></img>
        <br/>
        <button type="button" title="" class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal" data-target="#viewModal2" data-url="https://www.task-meister.com/" data-loading-target="commonModalBody" data-modal-title="Edit Client" data-action-url="https://www.task-meister.com/" data-action-method="GET" data-action-ajax-loading-target="" data-original-title="Edit" aria-describedby="" data-type="pdf" data-download="{{ url($event->event_item_content2) }}" data-discussion="{{ _url('projects/'.$event->event_parent_id.'/files') }}" data-src="https://www.task-meister.com/storage/files/{{substr($event->event_item_content2, strrpos($event->event_item_content2, '=') + 1)}}/{{ $event->event_item_content }}">
            <i class="sl-icon-eye"></i>
        </button>&nbsp;&nbsp;<a type="button" class="btn btn-outline-primary btn-circle btn-sm" href="{{ url($event->event_item_content2) }}">
            <i class="sl-icon-cloud-download"></i>
        </a>&nbsp;&nbsp;<a type="button" class="btn btn-outline-secondary btn-circle btn-sm" href="{{ _url('projects/'.$event->event_parent_id.'/files') }}">
            <i class="sl-icon-people"></i>
        </a>
    @else
        <a href="{{ url($event->event_item_content2) }}">{{ $event->event_item_content }}</a>
    @endif
    </div>
@endif

<!--task-->
@if($event->event_item == 'task')
<div class="x-description">
        <a href="{{ url('/tasks/v/'.$event->event_item_id.'/'.str_slug($event->event_parent_title)) }}">{{ $event->event_item_content }}</a>
</div>
@endif

<!--tickets-->
@if($event->event_item == 'ticket')
<div class="x-description"><a href="{{ url('tickets/'.$event->event_item_id) }}">{!! clean($event->event_item_content) !!}</a>
</div>
@endif

<!--invoice-->
@if($event->event_item == 'invoice')
<div class="x-description"><a href="{{ url('invoices/'.$event->event_item_id) }}">{!! clean($event->event_item_content)
                !!}</a>
</div>
@endif


<!--estimate-->
@if($event->event_item == 'estimate')
<div class="x-description"><a href="{{ url('estimates/'.$event->event_item_id) }}">{!! clean($event->event_item_content)
                !!}</a>
</div>
@endif

<!--project (..but do not show on project timeline)-->
@if($event->event_item == 'new_project' && request('timelineresource_type') != 'project')
<div class="x-description"><a
                href="{{ _url('projects/'.$event->event_parent_id) }}">{{ $event->event_parent_title }}</a>
</div>
@endif


<!--subscription-->
@if($event->event_item == 'subscription')
<div class="x-description"><a href="{{ url('subscriptions/'.$event->event_item_id) }}">
        {{ $event->event_item_content }}</a>
</div>
@endif


<!--proposal-->
@if($event->event_item == 'proposal')
<div class="x-description"><a href="{{ url('proposals/'. $event->event_item_id) }}">{{ $event->event_item_content }}</a>
</div>
@endif

<!--contract-->
@if($event->event_item == 'contract')
<div class="x-description"><a href="{{ url('contracts/'. $event->event_item_id) }}">{{ $event->event_item_content }}</a>
</div>
@endif