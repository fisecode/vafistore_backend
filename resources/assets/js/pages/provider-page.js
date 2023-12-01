'use strict';

// ajax setup
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

// Datatable (jquery)
$(function () {
  var select2 = $('.select2');

  var dt_provider_table = $('.datatables-provider'),
    offCanvasForm = $('#offcanvasAddProvider'),
    statusObj = {
      0: { title: 'not_active' },
      1: { title: 'active' }
    };

  if (dt_provider_table.length) {
    var dt_provider = dt_provider_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'dashboard/api/provider-list'
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'provider' },
        { data: 'api_key' },
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
          targets: 2,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            return `<span>${full.provider}</span>`;
          }
        },
        {
          targets: 3,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            const $id = full['id'];
            const $api_key = full['api_key'];
            const $merchant_code = full['merchant_code'];

            // const truncatedContent = truncated($content, 30);
            // const truncatedTitle = truncated(data, 30);
            const $row_output = `
              <div div class="d-flex justify-content-start align-items-center product-name">
                <div class="d-flex flex-column">
                  <span class="text-nowrap text-heading fw-medium">
                    Api Key / Secret Key :
                  </span>
                  <small class="text-truncate d-none d-sm-block">
                    ${$api_key}
                  </small>
                  <span class="text-nowrap text-heading fw-medium">
                    UserID / API ID / Merchant ID :
                  </span>
                  <small class="text-truncate d-none d-sm-block">
                    ${$merchant_code}
                  </small>
                </div>
              </div>`;
            return $row_output;
          }
        },
        {
          // Status
          targets: 4,
          responsivePriority: 3,
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
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-inline-block text-nowrap">' +
              `<button class="btn btn-sm btn-icon edit-record" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddProvider"><i class="mdi mdi-pencil-outline mdi-20px"></i></button>` +
              `<button class="btn btn-sm btn-icon delete-record" data-id="${full['id']}"><i class="mdi mdi-delete-outline mdi-20px"></i></button>`
            );
          }
        }
      ],
      order: [[2, 'asc']],
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
      buttons: [
        {
          text: '<i class="mdi mdi-plus me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New Provider</span>',
          className: 'add-new btn btn-primary mx-3',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddProvider'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['provider'];
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

  // edit record
  $(document).on('click', '.edit-record', function () {
    var provider_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // changing the title of offcanvas
    $('#offcanvasAddProviderLabel').html('Edit Provider');

    // get data
    $.get(`${baseUrl}dashboard/api/provider-list\/${provider_id}\/edit`, function (data) {
      $('#provider_id').val(data.id);
      $('#add-provider-api').val(data.api_key);
      $('#add-provider-api-id').val(data.merchant_code);
      $('#provider-org').val(data.id).trigger('change');
    });
  });

  // changing the title
  $('.add-new').on('click', function () {
    $('#provider_id').val('');
    $('#offcanvasAddProviderLabel').html('Add Provider');
    $('#provider-org').val('').trigger('change');
  });

  const addNewProviderForm = document.getElementById('addNewProviderForm');
  const providerField = jQuery(addNewProviderForm.querySelector('[name="provider"]'));

  // category form validation
  const fv = FormValidation.formValidation(addNewProviderForm, {
    fields: {
      provider: {
        validators: {
          notEmpty: {
            message: 'Please choose provider'
          }
        }
      },
      apikey: {
        validators: {
          notEmpty: {
            message: 'Please enter provider name'
          }
        }
      },
      merchant_code: {
        validators: {
          notEmpty: {
            message: 'Please enter merchant code'
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
    var formData = new FormData($('#addNewProviderForm')[0]);

    $.ajax({
      data: formData,
      url: `${baseUrl}dashboard/api/provider-list`,
      type: 'POST',
      contentType: false,
      processData: false,
      success: function (response) {
        dt_provider.draw();
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
        offCanvasForm.offcanvas('hide');
        console.log(err.responseJSON.message);
        Swal.fire({
          title: 'Duplicate Entry!',
          text: 'Provider name should be unique.',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
  });

  // Update Status
  $(document).on('change', '.switch-input', function () {
    var id = $(this).data('id');

    $.ajax({
      method: 'PUT',
      url: `${baseUrl}dashboard/api/provider-list/${id}`,
      data: {
        newStatus: $(this).is(':checked') ? 1 : 0
      },
      success: function (response) {
        dt_provider.draw();
        if (response.status === 'error') {
          Swal.fire({
            title: response.title,
            text: response.message,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        } else {
          Swal.fire({
            icon: 'success',
            title: response.title,
            text: response.message,
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        }
      },
      error: function (error) {
        console.error('Terjadi kesalahan dalam permintaan AJAX:');
        console.error(error);
      }
    });
  });

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var provider_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: `${baseUrl}dashboard/api/provider-list/${provider_id}`,
          success: function (response) {
            dt_provider.draw();

            if (response.status === 'error') {
              Swal.fire({
                title: 'Error!',
                text: response.message,
                icon: 'error',
                customClass: {
                  confirmButton: 'btn btn-success'
                }
              });
            } else {
              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: response.message,
                customClass: {
                  confirmButton: 'btn btn-success'
                }
              });
            }
          },
          error: function (error) {
            console.log(error);
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The provider is not deleted!',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
});
