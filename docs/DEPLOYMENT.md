# Deployment

How to release a new version and put it on the dev and live sites.

- [Environments](#environments)
- [Versioning](#versioning)
- [Release steps](#release-steps)
- [Install or update on a site](#install-or-update-on-a-site)
- [First go-live of a page](#first-go-live-of-a-page)
- [After deploying](#after-deploying)
- [Rollback](#rollback)
- [Migrating old pages](#migrating-old-pages)

---

## Environments

| Site | URL | Theme / editor | Cache |
|---|---|---|---|
| Local | `http://imetworld.local` | Twenty Twenty-Five (block theme) | none |
| Dev | `dev.imetworldwide.com` | Glidex + Elementor, Classic Editor | NitroPack (disconnected) |
| Live | `imetworldwide.com` | Glidex + Elementor, Classic Editor | NitroPack |

Always release to **dev first**. The local site uses a different theme, so dev is where the classic-theme behaviour
gets checked.

## Versioning

The version appears in three places and must match:

| File | Field |
|---|---|
| `imet-blocks.php` | ` * Version:` in the header and `define( 'IMET_BLOCKS_VERSION', '…' )` |
| `package.json` | `"version"` |
| `readme.txt` | `Stable tag:` |

Bump it for every release, using `major.minor.patch`:
- **patch:** fixes and style tweaks
- **minor:** new sections or behaviour
- **major:** breaking changes

A version change also re-runs the image import in wp-admin, so new images reach existing sites.

## Release steps

1. Work on a branch (`feature/…` or `fix/…`), never on `main`.
2. Bump the version (all three places) and add a [CHANGELOG](../CHANGELOG.md) entry.
3. Run `npm run build` and commit `src/` **and** `build/` together.
4. Push the branch and open a pull request into `main`. Merge it after review.
5. On an up-to-date `main`, build the zip:

   ```bash
   git switch main && git pull
   npm ci && npm run build
   npm run plugin-zip        # → imet-blocks.zip (git-ignored)
   ```

   The zip contains only what's needed at runtime: `imet-blocks.php`, `readme.txt`, `includes/`, `templates/`,
   `patterns/`, `build/` and `assets/`. It doesn't include `src/` or `node_modules/`.

## Install or update on a site

1. **Back up** the site (files and database) from the host or a backup plugin.
2. Go to **Plugins → Add New → Upload Plugin**, choose `imet-blocks.zip`, then **Install Now**.
   - **First install:** click **Activate**. The page images are imported into the Media Library.
   - **Update:** choose **Replace current with uploaded**. Images added in the new version are imported the next
     time an admin opens wp-admin.
3. Purge caches (see [After deploying](#after-deploying)).

**Requirements:** WordPress 6.5+ (6.8+ recommended) and PHP 7.4+.

## First go-live of a page

To replace an existing Elementor page without losing its URL or SEO:

1. On **dev**, create the new page (**Plugins → IMET Blocks → Create: …**) and finish it using the
   [Editor guide](EDITOR-GUIDE.md#publishing-checklist) checklist.
2. On **live**, after installing the plugin, create the same page as a **draft**.
3. Open the current Elementor page and note its **slug** and its **Rank Math** title, description and focus
   keyword.
4. Change the old page's slug to `…-old` and set it to **Draft**. Keep it; it's your rollback.
5. On the new page, set the **original slug**, paste the Rank Math fields, and **Publish**.
6. Run through [After deploying](#after-deploying).

## After deploying

- [ ] **NitroPack → Purge cache** (and Cloudflare, if used)
- [ ] If NitroPack's CSS/JS optimisation breaks the layout, add the page URL to NitroPack's excluded URLs. The plugin
      already combines CSS and delays analytics on IMET pages.
- [ ] Open the page **logged out** (private window) on desktop and a phone
- [ ] Submit the enquiry form once and confirm the email arrives
- [ ] Click the menu bar links, accordions, See More, videos and program cards
- [ ] [Rich Results Test](https://search.google.com/test/rich-results) shows the **FAQ**
- [ ] [PageSpeed Insights](https://pagespeed.web.dev) mobile score is acceptable

## Rollback

| Situation | Action |
|---|---|
| The new page has a problem | Set it to **Draft**, give the old Elementor page its original slug back and **Publish** it. |
| A plugin update broke something | Upload the previous version's zip with **Replace current with uploaded** (rebuild it from the matching git tag or commit), then purge caches. |
| Everything is wrong | Restore the backup taken before the release. |

Deactivating **IMET Blocks** doesn't delete content, but IMET pages lose their design until it's reactivated.

## Migrating old pages

Pages built with the earlier **IMET Generic Landing** plugin can be converted to IMET blocks with WP-CLI:

```bash
wp imet migrate <page-id> --dry-run   # show what would change
wp imet migrate <page-id>             # convert; the previous version is kept as a revision
```

Each section Group becomes the matching IMET block around the same content, and the page switches to the
**IMET Landing** template. When no page uses **IMET Generic** any more, deactivate and delete that old plugin.
