/**
 * Form Editors
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    // Update/reset user image of account page
    let accountUserImage = document.getElementById('uploadedAvatar');
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

  const form = document.getElementById('add-post');
  form.onsubmit = function () {
    // Populate hidden form on submit
    const about = document.querySelector('input[name=content]');
    about.value = quill.root.innerHTML;
  };

  // Basic Tags

  const tagifyBasicEl = document.querySelector('#post-tags');
  const TagifyBasic = new Tagify(tagifyBasicEl);

  const bs = document.querySelectorAll('button[type="submit"]');
  bs.forEach(button => {
    button.addEventListener('click', function () {
      if (this.name === 'publish') {
        document.getElementById('status').value = '0';
      } else if (this.name === 'draft') {
        document.getElementById('status').value = '1';
      } else if (this.name === 'unpublish') {
        document.getElementById('status').value = '2';
      }
    });
  });
})();

$(function () {
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

  const formRepeater = $('.form-repeater');

  // Form Repeater
  // ! Using jQuery each loop to add dynamic id and class for inputs. You may need to improve it based on form fields.
  // -----------------------------------------------------------------------------------------------------------------

  if (formRepeater.length) {
    const row = 2;
    const col = 1;
    formRepeater.on('submit', function (e) {
      e.preventDefault();
    });
    formRepeater.repeater({
      show: function () {
        const fromControl = $(this).find('.form-control, .form-select');
        const formLabel = $(this).find('.form-label');

        fromControl.each(function (i) {
          const id = 'form-repeater-' + row + '-' + col;
          $(fromControl[i]).attr('id', id);
          $(formLabel[i]).attr('for', id);
          col++;
        });

        row++;
        $(this).slideDown();
        $('.select2-container').remove();
        $('.select2.form-select').select2({
          placeholder: 'Placeholder text'
        });
        $('.select2-container').css('width', '100%');
        select2Focus('.form-select');
        $('.form-repeater:first .form-select').select2({
          dropdownParent: $(this).parent(),
          placeholder: 'Placeholder text'
        });
        $('.ecommerce-select2-dropdown .form-select').select2({
          dropdownParent: $('.ecommerce-select2-dropdown').parent()
        });
      }
    });
  }
});
