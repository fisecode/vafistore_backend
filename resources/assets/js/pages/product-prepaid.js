'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    // Update/reset user image of account page
    let accountUserImage = document.getElementById('uploadedImage');
    const fileInput = document.querySelector('.account-file-input');
    const resetFileInput = document.querySelector('.account-image-reset');

    if (accountUserImage) {
      fileInput.onchange = () => {
        if (fileInput.files[0]) {
          accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
          $('#uploadedImage').removeClass('hide-item'); // Show the image
          resetFileInput.style.display = 'block'; // Show the Reset button
          resetFileInput.classList.add('btn');
        }
      };

      resetFileInput.onclick = () => {
        let pathImage = document.getElementById('pathImage').value;
        let resetImage = '';
        fileInput.value = '';
        if (pathImage) {
          resetImage = storagePath + 'img/product/item/' + pathImage;
          $('#uploadedImage').removeClass('hide-item');
        } else {
          $('#uploadedImage').addClass('hide-item');
        }
        accountUserImage.src = resetImage;
        resetFileInput.style.display = 'none'; // Hide the Reset button
        resetFileInput.classList.remove('btn');
      };
    }
  })();
});

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
  var dt_prepaid_table = $('.datatables-product'),
    select1 = $('#filterBrand'),
    select2 = $('#filterProvider'),
    select3 = $('#filterStatus'),
    select4 = $('#bulkStatus'),
    select5 = $('#filterCategory'),
    offCanvasForm = $('#offcanvasEditProduct'),
    statusObj = {
      0: { title: 'not_active' },
      1: { title: 'active' }
    },
    providerObj = {
      4: { title: 'Vip Reseller' },
      5: { title: 'Digiflazz' }
    };

  if (select1.length) {
    select1.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Brands',
        dropdownParent: $this.parent()
      });
    });
  }
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Provider',
        dropdownParent: $this.parent()
      });
    });
  }
  if (select3.length) {
    select3.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Status',
        dropdownParent: $this.parent()
      });
    });
  }
  if (select4.length) {
    select4.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Status',
        dropdownParent: $this.parent()
      });
    });
  }
  if (select5.length) {
    select5.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Category',
        dropdownParent: $this.parent()
      });
    });
  }

  if (dt_prepaid_table.length) {
    var dt_prepaid = dt_prepaid_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'dashboard/product/prepaid-list',
        data: function (d) {
          d.status = $('#filterStatus').val();
          d.provider = $('#filterProvider').val();
          d.brand = $('#filterBrand').val();
          d.category = $('#filterCategory').val();
        }
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'item' },
        { data: 'brand' },
        { data: 'capital' },
        { data: 'selling' },
        { data: 'reseller' },
        { data: 'category' },
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
          responsivePriority: 3,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // For Checkboxes
          targets: 1,
          orderable: false,
          responsivePriority: 2,
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          },
          render: function (data) {
            return '<input type="checkbox" class="dt-checkboxes form-check-input" data-id="' + data + '">';
          },
          searchable: false
        },
        {
          // product
          targets: 2,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            const $id = full['id'];
            const $image = full['image'];
            const $item = full['item'];
            const $code = full['code'];
            let $output = '';

            if ($image) {
              $output = `<img src="${storagePath}img/product/item/${$image}" alt="Product-${$id}" class="rounded-2">`;
            } else {
              const states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              const stateNum = Math.floor(Math.random() * 6);
              const $state = states[stateNum];
              const $brand = full['brand'];
              const $initials = ($brand.match(/\b\w/g) || []).map(match => match.toUpperCase()).join('');
              $output = `<span class="avatar-initial rounded-2 bg-label-${$state}">${$initials}</span>`;
            }

            const truncatedContent = truncated($code, 30);
            const truncatedTitle = truncated($item, 30);
            const $row_output = `
      <div class="d-flex justify-content-start align-items-center product-name">
        <div class="avatar-wrapper me-3">
          <div class="avatar rounded-2 bg-label-secondary">
            ${$output}
          </div>
        </div>
        <div class="d-flex flex-column">
          <span class="text-nowrap text-heading fw-medium">
            ${truncatedTitle}
          </span>
          <small class="text-truncate d-none d-sm-block">
            ${truncatedContent}
          </small>
        </div>
      </div>`;
            return $row_output;
          }
        },
        {
          // brand
          targets: 3,
          render: function (data, type, full, meta) {
            return `<span>${full.brand}</span>`;
          }
        },
        {
          // capital price
          targets: 4,
          render: function (data, type, full, meta) {
            // Konversi nilai full.capital ke format mata uang Indonesia tanpa simbol dan nol di belakang koma
            const cp = convertToIdr(full.capital);

            // Return string dengan format mata uang Indonesia tanpa simbol dan nol di belakang koma
            return `<span>${cp}</span>`;
          }
        },
        {
          // selling price
          targets: 5,
          render: function (data, type, full, meta) {
            // Konversi nilai full.capital ke format mata uang Indonesia tanpa simbol dan nol di belakang koma
            const cs = convertToIdr(full.selling);

            // Return string dengan format mata uang Indonesia tanpa simbol dan nol di belakang koma
            return `<span>${cs}</span>`;
          }
        },
        {
          // reseller price
          targets: 6,
          render: function (data, type, full, meta) {
            // Konversi nilai full.capital ke format mata uang Indonesia tanpa simbol dan nol di belakang koma
            const cr = convertToIdr(full.reseller);

            // Return string dengan format mata uang Indonesia tanpa simbol dan nol di belakang koma
            return `<span>${cr}</span>`;
          }
        },
        {
          // provider
          targets: 8,
          render: function (data, type, full, meta) {
            var $provider = full['provider'];
            return '<span>' + providerObj[$provider].title + '</span>';
          }
        },
        {
          // Status
          targets: 9,
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
            var edit = baseUrl + 'dashboard/product/prepaid' + full['id'] + '/edit';
            return (
              '<div class="d-inline-block text-nowrap">' +
              `<button class="btn btn-sm btn-icon edit-record" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEditProduct"><i class="mdi mdi-pencil-outline mdi-20px"></i></button>` +
              '</div>'
            );
          }
        }
      ],
      order: [[3, 'asc']],
      dom:
        '<"card-header d-flex border-top rounded-0 flex-wrap py-md-0"' +
        '<"me-5 ms-n2"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0 gap-3"lB>>' +
        '>t' +
        '<"row mx-1"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 25, 50, 75, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Product',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      },
      // Buttons with Dropdown
      buttons: [
        {
          text: '<i class="mdi mdi-pencil-outline me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Bulk Edit</span>',
          className: 'bulk-edit btn btn-primary ms-n1'
          // attr: {
          //   'data-bs-toggle': 'modal',
          //   'data-bs-target': '#bulkEditModal'
          // }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['code'];
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
    $('.dataTables_length').addClass('mt-0 mt-md-3');
    $('.dt-action-buttons').addClass('pt-0');
    $('.dt-buttons').addClass('d-flex flex-wrap');
  }

  $('.filter').on('change', function () {
    var statusFilter = $('#filterStatus').val();
    var categoryFilter = $('#filterCategory').val();
    var providerFilter = $('#filterProvider').val();

    // Lakukan filtering untuk setiap kondisi filter yang dipilih
    dt_prepaid.column(10).search(statusFilter).column(5).search(categoryFilter).column(9).search(providerFilter).draw();
  });

  toastr.options = {
    timeOut: 5000,
    positionClass: 'toast-top-center',
    showDuration: '300',
    hideDuration: '1000'
  };

  $('.bulk-edit').on('click', function () {
    var selectedIds = [];
    $('.form-check-input:checked').each(function () {
      var id = $(this).data('id');
      if (id !== undefined) {
        selectedIds.push(id);
      }
    });

    if (selectedIds.length > 0) {
      $('#ids').val(selectedIds.join(',')); // Memasukkan ID langsung ke dalam elemen dengan ID 'ids'
      $('#bulkEditModal').modal('show');
    } else {
      toastr.info('Pilih setidaknya satu item untuk diedit.', '');
    }
  });

  $('#bulkEditModal').on('hidden.bs.modal', function () {
    // Membersihkan checkbox setelah sukses dan modal tertutup
    $('.form-check-input:checked').prop('checked', false);
    $('.form-check-input:indeterminate').prop('indeterminate', false);
    $('#bulkEditModal input, #bulkEditModal select').val('');
    $('#bulkStatus').val(null).trigger('change');
  });

  $('.btn-save-changes').on('click', function () {
    // Mendapatkan nilai dari formulir atau modal
    var bulkCategory = $('#bulkCategory').val();
    var bulkStatus = $('#bulkStatus').val();
    var selectedIds = $('#ids').val();

    // Lakukan permintaan AJAX ke server untuk menyimpan data
    $.ajax({
      url: `${baseUrl}dashboard/product/prepaid/save-bulk-edit`,
      method: 'POST', // Sesuaikan dengan metode yang sesuai
      data: {
        ids: selectedIds,
        bulkCategory: bulkCategory,
        bulkStatus: bulkStatus
        // Tambahkan data lainnya sesuai kebutuhan
      },
      success: function (response) {
        // Handle response dari server
        // Misalnya, tampilkan pesan sukses, refresh halaman, atau lakukan yang lainnya
        dt_prepaid.draw();
        toastr.success(response.message);
        $('#bulkEditModal').modal('hide'); // Menutup modal setelah penyimpanan berhasil
      },
      error: function (response) {
        // Handle error dari server
        toastr.success(response.message);
        // Tampilkan pesan error atau lakukan yang lainnya
      }
    });
  });

  // form
  const editProductForm = document.getElementById('editProductForm');

  // product form validation
  const fv = FormValidation.formValidation(editProductForm, {
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
      item: {
        validators: {
          notEmpty: {
            message: 'Please insert item name'
          }
        }
      },
      category: {
        validators: {
          notEmpty: {
            message: 'Please insert category'
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
      url: `${baseUrl}dashboard/product/prepaid-list`,
      type: 'POST',
      contentType: false,
      processData: false,
      success: function (response) {
        dt_prepaid.draw();
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

  $(document).on('click', '.edit-record', function () {
    var product_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // get data
    $.get(`${baseUrl}dashboard/product/prepaid-list\/${product_id}\/edit`, function (data) {
      let provider = '';
      if (data.provider == 4) {
        provider = 'Vip Reseller';
      } else if (data.provider == 5) {
        provider = 'Digiflazz';
      }
      if (data.image) {
        $('#uploadedImage').attr('src', storagePath + 'img/product/item/' + data.image);
        $('#uploadedImage').removeClass('hide-item'); // Hapus kelas 'hide-item'
      } else {
        $('#uploadedImage').attr('src', '');
        $('#uploadedImage').addClass('hide-item');
      }

      $('#product_id').val(data.id);
      $('#pathImage').val(data.image);
      $('#edit-code').val(data.code);
      $('#edit-item').val(data.item);
      $('#edit-brand').val(data.brand);
      $('#edit-category').val(data.category);
      $('#edit-capital').val(data.capital_price);
      $('#edit-selling').val(data.selling_price);
      $('#edit-reseller').val(data.reseller_price);
      $('#edit-provider').val(provider);
    });
  });

  // Update Status
  $(document).on('change', '.switch-input', function () {
    var id = $(this).data('id');

    $.ajax({
      method: 'PUT',
      url: `${baseUrl}dashboard/product/prepaid-list/${id}`,
      data: {
        newStatus: $(this).is(':checked') ? 1 : 0
      },
      success: function (response) {
        dt_prepaid.draw();

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
    $('#uploadedImage').attr('src', '');
    $('#uploadedImage').addClass('hide-item');
    fv.resetForm(true);
  });
});
