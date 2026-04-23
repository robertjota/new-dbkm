/*
 *  Document   : be_comp_dialogs.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Dialogs Page
 */

// SweetAlert2, for more examples you can check out https://github.com/sweetalert2/sweetalert2
class pageDialogs {
  static sweetAlert2() {
    let toast = Swal.mixin({
      buttonsStyling: false,
      target: '#page-container',
      customClass: {
        confirmButton: 'btn btn-success m-1',
        cancelButton: 'btn btn-danger m-1',
        input: 'form-control'
      }
    });

    let btns = [
      {selector: '.js-swal-simple', fire: () => toast.fire('Hi, this is just a simple message!')},
      {selector: '.js-swal-success', fire: () => toast.fire('Success', 'Everything was updated perfectly!', 'success')},
      {selector: '.js-swal-info', fire: () => toast.fire('Info', 'Just an informational message!', 'info')},
      {selector: '.js-swal-warning', fire: () => toast.fire('Warning', 'Something needs your attention!', 'warning')},
      {selector: '.js-swal-error', fire: () => toast.fire('Oops...', 'Something went wrong!', 'error')},
      {selector: '.js-swal-question', fire: () => toast.fire('Question', 'Are you sure about that?', 'question')},
      {selector: '.js-swal-confirm', fire: () => toast.fire({
        title: 'Are you sure?', text: 'You will not be able to recover this imaginary file!',
        icon: 'warning', showCancelButton: true,
        customClass: {confirmButton: 'btn btn-danger m-1', cancelButton: 'btn btn-secondary m-1'},
        confirmButtonText: 'Yes, delete it!'
      }).then(result => {
        if (result.value) {
          toast.fire('Deleted!', 'Your imaginary file has been deleted.', 'success');
        } else if (result.dismiss === 'cancel') {
          toast.fire('Cancelled', 'Your imaginary file is safe :)', 'error');
        }
      })},
      {selector: '.js-swal-custom-position', fire: () => toast.fire({position: 'top-end', title: 'Perfect!', text: 'Nice Position!', icon: 'success'})}
    ];

    btns.forEach(btn => {
      let el = document.querySelector(btn.selector);
      if (el) {
        el.addEventListener('click', btn.fire);
      }
    });
  }

  static init() {
    this.sweetAlert2();
  }
}

One.onLoad(() => pageDialogs.init());