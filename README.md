# SightPress form builder

`sightpress/plugin-form-builder`: the official SightPress plugin for accessible
custom forms. Licensed GPL-2.0-or-later. It replaces the never-released
`contact-form` plugin, including the core Contact page's form.

> **Status:** M0 scaffolding in place (manifest, bundle, quality gates, CI);
> features arrive in milestone M12.

It adds:

- Contact, registration, survey and application form **starters**, and a
  builder for new ones.
- **Multi-step forms** with save and resume, one question per page, and
  conditional questions.
- A **"Check your answers"** review step before submitting, and private file
  uploads.
- An **`::form` block** usable on any page or content type, a submission
  inbox, and CSV/JSON export.

Every form follows the public form pattern: works without
JavaScript, an error summary, honeypot plus a minimum time-to-submit plus rate
limits and moderation — never a CAPTCHA or other cognitive test.

## Development

```bash
composer install
vendor/bin/phpunit          # on PluginTestCase, enabled and disabled
vendor/bin/phpstan analyse
npx playwright test         # every manifest route, with axe-core
```

Check out `sightpress/core` as a sibling and require it with
`COMPOSER=composer.dev.json composer install` to run the test kernel.
