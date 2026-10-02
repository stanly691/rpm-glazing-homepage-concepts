# BGWC WordPress theme

`bgwc-theme/` is the Billy’s Gym & Wellness Centre holding page as a WordPress theme.
All of its text, links and images are ACF fields on the front page.
`bgwc-theme.zip` is the same folder zipped, ready to upload.

## Install

1. Plugins: make sure **Advanced Custom Fields** (or Secure Custom Fields) is active.
2. Appearance → Themes → Add New → Upload Theme → choose `bgwc-theme.zip` → Install → Activate.
3. Activating the theme creates a **Home** page and sets it as the front page.
4. Pages → Home → edit the **Homepage content** fields (Header, Hero, Buttons, Footer, SEO tabs).

Empty fields fall back to the original design copy and images, so the page looks right straight after activation.

## Join form (Gravity Forms)

Live at `/join/` (page "Join Billy’s"). The homepage button links there.
`gravity-forms/membership-signup.json` is the form export (Form #1). To rebuild it on
another site, go to Forms → Import/Export → Import Forms.

The form has 3 steps:

1. **Your plan.** Choose Monthly, Pay as you go, or GP referral (free), then a plan from a
   price list. Junior plans are for ages 11–15; adult plans are 16+. "Gym – concession"
   asks which concession (veteran, blue light, Universal Credit) and shows a "bring proof" note.
2. **Your details.** Name, date of birth, email, mobile and emergency contact.
   Parent/guardian fields appear only when the membership is for a child.
3. **Confirm.** Health question (details only if they answer "Yes"), optional interests,
   the total, the declaration and an email opt-in.

**Age rules** (checked in the browser by `assets/join.js` and again on the server by
`bgwc_age_problem()` in `functions.php`, using the date of birth):

| Plan | Ages |
|---|---|
| Junior gym / day / week pass | 11–15 |
| Children’s wellbeing gym (monthly or session) | under 16 |
| Adult gym / day / week pass, Gym – concession, Dual BJJ + gym | 16+ |
| GP referral | 16–25 |
| BJJ (monthly or session) | no limit |

**Who’s filling this in?** ("I’m joining" / "I’m a parent or guardian") decides the wording and,
together with the date of birth, which extra fields appear. The server works out "Member is under 18"
(hidden field 31) from the date of birth, so the browser can’t skip these:

| Who’s filling in | Member under 18 | Member 18+ |
|---|---|---|
| I’m joining (13+) | Parent/guardian name, mobile, email + "My parent or guardian knows I’m joining" tick | nothing extra |
| Parent or guardian | "Your name" (their email/mobile are the contact fields) | "Your name" |

Under-13s can’t sign themselves up (UK GDPR parental consent): the message offers a one-tap switch
to "I’m a parent or guardian" and keeps everything typed. The welcome email is copied to the
parent/guardian email (field 32) and greets the parent when they filled it in.

Emails: "New sign-up (staff)" goes to the site admin email, and "Welcome email (member)"
goes to the person signing up. Entries are under Forms → Entries.

The theme adds the plan prices, UK price format (£19.99), the "Your plan" summary on steps 2–3
(`assets/join.js`) and the styling (`functions.php`, `style.css`).
Colours are set in the shortcode's `styles` attribute on the Join page.

### Stripe payments

Stripe is connected (test and live) as "Billys Gym and Wellness Centre CIC". The site is in **Test** mode.

- **Card field** (field 34) on step 3, hidden for GP referrals. Card details go straight to Stripe.
- **Feeds** (Form Settings → Stripe):
  - *Monthly memberships*: subscription, amount = chosen plan, every 1 month, when join type is "Monthly".
    Charged from the day they sign up.
  - *Pay as you go*: one-off payment, amount = chosen pass, Stripe receipt to Email, when join type is "Pay as you go".
- The button reads "Pay £25.99 and join →" / "Pay £5.00 →"; monthly plans show "Then £25.99 each month."
- Thank-you message and welcome email say the payment went through (`bgwc_join_copy()` in `functions.php`).
- `gform_submission_data_pre_process_payment` adds £0.000001 so £19.99 is sent to Stripe as 1999p
  (the add-on truncated 1998.999… to 1998p, so £19.99 plans were billed £19.98).

Tested in test mode: monthly, pass, parent paying for a child, concession, declined card, 3-D Secure, GP referral.

To go live: Forms → Settings → Stripe → Mode **Live** → Save, then make one real small payment and refund it.
Clear the test entries (Forms → Entries) and test-mode subscriptions first.
