import { defineConfig } from 'i18next-cli';

export default defineConfig({
  locales: ['en', 'es'],
  extract: {
    input: 'resources/js/**/*.{js,jsx,ts,tsx}',
    output: 'resources/js/i18n/lang/{{language}}/{{namespace}}.ts',
    functions: ['t', '*.t', 'i18next.t', 'i18n.t'],
    defaultNS: 'common',
  },
  types: {
    input: 'resources/js/i18n/lang/**/*.ts',
    output: 'resources/js/types/i18next.d.ts',
    indentation: 2,
    resourcesFile: 'resources/js/types/resources.d.ts',
    enableSelector: true,
  },
  lint: {
    ignore: ['resources/js/components/ui/**/*.{ts,tsx}'],
  },
});
