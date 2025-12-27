<div class="col-lg-12  col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Everything (All Activities)</h5>
            <div class="dashboard-events profiletimeline" id="dashboard-client-events">
                <div class="vl"></div>
                @php $events = $payload['all_events'] ; @endphp
                @include('pages.timeline.components.misc.ajax')
            </div>
            <!--load more-->
        </div>
    </div>
</div>