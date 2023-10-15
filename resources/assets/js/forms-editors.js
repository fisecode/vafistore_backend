/**
 * Form Editors
 */

'use strict';

(function () {
  // // Snow Theme
  // // --------------------------------------------------------------------
  // const snowEditor = new Quill('#snow-editor', {
  //   bounds: '#snow-editor',
  //   modules: {
  //     formula: true,
  //     toolbar: '#snow-toolbar'
  //   },
  //   theme: 'snow'
  // });

  // // Bubble Theme
  // // --------------------------------------------------------------------
  // const bubbleEditor = new Quill('#bubble-editor', {
  //   modules: {
  //     toolbar: '#bubble-toolbar'
  //   },
  //   theme: 'bubble'
  // });

  // // Full Toolbar
  // // --------------------------------------------------------------------
  // const fullToolbar = [
  //   [
  //     {
  //       font: []
  //     },
  //     {
  //       size: []
  //     }
  //   ],
  //   ['bold', 'italic', 'underline', 'strike'],
  //   [
  //     {
  //       color: []
  //     },
  //     {
  //       background: []
  //     }
  //   ],
  //   [
  //     {
  //       script: 'super'
  //     },
  //     {
  //       script: 'sub'
  //     }
  //   ],
  //   [
  //     {
  //       header: '1'
  //     },
  //     {
  //       header: '2'
  //     },
  //     'blockquote',
  //     'code-block'
  //   ],
  //   [
  //     {
  //       list: 'ordered'
  //     },
  //     {
  //       list: 'bullet'
  //     },
  //     {
  //       indent: '-1'
  //     },
  //     {
  //       indent: '+1'
  //     }
  //   ],
  //   [{ direction: 'rtl' }],
  //   ['link', 'image', 'video', 'formula'],
  //   ['clean']
  // ];
  // const fullEditor = new Quill('#full-editor', {
  //   bounds: '#full-editor',
  //   placeholder: 'Type Something...',
  //   modules: {
  //     formula: true,
  //     toolbar: fullToolbar
  //   },
  //   theme: 'snow'
  // });

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
