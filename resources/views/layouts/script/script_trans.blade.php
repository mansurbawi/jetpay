    <script src="{{ asset('style/vendors/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset('style/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ asset('style/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('style/assets/js/main.js')}}"></script>
    <script src="{{ asset('style/vendors/chart.js/dist/Chart.bundle.min.js')}}"></script>
    <script src="{{ asset('style/assets/js/dashboard.js')}}"></script>
    <script src="{{ asset('style/assets/js/widgets.js')}}"></script>
    <script src="{{ asset('style/vendors/jqvmap/dist/jquery.vmap.min.js')}}"></script>
    <script src="{{ asset('style/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
    <script src="{{ asset('style/vendors/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>

    <script>
    jQuery(document).ready(function () {
        jQuery('#trans').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('alltrans') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "tanggaltransaksi" },
                { "data": "nomorpembayaran" },
                { "data": "nomorjurnalpembukuan" },
                { "data": "nama" },
                { "data": "totalnominal" },
                { "data": "choices" },
                { "data": "created_at" },
                { "data": "action" }
            ]    

        });
    });        
    </script>