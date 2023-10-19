'use strict';

function truncated(content, max) {
  const maxContentLength = max;
  const truncatedContent = content.length > maxContentLength ? content.slice(0, maxContentLength) + '...' : content;
  return truncatedContent;
}

$(function () {
  $('#datatables-post').DataTable({
    responsive: true,
    ajax: 'list',
    columns: [
      {
        data: 'title',
        name: 'content',
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
        name: 'kategori',
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
        data: 'author',
        name: 'author'
      },
      {
        data: 'status',
        name: 'status',
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
        data: 'action',
        name: 'action',
        orderable: true,
        searchable: false
      }
    ]
  });

  $(document).on('click', '.delete-post', function (e) {
    e.preventDefault();
    var size = $(this).data('size') == '' ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    $('#commonModal .modal-dialog').addClass('modal-' + size);
    $.ajax({
      url: url,
      success: function (data) {
        // alert(data);
        // return false;
        if (data.length) {
          $('#commonModal .modal-body').html(data);
          $('#commonModal').modal('show');
          // common_bind();
          // common_bind_select();
        } else {
          show_msg('Error', 'Permission denied', 'error');
          $('#commonModal').modal('hide');
        }
      },
      error: function (data) {
        data = data.responseJSON;
        show_msg('Error', data.error, 'error');
      }
    });
  });
});
