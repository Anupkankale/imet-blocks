# Changelog

All notable changes to IMET Blocks. Versions follow `major.minor.patch` (see
[Deployment → Versioning](docs/DEPLOYMENT.md#versioning)).

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
