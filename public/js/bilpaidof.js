    jQuery(document).ready(function () {
        jQuery('#billpaidof').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "https://pay.jetschool.id/lunasall",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "nomorPembayaran" },
                { "data": "created_at" },
                { "data": "nama" },
                { "data": "tipe" },
                { "data": "status" },
                { "data": "prioritas" },
                { "data": "totalNominal" },
                { "data": "action" }
            ]	 

        });
    });