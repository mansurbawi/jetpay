@extends('layouts.bill')
@section('content')

 <div class="content mt-3">
 	<div class="animated fadeIn">
		 <div class="col-lg-12">
		 	<div class="card">
		 		<form action="{{ url('billings/update') }}"  class="form-horizontal" method="post">{{ csrf_field() }}
		 		<div class="card-header">Create <strong>Virtual Account</strong></div>
		 		@foreach ($post as $posts)
		 		<div class="card-body">
		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="va_number" class=" form-control-label">Virtual Account Number</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="va_number" name="va_number" placeholder="Virtual Account Number" class="form-control" value="{{ $posts->nomorpembayaran }}"><input type="hidden" name="id" value="{{$posts->billings_id}}"> </div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="nama" class=" form-control-label">Nama</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="nama" name="nama" placeholder="Nama" class="form-control" value="{{ $posts->nama }}"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="no_induk" class=" form-control-label">Nomor Induk</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="no_induk" name="no_induk" placeholder="Nomor Induk" class="form-control" value="{{ $posts->nomorinduk }}"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="fakultas" class=" form-control-label">Fakultas</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="fakultas" name="fakultas" placeholder="Fakultas" class="form-control" value="{{ $posts->fakultas }}"></div>
		 				</div>		 						 						 				

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="jurusan" class=" form-control-label">Jurusan</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="jurusan" name="jurusan" placeholder="Jurusan" class="form-control" value="{{ $posts->jurusan }}"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="strata" class=" form-control-label">Strata</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="strata" name="strata" placeholder="Strata" class="form-control" value="{{ $posts->strata }}"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="periode" class=" form-control-label">Periode</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="periode" name="periode" placeholder="Periode" class="form-control" value="{{ $posts->periode }}"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="angkatan" class=" form-control-label">Angkatan</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="angkatan" name="angkatan" placeholder="Angkatan" class="form-control" value="{{ $posts->angkatan }}"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="jenis_tagihan" class=" form-control-label">Jenis Tagihan</label></div>
		 					<div class="col-12 col-md-9">
		 						<select data-placeholder="Jenis Tagihan" name="jenis_tagihan" class="standardSelect" tabindex="1" class="form-control">
		 							<option value="{{ $posts->id_type }}">{{ $posts->id_type }}</option>
                                    <option value="full">Close Payment</option>
                                    <option value="open">Open Payment</option>
                                    <option value="partial">Partial</option>
                                    <option value="partial2">Partial 2</option>
                                    <option value="partial3">Partial 3</option>
                                    <option value="partial4">Partial 4</option>
                                    <option value="partial5">Partial 5</option>
                                    <option value="partial6">Partial 6</option>
                                    <option value="partial7">Partial 7</option>
                                    <option value="partial8">Partial 8</option>
                                    <option value="partial9">Partial 9</option>
                                    <option value="partial10">Partial 10</option>
                                    <option value="partial11">Partial 11</option>
                                    <option value="partial12">Partial 12</option>		 							
		 						</select>
		 					</div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="prioritas" class=" form-control-label">Prioritas</label></div>
		 					<div class="col-12 col-md-9">
		 						<select data-placeholder="Prioritas" name = "prioritas" class="standardSelect" tabindex="1" class="form-control">
		 							<option value="{{ $posts->prioritas }}">{{ $posts->prioritas }}</option>
		 							@for ($i = 1; $i < 13; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
									@endfor
		 						</select>
		 					</div>
		 				</div>	 

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="kodetagihan" class=" form-control-label">Kode Tagihan</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="kodetagihan" name="kodetagihan" placeholder="Kode Tagihan" class="form-control" value="{{ $posts->kodedetailtagihan }}"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="desk_pendek" class=" form-control-label">Deskripsi Pendek</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="desk_pendek" name="desk_pendek" placeholder="Deskripsi Pendek" class="form-control" value="{{ $posts->deskripsipendek }}"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="desk_panjang" class=" form-control-label">Deskripsi Panjang</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="desk_panjang" name="desk_panjang" placeholder="Deskripsi Panjang" class="form-control" value="{{ $posts->deskripsipanjang }}"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="nominal" class=" form-control-label">Jumlah Tagihan</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="nominal" name="nominal" placeholder="Jumlah Tagihan" class="form-control" value="{{ $posts->nominal }}"></div>
		 				</div>			 							
		 		</div>
		 		@endforeach
		 		<div class="card-footer">
		 			<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-dot-circle-o"></i> Update</button>
		 			<button type="cancel" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Back</button>
		 		</div>
		 		</form>
		 	</div>

		 </div>
	</div>
</div>
@endsection