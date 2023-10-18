'use strict';

function truncated(content, max) {
  const maxContentLength = max;
  const truncatedContent = content.length > maxContentLength ? content.slice(0, maxContentLength) + '...' : content;
  return truncatedContent;
}

(function () {
  const dt_product_table = $('.datatables-products');

  if (dt_product_table.length) {
    dt_product_table.DataTable({
      ajax: { url: '/post/get', dataSrc: '' },
      columns: [
        { data: 'id' },
        {
          data: 'id',
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          orderable: false,
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          },
          searchable: false
        },
        {
          data: 'title',
          render: function (data, type, full) {
            const $id = full['id'];
            const $content = full['meta_desc'];
            const $image = full['image'];
            let $output = '';

            if ($image) {
              $output = `<img src="../storage/assets/img/posts/${$image}" alt="Product-${$id}" class="rounded-2">`;
            } else {
              const states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              const stateNum = Math.floor(Math.random() * 6);
              const $state = states[stateNum];
              const $kategori = full['kategori'];
              const $initials = ($kategori.match(/\b\w/g) || []).map(match => match.toUpperCase()).join('');
              $output = `<span class="avatar-initial rounded-2 bg-label-${$state}">${$initials}</span>`;
            }

            const maxContentLength = 50;
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
          data: 'kategori',
          render: function (data) {
            return (
              "<h6 class='text-truncate d-flex align-items-center mb-0'>" +
              (data === null ? 'No Category' : data) +
              '</h6>'
            );
          },
          responsivePriority: 5
        },
        {
          data: 'user.name'
        },
        {
          data: 'status',
          render: function (data) {
            let badgeClass, statusText;

            if (data === 0) {
              badgeClass = 'bg-label-success';
              statusText = 'Publish';
            } else if (data === 1) {
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
          data: '',
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            const $id = full['id'];
            return `<div class="d-inline-block text-nowrap">
            <a href="edit/${$id}" class="btn btn-sm btn-icon"><i class="mdi mdi-pencil-outline"></i></a>
            <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical me-2"></i></button>
            <div class="dropdown-menu dropdown-menu-end m-0">
            <a href="javascript:0;" class="dropdown-item">View</a>
            <a href="#" class="dropdown-item" data-post-id="${$id}" data-toggle="modal" data-target="#deleteModal">Delete</a>`;
          }
        }
      ],
      columnDefs: [
        {
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function () {
            return '';
          }
        }
      ]
    });
    $('.dataTables_length').addClass('mt-0 mt-md-3');
    $('.dt-action-buttons').addClass('pt-0');
    $('.dt-buttons').addClass('d-flex flex-wrap');
  }
})();
