/**
 * Form Editors
 */

'use strict';

(function () {
  // Variable declaration for table
  const dt_product_table = $('.datatables-products');

  // E-commerce Products datatable
  if (dt_product_table.length) {
    dt_product_table.DataTable({
      ajax: { url: '/post/get', dataSrc: '' }, // JSON file to add data
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'id' },
        { data: 'title' },
        {
          data: 'kategori',
          render: function (data, type, row) {
            return data === null ? 'No Category' : data;
          }
        },
        { data: 'user.name' },
        { data: 'status' },
        { data: '' }
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
          // For Checkboxes
          targets: 1,
          orderable: false,
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          },
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input" >';
          },
          searchable: false
        },
        {
          // Product name and product_brand
          targets: 2,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $title = full['title'],
              $id = full['id'],
              $content = full['meta_desc'],
              $image = full['image'];
            if ($image) {
              // For Product image

              var $output =
                '<img src="' +
                '../storage/assets/img/posts/' +
                $image +
                '" alt="Product-' +
                $id +
                '" class="rounded-2">';
            } else {
              // For Product badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $title = full['kategori'],
                $initials = $title.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-2 bg-label-' + $state + '">' + $initials + '</span>';
            }
            var maxContentLength = 50; // Set your desired maximum content length
            var truncatedContent =
              $content.length > maxContentLength ? $content.slice(0, maxContentLength) + '...' : $content;
            // Creates full output for Product name and product_brand
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center product-name">' +
              '<div class="avatar-wrapper me-3">' +
              '<div class="avatar rounded-2 bg-label-secondary">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<span class="text-nowrap text-heading fw-medium">' +
              $title +
              '</span>' +
              '<small class="text-truncate d-none d-sm-block">' +
              truncatedContent +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // Product Category

          targets: 3,
          responsivePriority: 5,
          render: function (data, type, full, meta) {
            var $category = full['kategori'];
            return "<h6 class='text-truncate d-flex align-items-center mb-0'>" + $category + '</h6>';
          }
        },
        {
          // Status
          targets: 5,
          render: function (data, type, full, meta) {
            const status = full['status'];
            const badgeClass = status === 0 ? 'bg-label-success' : 'bg-label-info';
            const statusText = status === 0 ? 'Publish' : 'Draft';

            return `<span class="badge rounded-pill ${badgeClass}" text-capitalized>${statusText}</span>`;
          }
        }
      ]
    });
  }
})();
