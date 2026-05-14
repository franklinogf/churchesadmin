export default {
  login: {
    title: 'Login to your account',
    shortTitle: 'Log in',
    description: 'Enter your email and password to access your account.',
    form: {
      email: {
        label: '$t(common:inputs.email.label)',
        placeholder: '$t(common:inputs.email.placeholder.email)',
      },
      password: {
        label: '$t(common:inputs.password.label)',
        placeholder: '$t(common:inputs.password.placeholder)',
        resetPassword: 'Forgot password?',
      },
      rememberMe: 'Remember me',
      submit: '$t(common:actions.logIn)',
    },
  },
  passwordConfirmation: {
    title: 'Confirm your password',
    shortTitle: 'Confirm password',
    description: 'Please confirm your password before continuing.',
    form: {
      password: {
        label: '$t(common:inputs.password.label)',
        placeholder: '$t(common:inputs.password.placeholder)',
      },
      submit: '$t(common:actions.confirmPassword)',
    },
  },
  forgotPassword: {
    title: 'Forgot password?',
    shortTitle: 'Forgot password',
    description:
      'No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
    form: {
      email: {
        label: '$t(common:inputs.email.label)',
        placeholder: '$t(common:inputs.email.placeholder.email)',
      },
      submit: 'Email password reset link',
    },
    return: '<0>Or, return to</0> <1>log in</1>',
  },
  resetPassword: {
    title: 'Reset your password',
    shortTitle: 'Reset password',
    description: 'Please enter your new password below',
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
      submit: 'Reset password',
    },
  },
  twoFactorChallenge: {
    title: {
      default: 'Authentication code',
      recovery: 'Recovery code',
    },
    toggle: {
      default: 'login using a recovery code',
      recovery: 'login using an authentication code',
    },
    description: {
      default: 'Enter the authentication code provided by your authenticator application.',
      recovery: 'Please confirm access to your account by entering one of your emergency recovery codes.',
    },
    shortTitle: 'Two-factor authentication',
    form: {
      recoveryCode: {
        placeholder: 'Enter recovery code',
      },
      submit: '$t(common:actions.continue)',
      orYouCan: 'Or, you can',
    },
  },
  otpChallenge: {
    title: 'Check your email',
    shortTitle: 'Verification code',
    description: 'Enter the 6-digit code we sent to your email address.',
    codeSent: 'A new code has been sent to your email address.',
    form: {
      submit: '$t(common:actions.continue)',
      resend: 'Resend code',
    },
  },
  agreement: {
    title: 'Terms & Agreement',
    shortTitle: 'Agreement',
    description: 'Please read and accept the following agreement to continue.',
    form: {
      accept: 'I have read and agree to the above terms',
      submit: 'Accept & Continue',
      decline: 'Decline & Log out',
    },
  },
  verifyEmail: {
    title: 'Verify email',
    shortTitle: 'Email verification',
    description: 'Please verify your email address by clicking on the link we just emailed to you.',
    verificationLinkSent: 'A new verification link has been sent to your email address.',
    actions: {
      resend: 'Resend verification email',
      logout: '$t(common:actions.logOut)',
    },
  },
} as const;
