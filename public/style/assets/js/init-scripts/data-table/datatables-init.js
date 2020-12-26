(function ($) {
    //    "use strict";


    /*  Data Table
    -------------*/

    $('#bootstrap-data-table').DataTable({
        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "All"]],
    });

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

    $('#trans-table').DataTable({
        dom: 'Bfrtip',
        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "All"]],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]    
    });

	$('#row-select').DataTable( {
        initComplete: function () {
				this.api().columns().every( function () {
					var column = this;
					var select = $('<select class="form-control"><option value=""></option></select>')
						.appendTo( $(column.footer()).empty() )
						.on( 'change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);

							column
								.search( val ? '^'+val+'$' : '', true, false )
								.draw();
						} );

					column.data().unique().sort().each( function ( d, j ) {
						select.append( '<option value="'+d+'">'+d+'</option>' )
					} );
				} );
			}
		} );

})(jQuery);
