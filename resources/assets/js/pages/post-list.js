/**
 * Form Editors
 */

'use strict';

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
    var about = document.querySelector('input[name=content]');
    about.value = JSON.stringify(quill.getContents());
  };
})();
