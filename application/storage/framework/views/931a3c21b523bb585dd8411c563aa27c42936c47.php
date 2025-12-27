<!--reminder-->
<?php if($payload['has_reminder']): ?>
<?php echo $__env->make('pages.reminders.cards.reminder-show', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php else: ?>
<?php echo $__env->make('pages.reminders.cards.reminder-add', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?><?php /**PATH /home/wec24/public_html/application/resources/views/pages/reminders/cards/wrapper.blade.php ENDPATH**/ ?>