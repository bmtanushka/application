<?php if(config('system.settings_projects_permissions_basis') == 'category_based'): ?>
<div class="alert alert-info"><?php echo app('translator')->get('lang.projects_assigned_auto'); ?></div>
<div class="alert alert-info"><?php echo app('translator')->get('lang.you_can_change_in_settings'); ?></div>
<?php else: ?>
<div class="form-group row">
    <div class="col-sm-12">
        <select name="assigned" id="assigned"
            class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
            multiple="multiple" tabindex="-1" aria-hidden="true">
            <!--array of assigned-->
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $assigned[] = $user->id; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <!--/#array of assigned-->
            <!--users list-->
            <?php $__currentLoopData = config('system.team_members'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($user->id); ?>" <?php echo e(runtimePreselectedInArray($user->id ?? '', $assigned ?? [])); ?>><?php echo e($user->full_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <!--/#users list-->
        </select>
    </div>
</div>


<div class="alert alert-info m-t-30">
    <?php echo app('translator')->get('lang.bulk_assign_info'); ?>
</div>
<?php endif; ?><?php /**PATH /home/wec24/public_html/application/resources/views/pages/projects/components/modals/assigned.blade.php ENDPATH**/ ?>