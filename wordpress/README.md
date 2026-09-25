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

1. **Your plan.** Choose Monthly membership, Day/week/session pass, or GP referral (free).
   Plans show as price cards. Choosing a veteran, blue light or Universal Credit plan
   shows a "bring proof" note.
2. **Your details.** Name, date of birth, email, mobile and emergency contact.
   Parent/guardian fields appear only when the membership is for a child.
3. **Confirm.** Health question (details only if they answer "Yes"), optional interests,
   the total, the declaration and an email opt-in.

Emails: "New sign-up (staff)" goes to the site admin email, and "Welcome email (member)"
goes to the person signing up. Entries are under Forms → Entries.

The theme adds the card prices, UK price format (£19.99) and the dark styling (`functions.php`, `style.css`).
Colours are set in the shortcode's `styles` attribute on the Join page.

### Go-live with Stripe (once the client has an account)

The Stripe Add-On is installed and active. The currency is GBP.

1. Forms → Settings → Stripe → **Connect with Stripe** (Live), and log in with the client's account.
2. Edit the form. On step 3, add a **Stripe** field above the Declaration.
   Give it conditional logic: show if "How would you like to join?" **is not** "GP referral (free, ages 16–25)".
3. Form Settings → Stripe → add two feeds:
   - **Monthly memberships:** Transaction type *Subscription*, Recurring amount *Form total*,
     Billing cycle *1 month*. Condition: "How would you like to join?" is "Monthly membership".
   - **Passes:** Transaction type *Products and Services*, Payment amount *Form total*.
     Condition: "How would you like to join?" is "Day, week or session pass".
   - In both feeds, map Email → Email and Name → Member’s name.
4. Change the confirmation text from "we’ll be in touch before we open" to a payment-received message.
5. Make one live payment with a real card, then refund it in Stripe.
