function axfInitDataTables () 
{
    // Default Datatable
    $('#datatable').DataTable(
    	{
    		"dom": 'iftps',
            "responsive": true,
            "stateSave": true,
            "paging": false,
            "order": [5, 'desc'],
            "fixedHeader": 
                {
                    "header": true,
                    "headerOffset": 60
                },
            "oLanguage": 
                {
                    "sSearch": "Buscar",
                    "sZeroRecords": "No se ha encontrado ningun registro !!!",
                    "sProcessing": "Ocupado ...",
                    "sLoadingRecords": " - fitrados de _MAX_ registros",
                    "sInfoEmpty": "No se ha encontrado ningun registro !!!",
                    "sEmptyTable": "No se ha encontrado ningun registro !!!",
                    "sLengthMenu": "Mostrando _MENU_ registros",
                    "sInfo": "Registros _START_ a _END_ ",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "oPaginate": 
                        {
                            'sNext':"Página siguiente", 
                            'sPrevious':"Página anterior", 
                            'sLast': "Última página", 
                            'sFirst': "Primera p´´agina"
                        }
                }
            

    	});

};