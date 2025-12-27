<!--attachment-->
<?php if($event->event_item == 'attachment'): ?>
<!-- <div class="x-description"><a href="<?php echo e(url($event->event_item_content2)); ?>"><?php echo e($event->event_item_content); ?></a> -->
<div class="x-description">
    <?php
       $extension = substr($event->event_item_content, strrpos($event->event_item_content, '.') + 1);
    ?>
        
    <?php if($extension=="pdf"): ?>
        <embed src="https://www.task-meister.com/storage/files/<?php echo e(substr($event->event_item_content2, strrpos($event->event_item_content2, '/') + 1)); ?>/<?php echo e($event->event_item_content); ?>" width="150" height="100" 
     type="application/pdf">
        <br/>
        <button type="button" title="" class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal" data-target="#viewModal2" data-url="https://www.task-meister.com/" data-loading-target="commonModalBody" data-modal-title="Edit Client" data-action-url="https://www.task-meister.com/" data-action-method="GET" data-action-ajax-loading-target="" data-original-title="Edit" aria-describedby="" data-type="pdf" data-download="<?php echo e(url($event->event_item_content2)); ?>" data-discussion="<?php echo e(url('/tasks/v/'.$event->event_parent_id.'/'.str_slug($event->event_parent_title))); ?>" data-src="https://www.task-meister.com/storage/files/<?php echo e(substr($event->event_item_content2, strrpos($event->event_item_content2, '/') + 1)); ?>/<?php echo e($event->event_item_content); ?>">
            <i class="sl-icon-eye"></i>
        </button>&nbsp;&nbsp;<a type="button" class="btn btn-outline-primary btn-circle btn-sm" href="<?php echo e(url($event->event_item_content2)); ?>">
            <i class="sl-icon-cloud-download"></i>
        </a>&nbsp;&nbsp;<a type="button" class="btn btn-outline-secondary btn-circle btn-sm" href="<?php echo e(url('/tasks/v/'.$event->event_parent_id.'/'.str_slug($event->event_parent_title))); ?>">
            <i class="sl-icon-people"></i>
        </a>
    <?php elseif($extension=="jpg" || $extension=="jpeg" || $extension=="png" || $extension=="ico"): ?>
        <img src="https://www.task-meister.com/storage/files/<?php echo e(substr($event->event_item_content2, strrpos($event->event_item_content2, '/') + 1)); ?>/<?php echo e($event->event_item_content); ?>" width="150" height="100"></img>
        <br/>
        <button type="button" title="" class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal" data-target="#viewModal1" data-url="https://www.task-meister.com/" data-loading-target="commonModalBody" data-modal-title="Edit Client" data-action-url="https://www.task-meister.com/" data-action-method="GET" data-action-ajax-loading-target="" data-original-title="Edit" aria-describedby="" data-type="img" data-download="<?php echo e(url($event->event_item_content2)); ?>" data-discussion="<?php echo e(url('/tasks/v/'.$event->event_parent_id.'/'.str_slug($event->event_parent_title))); ?>" data-src="https://www.task-meister.com/storage/files/<?php echo e(substr($event->event_item_content2, strrpos($event->event_item_content2, '/') + 1)); ?>/<?php echo e($event->event_item_content); ?>">
                <i class="sl-icon-eye"></i>
        </button>&nbsp;&nbsp;<a type="button" class="btn btn-outline-primary btn-circle btn-sm" href="<?php echo e(url($event->event_item_content2)); ?>">
            <i class="sl-icon-cloud-download"></i>
        </a>&nbsp;&nbsp;<a type="button" class="btn btn-outline-secondary btn-circle btn-sm" href="<?php echo e(url('/tasks/v/'.$event->event_parent_id.'/'.str_slug($event->event_parent_title))); ?>">
            <i class="sl-icon-people"></i>
        </a>
    <?php else: ?>
        <a href="<?php echo e(url($event->event_item_content2)); ?>"><?php echo e($event->event_item_content); ?></a>
    <?php endif; ?>
    </div>
<?php endif; ?>

<!--comment-->
<?php if($event->event_item == 'comment'): ?>
<div class="x-description"><?php echo clean($event->event_item_content); ?></div>
<?php endif; ?>

<!--status-->
<?php if($event->event_item == 'status'): ?>
<div class="x-description"><strong><?php echo e(cleanLang(__('lang.new_status'))); ?>:</strong> <?php echo e(runtimeLang($event->event_item_content)); ?>

</div>
<?php endif; ?>

<!--file-->
<?php if($event->event_item == 'file'): ?>
    <div class="x-description">
    <?php
       $extension = substr($event->event_item_content, strrpos($event->event_item_content, '.') + 1);
    ?>
        
    <?php if($extension=="pdf"): ?>
        <embed src="https://www.task-meister.com/storage/files/<?php echo e(substr($event->event_item_content2, strrpos($event->event_item_content2, '=') + 1)); ?>/<?php echo e($event->event_item_content); ?>" width="200" height="200" 
     type="application/pdf">
        <br/>
        <button type="button" title="" class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal" data-target="#viewModal2" data-url="https://www.task-meister.com/" data-loading-target="commonModalBody" data-modal-title="Edit Client" data-action-url="https://www.task-meister.com/" data-action-method="GET" data-action-ajax-loading-target="" data-original-title="Edit" aria-describedby="" data-type="pdf" data-download="<?php echo e(url($event->event_item_content2)); ?>" data-discussion="<?php echo e(_url('projects/'.$event->event_parent_id.'/files')); ?>" data-src="https://www.task-meister.com/storage/files/<?php echo e(substr($event->event_item_content2, strrpos($event->event_item_content2, '=') + 1)); ?>/<?php echo e($event->event_item_content); ?>">
            <i class="sl-icon-eye"></i>
        </button>&nbsp;&nbsp;<a type="button" class="btn btn-outline-primary btn-circle btn-sm" href="<?php echo e(url($event->event_item_content2)); ?>">
            <i class="sl-icon-cloud-download"></i>
        </a>&nbsp;&nbsp;<a type="button" class="btn btn-outline-secondary btn-circle btn-sm" href="<?php echo e(_url('projects/'.$event->event_parent_id.'/files')); ?>">
            <i class="sl-icon-people"></i>
        </a>
    <?php elseif($extension=="jpg" || $extension=="jpeg" || $extension=="png" || $extension=="ico"): ?>
        <img src="https://www.task-meister.com/storage/files/<?php echo e(substr($event->event_item_content2, strrpos($event->event_item_content2, '=') + 1)); ?>/<?php echo e($event->event_item_content); ?>" width="200" height="200"></img>
        <br/>
        <button type="button" title="" class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form" data-toggle="modal" data-target="#viewModal2" data-url="https://www.task-meister.com/" data-loading-target="commonModalBody" data-modal-title="Edit Client" data-action-url="https://www.task-meister.com/" data-action-method="GET" data-action-ajax-loading-target="" data-original-title="Edit" aria-describedby="" data-type="pdf" data-download="<?php echo e(url($event->event_item_content2)); ?>" data-discussion="<?php echo e(_url('projects/'.$event->event_parent_id.'/files')); ?>" data-src="https://www.task-meister.com/storage/files/<?php echo e(substr($event->event_item_content2, strrpos($event->event_item_content2, '=') + 1)); ?>/<?php echo e($event->event_item_content); ?>">
            <i class="sl-icon-eye"></i>
        </button>&nbsp;&nbsp;<a type="button" class="btn btn-outline-primary btn-circle btn-sm" href="<?php echo e(url($event->event_item_content2)); ?>">
            <i class="sl-icon-cloud-download"></i>
        </a>&nbsp;&nbsp;<a type="button" class="btn btn-outline-secondary btn-circle btn-sm" href="<?php echo e(_url('projects/'.$event->event_parent_id.'/files')); ?>">
            <i class="sl-icon-people"></i>
        </a>
    <?php else: ?>
        <a href="<?php echo e(url($event->event_item_content2)); ?>"><?php echo e($event->event_item_content); ?></a>
    <?php endif; ?>
    </div>
<?php endif; ?>

<!--task-->
<?php if($event->event_item == 'task'): ?>
<div class="x-description">
        <a href="<?php echo e(url('/tasks/v/'.$event->event_item_id.'/'.str_slug($event->event_parent_title))); ?>"><?php echo e($event->event_item_content); ?></a>
</div>
<?php endif; ?>

<!--tickets-->
<?php if($event->event_item == 'ticket'): ?>
<div class="x-description"><a href="<?php echo e(url('tickets/'.$event->event_item_id)); ?>"><?php echo clean($event->event_item_content); ?></a>
</div>
<?php endif; ?>

<!--invoice-->
<?php if($event->event_item == 'invoice'): ?>
<div class="x-description"><a href="<?php echo e(url('invoices/'.$event->event_item_id)); ?>"><?php echo clean($event->event_item_content); ?></a>
</div>
<?php endif; ?>


<!--estimate-->
<?php if($event->event_item == 'estimate'): ?>
<div class="x-description"><a href="<?php echo e(url('estimates/'.$event->event_item_id)); ?>"><?php echo clean($event->event_item_content); ?></a>
</div>
<?php endif; ?>

<!--project (..but do not show on project timeline)-->
<?php if($event->event_item == 'new_project' && request('timelineresource_type') != 'project'): ?>
<div class="x-description"><a
                href="<?php echo e(_url('projects/'.$event->event_parent_id)); ?>"><?php echo e($event->event_parent_title); ?></a>
</div>
<?php endif; ?>


<!--subscription-->
<?php if($event->event_item == 'subscription'): ?>
<div class="x-description"><a href="<?php echo e(url('subscriptions/'.$event->event_item_id)); ?>">
        <?php echo e($event->event_item_content); ?></a>
</div>
<?php endif; ?>


<!--proposal-->
<?php if($event->event_item == 'proposal'): ?>
<div class="x-description"><a href="<?php echo e(url('proposals/'. $event->event_item_id)); ?>"><?php echo e($event->event_item_content); ?></a>
</div>
<?php endif; ?>

<!--contract-->
<?php if($event->event_item == 'contract'): ?>
<div class="x-description"><a href="<?php echo e(url('contracts/'. $event->event_item_id)); ?>"><?php echo e($event->event_item_content); ?></a>
</div>
<?php endif; ?><?php /**PATH /home/wec24/public_html/application/resources/views/pages/events/includes/content.blade.php ENDPATH**/ ?>