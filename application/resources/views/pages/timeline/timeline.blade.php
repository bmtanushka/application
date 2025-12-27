<div class="profiletimeline p-t-0" id="timeline-container">
<!--ajax content here-->
@include('pages.timeline.components.misc.ajax')
<!--ajax content here-->
<!--load more-->

</div>

<!--load more-->
@if(isset($page['visibility_show_load_more']) && $page['visibility_show_load_more'])
<div class="autoload loadmore-button-container" id="timeline_see_more_button">
    <a data-url="{{ $page['url'] ?? '' }}" data-loading-target="timeline-container"
        href="javascript:void(0)" class="btn btn-rounded-x btn-secondary js-ajax-ux-request" id="load-more-button">{{ cleanLang(__('lang.show_more')) }}</a>
</div>
@endif