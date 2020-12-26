@extends('layouts.canceled')
@section('content')

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Data Table</strong>
                            </div>
                            <div class="card-body">
                                <table id="canceled" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Trans. Date</th>
                                            <th>Vritual Number</th>
                                            <th>Reference Number</th>
                                            <th>Nama</th>
                                            <th>Jumlah Pembayaran</th>
                                            <th>Payment Type</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>                        </div>
                    </div>

@endsection