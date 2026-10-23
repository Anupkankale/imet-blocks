# IMET Blocks

Reusable WordPress section blocks for the [IMET Worldwide](https://imetworldwide.com) website. Every section of a
landing page (hero, program highlights, FAQ, testimonials, call to action…) is a block. Pages are built by putting
blocks in order, and a whole page can be saved as a pattern.

The site is moving from Elementor to the block editor one page at a time. The first page built with this plugin is
the **Online Doctorate of Business Administration (DBA)** landing page, matched to its Figma design.

**Current version:** 1.7.4 · **Requires:** WordPress 6.5+, PHP 7.4+ · **License:** GPL-2.0-or-later

---

## Features

- **25 section blocks** in the editor's **IMET** category, some with a second look (block styles). Their content is normal WordPress blocks (headings,
  paragraphs, images, buttons), so editors change everything in place.
- **Page patterns:** a complete page in one click (**Plugins → Create: DBA Category Page** or
  **Create: DBA Course Page**, or + → Patterns → IMET).
- **One design system:** shared fonts, colours, section headers, utility classes and a button system with
  3 sizes and 7 colours, chosen from an **IMET button** panel in the editor sidebar.
- **Interactive sections:** smooth accordions, tabs, an expandable table, continuous marquees, animated counters
  and a YouTube video gallery that plays in place.
- **Responsive:** tested at 1440px, 1024px, 768px and 390px, with no horizontal scroll on phones.
- **Fast:** each block's CSS and JS load only on pages that use it. On landing pages the plugin also self-hosts
  fonts, combines CSS and delays analytics until the first interaction.
- **SEO:** FAQ structured data (FAQPage JSON-LD) is generated from the FAQ block.
- **Works alongside Elementor:** IMET pages use the **IMET Landing** template and open in the block editor even
  where the Classic Editor is active. Every other page is left alone.

## Quick start

### Use it on a site

1. Download the plugin zip (see [Deployment](docs/DEPLOYMENT.md)) and upload it at **Plugins → Add New → Upload
   Plugin**, then activate it. Activation imports the page images into the Media Library.
2. **Plugins → IMET Blocks → Create: DBA Category Page** creates a draft page with
   the full design.
3. Edit the text, images and links, add your form shortcode in the hero, then publish.

The **[Editor guide](docs/EDITOR-GUIDE.md)** explains every section in plain language.

### Work on the code

```bash
git clone https://github.com/Anupkankale/imet-blocks.git
cd imet-blocks
npm install
npm run build     # compile src/ → build/ (WordPress loads build/)
npm start         # rebuild on every change
```

Put the folder in `wp-content/plugins/` of a local WordPress site and activate **IMET Blocks**.
See the **[Developer guide](docs/DEVELOPER-GUIDE.md)**.

## Documentation

| Guide | For | What's in it |
|---|---|---|
| [Editor guide](docs/EDITOR-GUIDE.md) | Content editors | Creating a page, editing each section, buttons, videos, links, FAQs |
| [Blocks reference](docs/BLOCKS.md) | Everyone | Every block: what it shows, how it behaves, its anchor and classes |
| [Developer guide](docs/DEVELOPER-GUIDE.md) | Developers | Architecture, adding a block, CSS rules, shared JS helpers, testing |
| [Deployment](docs/DEPLOYMENT.md) | Whoever releases | Versioning, building the zip, uploading, going live, rollback |
| [Changelog](CHANGELOG.md) | Everyone | What changed in each version |

## Repository layout

```
imet-blocks.php     plugin bootstrap (version, constants, includes)
includes/           PHP: block registration, page template, patterns, media, speed, FAQ schema, migration
src/blocks/<name>/  one folder per block: block.json, style.scss, view.js, default.html, index.js
src/shared/         shared styles (base, buttons, utilities) and JS (section-block, view helpers)
src/editor/         editor additions (IMET button panel, content-only editing for IMET pages)
patterns/           page patterns
templates/          the IMET Landing page template
assets/             images and fonts referenced by the CSS and the patterns
build/              compiled output (committed, so the plugin runs without Node)
docs/               documentation
```

## Contributing

- Create a branch for every change (`feature/…`, `fix/…`, `docs/…`) and open a pull request into `main`. Never
  push straight to `main`.
- Run `npm run build` and commit `build/` together with your `src/` changes.
- Bump the version for every release (see [Deployment](docs/DEPLOYMENT.md#versioning)) and add a
  [Changelog](CHANGELOG.md) entry.
- Commit messages describe the change for people. Don't add AI attribution lines.

## License

GPL-2.0-or-later, the same as WordPress.
