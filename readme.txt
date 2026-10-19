=== IMET Blocks ===
Requires at least: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later

Reusable IMET section blocks (Hero, FAQ, Testimonials, CTA…) built from normal WordPress blocks, and page
patterns that compose them into full pages.

== How it is organised ==

src/blocks/<name>/      One folder per section block:
                          block.json    name, title, and "imet": the element + classes it renders
                          style.scss    the section's design (loaded only on pages that use the block)
                          view.js       the section's behaviour, if any (accordion, slider, counters…)
                          default.html  the content a newly inserted block starts with
                          index.js      registers the block (shared code: src/shared/section-block.js)
src/shared/             base.scss (fonts, colours, buttons — shared by all blocks), page-layout.scss
                        (IMET Landing template only), editor-canvas.scss, view.js helpers
patterns/               Full pages made of IMET blocks, one file per page (DBA page: dba-landing.php)
templates/              The "IMET Landing" page template
build/                  Compiled output — what WordPress loads. Rebuild after changing src/.

== Building ==

  npm install
  npm run build        (or: npm start — rebuilds on every change)
  npm run plugin-zip   (installable zip without node_modules/src)

== Making pages ==

* New page → Template: IMET Landing → insert blocks from the "IMET" category, or a whole page from
  Patterns → IMET. Plugins screen → "Create: <page>" makes a draft from a page pattern.
* Every block's content is normal blocks: edit text/images in place, add/remove/reorder freely.
* Links like #faq jump to the block with that HTML anchor (Block → Advanced → HTML anchor).

== Adding a new section block ==

1. Copy a folder in src/blocks/, rename it, and change "name", "title" and "imet" in block.json.
2. Put the section's default content in default.html and its CSS in style.scss (scoped to .imet-section.<class>).
3. npm run build. The block appears in the inserter.

== Adding a new page ==

Copy patterns/dba-landing.php, change Title/Description and the list of blocks. It shows up as a pattern and as
"Create: <title>" on the Plugins screen.

== Moving pages from IMET Generic Landing ==

  wp imet migrate <page-id> --dry-run   (shows what changes)
  wp imet migrate <page-id>             (converts; the old version is kept as a revision)

Each section Group becomes its IMET block around the same content, and the page switches to Template → IMET
Landing. In the editor the same works per section: select the Group → Transform → IMET <section>.
When no page uses Template → IMET Generic any more, deactivate and delete the IMET Generic Landing plugin.

== Enquiry form ==

The Hero's form card holds a Shortcode block: replace [imet_advisor_form] with any form plugin's shortcode
([contact-form-7 id="123"], [wpforms id="123"]…). Until then only editors see a placeholder.

== Notes ==

* Speed (IMET Landing template): unused theme/plugin assets skipped, fonts self-hosted and preloaded, CSS
  combined into wp-content/uploads/imet-blocks-cache/, analytics delayed until the first interaction or 5 s.
* If a caching/optimisation plugin is active, exclude these pages from "combine CSS", "delay JS" and
  "remove unused CSS".
