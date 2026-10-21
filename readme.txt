=== IMET Blocks ===
Requires at least: 6.5
Requires PHP: 7.4
Stable tag: 1.7.3
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
src/shared/             base.scss (fonts, colours — shared by all blocks), _buttons.scss (button system),
                        _utilities.scss (helper classes), page-layout.scss (IMET Landing template only),
                        editor-canvas.scss, view.js helpers
src/editor/             editor additions: the "IMET button" panel
patterns/               Full pages (Kind: page, e.g. dba-landing.php) and ready-made sections (Kind: section,
                        e.g. CTA banner, CTA card, button row) made of IMET blocks
templates/              The "IMET Landing" page template
build/                  Compiled output — what WordPress loads. Rebuild after changing src/.

== Building ==

  npm install
  npm run build        (or: npm start — rebuilds on every change)
  npm run plugin-zip   (installable zip without node_modules/src)

== Making pages ==

* New page → "Choose a pattern" → pick a page (e.g. "Online Doctorate of Business Administration (DBA)"),
  or insert single blocks from the "IMET" category. On save, a page with IMET blocks and no template chosen
  gets Template → IMET Landing automatically. Plugins screen → "Create: <page>" makes a draft from a pattern.
* Sites using the Classic Editor: IMET pages (IMET blocks in the content, or Template → IMET Landing) always
  open in the block editor; other pages are unchanged. Start an IMET page from Plugins → "Create: <page>", or
  set Template → IMET Landing in Page Attributes and Update.
* Every block's content is normal blocks: edit text/images in place, add/remove/reorder freely.
* Links like #faq jump to the block with that HTML anchor (Block → Advanced → HTML anchor).

== Buttons ==

One system for every button: same font (Inter 600), 6px corners, 2px border and hover. Select a Button block →
sidebar → "IMET button": Colour (Navy, White, Gold, Outline, Outline light) and Size:

  S  48px tall, 16px text   (phones 44px / 15px)
  M  60px tall, 18px text   (phones 54px / 16px)   ← default
  L  72px tall, 20px text   (phones 62px / 17px)

Classes (if typed by hand): btn-navy btn-white btn-gold btn-outline btn-outline-light btn-light btn-link + btn-sm btn-md btn-lg.
Icons: btn-arrow (circled arrow after), btn-icon-search, btn-icon-out (icon before).
Buttons row (core/buttons) classes: center (centred, 36px above), stack-mobile (full width on phones).
To change every button at once, edit the variables at the top of src/shared/_buttons.scss and rebuild.
Glass flash: a soft shine sweeps slowly (2.6s) across each button every 9s (staggered) and on hover/focus; not on text
links, and off for visitors who ask for reduced motion. To remove it, delete the "Glass flash" block in
_buttons.scss.

== Utilities ==

Helper classes for any block inside an IMET section (Block → Advanced → Additional CSS class):
spacing u-mt-0…64 / u-mb-0…64 / u-py-0|32|48|70, width u-narrow / u-wide / u-full, text u-center u-left
u-right u-gold u-navy u-white u-muted, background u-bg-navy u-bg-soft u-bg-card, box u-card u-rounded,
visibility u-hide-mobile u-hide-desktop. Full list: src/shared/_utilities.scss.

== Alumni videos (IMET Alumni Videos block) ==

A main player with thumbnails beside it and a "View More" button. Each video is an Image block:
click it → Link → paste the YouTube URL (watch, youtu.be, shorts and live links all work). Images with the
class "yt-auto" then show YouTube's own thumbnail; remove "yt-auto" to use the image you chose instead.
Clicking the main player plays it in place; clicking a thumbnail plays it in the main player.
Add or remove thumbnails by duplicating/deleting the images in the list. Set "View More" to the channel URL.

== Testimonials (IMET Testimonials block) ==

Eyebrow + title + text, then cards: quote, name, role and a LinkedIn logo. Click the logo's link to set the
person's LinkedIn profile. Duplicate a card to add one (3 per row; 2 on tablets; swipe on phones). "View More"
is a Text link button: set its link to the full testimonials page.

The gold eyebrow with a line under it is a shared style: add the class "eyebrow-line" to any paragraph
(add "left" too for a left-aligned header). Section headers: eyebrow-line → heading "title" → paragraph "sub",
inside a group with the class "sec-head" (Figma: 22px gold, 35px title, 18px text).
Buttons have a "Text link (underlined)" colour for links like "View More".

== DBA page (Figma: imetworldwide.com/online-doctorate-programs) ==

Section order: Hero → Program Highlights → Section navigation → Specializations → Fee card → What is a DBA →
Achievements → Career table → USP bento → Embassy logos → DBA v/s PhD → Who can Join → Industry Analytics →
Alumni videos → Cybersecurity banner → Testimonials → Certification → FAQ (first 5, "See More" shows the
rest) → CTA. Sections are separated by a thin grey line.

== Ready-made sections ==

+ → Patterns → IMET: "Heading, text and button", "Button row", "CTA banner (navy)", "CTA card (soft)".
To build something new, insert "IMET Section (blank)" and add blocks inside it — the fonts, buttons and
utilities all apply.

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
