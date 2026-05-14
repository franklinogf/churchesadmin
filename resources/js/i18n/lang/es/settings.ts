export default {
  title: 'Configuración',
  description: 'Administra la configuración de tu perfil y cuenta',
  menu: {
    profile: 'Perfil',
    security: 'Seguridad',
    appearance: 'Apariencia',
    board: 'Tablero de recogidas',
  },
  profile: {
    title: 'Información del perfil',
    description: 'Actualiza tu información personal y preferencias',
    messages: {
      unverified: 'Tu correo electrónico no está verificado.',
      verificationLinkSent: 'Se ha enviado un nuevo enlace de verificación a tu correo electrónico.',
    },
    actions: {
      resendEmailVerification: 'Haz clic aquí para reenviar el correo de verificación',
    },
  },
  security: {
    password: {
      title: 'Actualizar contraseña',
      description: 'Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerla segura',
    },
    title: 'Configuración de seguridad',
    otp: {
      title: 'Código de verificación al iniciar sesión (OTP)',
      description: 'Recibe un código de un solo uso cada vez que inicies sesión.',
    },
    twoFactor: {
      descriptionDisabled:
        'Cuando actives la autenticación de dos factores, se te pedirá un PIN seguro durante el inicio de sesión. Puedes obtener este PIN desde una aplicación compatible con TOTP en tu teléfono.',
      title: 'Autenticación de dos factores',
      description: 'Agrega seguridad adicional a tu cuenta usando autenticación de dos factores',
      descriptionEnabled:
        'Durante el inicio de sesión se te solicitará un PIN seguro y aleatorio, que puedes obtener desde la aplicación compatible con TOTP en tu teléfono.',
    },
  },
  agreement: {
    title: 'Configuración del acuerdo',
    description: 'Configura el acuerdo que los usuarios deben aceptar para acceder a la app.',
    menu: 'Acuerdo',
    form: {
      titleEn: 'Título (Inglés)',
      titleEs: 'Título (Español)',
      contentEn: 'Contenido (Inglés)',
      contentEs: 'Contenido (Español)',
      roles: 'Aplicar a roles',
      isActive: 'Activo',
      isActiveDescription: 'Cuando está activo, los usuarios deben aceptar este acuerdo para continuar.',
    },
    actions: {
      save: '$t(common:actions.save)',
      requireReAcceptance: 'Requerir nueva aceptación',
      requireReAcceptanceDescription: 'Los usuarios que ya aceptaron deberán aceptar de nuevo.',
      requireReAcceptanceConfirm: '¿Estás seguro? Todos los usuarios que aceptaron este acuerdo deberán aceptarlo nuevamente.',
    },
    messages: {
      saved: '$t(common:messages.saved)',
      reAcceptanceRequired: 'Todos los usuarios deberán aceptar el acuerdo nuevamente.',
    },
    noAgreement: 'Aún no hay acuerdo configurado.',
  },
  board: {
    title: 'Credenciales del tablero de recogidas',
    description: 'Actualiza el usuario y la contraseña usados para abrir el tablero de recogidas.',
    menu: 'Tablero de recogidas',
    form: {
      username: 'Usuario',
      password: 'Nueva contraseña',
      passwordConfirmation: 'Confirmar contraseña',
    },
  },
  appearance: {
    title: 'Configuración de apariencia',
    description: 'Actualiza la apariencia de tu cuenta, como el tema y las preferencias de idioma',
    theme: {
      tabs: {
        light: 'Claro',
        dark: 'Oscuro',
        system: 'Sistema',
      },
    },
  },
} as const;
