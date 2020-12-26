    $(document).ready(function () {
        $('#trans').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('alltrans') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "tanggalTransaksi" },
                { "data": "nomorPembayaran" },
                { "data": "nomorJurnalPembukuan" },
                { "data": "nama" },
                { "data": "totalNominal" },
                { "data": "choices" },
                { "data": "created_at" },
                { "data": "action" }
            ]	 

        });
    });