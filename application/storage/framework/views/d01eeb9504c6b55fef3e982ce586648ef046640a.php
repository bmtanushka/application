<!-- Todays Payments -->
<div class="col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body p-l-15 p-r-15">
            <div class="d-flex p-10 no-block">
                        <?php if(in_array(auth()->user()['id'], SP_CLIENT_USERS)): ?>
                            <a href="/tasks?db_project_status=completed">
                            <span class="align-slef-center">
                            <h2 class="m-b-0"><?php echo e($payload['tasks']['completed']); ?></h2>
                            <h6 class="text-muted m-b-0">Jobs - <?php echo e(cleanLang(__('lang.completed'))); ?></h6>
                        <?php else: ?>
                            <a href="/projects?db_project_status=completed">
                            <span class="align-slef-center">
                            <h2 class="m-b-0"><?php echo e($payload['projects']['completed']); ?></h2>
                            <h6 class="text-muted m-b-0"><?php echo e(cleanLang(__('lang.projects'))); ?> - <?php echo e(cleanLang(__('lang.completed'))); ?></h6>
                        <?php endif; ?>
                    </span>
                </a>
                <div class="align-self-center display-6 ml-auto"><i class="text-success icon-Credit-Card2"></i>
                </div>
            </div>
        </div>
        <div class="progress">
            <div class="progress-bar bg-success w-100 h-px-3" role="progressbar" aria-valuenow="100" aria-valuemin="0"
                aria-valuemax="100"></div>
        </div>
    </div>
</div><?php /**PATH /home/wec24/public_html/application/resources/views/pages/home/client/widgets/first-row/projects-completed.blade.php ENDPATH**/ ?>