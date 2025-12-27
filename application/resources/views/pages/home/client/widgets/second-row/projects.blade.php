@if(auth()->user()['id'] != 22)
<div class="col-lg-6  col-md-12">
    <div class="card">
        <div class="card-body">
            @if(in_array(auth()->user()['id'], SP_CLIENT_USERS))
                <h5 class="card-title">My Jobs</h5>
            @else
                <h5 class="card-title">{{ cleanLang(__('lang.my_projects')) }}</h5>
            @endif
            @if(in_array(auth()->user()['id'], SP_CLIENT_USERS))
                @php $projects = $payload['my_tasks'] ; @endphp
            @else
                @php $projects = $payload['my_projects'] ; @endphp
            @endif
            <div class="dashboard-projects" id="dashboard-client-projects">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{ cleanLang(__('lang.title')) }}</th>
                            <th>{{ cleanLang(__('lang.due_date')) }}</th>
                            <th>{{ cleanLang(__('lang.status')) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(in_array(auth()->user()['id'], SP_CLIENT_USERS))
                            @foreach($projects as $project)
                            <tr>
                                <td class="text-center">{{ $project->task_id }}</td>
                                <td class="txt-oflo"><a
                                        href="{{ _url('projects/'.$project->project_id) }}">{{ str_limit($project->task_title ??'---', 20) }}</a>
                                </td>
                                <td>{{ runtimeDate($project->task_date_due) }}</td>
                                @if($project->task_status == 1)
                                    @php $tStatus = 'new';  @endphp
                                @elseif($project->task_status == 2)
                                    @php $tStatus = 'completed';  @endphp
                                @else
                                    @php $tStatus = 'pending';  @endphp
                                @endif
                                <td><span class="text-success"><span
                                            class="label {{ runtimeProjectStatusColors($tStatus, 'label') }}">{{ runtimeLang($tStatus) }}</span></span>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            @foreach($projects as $project)
                            <tr>
                                <td class="text-center">{{ $project->project_id }}</td>
                                <td class="txt-oflo"><a
                                        href="{{ _url('projects/'.$project->project_id) }}">{{ str_limit($project->project_title ??'---', 20) }}</a>
                                </td>
                                <td>{{ runtimeDate($project->project_date_due) }}</td>
                                <td><span class="text-success"><span
                                            class="label {{ runtimeProjectStatusColors($project->project_status, 'label') }}">{{ runtimeLang($project->project_status) }}</span></span>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif