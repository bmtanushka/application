<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Activity Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4a5568;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .summary {
            background-color: #edf2f7;
            border-left: 4px solid #4299e1;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        .summary p {
            margin: 0;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        .notification-item {
            background-color: #f7fafc;
            border-left: 3px solid #4299e1;
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 4px;
        }
        .notification-item h4 {
            margin: 0 0 8px 0;
            font-size: 15px;
            color: #2d3748;
            font-weight: 600;
        }
        .notification-item p {
            margin: 0;
            font-size: 13px;
            color: #4a5568;
            line-height: 1.5;
        }
        .notification-time {
            font-size: 12px;
            color: #718096;
            margin-top: 8px;
        }
        .footer {
            background-color: #edf2f7;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #718096;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #4299e1;
            text-decoration: none;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4299e1;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #3182ce;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📧 Daily Activity Summary</h1>
            <p><?php echo e($date); ?></p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello <strong><?php echo e($client->first_name); ?> <?php echo e($client->last_name); ?></strong>,
            </div>

            <div class="summary">
                <p>
                    You have <strong><?php echo e($notificationCount); ?></strong> <?php echo e($notificationCount == 1 ? 'update' : 'updates'); ?> 
                    from <?php echo e($companyName); ?> today. Here's a summary of what happened:
                </p>
            </div>

            <!-- Tasks Section -->
            <?php if(isset($groupedNotifications['tasks']) && count($groupedNotifications['tasks']) > 0): ?>
            <div class="section">
                <div class="section-title">
                    📋 Tasks (<?php echo e(count($groupedNotifications['tasks'])); ?>)
                </div>
                <?php $__currentLoopData = $groupedNotifications['tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="notification-item">
                    <h4><?php echo e($notification['subject']); ?></h4>
                    <p><?php echo strip_tags($notification['message'], '<a><b><strong><i><em>'); ?></p>
                    <div class="notification-time">
                        <?php echo e(\Carbon\Carbon::parse($notification['created_at'])->diffForHumans()); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <!-- Projects Section -->
            <?php if(isset($groupedNotifications['projects']) && count($groupedNotifications['projects']) > 0): ?>
            <div class="section">
                <div class="section-title">
                    🚀 Projects (<?php echo e(count($groupedNotifications['projects'])); ?>)
                </div>
                <?php $__currentLoopData = $groupedNotifications['projects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="notification-item">
                    <h4><?php echo e($notification['subject']); ?></h4>
                    <p><?php echo strip_tags($notification['message'], '<a><b><strong><i><em>'); ?></p>
                    <div class="notification-time">
                        <?php echo e(\Carbon\Carbon::parse($notification['created_at'])->diffForHumans()); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <!-- Tickets Section -->
            <?php if(isset($groupedNotifications['tickets']) && count($groupedNotifications['tickets']) > 0): ?>
            <div class="section">
                <div class="section-title">
                    🎫 Tickets (<?php echo e(count($groupedNotifications['tickets'])); ?>)
                </div>
                <?php $__currentLoopData = $groupedNotifications['tickets']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="notification-item">
                    <h4><?php echo e($notification['subject']); ?></h4>
                    <p><?php echo strip_tags($notification['message'], '<a><b><strong><i><em>'); ?></p>
                    <div class="notification-time">
                        <?php echo e(\Carbon\Carbon::parse($notification['created_at'])->diffForHumans()); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <!-- Other Notifications -->
            <?php if(isset($groupedNotifications['other']) && count($groupedNotifications['other']) > 0): ?>
            <div class="section">
                <div class="section-title">
                    📌 Other Updates (<?php echo e(count($groupedNotifications['other'])); ?>)
                </div>
                <?php $__currentLoopData = $groupedNotifications['other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="notification-item">
                    <h4><?php echo e($notification['subject']); ?></h4>
                    <p><?php echo strip_tags($notification['message'], '<a><b><strong><i><em>'); ?></p>
                    <div class="notification-time">
                        <?php echo e(\Carbon\Carbon::parse($notification['created_at'])->diffForHumans()); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <div style="text-align: center;">
                <a href="<?php echo e(config('app.url')); ?>" class="btn">View in Dashboard</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong><?php echo e($companyName); ?></strong></p>
            <p>You're receiving this daily digest because you have an account with us.</p>
            <p>To manage your notification preferences, please contact your account manager.</p>
        </div>
    </div>
</body>
</html><?php /**PATH /home/wec24/public_html/application/resources/views/emails/client_daily_digest.blade.php ENDPATH**/ ?>