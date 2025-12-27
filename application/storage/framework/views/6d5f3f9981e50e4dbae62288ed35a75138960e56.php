<div class="card-attachments" id="card-attachments"
    data-upload-url="<?php echo e(url('/tasks/'.$task->task_id.'/attach-files')); ?>">
    <div class="x-heading"><i class="mdi mdi-cloud-download"></i><?php echo e(cleanLang(__('lang.attachments'))); ?></div>

    <?php if($task->permission_participate): ?>
    <div class="x-action"><a class="card_fileupload p-b-25" id="js-card-toggle-fileupload"
            href="javascript:void(0)"><?php echo e(cleanLang(__('lang.add_attachment'))); ?></a></div>


    <div class="hidden" id="card-fileupload-container">

        <!--tags-->
        <div class="form-group row">
            <div class="col-12 p-l-35 p-t-25">
                <input type="checkbox" class="form-control form-control-sm" id="completed" name="completed" />
                <label
                class="text-left control-label" for="completed">Completed file</label>
            </div>
            <label
                class="col-12 text-left control-label col-form-label required p-l-35"><?php echo e(cleanLang(__('lang.tags'))); ?></label>
            <div class="col-12 p-l-35">
                <select name="tags" id="tags"
                    class="form-control form-control-sm select2-multiple <?php echo e(runtimeAllowUserTags()); ?> select2-hidden-accessible card-attachment-tags"
                    multiple="multiple" tabindex="-1" aria-hidden="true">
                    <?php if(isset($attachment_tags)): ?>{
                        <?php $__currentLoopData = $attachment_tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment_tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($attachment_tag->tag_title); ?>">
                            <?php echo e($attachment_tag->tag_title); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    }
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!--dropzone-->
        <div class="dropzone dz-clickable" id="card_fileupload">
            <div class="dz-default dz-message">
                <i class="icon-Upload-toCloud"></i>
                <span><?php echo e(cleanLang(__('lang.drag_drop_file'))); ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
        <div class="x-content row p-b-25 p-t-25" id="card-attachments-container">
        <!--dynamic content here-->
    </div>
</div>
<!--attachemnts js--><?php /**PATH /home/wec24/public_html/application/resources/views/pages/task/components/attachments.blade.php ENDPATH**/ ?>