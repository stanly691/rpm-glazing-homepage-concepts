# RPM Glazing Systems: Manufacture Only page

Adam's request (6 Oct 2026):

> I would also like to add a page or some details about our manufacture only
> services for general builders or installation only companies to advertise this.

**Status: live on staging** at
http://rpmshopfronts.itcscloud.co.uk/services/manufacture-only/ (page ID 96).

Static copy of the live page: `manufacture-only/index.html`

## What changed on the site

The theme was edited through Appearance → Theme File Editor. `rpm-glazing/`
in this folder mirrors the theme's PHP files. Commit `f2f817e` is the untouched
1.2.5 snapshot, and the next commit holds the edits, so `git diff` shows every line changed.

| File | Change |
|---|---|
| `functions.php` | New ACF group **Additional Page Sections** on all pages except the front page: a *Detail List* (kicker, heading, text, items) and *Steps* (kicker, heading, intro, steps). A one-time setup routine, `rpm_seed_manufacture_only()` (option flag `rpm_manufacture_only_release`), creates and fills the page following the theme's existing seed pattern. It never overwrites content an editor has already set. |
| `page.php` | Renders the two optional sections between the highlight cards and the enquiry strip, only when they have items. Other pages are unchanged (rendered output checked). |

All text is ACF-driven, with nothing hardcoded in templates. Edit it under
**Pages → Manufacture Only**:

- **Page Content** group (existing theme fields): hero, intro, three "who it's for" cards, enquiry strip.
- **Additional Page Sections** group (new): "What we manufacture" list and "How it works" steps.

The same sections can now be used on any other page by filling them in.

## Where the page appears

| Location | How |
|---|---|
| Services dropdown in the main menu | Menu item added under Services (Appearance → Menus). |
| Services overview page | Seventh card added to its *Highlights* repeater. |
| Contact form "Required service" dropdown | Automatic: the form lists all Services child pages. |
| Footer | Not shown. The theme's footer lists only the first four services by design. |
| Homepage services grid | Not added. A seventh card would break the 3×2 grid. Add it under Pages → Home → Services if wanted. |

## Rollback

Paste the snapshot versions of `functions.php` and `page.php` (commit `f2f817e`)
back into the Theme File Editor. Then delete the page, its menu item and the
seventh Services card.

## To confirm with Adam

The copy avoids claims not in the brief or already on the site. These points need
his confirmation:

1. **Product range.** Is everything listed available supply-only? Rooflights & AOVs were left out.
2. **Delivery or collection.** Does RPM deliver to site, offer collection from Bridgend, or both?
3. **Glazing.** Supplied glazed, or frames only?
4. **Systems or brands** fabricated (e.g. Kawneer, Schüco, Senior, Smart).
5. **Survey service.** Will RPM survey and measure for trade customers, or only work to supplied sizes?
6. **Photo.** A workshop or fabrication photo to replace the current hero image.

## Other gaps found against the email thread

| # | Item | Status |
|---|---|---|
| 1 | Manufacture-only page (6 Oct) | **Done.** |
| 2 | Email shown next to phone (19 Aug) | Done (header, mobile menu, contact). |
| 3 | `rpmglazing.com` as main domain; 301 redirect from `rpmshopfronts.com` | Launch task. |
| 4 | Privacy policy | **Missing.** `/privacy-policy/` returns 404. Once a page is set under Settings → Privacy, the footer shows the link automatically. Wording to come from the client. |
| 5 | Blog / social widgets (brief questionnaire) | Not built and not confirmed by Adam. Ask him. |
| 6 | Default "Sample Page" still published | Delete before launch. |
| 7 | Service and About pages show "Built around your project" as both kicker and heading | Change *Main Section Heading* per page. |
| 8 | Enquiries go to `info@rpmglazing.com` | Confirm the mailbox exists and is monitored. |
| 9 | Site is `noindex` and password-protected | Switch off at go-live. |
| 10 | ISO 9001, CSCS, "Established 1970", "50+ years", Lister Hospital "recently secured" | Confirm with Adam. |

## Draft reply to Adam

> Subject: Re: New Website : RPM Shopfronts
>
> Hi Adam,
>
> Thanks, great to hear you're happy with the site.
>
> We've added a new **Manufacture Only** page under Services, aimed at general
> builders, main contractors and installation-only companies:
> http://rpmshopfronts.itcscloud.co.uk/services/manufacture-only/
>
> It covers who the service is for, what we manufacture, a simple five-step
> process from enquiry to finished frames, and an enquiry section. It's in the
> Services menu and on the Services page, and "Manufacture Only" is now an option
> on the enquiry form, so these leads are easy to spot.
>
> To finish it off, could you confirm:
>
> 1. Which products are available on a manufacture-only basis?
> 2. Do you deliver to site, offer collection from Bridgend, or both?
> 3. Are items supplied glazed, or as frames only?
> 4. Which aluminium systems do you fabricate (e.g. Kawneer, Schüco, Senior)?
> 5. Do you offer a survey service for trade customers, or work only to supplied sizes?
> 6. Do you have a photo of the workshop or fabrication in progress for the page banner?
>
> Ahead of launch we'll also need your Privacy Policy wording, and confirmation
> that info@rpmglazing.com is the right inbox for enquiries.
>
> Kind regards,
> Stanly
