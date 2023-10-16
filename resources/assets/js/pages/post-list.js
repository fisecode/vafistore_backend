/**
 * Form Editors
 */

'use strict';

(function () {
  // Variable declaration for table
  var dt_product_table = $('.datatables-products');

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
            var $name = full['title'],
              $id = full['id'],
              $product_brand = full['content'],
              $image = '1.png';
            if ($image) {
              // For Product image

              var $output =
                '<img src="' + assetsPath + 'img/avatars/' + $image + '" alt="Product-' + $id + '" class="rounded-2">';
            } else {
              // For Product badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['kategori'],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-2 bg-label-' + $state + '">' + $initials + '</span>';
            }
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
              $name +
              '</span>' +
              '<small class="text-truncate d-none d-sm-block">' +
              $product_brand +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        }
      ]
    });
  }
})();
