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
    FormValidation.formValidation(document.getElementById('add-post'), {
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
  const tagifyBasicEl = document.querySelector('#post-tags');
  const TagifyBasic = new Tagify(tagifyBasicEl);

  const bs = document.querySelectorAll('button[type="submit"]');
  bs.forEach(button => {
    button.addEventListener('click', function () {
      if (this.name === 'publish' || this.name === 'update') {
        document.getElementById('status').value = '1';
      } else if (this.name === 'draft') {
        document.getElementById('status').value = '0';
      } else if (this.name === 'unpublish') {
        document.getElementById('status').value = '2';
      }
    });
  });

  $('#discard').click(function (e) {
    e.preventDefault(); // Mencegah tindakan bawaan tombol
    // Kode untuk mengarahkan ke halaman daftar posting
    window.location.href = `${baseUrl}posts`;
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

  var offCanvasForm = $('#offcanvasAddCategory');

  // validating form and updating categories data
  const addNewUserForm = document.getElementById('addNewCategoryForm');

  // category form validation
  const fv = FormValidation.formValidation(addNewUserForm, {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: 'Please enter category name'
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
    // adding or updating user when form successfully validate
    $.ajax({
      data: $('#addNewCategoryForm').serialize(),
      url: `${baseUrl}post-category`,
      type: 'POST',
      success: function (response) {
        if (response.success) {
          // Refresh the Select2 dropdown
          $.each(response.categories, function (id, text) {
            $('#category-org').append(new Option(text, id, false, false));
          });
          $('#category-org').trigger('change');
        }
        offCanvasForm.offcanvas('hide');

        // sweetalert
        Swal.fire({
          icon: 'success',
          title: `Successfully ${response.message}!`,
          text: `Category ${response.message} Successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function (err) {
        offCanvasForm.offcanvas('hide');
        Swal.fire({
          title: 'Duplicate Entry!',
          text: 'Category already exist.',
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
