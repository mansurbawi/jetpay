@extends('layouts.bill')
@section('content')

 <div class="content mt-3">
    @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
    @endif 	
 	<div class="animated fadeIn">
		 <div class="col-lg-12">
		 	<div class="card">
		 		<form action="{{ url('billing/save') }}"  class="form-horizontal" method="post">{{ csrf_field() }}
		 		<div class="card-header">Create <strong>Virtual Account</strong></div>
		 		<div class="card-body">
		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="va_number" class=" form-control-label">Virtual Account Number</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="va_number" name="va_number" placeholder="Virtual Account Number" class="form-control"></div>
							<span style="color: red">@error('va_number'){{$message}}@enderror</span>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="nama" class=" form-control-label">Nama</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="nama" name="nama" placeholder="Nama" class="form-control"></div>
		 					<span style="color: red">@error('nama'){{$message}}@enderror</span>
						</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="no_induk" class=" form-control-label">Nomor Induk</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="no_induk" name="no_induk" placeholder="Nomor Induk" class="form-control"></div>
							<span style="color: red">@error('no_induk'){{$message}}@enderror</span>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="fakultas" class=" form-control-label">Fakultas</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="fakultas" name="fakultas" placeholder="Fakultas" class="form-control"></div>
							<span style="color: red">@error('fakultas'){{$message}}@enderror</span>
		 				</div>		 						 						 				

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="jurusan" class=" form-control-label">Jurusan</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="jurusan" name="jurusan" placeholder="Jurusan" class="form-control"></div>
							 <span style="color: red">@error('jurusan'){{$message}}@enderror</span>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="strata" class=" form-control-label">Strata</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="strata" name="strata" placeholder="Strata" class="form-control"></div>
							 <span style="color: red">@error('strata'){{$message}}@enderror</span>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="periode" class=" form-control-label">Periode</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="periode" name="periode" placeholder="Periode" class="form-control"></div>
							 <span style="color: red">@error('periode'){{$message}}@enderror</span>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="angkatan" class=" form-control-label">Angkatan</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="angkatan" name="angkatan" placeholder="Angkatan" class="form-control"></div>
							 <span style="color: red">@error('angkatan'){{$message}}@enderror</span>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="jenis_tagihan" class=" form-control-label">Jenis Tagihan</label></div>
		 					<div class="col-12 col-md-9">
		 						<select data-placeholder="Jenis Tagihan" name="jenis_tagihan" class="standardSelect" tabindex="1" class="form-control">
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
								 <span style="color: red">@error('jenis_tagihan'){{$message}}@enderror</span>
		 					</div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="prioritas" class=" form-control-label">Prioritas</label></div>
		 					<div class="col-12 col-md-9">
		 						<select data-placeholder="Prioritas" name = "prioritas" class="standardSelect" tabindex="1" class="form-control">
		 							@for ($i = 1; $i < 13; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
									@endfor
		 						</select>
								 <span style="color: red">@error('prioritas'){{$message}}@enderror</span>
		 					</div>
		 				</div>	

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="kodetagihan" class=" form-control-label">Kode Tagihan</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="kodetagihan" name="kodetagihan" placeholder="Kode Tagihan" class="form-control"></div>
							 <span style="color: red">@error('kodetagihan'){{$message}}@enderror</span>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="desk_pendek" class=" form-control-label">Deskripsi Pendek</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="desk_pendek" name="desk_pendek" placeholder="Deskripsi Pendek" class="form-control"></div>
							 <span style="color: red">@error('desk_pendek'){{$message}}@enderror</span>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="desk_panjang" class=" form-control-label">Deskripsi Panjang</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="desk_panjang" name="desk_panjang" placeholder="Deskripsi Panjang" class="form-control"></div>
		 				</div>

		 				<div class="row form-group">
		 					<div class="col col-md-3"><label for="nominal" class=" form-control-label">Jumlah Tagihan</label></div>
		 					<div class="col-12 col-md-9"><input type="text" id="nominal" name="nominal" placeholder="Jumlah Tagihan" class="form-control"></div>
		 				</div>		 						 						 				

		 		</div>
		 		<div class="card-footer">
		 			<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-dot-circle-o"></i> Submit</button>
		 			<button type="cancel" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Cancel</button>
		 		</div>
		 		</form>
		 	</div>
		 </div>



	</div>
</div>
@endsection