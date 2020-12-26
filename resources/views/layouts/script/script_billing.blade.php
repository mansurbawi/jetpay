    <script src="{{ asset('style/vendors/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset('style/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ asset('style/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('style/assets/js/main.js')}}"></script>
    <script src="{{ asset('style/vendors/chart.js/dist/Chart.bundle.min.js')}}"></script>
    <script src="{{ asset('style/assets/js/dashboard.js')}}"></script>
    <script src="{{ asset('style/assets/js/widgets.js')}}"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
    </script>

   <script>
    jQuery(document).ready(function () {
        jQuery('#billing').DataTable({
            "dom": "Bfrtip",
            "buttons": [
                        {
                            text: 'Import',
                            action: function ( e, dt, node, config ) {
                                window.location.assign("/import")
                            }
                        },
                        {
                            text: 'Create',
                            action: function ( e, dt, node, config ) {
                                window.location.assign("/create")
                            }
                        },
                                    {
                            text: 'Delete',
                            action: function ( e, dt, node, config ) {
                                window.location.assign("/delete")
                            }
                        }             
                        ],             
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('billall') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "nomorpembayaran" },
                { "data": "updated_at" },
                { "data": "nama" },
                { "data": "tipe" },
                { "data": "status" },
                { "data": "prioritas" },
                { "data": "totalnominal" },
                { "data": "sisa" },
                { "data": "created_at" },
                { "data": "action" }
            ]
  
         });
    });
</script>
<!-- <script>
(function ($) {
    $('#billing').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Import',
                action: function ( e, dt, node, config ) {
                    window.location.assign("/import")
                }
            },
            {
                text: 'Create',
                action: function ( e, dt, node, config ) {
                    window.location.assign("/create")
                }
            },
                        {
                text: 'Delete',
                action: function ( e, dt, node, config ) {
                    window.location.assign("/delete")
                }
            }                
        ]
    });    
});    
</script> -->