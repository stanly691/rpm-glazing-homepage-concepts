# RPM Glazing Systems: Manufacture Only page

Adam's request (6 Oct 2026):

> I would also like to add a page or some details about our manufacture only
> services for general builders or installation only companies to advertise this.

This folder adds that page to the existing `rpm-glazing` theme (v1.2.5) on
`rpmshopfronts.itcscloud.co.uk`. **All copy is ACF-driven.** The template holds
markup only, and the approved text is stored as each field's default value, so
the page opens pre-filled in wp-admin and stays fully editable.

Preview (static, built from the template's real output with the live theme CSS):
`manufacture-only/index.html`

## Files

| File | Goes in theme as | Purpose |
|---|---|---|
| `rpm-glazing/template-manufacture-only.php` | `wp-content/themes/rpm-glazing/template-manufacture-only.php` | Page template "Manufacture Only". Markup only; reads ACF fields. |
| `rpm-glazing/inc/acf-manufacture-only.php` | `wp-content/themes/rpm-glazing/inc/acf-manufacture-only.php` | Registers the "Manufacture Only page" ACF field group (free-ACF compatible, no repeaters). |

## Install (about 10 minutes)

1. Upload both files to the theme in the paths above (SFTP, or download/re-zip the theme).
2. In the theme's `functions.php` add:
   ```php
   require_once get_template_directory() . '/inc/acf-manufacture-only.php';
   ```
3. Compare the opening `<main ...>` line with the theme's `page.php` and the
   service template. The live pages output `<main id="main-content" class="rpm-shell">`
   directly after the header. If the theme's `header.php` already opens `<main>`,
   remove the `<main>` and `</main>` lines from the new template.
4. **Pages → Add New**
   - Title: `Manufacture Only`
   - Parent: `Services` (gives `/services/manufacture-only/`)
   - Template: `Manufacture Only`
   - The ACF tabs (Hero, Introduction, Who it is for, What we manufacture,
     How it works, Enquiry strip) appear pre-filled. Choose a **Hero image**
     (use media #50 `project-april-12-original.jpg` until a workshop photo
     arrives), then **Publish**. Publishing saves the defaults into the page.
5. **Appearance → Menus → Primary navigation**: add the page under *Services*,
   after *Rooflights & AOVs*.
6. Add the page wherever the theme lists services (all ACF/theme-option
   driven, so no code change is expected):
   - Footer *Services* column
   - *Services* overview page cards (`/services/`)
   - Homepage "Commercial glazing services" grid (optional: a 7th card breaks the
     3×2 grid, so a short line or banner under the grid may suit better)
   - Contact form *Required service* dropdown: add `Manufacture only (supply only)`
     so these leads can be identified.
7. Check `/services/manufacture-only/` on desktop and mobile.

## Page content (ACF defaults)

| Section | Content |
|---|---|
| Hero | **Manufacture-only service** / *Aluminium glazing manufactured for your installation team* / Supply-only fabrication of curtain walling, windows, doors and shopfronts from our Bridgend facility, for general builders, contractors and installation-only companies. |
| Intro | **Trade manufacturing** / *Our manufacturing, your installation*. Three short paragraphs: what the service is, how it works (drawings, schedules or sizes → review → manufacture to programme), and the area (South Wales and South West, further afield depending on the job). |
| Who it is for | General builders · Installation-only companies · Main contractors |
| What we manufacture | Curtain walling, Aluminium windows, Doors & entrances, Shopfronts, Glazed screens, Bespoke aluminium & glass |
| How it works | Send your enquiry → Review & quotation → Sizes signed off → Manufacture → Ready for your team |
| Enquiry strip | *Need glazing manufactured for your next project?* + Make an enquiry button, 01656 724704, info@rpmglazing.com |

The audience and area come from Adam's brief (4 Aug): general builders, main
contractors, installation-only companies; South Wales and South West, nationwide
depending on the job. Following Adam's 19 Aug request, the email address is shown next to the phone number.

## To confirm with Adam

The copy avoids claims that aren't in the brief or already on the site. These
points need Adam's confirmation before they're added:

1. **Product range.** Is everything in the list available supply-only? Rooflights &
   AOVs are deliberately left out (often bought in rather than fabricated).
2. **Delivery or collection.** Does RPM deliver to site, offer collection from Bridgend, or both?
3. **Glazing.** Supplied glazed, or frames only for the installer to glaze?
4. **Systems or brands** manufactured (e.g. Kawneer, Schüco, Senior, Smart). These are useful for SEO and for trade buyers.
5. **Survey service.** Will RPM survey and measure for trade customers, or only manufacture to supplied sizes?
6. **Photo.** A workshop or fabrication photo for the hero.

## Other gaps found while reviewing the site against the email thread

| # | Item | Status |
|---|---|---|
| 1 | Manufacture-only page (6 Oct request) | **Added here.** Needs the install steps above. |
| 2 | Email shown next to phone (19 Aug) | Done on site (header, mobile menu, contact). |
| 3 | Main domain `rpmglazing.com`; redirect `rpmshopfronts.com` to it (4 Aug) | **Launch task.** DNS plus a 301 redirect for every path. |
| 4 | Privacy statement (brief: client to provide) | **Missing.** The site has Cookie Policy and Terms but `/privacy-policy/` returns 404, and the enquiry form collects personal data. |
| 5 | Blog / social media widgets (in the brief questionnaire) | Not built, and Adam didn't confirm whether he wants them. Ask him. |
| 6 | "Sample Page" still published (`/sample-page/`) | Delete before launch. |
| 7 | Service pages and About repeat "Built around your project" as both eyebrow and heading | Change the heading field on each page in wp-admin. |
| 8 | Contact form sends to `info@rpmglazing.com` | Confirm the mailbox exists and is monitored (Adam's email is `@rpmshopfronts.com`). |
| 9 | Site is `noindex` and password-protected | Turn on search indexing and remove password protection at go-live. |
| 10 | Claims to verify: ISO 9001, CSCS, "Established 1970", "50+ years", Lister Hospital "recently secured" | Confirm with Adam (placeholder content was used for the preview). |

## Draft reply to Adam

> Subject: Re: New Website : RPM Shopfronts
>
> Hi Adam,
>
> Thanks, great to hear you're happy with the site.
>
> We've put together a new **Manufacture Only** page under Services, aimed at
> general builders, main contractors and installation-only companies. It covers
> who the service is for, what we manufacture, a simple five-step process from
> enquiry to finished frames, and a clear enquiry section with both phone and email.
> It will also be added to the Services menu, the footer and the contact form.
>
> To finish it off, could you confirm a few details?
>
> 1. Which products are available on a manufacture-only basis (curtain walling,
>    windows, doors, shopfronts, screens, bespoke)?
> 2. Do you deliver to site, offer collection from Bridgend, or both?
> 3. Are items supplied glazed, or as frames only?
> 4. Which aluminium systems do you fabricate (e.g. Kawneer, Schüco, Senior)?
> 5. Do you offer a survey service for trade customers, or work only to supplied sizes?
> 6. Do you have a photo of the workshop or fabrication in progress for the page banner?
>
> Ahead of launch we'll also need your Privacy Policy wording, and confirmation
> that `info@rpmglazing.com` is the right inbox for enquiries. We'll set
> www.rpmglazing.com as the main address and redirect rpmshopfronts.com to it.
>
> Kind regards,
> Stanly
