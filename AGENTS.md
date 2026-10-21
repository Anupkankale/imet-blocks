# AGENTS.md — IMET Blocks

Context for AI coding agents (and developers) working in this repository. Read this before changing anything.

## What this is

`imet-blocks` is a WordPress plugin. It holds the reusable **section blocks** used to rebuild the IMET Worldwide
site (imetworldwide.com), moving it from Elementor to the block editor, one page at a time. Every section of a page
(Hero, FAQ, Testimonials, CTA…) is one block, and a page is a **pattern** that lists blocks in order. The first page
is the Online DBA landing page (`patterns/dba-landing.php`), built to match a Figma design.

- **Live site:** Glidex theme (classic) with an Elementor header and footer, the Classic Editor, and NitroPack
  caching. Only the IMET pages use this plugin; every other page stays on Elementor.
- **Local dev:** a "Local" (Flywheel) site at `http://imetworld.local` with WordPress 7.x and the Twenty Twenty-Five
  theme (a block theme). Make sure changes work under **both** theme types.
- **Predecessor:** the plugin `imet-generic-landing` was the same page as one monolithic template. It is being
  retired; `wp imet migrate` converts its pages.

## Layout

```
imet-blocks.php            bootstrap: constants (IMET_BLOCKS_VERSION/DIR/URL), requires, activation hook
includes/
  register.php             block registration (build/blocks-manifest.php), "IMET" inserter category,
                           shared style handle `imet-base`, default content sent to the editor,
                           classic-theme dequeue of unused block styles
  templates.php            "IMET Landing" page template, patterns (patterns/*.php), "Create: <page>" links,
                           [imet_advisor_form] placeholder, block editor forced on for IMET pages,
                           landing-page front-end assets
  perf.php                 speed work on landing pages: CSS combine, Google Fonts removal, delayed analytics
  media.php                images imported to the Media Library; {{ID:file}} / {{URL:file}} tokens
  faq-schema.php           FAQPage JSON-LD from the imet/faq block
  video.php                Image blocks with class "yt-auto" show their YouTube video's thumbnail
  migrate.php              WP-CLI `wp imet migrate <id> [--dry-run]`
templates/page-landing.php the IMET Landing page template (renders content before wp_head)
patterns/                  page patterns (Kind: page) and ready-made sections (Kind: section)
src/
  blocks/<name>/           one folder per section block (see below)
  shared/                  base.scss (tokens, fonts, headers), _buttons.scss, _utilities.scss,
                           page-layout.scss, editor-canvas.scss, section-block.js, view.js helpers
  editor/                  editor-only JS: "IMET button" panel, content-only mode for IMET pages
assets/images, assets/fonts  static files; CSS references them with relative paths (stable names)
build/                     compiled output (committed, so the plugin works without Node on the server)
```

## Commands

```bash
npm install
npm run build        # always run after editing src/ — WordPress only loads build/
npm start            # rebuild on change
npm run plugin-zip   # installable imet-blocks.zip (ignored by git)
```

- `npm run lint:js` currently crashes under Node 24 (a bug in a wp-scripts dependency). This is not a code
  problem; check by building instead.
- `build/` is committed. Rebuild and commit it together with every `src/` change.

## How a section block works

Every block in `src/blocks/<name>/` follows the same pattern:

| File | Role |
|---|---|
| `block.json` | Metadata, plus a custom **`imet`** key: `{ "tagName": "section", "className": "section faq", "anchor": "faq" }`, which sets the block's wrapper element, its classes and its default HTML anchor. |
| `index.js` | Always `registerSectionBlock( metadata )` from `src/shared/section-block.js`. |
| `default.html` | The inner content a newly inserted block starts with: normal core blocks, with `{{ID:file}}` / `{{URL:file}}` image tokens. |
| `style.scss` | The section's CSS. Only loaded on pages that use the block. |
| `view.js` | Optional front-end behaviour (accordion, slider, counters…). Use the `each()` helper from `src/shared/view.js`. |

- **Saved markup:** `<{tagName} class="wp-block-imet-<name> imet-section <className>">` wrapping
  `InnerBlocks.Content`. The block is static and everything inside it stays editable.
- **Don't change a block's `tagName` or `className`.** Doing so invalidates every saved copy of the block. If you
  have to, add a `deprecated` entry.
- **`imet/section`** is a blank wrapper for building new sections out of the shared styles.

## CSS rules (important)

- **Scope everything** to `.imet-section`. The section root is `.imet-section.<class>`
  (e.g. `.imet-section.faq`); descendants are `.imet-section .x`. Never style bare elements or theme classes.
- **Cascade order:** base.css loads before the block CSS, and the landing template moves IMET styles after the
  theme's. Avoid same-specificity fights between files. Section mobile padding is the last rule in each block's
  file for exactly this reason.
- **Section header** (shared, in base.scss): a group `.sec-head` holding
  `p.eyebrow-line` (gold text with a 74px gold line; add `left` for left-aligned) → `h2.title` → `p.sub`.
  The Figma sizes are 22px / 35px / 18px.
- **Font sizes:** `clamp(min, base + slope·vw, max)` must reach `max` at **1440px**, the Figma frame width.
  A linear fit from 768px to 1440px works.
- **Buttons:** use `src/shared/_buttons.scss` only. Classes on `.wp-block-button`:
  - colour: `btn-navy`, `btn-white`, `btn-gold`, `btn-outline`, `btn-outline-light`, `btn-light`, `btn-link`
  - size: `btn-sm`, `btn-md`, `btn-lg`, which are exactly 48 / 60 / 72px tall (44 / 54 / 62px on phones)
  - icons: `btn-arrow`, `btn-icon-search`, `btn-icon-out`

  Never give a single section its own button sizes. Every button has the glass flash animation.
- **Utilities:** `u-mt-*`, `u-mb-*`, `u-py-*`, `u-center`, `u-bg-navy`, `u-card`, `u-hide-mobile`… are in
  `_utilities.scss`.
- **CSS `url()`s** are not processed by webpack (`url: false`). Point them at `../../../assets/...` from a block,
  or `../../assets/...` from `src/shared`.

## Content and patterns

- `patterns/dba-landing.php` is a **static copy** of the blocks' default content in page order. When you change a
  block's `default.html`, update that block's section in the pattern too, or regenerate the pattern from the
  blocks in the same order. Existing pages are never changed by either edit; only new pages and new inserts are.
- DBA page order: hero → subnav → highlights → specializations → cost-banner → overview → achievements →
  career-table → usp-bento → embassy → dba-vs-phd → who-can-join → analytics → alumni-video → cyber-banner →
  testimonials → certificate → faq → cta → float-contact.
- **Subnav links** (`#overview`, `#programs`, `#presence`, `#specializations`, `#who-can-join`, `#dba-vs-phd`,
  `#faq`) must match block anchors.
- **New images:**
  1. Add the file to `assets/images/`.
  2. Add it to `imet_blocks_media_files()` in `includes/media.php`.
  3. Bump `IMET_BLOCKS_VERSION` in `imet-blocks.php`, `package.json` and `readme.txt`. The bump re-runs the media
     import in wp-admin after an update.

## Things that are easy to break

- **The landing template** renders the content **before** `wp_head()`, so block styles print in `<head>`. Keep
  it that way to avoid layout shift.
- **Classic themes** enqueue every block's style on every page. `register.php` dequeues the IMET styles the
  current page doesn't use.
- **The Classic Editor** is active on the live site. `use_block_editor_for_post` forces the block editor for IMET
  pages only. Don't change that for other pages.
- **In the editor**, IMET Landing pages switch to content-only mode (`src/editor/content-only.js`).
- **The FAQ** hides questions 6 and up on the front end only (`:where(body:not(.editor-styles-wrapper))`).
  Editors must still see every question.
- **The `[imet_advisor_form]` shortcode** is a placeholder. Real sites paste a form plugin's shortcode in its
  place.
- **Keep `perf.php` in step with the theme.** It dequeues Glidex/Elementor handles by name, so adding a
  theme or plugin feature to the page may mean removing a handle from those lists.

## Verifying a change

1. Run `npm run build`.
2. Check http://imetworld.local/imet-preview/: it's built from the DBA pattern, so refresh its content when the
   pattern changes. Check at 1440px and 390px.
3. Make sure no block is invalid in the editor, and that there's no horizontal scroll on phones.
4. If the change touches the design, compare against the Figma export. It's a 1440px-wide JPEG at 1.5× scale,
   kept outside the repo; ask the user for its path.

**WP-CLI on the Local site:** WP-CLI needs Local's MySQL socket. For example:

```bash
php -d mysqli.default_socket="$HOME/Library/Application Support/Local/run/<site-id>/mysql/mysqld.sock" \
  "$(which wp)" <command>
```

## Git and workflow rules (from the repo owner)

- **Never commit or push straight to `main`.** Create a branch for every change (`feature/…`, `fix/…`,
  `docs/…`), push it, and offer a pull request. Only merge when asked.
- **No AI attribution:** no "Co-Authored-By: Claude" or similar lines in commits or PRs.
- **Bump the version** (`imet-blocks.php` header + constant, `package.json`, `readme.txt` stable tag) on every
  release-worthy change. Rebuild `imet-blocks.zip` for upload. It's git-ignored.
- **Uploading to a site:** Plugins → Add New → Upload → "Replace current with uploaded". Then purge NitroPack.
