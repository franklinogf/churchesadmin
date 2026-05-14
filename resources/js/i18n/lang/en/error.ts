export default {
  '403': {
    message: 'Forbidden',
    description: 'Sorry, you are forbidden from accessing this page.',
  },
  '404': {
    message: 'Page Not Found',
    description: 'Sorry, the page you are looking for could not be found.',
  },
  '500': {
    message: 'Internal Server Error',
    description: 'Whoops, something went wrong on our servers.',
  },
  '503': {
    message: 'Service Unavailable',
    description: 'Sorry, we are doing some maintenance. Please check back soon.',
  },
  actions: {
    go_back: 'Go Back',
    home: 'Home',
    retry: 'Retry',
  },
} as const;
