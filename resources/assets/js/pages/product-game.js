'use strict';

function truncated(content, max) {
  const maxContentLength = max;
  const truncatedContent = content.length > maxContentLength ? content.slice(0, maxContentLength) + '...' : content;
  return truncatedContent;
}

var dt_user_table = $('.datatables-posts'),
  select2 = $('.select2'),
  userView = baseUrl + 'app/user/view/account',
  offCanvasForm = $('#offcanvasAddCategory'),
  statusObj = {
    0: { title: 'not_active' },
    1: { title: 'active' }
  },
  providerObj = {
    4: { title: 'Vip Reseller' },
    5: { title: 'Digiflazz' }
  };

// ajax setup
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

// Datatable (jquery)
$(function () {
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + '/product/game-list'
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
          // provider
          targets: 9,
          render: function (data, type, full, meta) {
            var $provider = full['provider'];
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
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var edit = baseUrl + 'post/' + full['id'] + '/edit';
            return (
              '<div class="d-inline-block text-nowrap">' +
              '<a href="' +
              edit +
              '" class="btn btn-sm btn-icon"><i class="mdi mdi-pencil-outline"></i></a>' +
              '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical mdi-20px"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="' +
              userView +
              '" class="dropdown-item">View</a>' +
              `<a href="javascript:;" class="dropdown-item delete-record" data-id="${full['id']}">Delete</a>` +
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

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var post_id = $(this).data('id'),
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
          url: `${baseUrl}post-list/${post_id}`,
          success: function () {
            dt_user.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The post has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The post is not deleted!',
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
});
