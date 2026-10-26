# Changelog

All notable changes to IMET Blocks. Versions follow `major.minor.patch` (see
[Deployment → Versioning](docs/DEPLOYMENT.md#versioning)).

## Unreleased
- **Automated tests:** `npm test` (static checks + Jest unit tests for `view.js`) and `npm run test:e2e` (Playwright
  on the preview page at 1440px and 390px). No change to the plugin itself.

## 1.9.0 — 2026-10-26
- **New page pattern: Home Page** (`patterns/home-page.php`): the content and section order of imetworldwide.com, matched to the
  live page's spacing and layout at 1440px and 390px (every section within about 30px of the live height; the live header
  overlay and the enquiry form are not counted). Use **Create: Home Page** under Plugins, or the pattern inserter.
- **New block styles "Home"** (other pages are unchanged): Hero, Highlights, Cost Banner, Recognition Logos, Tabs,
  Specializations, Step Cards, Alumni Videos, Certificate, Call to Action, Testimonials, FAQ and the blank Section. See the
  styles table in [docs/BLOCKS.md](docs/BLOCKS.md). Pages whose hero uses the Home style also get 28px titles (25px on phones)
  and 18px gold eyebrows without the underline.
- Front end: graduate cards, employer logos and client testimonials glide sideways; "Why choose" and success stories have
  arrow buttons; the three alumni videos play in place; the FAQ shows every question.
- 64 new images in `assets/images/` (home-*), imported to the Media Library with alt text.
- Speed: the hero graduate cards load straight away; the images further down (logos, emblems, card and tab photos, banners, testimonials,
  map, video thumbnails) are lazy-loaded; YouTube thumbnails now carry their width and height.
- Not identical to the live page: buttons keep the plugin's button sizes (48 / 60 / 72px) instead of the live page's 40px
  buttons; the MBA and Diploma tab panels list titles only; the live page's header, footer, popup and chat button stay in Elementor.

## 1.8.1 — 2026-10-23
- **DBA Course Page, pixel match to the Figma (1440):** every section from the menu bar down is now within ±3px of the
  design (was up to +174px). New block styles, so the DBA Category Page is unchanged: Highlights **Wide**, Program
  Overview **Photo**, Testimonials **Compact**, FAQ **Compact**, Call to Action **Compact**. Section titles on course pages
  are 38px (Concentrations 48px); hero, tabs, curriculum, admission, research steps and concentrations spacing tuned.
- **Step Cards:** the photo no longer sets the card height (cards are 188px as designed).
- **Program Overview:** with a button under the photo, the photo row is only as tall as the photo.
- No sideways scrolling at any width (wide layouts start where they fit).

## 1.8.0 — 2026-10-23
- **New pattern: DBA Course Page** (one DBA specialization, shipped with DBA in Cybersecurity content), from the
  "Cyber security page – Course Page Structure" design. Built mostly from existing blocks; also on the Plugins screen as
  **Create: DBA Course Page**.
- **New blocks:** IMET Tabs (pill tab bar + panels, ARIA tabs), IMET About Card, IMET Process Timeline, IMET Step Cards.
- **Block styles** on existing blocks (existing pages unchanged): Hero **Dark** (navy, breadcrumb, rating),
  Who Can Join **Boxed** (curriculum: grey boxes, all closed), Specializations **Five per row**, Recognition Logos
  **Logos**. Button class `btn-icon-download`; optional button under the photo in Program Overview.
- **Marquee fix:** a row only scrolls when its own items overflow (the copies made for the loop no longer count).
- 15 new images (course photos, research steps, alumni company logos), imported into the Media Library on update.

## 1.7.8 — 2026-10-23
- **Patterns:** removed the unused section patterns (Heading, text and button; Button row; CTA banner (navy);
  CTA card (soft)). Patterns → IMET now lists only the DBA page, renamed **DBA Category Page** (also on the Plugins
  screen: **Create: DBA Category Page**; the pattern slug `imet-blocks/dba-landing` is unchanged). The IMET Section (blank) block, the button system and
  the helper classes are unchanged; `Kind: section` patterns are still supported for future ones.

## 1.7.7 — 2026-10-23
- **SEO / accessibility:**
  - The IMET Landing template wraps the page content in `<main id="content">`, so the page has a main landmark and
    the theme's "Skip to content" link works.
  - Card titles in What is a DBA?, the Our USP's table and the USP cards are H3 (were H4), and the hero form title
    "Talk to an Advisor" is H2 (was H3), so headings go H1 → H2 → H3 without skipping a level. They look the same; pages built with H4 keep their styling.
  - The four USP card photos have descriptive alt text.
  - The FAQ's "See More" is announced as a button (role, expanded state); focus moves to the first revealed question.

## 1.7.6 — 2026-10-22
- **DBA page:** the floating WhatsApp "Contact us" button is no longer part of the page pattern; the site's own
  WhatsApp button is used. The IMET Floating Contact block is still available for sites without one.

## 1.7.5 — 2026-10-22
- **Live site (Hello Elementor) spacing:** WordPress printed its global styles a second time in the footer, after the
  IMET CSS, adding a 24px gap above almost every heading, paragraph and group (sections 25–160px too tall). They're no
  longer printed on IMET pages with classic themes.
- **Theme-proof styles:** the sections set their own letter spacing, table text size, comparison-table header border
  and `<sub>` line height, instead of picking up the theme's.
- **Our Achievement:** the four counters stay on one row on desktop.

## 1.7.4 — 2026-10-22
- **Alumni videos:** the main player reads as a video: gold play button with a soft pulse, dark gradient and a
  "Watch now" caption. One click plays it in place.
- **Alumni videos:** three thumbnails that fill the column with View More. Each has a play icon, a caption, hover
  zoom and a "Now playing" badge, and clicking one plays it in the main player. The gallery uses the full content
  width.

## 1.7.3 — 2026-10-22
- **Who can Join and FAQ:** smooth open/close animation (no more jumping). Thin dividers and a navy open state
  with a minus icon.
- **Accordion questions:** no theme focus box after a mouse click.

## 1.7.2 — 2026-10-22
- **Our USP's table:** live-site content (5 columns × 5 rows), with See More / Close. Rows line up across columns.
- **Buttons:** no theme focus outline after a mouse click.

## 1.7.1 — 2026-10-22
- **Section menu bar:** moved below Program Highlights.

## 1.7.0 — 2026-10-22
- **Accreditation logos:** one scrolling row on phones.
- **Marquee:** now a shared helper, `marquee()`.

## 1.6.9 — 2026-10-22
- **Hero:** no extra right padding when stacked on tablets and phones.

## 1.6.8 — 2026-10-22
- **Mobile:** stacked sections centre their heading, intro and buttons. The achievement stats are 2 × 2 on phones,
  and the WhatsApp button is a circle only on phones.
- **Program cards:** back to the Figma height (68px).

## 1.6.7 — 2026-10-22
- **Our Programs:** continuous marquee scroll.

## 1.6.6 — 2026-10-22
- **Our Programs:** auto-scroll replaces the arrow buttons.

## 1.6.5 — 2026-10-22
- **Our Programs:** 4 cards per view, a brand-colour hover, and links to the six DBA program pages.

## 1.6.4 — 2026-10-22
- **Overview:** the benefit cards work as tabs, with the first one open.

## 1.6.3 — 2026-10-22
- **Overview:** every benefit card has a description.

## 1.6.2 — 2026-10-22
- **Alumni videos:** matched to the Figma (position, sample images, no default highlight).

## 1.6.1 — 2026-10-22
- **USP grid:** Global Accreditation card.
- **Spacing:** matched to the Figma; every section header is within 4px.

## 1.6.0 — 2026-10-21
- **DBA page:** Figma section order and design for every section, new images, and the shared section header.

## 1.5.0 – 1.5.2 — 2026-10-21
- **Buttons:** a glass flash effect, then slowed to a 2.6s sweep every 9s.

## 1.4.0 — 2026-10-21
- **Testimonials:** card grid design.

## 1.3.0 — 2026-10-21
- **Alumni videos:** main player plus a thumbnail gallery.

## 1.1.0 – 1.2.0 — 2026-10-21
- **Video section:** iterations on the layout.

## 1.0.0 — 2026-10-19 to 2026-10-21
- **First release:** 20 section blocks built from the IMET Generic Landing page, the IMET Landing template, the DBA
  page pattern, speed optimisations, FAQ schema, and WP-CLI migration.
- **Editor:**
  - Page patterns show in the editor with block themes.
  - IMET pages open in the block editor where the Classic Editor is active, and are edited content-only.
- **Design system:**
  - A uniform button system (3 sizes, IMET button panel) and utility classes.
  - The blank IMET Section block and ready-made section patterns.
