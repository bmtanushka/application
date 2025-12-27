<!-- Invoice - Due-->
<div class="col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body p-l-15 p-r-15">
            <div class="d-flex p-10 no-block">
                <a href="/invoices?db_filter_bill_due_date_mtd=yes&db_project_status=completed">
                    <span class="align-slef-center">
                        <h2 class="m-b-0">{{ runtimeMoneyFormat($payload['invoices']['due']) }}</h2>
                        <h6 class="text-muted m-b-0">{{ cleanLang(__('lang.invoices')) }} - MTD</h6>
                    </span>
                </a>
                <div class="align-self-center display-6 ml-auto"><i class="text-warning icon-Coin"></i></div>
            </div>
        </div>
        <div class="progress">
            <div class="progress-bar bg-warning w-100 h-px-3" role="progressbar" aria-valuenow="100" aria-valuemin="0"
                aria-valuemax="100"></div>
        </div>
    </div>
</div>