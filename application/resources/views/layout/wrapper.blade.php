<!DOCTYPE html>
<html lang="en" class="{{ auth()->user()->type ?? '' }} {{ config('visibility.page_rendering') }}">

<!--CRM - GROWCRM.IO-->
@include('layout.header')

<body id="main-body"
    class="loggedin fix-header card-no-border fix-sidebar {{ config('settings.css_kanban') }} {{ runtimePreferenceLeftmenuPosition(auth()->user()->left_menu_position) }} {{ $page['page'] ?? '' }}">
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
                "_token": "{{ csrf_token() }}",
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
        @include('nav.topnav') @include('nav.leftmenu')
        <!--top nav-->


        <!--page wrapper-->
        <div class="page-wrapper">

            <!--overlay-->
            <div class="page-wrapper-overlay js-close-side-panels hidden" data-target=""></div>
            <!--overlay-->

            <!--preloader-->
            @if(config('visibility.page_rendering') == '' || config('visibility.page_rendering') != 'print-page')
            <div class="preloader">
                <div class="loader">
                    <div class="loader-loading"></div>
                </div>
            </div>
            @endif
            <!--preloader-->


            <!-- main content -->
            @yield('content')
            <!-- /#main content -->


            <!--reminders panel-->
            @include('pages.reminders.misc.reminder-panel')

            <!--notifications panel-->
            @include('nav.notifications-panel')
        </div>
        <!--page wrapper-->
    </div>

    <!--common modals-->
    @include('modals.actions-modal-wrapper')
    @include('modals.common-modal-wrapper')
    @include('modals.plain-modal-wrapper')
    @include('pages.search.modal.search')
    @include('pages.authentication.modal.relogin')

    <!--selector - modals-->
    @include('modals.create')


    <!--js footer-->
    @include('layout.footerjs')

    <!--js automations-->
    @include('layout.automationjs')

    <!--[note: no sanitizing required] for this trusted content, which is added by the admin-->
    {!! config('system.settings_theme_body') !!}
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
            <h3>You have {{ auth()->user()->count_unread_notifications }} unread Notification.</h3>
                
          <div class="modal-footer">
    
          </div>
        </div>
      </div>
    </div>
    @if(1==2 && auth()->user()->count_unread_notifications > 0)
    <script type="text/javascript">
        $(window).on('load', function() {
            //$('#notifyModal').modal('show');
            $('.js-toggle-notifications-panel').click();
        });
    </script>
    @endif
</body>


<!--[PRINTING]-->
@if(config('visibility.page_rendering') == 'print-page')
<script src="public/js/dynamic/print.js?v={{ config('system.versioning') }}"></script>
@endif

</html>