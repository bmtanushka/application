<!--description-->
<div class="card-description" id="card-description">
    <div class="x-heading"><i class="mdi mdi-file-document-box"></i>Description</div>
    <div class="x-content rich-text-formatting" id="card-description-container">
        <?php echo $task->task_description ?? ''; ?>

    </div>
    
    <!--buttons: edit-->
    <div id="card-description-edit">
        <div class="x-action" id="card-description-button-edit">
            <a href="javaScript:void(0);">Edit Description</a>
        </div>
        <input type="hidden" name="task_description" id="card-description-input" value="<?php echo e($task->task_description); ?>">
    </div>
    
    <!--button: submit & cancel-->
    <div id="card-description-submit" class="p-t-10 hidden text-right">
        <button type="button" class="btn waves-effect waves-light btn-xs btn-default" id="card-description-button-cancel">Cancel</button>
        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger js-description-save"
            data-url="<?php echo e(url('/tasks/' . $task->task_id . '/update-description')); ?>" 
            data-progress-bar='hidden'
            data-type="form" 
            data-form-id="card-description" 
            data-ajax-type="post"
            id="card-description-button-save">Save</button>
    </div>
</div>

<!--checklist-->
<div class="card-checklist" id="card-checklist">
    <div class="x-heading clearfix">
        <span class="pull-left"><i class="mdi mdi-checkbox-marked"></i>Checklist</span>
        <span class="pull-right p-t-5" id="card-checklist-progress"><?php echo e($progress['completed'] ?? 0); ?>/<?php echo e($progress['total'] ?? 0); ?></span>
    </div>
    <div class="progress" id="card-checklist-progress-container">
        <?php echo $__env->make('pages/task/components/progressbar', ['progress' => $progress], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="x-content" id="card-checklists-container">
        <?php echo $__env->make('pages/task/components/checklist', ['checklists' => $checklists, 'progress' => $progress], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    
    <div class="x-action">
        <a href="javascript:void(0)" class="js-card-checklist-toggle" id="card-checklist-add-new"
            data-action-url="<?php echo e(url('/tasks/' . $task->task_id . '/add-checklist')); ?>" 
            data-toggle="new">Create A New Item</a>
    </div>
</div><?php /**PATH /home/wec24/public_html/application/resources/views/pages/task/content/details/show.blade.php ENDPATH**/ ?>