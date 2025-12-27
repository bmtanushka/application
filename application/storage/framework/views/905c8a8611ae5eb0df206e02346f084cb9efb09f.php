<?php if(auth()->user()['id'] != 22): ?>
<div class="col-lg-6  col-md-12">
    <div class="card">
        <div class="card-body">
            <?php if(in_array(auth()->user()['id'], SP_CLIENT_USERS)): ?>
                <h5 class="card-title">My Jobs</h5>
            <?php else: ?>
                <h5 class="card-title"><?php echo e(cleanLang(__('lang.my_projects'))); ?></h5>
            <?php endif; ?>
            <?php if(in_array(auth()->user()['id'], SP_CLIENT_USERS)): ?>
                <?php $projects = $payload['my_tasks'] ; ?>
            <?php else: ?>
                <?php $projects = $payload['my_projects'] ; ?>
            <?php endif; ?>
            <div class="dashboard-projects" id="dashboard-client-projects">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th><?php echo e(cleanLang(__('lang.title'))); ?></th>
                            <th><?php echo e(cleanLang(__('lang.due_date'))); ?></th>
                            <th><?php echo e(cleanLang(__('lang.status'))); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(in_array(auth()->user()['id'], SP_CLIENT_USERS)): ?>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($project->task_id); ?></td>
                                <td class="txt-oflo"><a
                                        href="<?php echo e(_url('projects/'.$project->project_id)); ?>"><?php echo e(str_limit($project->task_title ??'---', 20)); ?></a>
                                </td>
                                <td><?php echo e(runtimeDate($project->task_date_due)); ?></td>
                                <?php if($project->task_status == 1): ?>
                                    <?php $tStatus = 'new';  ?>
                                <?php elseif($project->task_status == 2): ?>
                                    <?php $tStatus = 'completed';  ?>
                                <?php else: ?>
                                    <?php $tStatus = 'pending';  ?>
                                <?php endif; ?>
                                <td><span class="text-success"><span
                                            class="label <?php echo e(runtimeProjectStatusColors($tStatus, 'label')); ?>"><?php echo e(runtimeLang($tStatus)); ?></span></span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($project->project_id); ?></td>
                                <td class="txt-oflo"><a
                                        href="<?php echo e(_url('projects/'.$project->project_id)); ?>"><?php echo e(str_limit($project->project_title ??'---', 20)); ?></a>
                                </td>
                                <td><?php echo e(runtimeDate($project->project_date_due)); ?></td>
                                <td><span class="text-success"><span
                                            class="label <?php echo e(runtimeProjectStatusColors($project->project_status, 'label')); ?>"><?php echo e(runtimeLang($project->project_status)); ?></span></span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?><?php /**PATH /home/wec24/public_html/application/resources/views/pages/home/client/widgets/second-row/projects.blade.php ENDPATH**/ ?>