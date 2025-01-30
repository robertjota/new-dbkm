/*
 *  Document   : be_tables_datatables.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in DataTables Page
 */

// DataTables, for more examples you can check out https://www.datatables.net/
class pageTablesDatatables {
  /*
   * Init DataTables functionality
   *
   */
  static initDataTables() {
    // Override a few default classes
    jQuery.extend(jQuery.fn.DataTable.ext.classes, {
      sWrapper: "dataTables_wrapper dt-bootstrap5",
      sFilterInput: "form-control form-control-sm",
      sLengthSelect: "form-select form-select-sm"
    });

    // Override a few defaults
    jQuery.extend(true, jQuery.fn.DataTable.defaults, {
      language: {
        lengthMenu: "_MENU_",
        search: "_INPUT_",
        searchPlaceholder: "Buscar...",
        emptyTable: "No hay información",
        info: "Página <strong>_PAGE_</strong> de <strong>_PAGES_</strong>",
        infoEmpty: "Página <strong>1</strong> de <strong>1</strong>",
        zeroRecords: "Sin resultados encontrados",
        paginate: {
          first: '<i class="fa fa-angle-double-left"></i>',
          previous: '<i class="fa fa-angle-left"></i>',
          next: '<i class="fa fa-angle-right"></i>',
          last: '<i class="fa fa-angle-double-right"></i>'
        }
      },
      responsive: true
    });

    // Override buttons default classes
    jQuery.extend(true, jQuery.fn.DataTable.Buttons.defaults, {
      dom: {
        button: {
          className: 'btn btn-sm btn-primary'
        },
      }
    });

    // Init full DataTable
    jQuery('.js-dataTable-full').DataTable({
      pageLength: 15,
      lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]],
      autoWidth: false,

    });

    // Init full extra DataTable
    jQuery('.js-dataTable-full-pagination').DataTable({
      pagingType: "full_numbers",
      pageLength: 15,
      lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]],
      autoWidth: false,

    });

    // Init simple DataTable
    jQuery('.js-dataTable-simple').DataTable({
      pageLength: 15,
      lengthMenu: false,
      searching: false,
      autoWidth: false,
      dom: "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6'p>>",

    });

    // Init DataTable with Buttons
    jQuery('.js-dataTable-buttons').DataTable({
      pageLength: 15,
      lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]],
      autoWidth: false,
      // buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      buttons: [

        /* {
          extend: 'copy',
          className: 'btn btn-sm btn-light',
          text: 'Copiar'
        }, */
        /* {
          extend: 'csv',
          className: 'btn btn-sm btn-light',
          text: 'CSV'
        }, */
        {
          extend: 'excel',
          className: 'btn btn-sm btn-secondary',
          text: 'Excel'
        },
        {
          extend: 'pdf',
          download: 'open',
          className: 'btn btn-sm btn-danger',
          pageSize: 'LETTER',
          title: 'Reporte',
        },
        {
          extend: 'print',
          className: 'btn btn-sm btn-success',
          text: 'Imprimir'
        }
    ],
      dom: "<'row'<'col-sm-12'<'bg-body-light py-2 mb-2'B>>>" +
        "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

    });

    // Init responsive DataTable
    jQuery('.js-dataTable-responsive').DataTable({
      pagingType: "full_numbers",
      pageLength: 15,
      lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]],
      autoWidth: false,

    });
  }

  /*
   * Init functionality
   *
   */
  static init() {
    this.initDataTables();
  }
}

// Initialize when page loads
One.onLoad(() => pageTablesDatatables.init());
