import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import { LaravelReactI18nProvider } from 'laravel-react-i18n';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import ReactDOMServer from 'react-dom/server';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
  createInertiaApp({
    page,
    render: ReactDOMServer.renderToString,
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup: ({ App, props }) => {
      return (
        <LaravelReactI18nProvider fallbackLocale="en" files={import.meta.glob('/lang/*.json', { eager: true })}>
          <App {...props} />
        </LaravelReactI18nProvider>
      );
    },
  }),
);
