<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-7 p-b-9 align-self-center text-right {{ $page['list_page_actions_size'] ?? '' }} {{ $page['list_page_container_class'] ?? '' }}"
    id="list-page-actions-container">
    <div id="list-page-actions">
        <!--SEARCH BOX-->
        @if( config('visibility.list_page_actions_search'))
        <div class="header-search" id="header-search">
            <i class="sl-icon-magnifier"></i>
            <input type="text" class="form-control search-records list-actions-search"
                data-url="{{ $page['dynamic_search_url'] ?? '' }}" data-type="form" data-ajax-type="post"
                data-form-id="header-search" id="search_query" name="search_query"
                placeholder="{{ cleanLang(__('lang.search')) }}">
        </div>
        @endif

        <!--TOGGLE STATS-->
        @if( config('visibility.stats_toggle_button'))
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.quick_stats')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-stats-widget update-user-ux-preferences"
            data-type="statspanel" data-progress-bar="hidden"
            data-url-temp="{{ url('/') }}/{{ auth()->user()->team_or_contact }}/updatepreferences" data-url=""
            data-target="list-pages-stats-widget">
            <i class="ti-stats-up"></i>
        </button>
        @endif

        <!--EXPORT-->
        @if(config('visibility.list_page_actions_exporting'))
        <button type="button" data-toggle="tooltip" title="@lang('lang.export_invoices')"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-side-panel"
            data-target="sidepanel-export-invoices">
            <i class="ti-export"></i>
        </button>
        @endif

        <!--FILTERING-->
        @if(config('visibility.list_page_actions_filter_button'))
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.filter')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-side-panel"
            data-target="{{ $page['sidepanel_id'] ?? '' }}">
            <i class="mdi mdi-filter-outline"></i>
        </button>
        @endif
        @if(auth()->user()->is_client)
        @php
        if(isset($_GET['filter_bill_due_date_type']))
            $filter_bill_due_date_type = $_GET['filter_bill_due_date_type'];
        else
            $filter_bill_due_date_type = "";
        if(isset($_GET['filter_bill_due_date_start_client']))    
            $filter_bill_due_date_start_client = $_GET['filter_bill_due_date_start_client'];
        else
            $filter_bill_due_date_start_client = "";
        if(isset($_GET['filter_bill_due_date_end_client']))    
            $filter_bill_due_date_end_client = $_GET['filter_bill_due_date_end_client'];
        else
            $filter_bill_due_date_end_client = "";
        @endphp
        <form action="" method="GET">
            <div class="fields">
                <div class="row">
                    <div class="col-md-4">
                        <select name="filter_bill_due_date_type" class="form-control form-control-sm" autocomplete="off">
                            <option value="custom" @php echo $filter_bill_due_date_type == 'custom' ? 'selected' : ''; @endphp  >Custom</option>
                            <option value="this-month" @php echo $filter_bill_due_date_type == 'this-month' ? 'selected' : ''; @endphp>This Month</option>
                            <option value="last-month" @php echo $filter_bill_due_date_type == 'last-month' ? 'selected' : ''; @endphp>Last Month</option>
                            <option value="this-year" @php echo $filter_bill_due_date_type == 'this-year' ? 'selected' : ''; @endphp>This Year</option>
                            <option value="last-year" @php echo $filter_bill_due_date_type == 'last-year' ? 'selected' : ''; @endphp>Last Year</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="filter_bill_due_date_start_client"
                            class="form-control form-control-sm pickadate" autocomplete="off"
                            placeholder="Start" value="@php echo $filter_bill_due_date_type == 'custom' ? $filter_bill_due_date_start_client : ''; @endphp">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="filter_bill_due_date_end_client"
                            class="form-control form-control-sm pickadate" autocomplete="off" 
                            placeholder="End" value="@php echo $filter_bill_due_date_type == 'custom' ? $filter_bill_due_date_end_client : ''; @endphp">
                    </div>
                    <div class="col-md-2">
                        <input type="submit" name="filter_bill_due_date_client"
                            class="btn-info form-control-sm" value="FILTER"/>
                    </div>
                </div>
            </div>
        </form>
        @php
        $myInvoiceSearchTitle = "Search results for Invoices due date ";
        if($filter_bill_due_date_type == 'custom')
            echo "<h3 class=\"p-t-15\">".$myInvoiceSearchTitle."between ".$filter_bill_due_date_start_client." and ".$filter_bill_due_date_end_client."</h3>";
        else if($filter_bill_due_date_type == 'this-month')
            echo "<h3 class=\"p-t-15\">".$myInvoiceSearchTitle."within this month</h3>";
        else if($filter_bill_due_date_type == 'last-month')
            echo "<h3 class=\"p-t-15\">".$myInvoiceSearchTitle."within previous month</h3>";
        else if($filter_bill_due_date_type == 'this-year')
            echo "<h3 class=\"p-t-15\">".$myInvoiceSearchTitle."within this year</h3>";
        else if($filter_bill_due_date_type == 'last-year')
            echo "<h3 class=\"p-t-15\">".$myInvoiceSearchTitle."within previous year</h3>";
        
        @endphp
        
        @endif
        <!--ADD NEW ITEM-->
        @if(config('visibility.list_page_actions_add_button'))
        <button type="button"
            class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form {{ $page['add_button_classes'] ?? '' }}"
            data-toggle="modal" data-target="#commonModal" data-url="{{ $page['add_modal_create_url'] ?? '' }}"
            data-loading-target="commonModalBody" data-modal-title="{{ $page['add_modal_title'] ?? '' }}"
            data-action-url="{{ $page['add_modal_action_url'] ?? '' }}"
            data-action-method="{{ $page['add_modal_action_method'] ?? '' }}"
            data-action-ajax-class="{{ $page['add_modal_action_ajax_class'] ?? '' }}"
            data-modal-size="{{ $page['add_modal_size'] ?? '' }}"
            data-action-ajax-loading-target="{{ $page['add_modal_action_ajax_loading_target'] ?? '' }}"
            data-save-button-class="{{ $page['add_modal_save_button_class'] ?? '' }}" data-project-progress="0">
            <i class="ti-plus"></i>
        </button>
        @endif

        <!--add new button (link)-->
        @if( config('visibility.list_page_actions_add_button_link'))
        <a id="fx-page-actions-add-button" type="button" class="btn btn-danger btn-add-circle edit-add-modal-button"
            href="{{ $page['add_button_link_url'] ?? '' }}">
            <i class="ti-plus"></i>
        </a>
        @endif
    </div>
</div>