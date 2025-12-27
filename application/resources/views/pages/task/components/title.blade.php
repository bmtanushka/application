<div class="card-title m-b-0">
    <span data-toggle="tooltip" data-placement="top" title="Click to edit the title" id="{{ runtimePermissions('task-edit-title', $task->permission_edit_task) }}"> {{ $task->task_title }}
    </span>
</div>
<!--buttons: edit-->
@if($task->permission_edit_task)
<div id="card-title-edit" class="card-title-edit hidden">
    <input type="text" class="form-control form-control-sm card-title-input" id="task_title" name="task_title">
    <!--button: subit & cancel-->
    <div id="card-title-submit" class="p-t-10 text-right">
        <button type="button" class="btn waves-effect waves-light btn-xs btn-default"
            id="card-title-button-cancel">{{ cleanLang(__('lang.cancel')) }}</button>
        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
            data-url="{{ urlResource('/tasks/'.$task->task_id.'/update-title') }}" data-progress-bar='hidden'
            data-type="form" data-form-id="card-title-edit" data-ajax-type="post"
            id="card-title-button-save">{{ cleanLang(__('lang.save')) }}</button>
    </div>
</div>
@endif
<div class=""><small><strong>@lang('lang.project'): </strong></small><small id="card-task-milestone-title"><a
            href="{{ url('projects/'.$task->project_id ?? '') }}">{{ $task->project_title ?? '---' }}</a></small></div>
            
<div class=""><small><strong>Job Number: </strong></small><small data-toggle="tooltip" data-placement="top" title="Click to edit the job number" id="card-job-number-editable">{{ $task->task_job_number ?? '---' }}</small></div>
<!--buttons: edit-->
@if($task->permission_edit_task)
<div id="card-job-number-edit" class="card-job-number-edit hidden">
    <input type="text" class="form-control form-control-sm card-job-number-input" id="task_job_number" name="task_job_number">
    <!--button: subit & cancel-->
    <div id="card-job-number-submit" class="p-t-10 text-right">
        <button type="button" class="btn waves-effect waves-light btn-xs btn-default"
            id="card-job-number-button-cancel">{{ cleanLang(__('lang.cancel')) }}</button>
        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
            data-url="{{ urlResource('/tasks/'.$task->task_id.'/update-job-number') }}" data-progress-bar='hidden'
            data-type="form" data-form-id="card-job-number-edit" data-ajax-type="post"
            id="card-job-number-button-save">{{ cleanLang(__('lang.save')) }}</button>
    </div>
</div>
@endif
<div class="m-b-15"><small><strong>@lang('lang.milestone'): </strong></small><small
        id="card-task-milestone-title">{{ runtimeLang($task->milestone_title, 'task_milestone') }}</small></div>
<!--this item is archived notice-->
@if(runtimeArchivingOptions())
<div id="card_archived_notice_{{ $task->task_id }}"
    class="alert alert-warning p-t-7 p-b-7 {{ runtimeActivateOrAchive('archived-notice', $task->task_active_state) }}">
    <i class="mdi mdi-archive"></i> @lang('lang.this_task_is_archived')
</div>
@endif