'use strict';

function truncated(content, max) {
  const maxContentLength = max;
  const truncatedContent = content.length > maxContentLength ? content.slice(0, maxContentLength) + '...' : content;
  return truncatedContent;
}

function convertToIdr(price) {
  const formattedCapital = price.toLocaleString('id-ID', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
  });
  return formattedCapital;
}

// ajax setup
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

// Datatable (jquery)
$(function () {
  var dt_game_table = $('.datatables-games'),
    select2 = $('.select2'),
    offCanvasForm = $('#offcanvasEditProduct'),
    statusObj = {
      0: { title: 'not_active' },
      1: { title: 'active' }
    },
    providerObj = {
      4: { title: 'Vip Reseller' },
      5: { title: 'Digiflazz' }
    },
    statusFilterValObj = {
      0: { title: 'Not Active' },
      1: { title: 'Active' }
    };

  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select value',
        dropdownParent: $this.parent()
      });
    });
  }

  if (dt_game_table.length) {
    var dt_game = dt_game_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + '/product/game-list',
        data: function (d) {
          d.status = $('#filterStatus').val();
          d.provider = $('#filterProvider').val();
          d.category = $('#filterCategory').val();
        }
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'image' },
        { data: 'code' },
        { data: 'item' },
        { data: 'category' },
        { data: 'capital' },
        { data: 'selling' },
        { data: 'reseller' },
        { data: 'provider' },
        { data: 'status' },
        { data: 'action' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          searchable: false,
          orderable: false,
          targets: 1,
          render: function (data, type, full, meta) {
            return `<span>${full.fake_id}</span>`;
          }
        },
        {
          // image
          targets: 2,
          render: function (data, type, full, meta) {
            const $id = full['id'];
            const $image = full['image'];
            let $output = '';

            if ($image) {
              $output = `<img src="../storage/assets/img/product/${$image}" alt="Product-${$id}" class="rounded-2">`;
            } else {
              const states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              const stateNum = Math.floor(Math.random() * 6);
              const $state = states[stateNum];
              const $category = full['category'];
              const $initials = ($category.match(/\b\w/g) || []).map(match => match.toUpperCase()).join('');
              $output = `<span class="avatar-initial rounded-2 bg-label-${$state}">${$initials}</span>`;
            }
            const $row_output = `
      <div class="d-flex justify-content-start align-items-center product-name">
        <div class="avatar-wrapper me-3">
          <div class="avatar rounded-2 bg-label-secondary">
            ${$output}
          </div>
        </div>
      </div>`;
            return $row_output;
          }
        },
        {
          // code
          targets: 3,
          render: function (data, type, full, meta) {
            return `<span>${full.code}</span>`;
          }
        },
        {
          // item
          targets: 4,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            return `<span>${full.item}</span>`;
          }
        },
        {
          // category
          targets: 5,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            return `<span>${full.category}</span>`;
          }
        },
        {
          // capital price
          targets: 6,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            // Konversi nilai full.capital ke format mata uang Indonesia tanpa simbol dan nol di belakang koma
            const cp = convertToIdr(full.capital);

            // Return string dengan format mata uang Indonesia tanpa simbol dan nol di belakang koma
            return `<span>${cp}</span>`;
          }
        },
        {
          // selling price
          targets: 7,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            // Konversi nilai full.capital ke format mata uang Indonesia tanpa simbol dan nol di belakang koma
            const cs = convertToIdr(full.selling);

            // Return string dengan format mata uang Indonesia tanpa simbol dan nol di belakang koma
            return `<span>${cs}</span>`;
          }
        },
        {
          // reseller price
          targets: 8,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            // Konversi nilai full.capital ke format mata uang Indonesia tanpa simbol dan nol di belakang koma
            const cr = convertToIdr(full.reseller);

            // Return string dengan format mata uang Indonesia tanpa simbol dan nol di belakang koma
            return `<span>${cr}</span>`;
          }
        },
        {
          // provider
          targets: 9,
          render: function (data, type, full, meta) {
            var $provider = full['provider'];
            return '<span>' + providerObj[$provider].title + '</span>';
          }
        },
        {
          // Status
          targets: 10,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $status = full['status'];
            var statusSwitchObj = {
              not_active:
                '<label class="switch switch-primary switch-sm">' +
                `<input type="checkbox" class="switch-input" id="switch" data-id="${full['id']}">` +
                '<span class="switch-toggle-slider">' +
                '<span class="switch-off">' +
                '</span>' +
                '</span>' +
                '</label>',
              active:
                '<label class="switch switch-primary switch-sm">' +
                `<input type="checkbox" class="switch-input" checked="" data-id="${full['id']}">` +
                '<span class="switch-toggle-slider">' +
                '<span class="switch-on">' +
                '</span>' +
                '</span>' +
                '</label>'
            };
            return (
              "<span class='text-truncate' >" +
              statusSwitchObj[statusObj[$status].title] +
              '<span class="d-none">' +
              statusObj[$status].title +
              '</span>' +
              '</span>'
            );
          }
        },
        {
          // Actions
          targets: -1,
          responsivePriority: 5,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var edit = baseUrl + 'product/game' + full['id'] + '/edit';
            return (
              '<div class="d-inline-block text-nowrap">' +
              `<button class="btn btn-sm btn-icon edit-record" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEditProduct"><i class="mdi mdi-pencil-outline mdi-20px"></i></button>` +
              '</div>'
            );
          }
        }
      ],
      order: [[5, 'asc']],
      dom:
        '<"row mx-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search..'
      },
      // Buttons with Dropdown
      buttons: [],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['title'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }

  $('.filter').on('change', function () {
    var statusFilter = $('#filterStatus').val();
    var categoryFilter = $('#filterCategory').val();
    var providerFilter = $('#filterProvider').val();

    // Lakukan filtering untuk setiap kondisi filter yang dipilih
    dt_game.column(10).search(statusFilter).column(5).search(categoryFilter).column(9).search(providerFilter).draw();
  });

  // form
  const editProductForm = document.getElementById('editProductForm');

  // product form validation
  const fv = FormValidation.formValidation(editProductForm, {
    fields: {
      item: {
        validators: {
          notEmpty: {
            message: 'Please insert item name'
          }
        }
      },
      selling: {
        validators: {
          notEmpty: {
            message: 'Please insert selling price'
          }
        }
      },
      reseller: {
        validators: {
          notEmpty: {
            message: 'Please insert reseller price'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-4';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    // adding or updating category when form successfully validate
    var formData = new FormData($('#editProductForm')[0]);

    $.ajax({
      data: formData,
      url: `${baseUrl}product/game-list`,
      type: 'POST',
      contentType: false,
      processData: false,
      success: function (response) {
        dt_game.draw();
        offCanvasForm.offcanvas('hide');

        // sweetalert
        Swal.fire({
          icon: 'success',
          title: `${response.message}`,
          text: `${response.message}`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function (err) {
        console.log(err.responseJSON.message);
        offCanvasForm.offcanvas('hide');
        Swal.fire({
          title: 'Duplicate Entry!',
          text: 'Sort Order Already Use.',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  $(document).on('click', '.edit-record', function () {
    var product_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // get data
    $.get(`${baseUrl}product/game-list\/${product_id}\/edit`, function (data) {
      let provider = '';
      if (data.jenis == 4) {
        provider = 'Vip Reseller';
      } else if (data.jenis == 5) {
        provider = 'Digiflazz';
      }
      $('#product_id').val(data.id);
      $('#edit-code').val(data.code);
      $('#edit-item').val(data.title);
      $('#edit-category').val(data.kategori);
      $('#edit-capital').val(data.harga_modal);
      $('#edit-selling').val(data.harga_jual);
      $('#edit-reseller').val(data.harga_reseller);
      $('#edit-provider').val(provider);
    });
  });

  // Update Status
  $(document).on('change', '.switch-input', function () {
    var id = $(this).data('id');

    $.ajax({
      method: 'PUT',
      url: `${baseUrl}product/game-list/${id}`,
      data: {
        newStatus: $(this).is(':checked') ? 1 : 0
      },
      success: function (response) {
        dt_game.draw();

        // sweetalert
        Swal.fire({
          icon: 'success',
          title: `${response.title}`,
          text: `${response.message}`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function (error) {
        console.error('Terjadi kesalahan dalam permintaan AJAX:');
        console.error(error);
      }
    });
  });

  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
  });
});
