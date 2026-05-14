export default {
  '403': {
    message: 'Prohibido',
    description: 'No tienes permiso para acceder a esta página.',
  },
  '404': {
    message: 'No encontrado',
    description: 'La página que estás buscando no existe.',
  },
  '500': {
    message: 'Error interno del servidor',
    description: 'Ha ocurrido un error en el servidor. Por favor, inténtalo de nuevo más tarde.',
  },
  '503': {
    message: 'Servicio no disponible',
    description: 'El servicio no está disponible en este momento. Por favor, inténtalo de nuevo más tarde.',
  },
  actions: {
    go_back: 'Ir atrás',
    home: 'Inicio',
    retry: 'Reintentar',
  },
} as const;
