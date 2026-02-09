---
name: adding-translations
description: >-
  Adds new translations to the application. Activates when modifying language files
  (e.g., `lang/es.json`) or when user mentions adding translations, updating language files, or working with localization.
---

# Adding Translations

## When to Apply

Activate this skill when:

- Modifying language files such as `lang/es.json` or any other language file in the `lang` directory.
- When the user mentions adding translations, updating language files, or working with localization in general.

## Important Note

- When adding new translations to `lang/es.json` or any other language file, you must run `php artisan utils:ts-lang-keys` to regenerate the TypeScript translation types.
- This ensures type safety for the translation keys in the frontend code.