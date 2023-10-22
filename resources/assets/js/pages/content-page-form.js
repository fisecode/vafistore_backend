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
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    });
  });
})();

$(function () {
  $('#discard').on('click', function (e) {
    e.preventDefault(); // Mencegah tindakan bawaan tombol
    window.location.href = `${baseUrl}page`;
  });
});
