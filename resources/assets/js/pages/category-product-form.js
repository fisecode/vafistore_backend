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
  const ap = document.querySelector('.description-editor');
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
    const content = document.querySelector('input[name=description]');
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
    const contentHt = document.querySelector('input[name=helpText]');
    contentHt.value = quillHt.root.innerHTML;
  });

  document.addEventListener('DOMContentLoaded', function (e) {
    // Update/reset image of product page
    let productImage = document.getElementById('uploadedImage');
    let productSubImage = document.getElementById('uploadedSubImage');
    const fileInput = document.querySelector('.product-file-input');
    const fileInputSub = document.querySelector('.sub-file-input');
    const resetFileInput = document.querySelector('.product-image-reset');
    const resetFileInputSub = document.querySelector('.sub-image-reset');

    if (productImage) {
      const resetImage = productImage.src;

      fileInput.onchange = () => {
        if (fileInput.files[0]) {
          productImage.src = window.URL.createObjectURL(fileInput.files[0]);
          productImage.style.display = 'block'; // Show the image
          resetFileInput.style.display = 'block'; // Show the Reset button
          resetFileInput.classList.add('btn');
        }
      };

      resetFileInput.onclick = () => {
        fileInput.value = '';
        productImage.src = resetImage;
        productImage.style.display = 'none'; // Hide the image
        resetFileInput.style.display = 'none'; // Hide the Reset button
        resetFileInput.classList.remove('btn');
      };
    }

    if (productSubImage) {
      const resetSubImage = productSubImage.src;

      fileInputSub.onchange = () => {
        if (fileInputSub.files[0]) {
          productSubImage.src = window.URL.createObjectURL(fileInputSub.files[0]);
          productSubImage.style.display = 'block'; // Show the image
          resetFileInputSub.style.display = 'block'; // Show the Reset button
          resetFileInputSub.classList.add('btn');
        }
      };

      resetFileInputSub.onclick = () => {
        fileInputSub.value = '';
        productSubImage.src = resetSubImage;
        productSubImage.style.display = 'none'; // Hide the image
        resetFileInputSub.style.display = 'none'; // Hide the Reset button
        resetFileInputSub.classList.remove('btn');
      };
    }

    FormValidation.formValidation(document.getElementById('add-page'), {
      fields: {
        productName: {
          validators: {
            notEmpty: {
              message: 'Product name is required'
            }
          }
        },
        description: {
          validators: {
            notEmpty: {
              message: 'description is required'
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
        },
        subImage: {
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
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
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

  $('#optionServerToggle').on('change', function () {
    // Periksa apakah checkbox di-centang atau tidak
    if ($(this).is(':checked')) {
      // Jika di-centang, tampilkan elemen repeater-list
      $('#repeaterList, #addButton').show();
    } else {
      // Jika tidak di-centang, sembunyikan elemen repeater-list
      $('#repeaterList, #addButton').hide();
    }
  });

  // Periksa apakah checkbox option_server di-centang atau tidak
  if ($('#optionServerToggle').is(':checked')) {
    // Jika di-centang, tampilkan elemen repeater-list
    $('#repeaterList, #addButton').show();
  } else {
    // Jika tidak di-centang, sembunyikan elemen repeater-list
    $('#repeaterList, #addButton').hide();
  }

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
  var row = 1;

  if (formRepeater.length) {
    formRepeater.repeater({
      show: function () {
        var repeaterContainer = $(this);
        var fromControl = repeaterContainer.find('.form-control, .form-select');
        var formLabel = repeaterContainer.find('.form-label');

        fromControl.each(function (i) {
          var id = 'form-repeater-' + row + '-' + (i + 1);
          $(fromControl[i]).attr('id', id);
          $(formLabel[i]).attr('for', id);
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
      var repeaterContainer = $(this).closest('.form-repeater');
      var repeaterList = repeaterContainer.find('[data-repeater-list="group-a"]');
      var lastRepeater = repeaterList.find('[data-repeater-item]:last');
      var fromControl = lastRepeater.find('.form-control, .form-select');
      var formLabel = lastRepeater.find('.form-label');

      // Set value menjadi kosong hanya untuk elemen pada repeater baru
      fromControl.val('');

      // Hanya ubah nilai id dan for untuk elemen yang merupakan duplikat dari elemen pertama
      fromControl.each(function (i) {
        if (i > 0) {
          var id = 'form-repeater-' + row + '-' + (i + 1);
          $(fromControl[i]).attr('id', id);
          $(formLabel[i]).attr('for', id);
        }
      });

      // Set a unique data-repeater-id for the new repeater item
      var deleteButton = repeaterContainer.find('[data-repeater-delete]');
      var deleteButtonId = Math.random().toString(36).substring(7);
      deleteButton.attr('data-repeater-id', deleteButtonId);

      row++;

      repeaterContainer.find('[data-repeater-list="group-a"]').repeater('add');
    });
  }
});
