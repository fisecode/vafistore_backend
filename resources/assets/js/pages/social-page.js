'use strict';

// ajax setup
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

// Datatable (jquery)
$(function () {
  var dt_socials_table = $('.datatables-socials'),
    offCanvasForm = $('#offcanvasAddSocials'),
    statusObj = {
      0: { title: 'not_active' },
      1: { title: 'active' }
    };

  if (dt_socials_table.length) {
    var dt_socials = dt_socials_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'dashboard/social-list'
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'name' },
        { data: 'url' },
        { data: 'icon' },
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
            return `<span>${full.name}</span>`;
          }
        },
        {
          targets: 3,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return `<span>${full.url}</span>`;
          }
        },
        {
          targets: 4,
          responsivePriority: 5,
          render: function (data, type, full, meta) {
            return `<span>${full.icon}</span>`;
          }
        },
        {
          // Status
          targets: 5,
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
              `<button class="btn btn-sm btn-icon edit-record" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddSocials"><i class="mdi mdi-pencil-outline mdi-20px"></i></button>` +
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
          text: '<i class="mdi mdi-plus me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New Socials</span>',
          className: 'add-new btn btn-primary mx-3',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddSocials'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['description'];
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
    var socials_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // changing the title of offcanvas
    $('#offcanvasAddSocialsLabel').html('Edit Socials');

    // get data
    $.get(`${baseUrl}dashboard/social-list\/${socials_id}\/edit`, function (data) {
      $('#socials_id').val(data.id);
      $('#add-socials-name').val(data.name);
      $('#add-socials-url').val(data.url);
      $('#add-socials-icon').val(data.icon);

      if (data.image) {
        $('#uploadedImage').attr('src', storagePath + 'img/socials/' + data.image);
        $('#uploadedImage').removeClass('hide-item'); // Hapus kelas 'hide-item'
      }
    });
  });

  // changing the title
  $('.add-new').on('click', function () {
    $('#socials_id').val('');
    $('#offcanvasAddSocialsLabel').html('Add Socials');
    $('#uploadedImage').attr('src', storagePath + 'img/socials/no-image.jpg');
    $('#uploadedImage').addClass('hide-item');
  });

  const addNewSocialsForm = document.getElementById('addNewSocialsForm');

  // category form validation
  const fv = FormValidation.formValidation(addNewSocialsForm, {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: 'Please enter social media name'
          }
        }
      },
      url: {
        validators: {
          notEmpty: {
            message: 'Please enter url'
          }
        }
      },
      icon: {
        validators: {
          notEmpty: {
            message: 'Please enter icon'
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
    var formData = new FormData($('#addNewSocialsForm')[0]);

    $.ajax({
      data: formData,
      url: `${baseUrl}dashboard/social-list`,
      type: 'POST',
      contentType: false,
      processData: false,
      success: function (response) {
        dt_socials.draw();
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
          text: 'Socials name should be unique.',
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
      url: `${baseUrl}dashboard/social-list/${id}`,
      data: {
        newStatus: $(this).is(':checked') ? 1 : 0
      },
      success: function (response) {
        dt_socials.draw();

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

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var social_id = $(this).data('id'),
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
          url: `${baseUrl}dashboard/social-list/${social_id}`,
          success: function () {
            dt_socials.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The page has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The page is not deleted!',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
});
