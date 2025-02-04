/*
 *  Document   : op_auth_signin.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Sign In Page
 */

class pageAuthSignIn {
  /*
   * Init Sign In Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
   *
   */
  static initValidation() {
    // Load default options for jQuery Validation plugin
    One.helpers('jq-validation');

    // Init Form Validation
    jQuery('.js-validation-signin').validate({
      rules: {
        'username': {
          required: true,
          minlength: 3
        },
        'login': {
          required: true,
          minlength: 3
        },
        'password': {
          required: true,
          minlength: 5
        }
      },
      messages: {
      'username': {
          required: 'Por favor ingresa un nombre de usuario',
          minlength: 'Tu nombre de usuario debe tener al menos 3 caracteres'
      },
      'login': {
          required: 'Por favor ingresa un nombre de usuario',
          minlength: 'Tu nombre de usuario debe tener al menos 3 caracteres'
      },
      'password': {
          required: 'Por favor ingresa una contraseña',
          minlength: 'Tu contraseña debe tener al menos 5 caracteres'
      }
}

    });
  }

  /*
   * Init functionality
   *
   */
  static init() {
    this.initValidation();
  }
}

// Initialize when page loads
One.onLoad(() => pageAuthSignIn.init());
