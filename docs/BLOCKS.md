# Blocks reference

All blocks are in the editor's **IMET** category. Each one is a wrapper (`<section>`, `<nav>` or `<div>`) around
normal core blocks, so its content is edited in place. A newly inserted block starts with its default content
(`src/blocks/<name>/default.html`).

- **Root classes:** always `wp-block-imet-<name> imet-section` plus the classes listed below. The CSS targets these
  classes, so don't remove them.
- **Default anchor:** set automatically when the block is inserted (sidebar → Advanced → HTML anchor).
- **Script:** the block loads a front-end script (`view.js`) only on pages where it appears.

## Section blocks

| Block | Name | Element · root classes | Default anchor | Script | What it shows / does |
|---|---|---|---|---|---|
| IMET Hero | `imet/hero` | section · `hero` | `top` | ✓ | Title, intro, three laurel stats, button, enquiry form card (Shortcode block), accreditation logos card. Logos become a marquee on phones. Phone-code/flag helper for forms that use `#phoneCode` / `#phoneInput` / `.h-flag`. |
| IMET Highlights | `imet/highlights` | section · `highlights` | `overview` | – | Left-aligned "Overview" header, icon list (bold label in gold), button; illustration on the right. |
| IMET Section Navigation | `imet/subnav` | nav · `subnav` | `subnav` | ✓ | Sticky navy bar of in-page links; highlights the section in view; in-page links land just below the bar. |
| IMET Specializations | `imet/specializations` | section · `section specs` | `specializations` | ✓ | "Our Programs" header and linked program cards: 4 per view (3 / 2 / 1.2 on smaller screens), continuous marquee, navy-and-gold hover. |
| IMET Cost Banner | `imet/cost-banner` | section · `cost-banner` | – | – | Rounded card: fee, white "Up to 45%" ribbon, scholarship text and button. |
| IMET Program Overview | `imet/overview` | section · `section overview` | `programs` | ✓ | "What is a DBA?" header, six benefit cards working as tabs (first open), photo with stat badges. |
| IMET Achievements | `imet/achievements` | section · `achieve` | `presence` | ✓ | Navy card: title and four counters that count up on scroll. |
| IMET Career Path Table | `imet/career-table` | section · `section career` | – | ✓ | "Our USP's" header, 5 × 5 table (columns share row heights through CSS subgrid). First row shown; **See More** / **Close** toggles the rest. |
| IMET USP Bento Grid | `imet/usp-bento` | section · `section usp` | `usp` | – | Six feature cards in a two-column grid (light cards: gold border; navy cards: blue border) and a button. |
| IMET Recognition Logos | `imet/embassy` | section · `section embassy` | – | – | Header and six captioned emblems. |
| IMET Comparison Table | `imet/dba-vs-phd` | section · `section vs` | `dba-vs-phd` | – | Header and a core Table laid out as DBA vs PhD with a highlighted DBA column; criteria become row headings on phones. |
| IMET Who Can Join | `imet/who-can-join` | section · `section join` | `who-can-join` | ✓ | Accordion of Details blocks beside a photo; smooth height animation, one open at a time, first open. |
| IMET Image + Bullet List | `imet/analytics` | section · `section analytics` | – | – | Image card beside an "Insights" header, dotted list and button. |
| IMET Alumni Videos | `imet/alumni-video` | section · `section alumni` | – | ✓ | Main video (play button, caption) and three thumbnails + View More. Clicks play YouTube in place; thumbnails play in the main player. |
| IMET CTA Banner with Avatars | `imet/cyber-banner` | section · `cyber` | – | – | Blue rounded card, title, two white buttons with icons, four round avatars. |
| IMET Testimonials | `imet/testimonials` | section · `section testimonials` | – | ✓ | Header, three quote cards with a LinkedIn link, View More text link; cards swipe on phones. |
| IMET Certificate | `imet/certificate` | section · `section cert` | – | – | Rounded photo beside a header, tick list and button. |
| IMET FAQ | `imet/faq` | section · `section faq` | `faq` | ✓ | Accordion of Details blocks; first 5 shown, **See More** shows the rest; outputs FAQPage JSON-LD. |
| IMET Call to Action | `imet/cta` | section · `cta-wrap` | – | – | Deep-blue rounded card with heading, text, button and a photo fading in on the right. |
| IMET Floating Contact | `imet/float-contact` | div · `float-contact` | – | – | WhatsApp link fixed at the bottom left; circle only on phones. Not in the DBA page (the site has its own WhatsApp button); insert it only on sites without one. |
| IMET Section (blank) | `imet/section` | section · `section imet-blank` | – | – | Empty section with the IMET design, for building new sections. |

## Patterns

| Pattern | Kind | Where |
|---|---|---|
| Online Doctorate of Business Administration (DBA) | Full page | "Choose a pattern" on new pages, + → Patterns → IMET, **Plugins → Create: …** |

**DBA page order:** Hero → Highlights → Section Navigation → Specializations → Cost Banner → Program Overview →
Achievements → Career Path Table → USP Bento → Recognition Logos → Comparison Table → Who Can Join →
Image + Bullet List → Alumni Videos → CTA Banner with Avatars → Testimonials → Certificate → FAQ → Call to Action.

## Shared classes inside sections

| Class | On | Effect |
|---|---|---|
| `sec-head` | Group | Centred section header wrapper |
| `eyebrow-line` (+ `left`) | Paragraph | Gold eyebrow text with a gold line under it |
| `title` (+ `left`) | Heading | Section title (35px; bold words turn gold) |
| `sub` | Paragraph | Intro text under a title |
| `imet-container` (+ `narrow`) | Group | 1200px (1020px) content width |
| `btn-*` | Button | See [Buttons](EDITOR-GUIDE.md#buttons) |
| `u-*` | Any block | Utility classes, see [Adding new sections](EDITOR-GUIDE.md#adding-new-sections) |
| `yt-auto` | Image linked to YouTube | Shows YouTube's thumbnail instead of the chosen image |
