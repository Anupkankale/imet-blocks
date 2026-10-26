# Developer guide

How the plugin is built and how to change it safely.

- [Architecture](#architecture)
- [Local setup](#local-setup)
- [How a section block works](#how-a-section-block-works)
- [Adding a block](#adding-a-block)
- [Adding a page or section pattern](#adding-a-page-or-section-pattern)
- [Styling rules](#styling-rules)
- [Front-end scripts](#front-end-scripts)
- [PHP: hooks and features](#php-hooks-and-features)
- [Images and fonts](#images-and-fonts)
- [Testing a change](#testing-a-change)
- [Known limitations](#known-limitations)

---

## Architecture

```
┌────────────── imet-blocks.php (version, constants, require includes/*) ───────────────┐
│ includes/register.php   registers build/blocks/* from build/blocks-manifest.php        │
│                         registers the shared style "imet-base"                         │
│                         sends each block's default content to the editor               │
│                         classic themes: dequeues styles of IMET blocks not on the page │
│ includes/templates.php  "IMET Landing" page template, patterns, Create: <page> links,   │
│                         block editor forced on for IMET pages, landing-page assets     │
│ includes/perf.php       landing pages: combine CSS, drop Google Fonts, delay analytics │
│ includes/media.php      image import to the Media Library, {{ID:…}}/{{URL:…}} tokens   │
│ includes/faq-schema.php FAQPage JSON-LD from imet/faq                                  │
│ includes/video.php      yt-auto images show their YouTube thumbnail                    │
│ includes/migrate.php    `wp imet migrate` (pages from the old IMET Generic Landing)    │
└───────────────────────────────────────────────────────────────────────────────────────┘
src/ ──(npm run build: @wordpress/scripts + webpack.config.js)──▶ build/
```

- **Build tool:** `@wordpress/scripts` with a small `webpack.config.js` on top. It adds the shared stylesheets as
  entries, leaves CSS `url()`s alone (they point at `assets/` with stable names), and copies each block's
  `default.html` into `build/`.
- **Committed build:** `build/` is committed, so the plugin runs on a server without Node.
- **Where it runs:**
  - The live site uses the **Glidex** theme (classic) with an Elementor header and footer and the Classic Editor.
  - The local site uses **Twenty Twenty-Five** (a block theme).
  - Changes must work under both.

## Local setup

1. Install a local WordPress site (the project uses [Local](https://localwp.com)) and clone the repo into
   `wp-content/plugins/imet-blocks`.
2. Run `npm install`, then `npm run build`.
3. Activate **IMET Blocks**. Activation imports the images.
4. Create a page from the DBA pattern (**Plugins → Create: …**) to work against.

**WP-CLI with Local:** point PHP at Local's MySQL socket.

```bash
php -d mysqli.default_socket="$HOME/Library/Application Support/Local/run/<site-id>/mysql/mysqld.sock" \
  "$(which wp)" plugin list
```

| Command | Purpose |
|---|---|
| `npm run build` | Production build |
| `npm start` | Watch and rebuild |
| `npm run plugin-zip` | Installable `imet-blocks.zip` (git-ignored) |
| `npm run lint:css` | Stylelint |
| `npm run lint:js` | ESLint (currently crashes under Node 24 because of a wp-scripts dependency) |

## How a section block works

```
src/blocks/faq/
  block.json     metadata + "imet": { "tagName": "section", "className": "section faq", "anchor": "faq" }
  index.js       registerSectionBlock( metadata )
  default.html   the inner blocks a new FAQ block starts with
  style.scss     the FAQ's CSS (loaded only where the block is used)
  view.js        front-end behaviour (accordion, See More)
```

`src/shared/section-block.js` turns the `imet` key into the block:

- **save:** `<section class="wp-block-imet-faq imet-section section faq" id="faq">` + `InnerBlocks.Content`. It's
  static markup; everything inside is core blocks.
- **edit:** the same wrapper. On first insert it fills the block with `default.html` (sent by PHP as
  `window.imetBlocks.defaults`) and sets the default anchor.
- **transforms:** a core Group carrying the section's last class (e.g. `faq`) can be turned into the block.

> **Never change a block's `tagName` or `className`** once pages use it. Every saved copy would become invalid.
> If you must, add a `deprecated` entry in `index.js`.

## Adding a block

1. Copy a block folder, e.g. `src/blocks/cta` → `src/blocks/partners`.
2. Edit `block.json`:
   - `name`: `imet/partners`
   - `title`: "IMET Partners"
   - `description`
   - `imet.className`: `section partners`, unique last class
   - `imet.anchor`, if the section should have one
   - `viewScript`, only if you add a `view.js`
3. Write `default.html`: the starting content as block markup. The easiest way is to build it in the editor, open
   **Code editor** and copy it. Replace Media Library IDs and URLs with `{{ID:file}}` / `{{URL:file}}` tokens for
   images in `assets/images/`.
4. Write `style.scss`, scoped to `.imet-section.partners` (see [Styling rules](#styling-rules)).
5. Run `npm run build`. The block appears in the IMET category.
6. If it belongs on the DBA page, add it to `patterns/dba-landing.php` in the right place (see below).

## Adding a page or section pattern

Patterns are PHP files in `patterns/` with a header:

```php
<?php
/**
 * Title: Online MBA in Cybersecurity
 * Description: The complete MBA landing page.
 * Kind: page        (page = full page; section = inserter only)
 */
?>
<!-- wp:imet/hero {"anchor":"top"} --> … <!-- /wp:imet/hero -->
…
```

- **Kind: page** patterns appear in "Choose a pattern" on new pages, in the inserter, and as
  **Plugins → Create: <Title>**.
- **Kind: section** patterns appear in the inserter only.
- A pattern is a **static copy**. When you change a block's `default.html`, also update that block's part of every
  pattern that uses it, or the pattern keeps the old content. Existing pages never change; only new pages and new
  inserts do.

## Styling rules

**Block styles** (a second look for an existing block) are declared in its `block.json` → `"styles"` and styled with
`.imet-section.<key>.is-style-<name>`. WordPress adds the class to the block's root only when an editor (or a pattern)
picks the style, so existing pages never change. Prefer a style over a new block when the content structure is the
same (e.g. Who Can Join → **Boxed** for a curriculum); add a block only for a new structure.

**Section title size** comes from `--imet-title-size` (fallback: the standard 26px → 35px clamp). Course pages set it
to 38px with `.imet-page:has(> .wp-block-imet-hero.is-style-dark)` in `base.scss`; a block style can set its own
(Specializations **Five per row**: 48px). Fixed-width desktop layouts in block styles start at a breakpoint where the
1200px container plus any overhang fits (e.g. Highlights **Wide** from 1281px), so no screen width scrolls sideways.

1. **Scope everything to `.imet-section`.**
   - Root: `.imet-section.faq`.
   - Inside: `.imet-section .faq-acc`.
   - Never style bare elements or theme classes. The root rule and `.imet-page .x` used to have the same
     specificity, so the cascade order stays predictable.
2. **Cascade order:** `imet-base` loads before every block's CSS, and on landing pages the IMET styles are moved
   after the theme's. Don't rely on the order between two block files. A section's mobile padding is the last rule
   in its own file for this reason.
3. **Section header:** a group `.sec-head` with `p.eyebrow-line` → `h2.title` → `p.sub` (22 / 35 / 18px at 1440px).
   Add `left` to the eyebrow and title for a left-aligned header. It centres automatically below 900px.
4. **Font sizes:**
   - Use `clamp(min, base + slope·vw, max)`.
   - It must reach `max` at **1440px**, the Figma frame width. Fit it linearly between 768px and 1440px.
5. **Buttons:**
   - Use the system in `src/shared/_buttons.scss`:
     - colour classes: `btn-navy`, `btn-white`, `btn-gold`, `btn-outline`, `btn-outline-light`, `btn-light`,
       `btn-link`
     - size classes: `btn-sm`, `btn-md`, `btn-lg`
     - icon classes: `btn-arrow`, `btn-icon-search`, `btn-icon-out`
   - Don't give a section its own button sizes. To change all buttons, edit the variables at the top of the file.
6. **Utilities:** `src/shared/_utilities.scss` (`u-…`). They use `!important` so they win.
7. **Assets in CSS:** webpack doesn't process `url()`. Reference `../../../assets/…` from a block and
   `../../assets/…` from `src/shared/`.
8. **Editor vs front end:**
   - Rules that hide content (closed tabs, extra FAQ questions, collapsed table rows) are wrapped in
     `:where(body:not(.editor-styles-wrapper))`, so editors still see and edit everything.
   - Editor-only fixes for the IMET Landing canvas live in `src/shared/editor-canvas.scss`.
9. **Breakpoints in use:** 1200, 1100, 1000, 900, 800, 782, 700, 640, 520 and 480px.
   - **640px** is "phone".
   - **900px** is where most two-column sections stack.

## Front-end scripts

`src/shared/view.js` exports the helpers every `view.js` uses:

| Helper | Used by | What it does |
|---|---|---|
| `each( selector, fn )` | all | Runs `fn` for each match once the DOM is ready |
| `reduceMotion()` | all | `true` if the visitor prefers reduced motion |
| `accordion( group, { openFirst } )` | Who Can Join, FAQ | One item open at a time. Wraps answers in `.acc-body` and animates their height; `.is-closing` marks an item animating shut (still `[open]`). Style answers with `details p`. |
| `marquee( row, items, { speed } )` | Specializations, hero logos (phones) | Clones the items once (`.is-clone`, `aria-hidden`) and scrolls continuously. Only moves while the original items overflow the row (CSS decides per screen size; the clones don't count), it's on screen, and it isn't hovered, focused or touched. |

**Per-block scripts:**
- **Subnav:** scroll-spy, and in-page links that land below the bar.
- **Achievements:** counters.
- **Overview:** tabs.
- **Career table:** See More / Close.
- **Alumni videos:** play in place.
- **Testimonials:** the old slider, kept for pages built before the card grid.
- **Hero:** phone code and flag, plus the logo marquee.

Scripts are classic scripts (`viewScript`), loaded only where the block is used. Keep them dependency-free.

## PHP: hooks and features

| Feature | Where | Notes |
|---|---|---|
| Block registration | `register.php` | Uses `wp_register_block_types_from_metadata_collection` (WP 6.8+) with fallbacks for 6.5–6.7. |
| Shared style handle `imet-base` | `register.php` | Listed in every block's `style` array, so it loads once. |
| Default content to the editor | `register.php` | `window.imetBlocks.defaults`, added before `wp-blocks`. |
| IMET Landing template | `templates.php`, `templates/page-landing.php` | Renders the content **before** `wp_head()` so block styles print in `<head>` (no layout shift). Wraps it in `<main id="content" class="imet-page">` (main landmark, target of the theme's skip link). Also registered as a block template, so block themes show its name. |
| Auto template | `templates.php` | On save, a page with `<!-- wp:imet/` and no template gets IMET Landing. |
| Block editor on IMET pages | `templates.php` | `use_block_editor_for_post` at max priority, IMET pages only. |
| Content-only editing | `src/editor/content-only.js` | IMET Landing pages open in post-only rendering mode. |
| IMET button panel | `src/editor/button-panel.js` | `editor.BlockEdit` filter on `core/button`. |
| Speed (landing pages) | `perf.php` | Moves body stylesheets to `<head>`, drops Google Fonts, combines CSS into `uploads/imet-blocks-cache/`, delays GTM, gtag and Clarity until the first interaction or 5s. |
| FAQ schema | `faq-schema.php` | `render_block_imet/faq` collects questions; printed in `<head>` or the footer. |
| YouTube thumbnails | `video.php` | `render_block_core/image` swaps the `src` for images with class `yt-auto`. |
| Form placeholder | `templates.php` | `[imet_advisor_form]` shows a note to editors and nothing to visitors. |
| Migration | `migrate.php` | `wp imet migrate <id> [--dry-run]` turns section Groups into IMET blocks. |

### What the plugin overrides on the site (and where it stops)

These are deliberate. They are listed here so nobody is surprised by them.

| Override | What it does | Where it stops |
|---|---|---|
| **Classic Editor** (`use_block_editor_for_post`, priority `PHP_INT_MAX`) | The live site runs the Classic Editor. IMET pages must open in the block editor, so the plugin has the last word on this filter. | Only pages that use the IMET Landing template or contain an `<!-- wp:imet/` block (`imet_blocks_is_imet_page()`). Every other page and post keeps the editor the site chose. |
| **Template on save** (`save_post_page`) | A page that contains IMET blocks and has no template gets IMET Landing. | A template chosen on purpose is kept; revisions are skipped. |
| **Landing-page HTML rewrite** (`perf.php`) | Moves stylesheets, removes Google Fonts, combines CSS, delays analytics scripts until the first interaction. | Only the IMET Landing template. It matches the Glidex/Elementor handles by name, so a theme update can need a change here; `tests/php/PerfTest.php` covers the rewrite. |
| **Media import** (`media.php`, `admin_init`) | After a version change, the images in `assets/images/` are imported to the Media Library. | Runs once per version; images already imported are reused. |
| **Image loading hints** (`perf.php`, `render_block_core/image`) | Hero graduate cards load at once; the images further down are lazy. | Only images with the Home page class names listed in the filter. |

If you add an override, add a row here and a test next to it.

## Images and fonts

- **Fonts** (`assets/fonts`):
  - **Montserrat** and **Inter** are self-hosted under unique family names (`IMET Montserrat` / `IMET Inter`), with
    metric-matched fallbacks to avoid layout shift.
  - **Quantico** and **Cambay** are also self-hosted, for the theme's header and footer.
  - Landing pages preload Montserrat, Inter and Quantico Bold.
- **Images** (`assets/images`):
  - Photos used in default content are listed in `imet_blocks_media_files()` (`includes/media.php`).
  - They're imported into the Media Library on activation, and again after a version change.
  - Decorative icons used only in CSS are not imported.
- **Adding an image:**
  1. Add the file to `assets/images`.
  2. List it in `imet_blocks_media_files()`.
  3. Reference it in content with `{{ID:file}}` / `{{URL:file}}`.
  4. **Bump the version** so existing sites import it.

## Testing a change

### Automated tests

| Command | What it checks | Needs |
|---|---|---|
| `npm run test:static` | Block metadata and files, `build/` in step with `src/`, the version in all four places, image tokens, in-page links, `php -l` on every PHP file | Node (PHP optional) |
| `npm run test:unit` | `src/shared/view.js` (Jest + jsdom): `each`, `reduceMotion`, `accordion` | Node |
| `npm run test:e2e` | The DBA preview page in Chromium at 1440px and 390px: one `h1`, no script errors, no sideways scroll, sub-nav targets, FAQ See More and one-open-at-a-time, alt text | the local site; `npx playwright install chromium` once |
| `npm test` | static + unit | Node |

`test:e2e` uses `http://imetworld.local/imet-preview/`. Override with `BASE_URL=... PREVIEW_PATH=... npm run test:e2e`.
The static test for placeholder `href="#"` links is marked `todo`: it reports the dead buttons without failing the run.
Remove `todo` once they are fixed.

### By hand

The tests don't replace looking at the page. Check each change by hand:

1. **Build:** `npm run build` with no errors.
2. **Editor:** open a page with the block, and check for no "invalid content" warnings and no console errors.
3. **Front end at 1440px:** compare with the Figma.
4. **Front end at 390px:** check alignment, that nothing overflows (`document.documentElement.scrollWidth` should be
   390), and that touch behaviour works.
5. **Behaviour:** click through accordions, tabs, See More, videos and menu links. Check keyboard use (Tab,
   Enter/Space) and that reduced motion turns animations off.
6. **Classic theme:** before a release, check on the dev site (Glidex + Elementor).

## Known limitations

- **Patterns** are copies. Editing a block's default content doesn't update pages that already exist.
- **Glidex/Elementor:** `perf.php` dequeues their handles by name. New theme features used on a landing page may
  need their handle removed from those lists.
- **Autoplay:** videos start on click; autoplay would have to be muted and needs real YouTube links.
- **Lint:** `lint:js` doesn't run on Node 24. Use Node 20 LTS if you need it.
