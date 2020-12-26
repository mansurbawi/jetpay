    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./"><img src="{{ url('style/images/logo.png')}}" alt="Logo"></a>
                <a class="navbar-brand hidden" href="./"><img src="{{ url('style/images/logo2.png')}}" alt="Logo"></a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="{{ url('home')}}"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <h3 class="menu-title">Billing</h3><!-- /.menu-title -->
                     <li>
                        <a href="{{url('billings')}}"> <i class="menu-icon fa fa-th"></i>Billings </a>
                    </li>
                    <li>
                        <a href="{{url('billpaidoff')}}"> <i class="menu-icon ti-book"></i>Bill's Paid Off </a>
                    </li>
                    <li>
                        <a href="{{url('billnoactive')}}"> <i class="menu-icon ti-trash"></i>Bill's not Active </a>
                    </li>

                    <h3 class="menu-title">Transaction</h3><!-- /.menu-title -->

                    <li>
                        <a href="{{url('transaction')}}"> <i class="menu-icon fa fa-table"></i>Transaction </a>
                    </li>
                    <li>
                        <a href="{{url('canceled')}}"> <i class="menu-icon ti-close"></i>Transaction Canceled </a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>
    