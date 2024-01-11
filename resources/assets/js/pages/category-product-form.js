/**
 * Form Editors
 */

'use strict';

// ajax setup
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

(function () {
  const ap = document.querySelector('.content-editor');
  const quill = new Quill(ap, {
    modules: {
      toolbar: '.content-toolbar'
    },
    placeholder: 'Write Content',
    theme: 'snow'
  });

  // Mendengarkan perubahan di Quill Editor
  quill.on('text-change', function () {
    // Dapatkan nilai dari Quill Editor dan perbarui input 'content'
    const content = document.querySelector('input[name=content]');
    content.value = quill.root.innerHTML;
  });

  const ht = document.querySelector('.help-text-editor');
  const quillHt = new Quill(ht, {
    modules: {
      toolbar: '.ht-toolbar'
    },
    placeholder: 'Write Content',
    theme: 'snow'
  });

  // Mendengarkan perubahan di Quill Editor
  quillHt.on('text-change', function () {
    // Dapatkan nilai dari Quill Editor dan perbarui input 'content'
    const contentHt = document.querySelector('input[name=content]');
    contentHt.value = quillHt.root.innerHTML;
  });

  document.addEventListener('DOMContentLoaded', function (e) {
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

    FormValidation.formValidation(document.getElementById('add-page'), {
      fields: {
        title: {
          validators: {
            notEmpty: {
              message: 'Title is required'
            }
          }
        },
        content: {
          validators: {
            notEmpty: {
              message: 'Content is required'
            }
          }
        },
        image: {
          validators: {
            file: {
              extension: 'jpg,jpeg,png',
              type: 'image/jpeg,image/png',
              maxSize: 800000,
              message: 'The selected file is not valid'
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
    });
  });
})();

$(function () {
  $('#discard').on('click', function (e) {
    e.preventDefault(); // Mencegah tindakan bawaan tombol
    window.location.href = `${baseUrl}dashboard/product/category`;
  });

  // Select2
  const select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      const $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder') // for dynamic placeholder
      });
    });
  }

  var formRepeater = $('.form-repeater');

  if (formRepeater.length) {
    var row = 2;
    var col = 1;

    formRepeater.repeater({
      show: function () {
        var fromControl = $(this).find('.form-control, .form-select');
        var formLabel = $(this).find('.form-label');

        fromControl.each(function (i) {
          var id = 'form-repeater-' + row + '-' + col;
          $(fromControl[i]).attr('id', id);
          $(formLabel[i]).attr('for', id);
          col++;
        });

        row++;

        $(this).slideDown();
      },
      hide: function (e) {
        confirm('Are you sure you want to delete this element?') && $(this).slideUp(e);
      }
    });

    // Pindahkan penanganan peristiwa click ke dalam repeater
    formRepeater.on('click', '.btn-repeater', function () {
      var fromControl = $(this).closest('.form-repeater').find('.form-control, .form-select');
      var formLabel = $(this).closest('.form-repeater').find('.form-label');

      fromControl.each(function (i) {
        var id = 'form-repeater-' + row + '-' + col;
        $(fromControl[i]).attr('id', id);
        $(formLabel[i]).attr('for', id);
        col++;
      });

      row++;

      $(this).closest('.form-repeater').find('[data-repeater-list="group-a"]').repeater('add');
    });
  }
});
