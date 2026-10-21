# Editor guide

How to build and edit IMET landing pages in WordPress. No code needed.

- [Before you start](#before-you-start)
- [Create a page](#create-a-page)
- [Editing basics](#editing-basics)
- [Buttons](#buttons)
- [Section by section](#section-by-section)
- [The section menu bar and anchors](#the-section-menu-bar-and-anchors)
- [Adding new sections](#adding-new-sections)
- [Publishing checklist](#publishing-checklist)
- [Troubleshooting](#troubleshooting)

---

## Before you start

- The **IMET Blocks** plugin must be active (**Plugins** screen).
- IMET pages use the **IMET Landing** template. It gives the page its full-width layout and speed optimisations.
  Pages created with the steps below get it automatically.
- IMET pages always open in the **block editor**, even though the rest of the site uses the Classic Editor or
  Elementor.

## Create a page

**The easiest way:** go to **Plugins**, find **IMET Blocks**, and click
**Create: Online Doctorate of Business Administration (DBA)**. A draft page with the complete design opens in the
editor.

**Or, from a blank page:**
1. **Pages → Add New**, give it a title.
2. In the right sidebar, open **Page Attributes → Template → IMET Landing** and click **Save draft**.
3. Reload the page so it opens in the block editor.
4. Click **+** (top left) → **Patterns** → **IMET** → choose the DBA page or a single section.

> A page that contains IMET blocks automatically gets the IMET Landing template when you save it, if no other
> template was chosen.

## Editing basics

| To… | Do this |
|---|---|
| Change text | Click it and type. |
| Change a link | Select the text or button → click the link icon in the toolbar → paste the URL. |
| Replace an image | Click the image → **Replace** in the toolbar → pick from the Media Library or upload. |
| Add an item (card, question, row…) | Select an existing one → **⋮** (toolbar) → **Duplicate**, then edit the copy. |
| Remove something | Select it → **⋮** → **Delete**. |
| Move a section | Open **List View** (top toolbar, ☰ icon) and drag the section up or down. |
| Add a section | Click **+** → search "IMET" or open the **IMET** category. |

**Gold words in headings:** text you make **bold** inside a section title turns gold (for example "DBA v/s Phd").

**Don't change** the **Additional CSS class** of a section or its inner groups unless this guide tells you to.
The design depends on those classes.

## Buttons

Select any button and use the **IMET button** panel in the right sidebar:

| Setting | Options |
|---|---|
| **Colour** | Navy (primary) · White (on dark backgrounds) · Gold · Outline · Outline light (on dark backgrounds) · Light grey · Text link (underlined) |
| **Size** | **S** 48px · **M** 60px (default) · **L** 72px (a little smaller on phones) |

Every button has the same font, corners and hover, and a soft "glass" shine that sweeps across it now and then.

## Section by section

The DBA page, top to bottom.

### Hero

- **Title, intro text, stats:** click and edit. The stats are three groups of a number + a label.
- **Enquiry form:** the "Talk to an Advisor" card contains a **Shortcode** block with `[imet_advisor_form]`. Replace
  it with your form plugin's shortcode, for example `[contact-form-7 id="123"]`. Until you do, only logged-in
  editors see a placeholder message; visitors see nothing.
- **Accreditation logos:** five images. On phones they scroll sideways automatically.

### Program Highlights (Overview)

An icon list with a button and an image. Each list item starts with a **bold label** (shown in gold). The four
icons follow the item order: network, thesis, research, career.

### Section menu bar

See [The section menu bar and anchors](#the-section-menu-bar-and-anchors).

### Our Programs (Specializations)

Six program cards that glide sideways continuously (they pause when a visitor hovers). Each card is a paragraph
with a link:
- **Text:** click and edit.
- **Link:** select the text → link icon → set the program page URL.
- **Add a card:** duplicate one.

### Program Fee

The fee, the "Up to 45%" ribbon and the "Check Your Eligibility" button. Edit the text and the button link.

### What is a DBA? (Overview)

The header, then six benefit cards beside a photo. On the live page the cards work as **tabs**: the first one is
open, and clicking another opens its description. In the editor all descriptions are visible so you can edit them.
Keep each description to about one sentence so the cards stay the same height.

### Our Achievement

Four counters. Type the number as you want it shown (for example `2,100+` or `90%`). It counts up when the
visitor scrolls to it.

### Our USP's table

Five columns, each a **heading** plus **five entries**. The live page shows the first row with **See More**, which
opens the rest and turns into **Close**. Keep exactly five entries in every column so the rows line up.

### Doctorate for Visionaries (USP cards)

Six cards in a grid (Flexibility, Industry-Focused Doctorate, Alumni Network, Global Accreditation, Doctorate with
ROI, Executive Mentorship). Edit the text; replace photos with **Replace**.

### Embassy recognition

Six emblems with captions. Replace images and edit captions in place.

### DBA v/s PhD

A comparison **table** block. Click a cell to edit it. Rows are criteria; the DBA column is highlighted
automatically.

### Who Can Join

An accordion of **Details** blocks beside a photo. Click a question to edit it; the answer is the paragraph inside.
Only one item is open at a time on the live page, and the first starts open.

### Industry Analytics

An image, a heading and a bullet list.

### What Our Clients Say (videos)

A main video, three video thumbnails and **View More**.
- **Set a video:** click its image → link icon → paste the YouTube URL (`youtube.com/watch?v=…`, `youtu.be/…` and
  Shorts links all work). The picture is then replaced by YouTube's own thumbnail on the live page.
- **Caption:** the text under each image is its caption. Click to edit.
- On the live page, clicking the main video plays it in place; clicking a thumbnail plays it in the main player.
- **View More:** set its link to the YouTube channel.

### Cybersecurity banner

A blue card with a heading, two buttons and four round photos.

### Testimonials

Three cards: a quote and a LinkedIn logo. The logo is a link. Select it and set the person's LinkedIn profile URL.
Duplicate a card to add one (three per row; they swipe on phones). Set **View More** to the full testimonials page.

### Certification

A photo, a heading, a tick list and a button.

### FAQ

Each question is a **Details** block (question = summary, answer = paragraph inside). The live page shows the first
five with **See More** for the rest. Google's FAQ structured data is generated automatically from these questions.
To add a question, duplicate one.

### Call to action

The final navy card with a photo. Edit the heading (bold words are the larger part), text and button.

### Floating contact (WhatsApp)

The "Contact us" link fixed at the bottom left. Set its link to `https://wa.me/<your number>`, with the country code
and no "+" or spaces (for example `https://wa.me/971501234567`). On phones it shows only the green circle.

## The section menu bar and anchors

The navy bar links to sections on the same page and sticks to the top while scrolling.

- Each link points to an **anchor** such as `#faq`.
- A section's anchor is set in the section block → sidebar → **Advanced → HTML anchor**.
- The default anchors are:

  | Link | Anchor | Section |
  |---|---|---|
  | Program Overview | `#overview` | Program Highlights |
  | Our Programs | `#programs` | What is a DBA? |
  | Digital Presence | `#presence` | Our Achievement |
  | Specializations | `#specializations` | Our Programs |
  | Who can Join | `#who-can-join` | Who Can Join |
  | DBA v/s Ph.D | `#dba-vs-phd` | DBA v/s PhD |
  | FAQ | `#faq` | FAQ |

If you rename an anchor, update the matching link in the menu bar.

## Adding new sections

- **+ → Patterns → IMET** has ready-made sections: *Heading, text and button*, *Button row*, *CTA banner (navy)* and
  *CTA card (soft)*.
- **IMET Section (blank)** gives you an empty section with the IMET fonts and spacing. Build anything inside it.
- **Helper classes:** add these under **Advanced → Additional CSS class** on any block inside a section.

| Class | Effect |
|---|---|
| `u-center`, `u-left`, `u-right` | Text alignment |
| `u-mt-0` … `u-mt-64`, `u-mb-0` … `u-mb-64` | Space above / below (0, 8, 16, 24, 32, 48, 64px) |
| `u-bg-navy`, `u-bg-soft`, `u-bg-card` | Background colours |
| `u-card` | White rounded card with a shadow |
| `u-hide-mobile`, `u-hide-desktop` | Hide on phones / on larger screens |
| `eyebrow-line` (on a paragraph) | Small gold heading with a gold line under it |

## Publishing checklist

- [ ] Form shortcode added in the hero
- [ ] Every button and card link set (no `#` placeholders)
- [ ] Real YouTube links on the videos; WhatsApp number set
- [ ] SEO title and description set (Rank Math)
- [ ] **Preview** checked on desktop and on a phone
- [ ] Template is **IMET Landing** (sidebar → Template)
- [ ] After publishing: purge the cache (NitroPack) and check the page logged out

## Troubleshooting

| Problem | Fix |
|---|---|
| The page opens in the classic editor | Set **Template → IMET Landing**, click **Update**, reload. |
| The design looks unstyled on the live page | Check that the template is **IMET Landing**, then purge the cache. |
| "This block contains unexpected or invalid content" | Click **Attempt recovery**. If that fails, delete the block and insert it again from the IMET category. |
| A video doesn't play in place | The image link must be a YouTube video URL, not the channel or a playlist. |
| A menu-bar link doesn't scroll anywhere | The target section's **HTML anchor** must match the link (without the `#`). |
| Changes don't show on the live site | Purge NitroPack (and Cloudflare, if used), then hard-refresh (Cmd/Ctrl + Shift + R). |
