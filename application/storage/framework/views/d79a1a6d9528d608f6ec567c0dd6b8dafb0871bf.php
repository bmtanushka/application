<!DOCTYPE html>
<html lang="en" class="<?php echo e(auth()->user()->type ?? ''); ?> <?php echo e(config('visibility.page_rendering')); ?>">

<!--CRM - GROWCRM.IO-->
<?php echo $__env->make('layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body id="main-body"
    class="loggedin fix-header card-no-border fix-sidebar <?php echo e(config('settings.css_kanban')); ?> <?php echo e(runtimePreferenceLeftmenuPosition(auth()->user()->left_menu_position)); ?> <?php echo e($page['page'] ?? ''); ?>">
    <script type="module">
      // Import the functions you need from the SDKs you need
      import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
      //import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-analytics.js";
      import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-messaging.js";
      // TODO: Add SDKs for Firebase products that you want to use
      // https://firebase.google.com/docs/web/setup#available-libraries
    
      // Your web app's Firebase configuration
      // For Firebase JS SDK v7.20.0 and later, measurementId is optional
      const firebaseConfig = {
        apiKey: "AIzaSyADenDJgSjk6hzWCe078azfpJkLUtf1_SI",
        authDomain: "task-meister-e16df.firebaseapp.com",
        projectId: "task-meister-e16df",
        storageBucket: "task-meister-e16df.appspot.com",
        messagingSenderId: "502482932312",
        appId: "1:502482932312:web:10d40f0721c67c41c7b02d",
        measurementId: "G-WR57JEY6CJ"
      };
    
      // Initialize Firebase
      const app = initializeApp(firebaseConfig);
      //const analytics = getAnalytics(app);
      const messaging = getMessaging(app);
      
      navigator.serviceWorker.register("sw.js").then(registration => {
      
        getToken(messaging, { 
            serviceWorkerRegistration: registration,
            vapidKey: 'BK925fvEwkZtcmq5DQXabGLcyH8ein2T-F9EfgQsvg-uUB7zJ7roCA55_79HzH72pD8PsY1NjfiKIrx-T5QUsG0' }).then((currentToken) => {
        if (currentToken) {
            console.log("Token is: "+currentToken);
            $.ajax({
               type:'POST',
               url:'/pushtoken',
               data: {
                "_token": "<?php echo e(csrf_token()); ?>",
                "push_token": currentToken
                },
               success:function(data) {
                   console.log(data.msg);
               }
            });
        } else {
            console.log('No registration token available. Request permission to generate one.');
        }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
        });
        
      });
      
    </script>
    <!--main wrapper-->
    <div id="main-wrapper">


        <!---------------------------------------------------------------------------------------
            [NEXTLOOP}
             always collapse left menu for small devices
            (NB: this code is in the correct place. It must run before menu is added to DOM)
         --------------------------------------------------------------------------------------->

        <!--top nav-->
        <?php echo $__env->make('nav.topnav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <?php echo $__env->make('nav.leftmenu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!--top nav-->


        <!--page wrapper-->
        <div class="page-wrapper">

            <!--overlay-->
            <div class="page-wrapper-overlay js-close-side-panels hidden" data-target=""></div>
            <!--overlay-->

            <!--preloader-->
            <?php if(config('visibility.page_rendering') == '' || config('visibility.page_rendering') != 'print-page'): ?>
            <div class="preloader">
                <div class="loader">
                    <div class="loader-loading"></div>
                </div>
            </div>
            <?php endif; ?>
            <!--preloader-->


            <!-- main content -->
            <?php echo $__env->yieldContent('content'); ?>
            <!-- /#main content -->


            <!--reminders panel-->
            <?php echo $__env->make('pages.reminders.misc.reminder-panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!--notifications panel-->
            <?php echo $__env->make('nav.notifications-panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <!--page wrapper-->
    </div>

    <!--common modals-->
    <?php echo $__env->make('modals.actions-modal-wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('modals.common-modal-wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('modals.plain-modal-wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.search.modal.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.authentication.modal.relogin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!--selector - modals-->
    <?php echo $__env->make('modals.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <!--js footer-->
    <?php echo $__env->make('layout.footerjs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!--js automations-->
    <?php echo $__env->make('layout.automationjs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!--[note: no sanitizing required] for this trusted content, which is added by the admin-->
    <?php echo config('system.settings_theme_body'); ?>

    <div class="modal" aria-labelledby="foo" id="viewModal1" style="display: none;" aria-hidden="false">
      <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">View Attachment</h5>
            <a type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            <a type="button" href="" class="btn btn-primary" id="modalDownloadImg">Download</a>
            <a type="button" href="" target="_new" class="btn btn-primary" id="modalDiscussImg">Discussion</a>
          </div>
          <div class="modal-body" style="padding: 2px;">
            <img src="" id="modalBodyImg"/>
          </div>
          <div class="modal-footer">
    
          </div>
        </div>
      </div>
    </div>
    <div class="modal" aria-labelledby="foo" id="viewModal2" style="display: none;" aria-hidden="false">
      <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">View Attachment</h5>
            <a type="button" class="btn btn-primary" data-dismiss="modal">Close</a>
            <a type="button" href="" class="btn btn-primary" id="modalDownloadPdf">Download</a>
            <a type="button" href="" target="_new" class="btn btn-primary" id="modalDiscussPdf">Discussion</a>
          </div>
          <div class="modal-body" style="padding: 2px;">
            <embed id="modalBodyEmbed" src="" style="width:100%;height:80vh;" type="application/pdf"></embed>
            </div>
          <div class="modal-footer">
    
          </div>
        </div>
      </div>
    </div>
    <script>
    $('#viewModal1').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var src = button.data('src') // Extract info from data-* attributes
      var dwld = button.data('download')
      var discussion = button.data('discussion')
      var modal = $(this)
      $("#modalBodyImg").attr("src", src);
      $("#modalDownloadImg").attr("href", dwld);
      $("#modalDiscussImg").attr("href", discussion);
    })
    $('#viewModal2').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var src = button.data('src') // Extract info from data-* attributes
      var dwld = button.data('download')
      var discussion = button.data('discussion')
      var modal = $(this)
      $("#modalBodyEmbed").attr("src", src);
      $("#modalDownloadPdf").attr("href", dwld);
      $("#modalDiscussPdf").attr("href", discussion);
    })
    </script>
    <div class="modal" aria-labelledby="foo" id="notifyModal" style="display: none;" aria-hidden="false">
      <div class="modal-dialog" style="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="">Notifications</h5>
            <a type="button" class="btn btn-primary" data-dismiss="modal">Close</a>
          </div>
          <div class="modal-body" style="">
            <h3>You have <?php echo e(auth()->user()->count_unread_notifications); ?> unread Notification.</h3>
                
          <div class="modal-footer">
    
          </div>
        </div>
      </div>
    </div>
    <?php if(1==2 && auth()->user()->count_unread_notifications > 0): ?>
    <script type="text/javascript">
        $(window).on('load', function() {
            //$('#notifyModal').modal('show');
            $('.js-toggle-notifications-panel').click();
        });
    </script>
    <?php endif; ?>
</body>


<!--[PRINTING]-->
<?php if(config('visibility.page_rendering') == 'print-page'): ?>
<script src="public/js/dynamic/print.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<?php endif; ?>

</html><?php /**PATH /home/wec24/public_html/application/resources/views/layout/wrapper.blade.php ENDPATH**/ ?>