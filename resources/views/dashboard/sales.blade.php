<div class="row match-height">
    <div class="col-lg-12 col-12">
        <div class="card card-statistics">
            <div class="card-header">
                <h4 class="card-title">Statistics</h4>
            </div>

            <div class="card-body statistic-body">
                <div class="row">
                    <div class="col-xl-2 col-sm-6 col-12 mb-2 mb-xl-0">
                        <div class="media">
                            <div class="avatar bg-light-primary mr-1">
                                <div class="avatar-content">
                                    <i data-feather="trending-up" class="avatar-icon"></i>
                                </div>
                            </div>
                            <div class="media-body my-auto">
                                <h4 class="font-weight-bolder mb-0">{{ $sales }}</h4>
                                <p class="card-text font-small-3 mb-0">Sales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                        <div class="media">
                            <div class="avatar bg-light-info mr-1">
                                <div class="avatar-content">
                                    <i data-feather="user" class="avatar-icon"></i>
                                </div>
                            </div>
                            <div class="media-body my-auto">
                                <h4 class="font-weight-bolder mb-0">{{$customers}}</h4>
                                <p class="card-text font-small-3 mb-0">Customers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                        <div class="media">
                            <div class="avatar bg-light-danger mr-1">
                                <div class="avatar-content">
                                    <i data-feather="box" class="avatar-icon"></i>
                                </div>
                            </div>
                            <div class="media-body my-auto">
                                <h4 class="font-weight-bolder mb-0">{{$products}}</h4>
                                <p class="card-text font-small-3 mb-0">Catalogs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <b>Jenis Penjualan</b>
        <br><br>
        <div class="table-responsive">
            <table class=" table table-condensed table-striped table-bordered">
                @foreach($jenis_penjualan as $jenis)
                <tr>
                    <th style="width: 50%">{{ $jenis->jenis_penjualan }}</th>
                    <td>{{ $jenis->count }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>