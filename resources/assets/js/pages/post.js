'use strict';

function truncated(content, max) {
  const maxContentLength = max;
  const truncatedContent = content.length > maxContentLength ? content.slice(0, maxContentLength) + '...' : content;
  return truncatedContent;
}

var dt_user_table = $('.datatables-posts'),
  select2 = $('.select2'),
  offCanvasForm = $('#offcanvasAddCategory');

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
        url: baseUrl + 'dashboard/post-list'
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'title' },
        { data: 'category' },
        { data: 'author' },
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
          // Content
          targets: 1,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            const $id = full['id'];
            const $content = full['meta_desc'];
            const $image = full['image'];
            let $output = '';

            if ($image) {
              $output = `<img src="${storagePath}img/posts/${$image}" alt="Product-${$id}" class="rounded-2">`;
            } else {
              const states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              const stateNum = Math.floor(Math.random() * 6);
              const $state = states[stateNum];
              const $category = full['category'];
              const $initials = ($category.match(/\b\w/g) || []).map(match => match.toUpperCase()).join('');
              $output = `<span class="avatar-initial rounded-2 bg-label-${$state}">${$initials}</span>`;
            }

            const truncatedContent = truncated($content, 30);
            const truncatedTitle = truncated(data, 30);
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
          // category
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            return `<span>${full.category}</span>`;
          }
        },
        {
          // Author
          targets: 3,
          responsivePriority: 5,
          render: function (data, type, full, meta) {
            return `<span>${full.author}</span>`;
          }
        },
        {
          // Status
          targets: 4,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            let badgeClass, statusText;

            if (data === 1) {
              badgeClass = 'bg-label-success';
              statusText = 'Publish';
            } else if (data === 0) {
              badgeClass = 'bg-label-info';
              statusText = 'Draft';
            } else if (data === 2) {
              badgeClass = 'bg-label-warning';
              statusText = 'Unpublish';
            } else {
              badgeClass = 'bg-label-default';
              statusText = 'Unknown';
            }

            return `<span class="badge rounded-pill ${badgeClass}" text-capitalized>${statusText}</span>`;
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var edit = baseUrl + 'dashboard/post/' + full['id'] + '/edit';
            return (
              '<div class="d-inline-block text-nowrap">' +
              '<a href="' +
              edit +
              '" class="btn btn-sm btn-icon"><i class="mdi mdi-pencil-outline"></i></a>' +
              '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical mdi-20px"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="#" class="dropdown-item">View</a>' +
              `<a href="javascript:;" class="dropdown-item delete-record" data-id="${full['id']}">Delete</a>` +
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
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
          text: '<i class="mdi mdi-plus me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New Post</span>',
          className: 'add-new btn btn-primary mx-3',
          action: function () {
            window.location.href = '/dashboard/post/add';
          }
        }
      ],
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
          url: `${baseUrl}dashboard/post-list/${post_id}`,
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
