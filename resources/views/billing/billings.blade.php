@extends('layouts.bill')
@section('content')

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Data Table</strong>
                            </div>
                            <div class="card-body">
                                <table id="billing" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Virtual Number</th>
                                            <th>Tanggal</th>
                                            <th>Nama</th>
                                            <th>Type</th>
                                            <th>Finance</th>
                                            <th>Prioritas</th>
                                            <th>Jumlah Tagihan</th>
                                            <th>Sisa</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

@endsection