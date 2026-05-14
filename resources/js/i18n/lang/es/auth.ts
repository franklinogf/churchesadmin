export default {
  login: {
    title: 'Inicia sesión en tu cuenta',
    shortTitle: 'Iniciar sesión',
    description: 'Ingresa tu correo electrónico y contraseña para acceder a tu cuenta.',
    form: {
      email: {
        label: '$t(common:inputs.email.label)',
        placeholder: '$t(common:inputs.email.placeholder.email)',
      },
      password: {
        label: '$t(common:inputs.password.label)',
        placeholder: '$t(common:inputs.password.placeholder)',
        resetPassword: '¿Olvidaste tu contraseña?',
      },
      rememberMe: 'Recuérdame',
      submit: '$t(common:actions.logIn)',
    },
  },
  passwordConfirmation: {
    title: 'Confirma tu contraseña',
    shortTitle: 'Confirmar contraseña',
    description: 'Confirma tu contraseña antes de continuar.',
    form: {
      password: {
        label: '$t(common:inputs.password.label)',
        placeholder: '$t(common:inputs.password.placeholder)',
      },
      submit: '$t(common:actions.confirmPassword)',
    },
  },
  forgotPassword: {
    title: '¿Olvidaste tu contraseña?',
    shortTitle: 'Olvidaste tu contraseña',
    description: 'No hay problema. Solo indícanos tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña y elegir una nueva.',
    form: {
      email: {
        label: '$t(common:inputs.email.label)',
        placeholder: '$t(common:inputs.email.placeholder.email)',
      },
      submit: 'Enviar enlace para restablecer la contraseña',
    },
    return: '<0>O bien, vuelve a</0> <1>iniciar sesión</1>',
  },
  resetPassword: {
    title: 'Restablece tu contraseña',
    shortTitle: 'Restablecer contraseña',
    description: 'Ingresa tu nueva contraseña a continuación',
    form: {
      email: '$t(common:inputs.email.label)',
      password: {
        label: '$t(common:inputs.newPassword.label)',
        placeholder: '$t(common:inputs.newPassword.placeholder)',
      },
      confirmPassword: {
        label: '$t(common:inputs.confirmPassword.label)',
        placeholder: '$t(common:inputs.confirmPassword.placeholder)',
      },
      submit: 'Restablecer contraseña',
    },
  },
  twoFactorChallenge: {
    title: {
      default: 'Código de autenticación',
      recovery: 'Código de recuperación',
    },
    toggle: {
      default: 'iniciar sesión con un código de recuperación',
      recovery: 'iniciar sesión con un código de autenticación',
    },
    description: {
      default: 'Ingresa el código de autenticación proporcionado por tu aplicación autenticadora.',
      recovery: 'Confirma el acceso a tu cuenta ingresando uno de tus códigos de recuperación de emergencia.',
    },
    shortTitle: 'Autenticación de dos factores',
    form: {
      recoveryCode: {
        placeholder: 'Ingresa el código de recuperación',
      },
      submit: '$t(common:actions.continue)',
      orYouCan: 'O bien, puedes',
    },
  },
  otpChallenge: {
    title: 'Revisa tu correo electrónico',
    shortTitle: 'Código de verificación',
    description: 'Ingresa el código de 6 dígitos que enviamos a tu correo electrónico.',
    codeSent: 'Se ha enviado un nuevo código a tu correo electrónico.',
    form: {
      submit: '$t(common:actions.continue)',
      resend: 'Reenviar código',
    },
  },
  agreement: {
    title: 'Términos y Acuerdo',
    shortTitle: 'Acuerdo',
    description: 'Por favor lee y acepta el siguiente acuerdo para continuar.',
    form: {
      accept: 'He leído y acepto los términos anteriores',
      submit: 'Aceptar y continuar',
      decline: 'Rechazar y cerrar sesión',
    },
  },
  verifyEmail: {
    title: 'Verifica tu correo electrónico',
    shortTitle: 'Verificación de correo',
    description: 'Verifica tu correo electrónico haciendo clic en el enlace que te acabamos de enviar.',
    verificationLinkSent: 'Se ha enviado un nuevo enlace de verificación a tu correo electrónico.',
    actions: {
      resend: 'Reenviar correo de verificación',
      logout: '$t(common:actions.logOut)',
    },
  },
} as const;
