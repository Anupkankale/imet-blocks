# Contributing to IMET Blocks

How changes get into this repository. The project context and conventions are in [docs/DEVELOPER-GUIDE.md](docs/DEVELOPER-GUIDE.md).

## Scope

Work only inside this plugin. Do not change WordPress core, other plugins, mu-plugins or the theme. If a fix seems to need
one of those, describe it in the pull request and find a way to do it from inside the plugin.

## Workflow

1. **Never commit or push to `main`.** Branch from `main`: `feature/…`, `fix/…`, `docs/…`, `chore/…`.
2. Make the change. Run `npm run build` after editing `src/`; `build/` is committed, so commit it with the change.
3. Run the checks (below). Fix what they report.
4. Open a pull request using the template. Merge it yourself only when the checks pass; for anything large, ask for a review first.
5. Release-worthy change: bump the version in four places (`imet-blocks.php` header and `IMET_BLOCKS_VERSION`,
   `package.json`, `readme.txt` Stable tag) and add a CHANGELOG entry. `npm test` fails if the four differ.

## Checks

| Command | What it checks |
|---|---|
| `npm test` | Static checks (blocks, `build/` in step with `src/`, version, images, anchors, `php -l`) and JavaScript unit tests |
| `npm run test:php` | PHP unit tests (needs `composer install` once) |
| `npm run test:e2e` | Browser tests on the preview page: behaviour, spacing and sizes, Core Web Vitals and budgets |
| `npm run lint:js`, `npm run lint:css` | Code style. Existing style problems are warnings; new errors fail |

## Commits

- A short imperative subject, one logical change per commit.
- Your own git identity (`git config --global user.name` and `user.email`). The `.mailmap` file maps older identities to one name.
- No AI attribution lines in commits or pull requests.

## Tests

A change to PHP needs a test in `tests/php/`; a change to spacing or layout needs the snapshot in
`tests/e2e/spacing.golden.json` updated on purpose (`UPDATE_GOLDEN=1 npm run test:e2e -- spacing --workers=1`).
