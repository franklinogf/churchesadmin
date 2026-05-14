export default {
  title: 'Settings',
  description: 'Manage your profile and account settings',
  menu: {
    profile: 'Profile',
    security: 'Security',
    appearance: 'Appearance',
    board: 'Pickup Board',
  },
  profile: {
    title: 'Profile information',
    description: 'Update your personal information and preferences',
    messages: {
      unverified: 'Your email address is unverified.',
      verificationLinkSent: 'A new verification link has been sent to your email address.',
    },
    actions: {
      resendEmailVerification: 'Click here to resend the verification email',
    },
  },
  security: {
    title: 'Security settings',
    password: {
      title: 'Update password',
      description: 'Ensure your account is using a long, random password to stay secure',
    },
    otp: {
      title: 'Login verification code (OTP)',
      description: 'Receive a one-time code each time you log in for extra security.',
    },
    twoFactor: {
      descriptionDisabled:
        'When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.',
      title: 'Two-factor authentication',
      description: 'Add additional security to your account using two-factor authentication',
      descriptionEnabled:
        'You will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.',
    },
  },
  agreement: {
    title: 'Agreement settings',
    description: 'Configure the terms agreement that users must accept to access the app.',
    menu: 'Agreement',
    form: {
      titleEn: 'Title (English)',
      titleEs: 'Title (Spanish)',
      contentEn: 'Content (English)',
      contentEs: 'Content (Spanish)',
      roles: 'Apply to roles',
      isActive: 'Active',
      isActiveDescription: 'When active, users must accept this agreement to continue.',
    },
    actions: {
      save: '$t(common:actions.save)',
      requireReAcceptance: 'Require re-acceptance',
      requireReAcceptanceDescription: 'Users who have already accepted will need to accept again.',
      requireReAcceptanceConfirm: 'Are you sure? All users who have accepted this agreement will need to accept it again.',
    },
    messages: {
      saved: '$t(common:messages.saved)',
      reAcceptanceRequired: 'All users will need to accept the agreement again.',
    },
    noAgreement: 'No agreement configured yet.',
  },
  board: {
    title: 'Pickup board credentials',
    description: 'Update the username and password used to open the pickup board.',
    menu: 'Pickup Board',
    form: {
      username: 'Username',
      password: 'New password',
      passwordConfirmation: 'Confirm password',
    },
  },
  appearance: {
    title: 'Appearance settings',
    description: "Update your account's appearance settings, such as theme and language preferences",
    theme: {
      tabs: {
        light: 'Light',
        dark: 'Dark',
        system: 'System',
      },
    },
  },
} as const;
