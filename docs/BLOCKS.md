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
| IMET Tabs | `imet/tabs` | section · `section tabs` | `learn-more` | ✓ | Header, a pill bar of tab labels (`.tab-label` paragraphs in `.tab-nav`) and one panel per label (`.tab-panel` groups, matched by position) holding a two-column green-tick list. First tab open; click or ←/→/Home/End; ARIA tabs. The editor shows every panel. |
| IMET About Card | `imet/about-card` | section · `section about-card` | `about` | – | Header and intro, then a peach-to-lavender card: green-tick list (`.about-ticks`) beside a title and a list on a gold timeline line (`.about-key`). |
| IMET Process Timeline | `imet/process` | section · `section process` | `admission` | – | Peach band: steps (`.p-step` = H3 + text) on a horizontal navy line with dots, then a button. 3, 4 or 5 steps; vertical on phones. |
| IMET Step Cards | `imet/step-cards` | section · `section step-cards` | `research-steps` | – | Header, then step cards in two columns (left navy, right light) with a gold "Step n:" tag, H3, text and a photo; one column on tablets, photo on top on phones. |
| IMET Section (blank) | `imet/section` | section · `section imet-blank` | – | – | Empty section with the IMET design, for building new sections. |

## Block styles

Some blocks have a second look, chosen in the sidebar (**Styles**). It adds an `is-style-*` class to the block; pages
that don't choose it are unchanged.

| Block | Style | Look |
|---|---|---|
| IMET Hero | **Dark** | Navy background, white text, optional breadcrumb (`.h-crumbs`) and rating (`.h-rating`) lines above the title, no accreditation card. |
| IMET Who Can Join | **Boxed** | Grey rounded items with a navy +/−, no icons, all closed at first, rounded photo (e.g. a curriculum). |
| IMET Specializations | **Five per row** | Five cards fill the container on desktop, so they stay still; they scroll on smaller screens. |
| IMET Recognition Logos | **Logos** | Company logos without captions in one centred row. |
| IMET Highlights | **Wide** | One-line 34px title over a wider text column, compact 18px list, 386px button, 478px image (from 1281px wide; default layout below). |
| IMET Program Overview | **Photo** | Larger cards (20px titles, 17px text, no gold highlight on the open card), 633px photo with a button under it (from 1241px). |
| IMET Testimonials | **Compact** | Intro closer to the title, 210px cards, "View More" closer. |
| IMET FAQ | **Compact** | Questions right under the title, 14.5px answers on taller lines, a larger "See More". |
| IMET Call to Action | **Compact** | 38px title, two-line intro, 66px button with 24px text, 32px corners. |
| IMET Hero | **Home** | Centred badge, title, lead and button over a soft gradient, then a row of graduate cards that glide sideways. |
| IMET Highlights | **Home** | Grey-blue band, outlined gold pills, and the enquiry form in a white card on the right. |
| IMET Cost Banner | **Home** | 952px card with smaller type. |
| IMET Recognition Logos | **Home** | Accreditation logos (add class `is-accred`), 80px embassy emblems, or two gliding rows of alumni employer logos (class `is-employers`). |
| IMET Tabs | **Home** | Plain pills over a navy panel: photo on the left, timeline of programs on the right. |
| IMET Specializations | **Home** | Five white cards with title, navy arrow, line and description; stacked on phones. |
| IMET Step Cards | **Home** | Three pastel cards in a sideways row with arrows ("Why choose IMET"); with class `is-cases`, four portrait photo cards (case studies). |
| IMET Alumni Videos | **Home** | Three equal videos with the title under each; each plays in place. |
| IMET Certificate | **Home** | Text on the left, three portrait photos and a wide group photo on the right. |
| IMET Call to Action | **Home** | Navy "Publish your research" banner (class `is-publish`) or the closing banner over a photo (class `is-final`). |
| IMET Testimonials | **Home** | Success stories row of navy quote cards and photos (class `is-stories`), or client testimonial cards that glide (class `is-clients`). |
| IMET FAQ | **Home** | Every question shown as grey rounded rows, no "See More". |
| IMET Section (blank) | **Home** | 30px above and below, for the map under "Affiliated Information Centers". |

Also available: the button class `btn-icon-download` (download icon after the text), and an optional button row after
the photo in **IMET Program Overview**.

## Patterns

| Pattern | Kind | Where |
|---|---|---|
| DBA Category Page | Full page | "Choose a pattern" on new pages, + → Patterns → IMET, **Plugins → Create: …** |
| DBA Course Page | Full page | "Choose a pattern" on new pages, + → Patterns → IMET, **Plugins → Create: DBA Course Page** |

**DBA Category Page order:** Hero → Highlights → Section Navigation → Specializations → Cost Banner → Program Overview →
Achievements → Career Path Table → USP Bento → Recognition Logos → Comparison Table → Who Can Join →
Image + Bullet List → Alumni Videos → CTA Banner with Avatars → Testimonials → Certificate → FAQ → Call to Action.

**DBA Course Page order:** Hero (Dark) → Section Navigation → Highlights (Wide) → Cost Banner → Who Can Join (requirements) →
Specializations (Five per row) → Program Overview (Photo, benefits, with a button) → Tabs → CTA Banner with Avatars →
Testimonials (Compact) → About Card → Who Can Join (Boxed, curriculum) → Process Timeline → Step Cards → Recognition Logos (Logos,
alumni companies) → FAQ (Compact) → Call to Action (Compact).

**Course page title size:** on a page whose hero uses the **Dark** style, section titles are 38px (the course design)
instead of 35px; Testimonials and FAQ keep the standard size, and Specializations **Five per row** uses 48px.

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

**Home Page order:** Hero (Home) → Highlights (Home, with the enquiry form) → Cost Banner (Home) → Recognition Logos (Home, accreditation) →
Tabs (Home) → Recognition Logos (Home, embassies) → Specializations (Home) → Step Cards (Home, why choose) → Alumni Videos (Home) →
Recognition Logos (Home, employers) → Certificate (Home) → Step Cards (Home, case studies) → Call to Action (Home, publish) →
Testimonials (Home, success stories) → Section (Home, map) → Testimonials (Home, clients) → FAQ (Home) → Call to Action (Home, closing).
