'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    // Update/reset user image of account page
    let accountUserImage = document.getElementById('uploadedImage');
    const fileInput = document.querySelector('.account-file-input');
    const resetFileInput = document.querySelector('.account-image-reset');

    if (accountUserImage) {
      const resetImage = accountUserImage.src;

      fileInput.onchange = () => {
        if (fileInput.files[0]) {
          accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
          accountUserImage.style.display = 'block'; // Show the image
          resetFileInput.style.display = 'block'; // Show the Reset button
          resetFileInput.classList.add('btn');
        }
      };

      resetFileInput.onclick = () => {
        fileInput.value = '';
        accountUserImage.src = resetImage;
        accountUserImage.style.display = 'none'; // Hide the image
        resetFileInput.style.display = 'none'; // Hide the Reset button
        resetFileInput.classList.remove('btn');
      };
    }
  })();
});

// ajax setup
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

// Datatable (jquery)
$(function () {
  var dt_slides_table = $('.datatables-slide'),
    offCanvasForm = $('#offcanvasAddSlide'),
    statusObj = {
      0: { title: 'not_active' },
      1: { title: 'active' }
    };

  if (dt_slides_table.length) {
    var dt_slides = dt_slides_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'dashboard/slide-list'
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'image' },
        { data: 'description' },
        { data: 'sort' },
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
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            const $id = full['id'];
            const $image = full['image'];
            let $output = '';

            if ($image) {
              $output = `<img src="${storagePath}img/slides/${$image}" alt="slide-${$id}" class="w-px-250 h-auto d-block">`;
            } else {
              $output = '<span>No Image</span>';
            }
            const $row_output = `${$output}`;
            return $row_output;
          }
        },
        {
          targets: 2,
          responsivePriority: 5,
          render: function (data, type, full, meta) {
            return `<span>${full.description}</span>`;
          }
        },
        {
          targets: 3,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return `<span>${full.sort}</span>`;
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
              `<button class="btn btn-sm btn-icon edit-record" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddSlide"><i class="mdi mdi-pencil-outline mdi-20px"></i></button>` +
              `<button class="btn btn-sm btn-icon delete-record" data-id="${full['id']}"><i class="mdi mdi-delete-outline mdi-20px"></i></button>`
            );
          }
        }
      ],
      order: [[3, 'asc']],
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
          text: '<i class="mdi mdi-plus me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New Slide</span>',
          className: 'add-new btn btn-primary mx-3',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddSlide'
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
    var slide_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // changing the title of offcanvas
    $('#offcanvasAddSlideLabel').html('Edit Slide');

    // get data
    $.get(`${baseUrl}dashboard/slide-list\/${slide_id}\/edit`, function (data) {
      $('#slide_id').val(data.id);
      $('#add-slide-description').val(data.description);
      $('#add-slide-sort').val(data.sort);

      if (data.image) {
        $('#uploadedImage').attr('src', storagePath + 'img/slides/' + data.image);
        $('#uploadedImage').removeClass('hide-item'); // Hapus kelas 'hide-item'
      }
    });
  });

  // changing the title
  $('.add-new').on('click', function () {
    $('#slide_id').val('');
    $('#offcanvasAddSlideLabel').html('Add Slide');
    $('#uploadedImage').attr('src', storagePath + 'img/slides/no-image.jpg');
    $('#uploadedImage').addClass('hide-item');
  });

  const addNewSlideForm = document.getElementById('addNewSlideForm');

  // category form validation
  const fv = FormValidation.formValidation(addNewSlideForm, {
    fields: {
      image: {
        validators: {
          file: {
            extension: 'jpg,jpeg,png',
            type: 'image/jpeg,image/png',
            maxSize: 800000,
            message: 'The selected file is not valid'
          }
        }
      },
      description: {
        validators: {
          notEmpty: {
            message: 'Please enter description'
          }
        }
      },
      sortOrder: {
        validators: {
          notEmpty: {
            message: 'Please enter sort order'
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
    var formData = new FormData($('#addNewSlideForm')[0]);

    $.ajax({
      data: formData,
      url: `${baseUrl}dashboard/slide-list`,
      type: 'POST',
      contentType: false,
      processData: false,
      success: function (response) {
        dt_slides.draw();
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
          text: `${err.responseJSON.message}`,
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
      url: `${baseUrl}dashboard/slide-list/${id}`,
      data: {
        newStatus: $(this).is(':checked') ? 1 : 0
      },
      success: function (response) {
        dt_slides.draw();

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
    var page_id = $(this).data('id'),
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
          url: `${baseUrl}dashboard/slide-list/${page_id}`,
          success: function () {
            dt_slides.draw();
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

  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    $('#uploadedImage').attr('src', '');
    $('#uploadedImage').addClass('hide-item');
    fv.resetForm(true);
  });
});
