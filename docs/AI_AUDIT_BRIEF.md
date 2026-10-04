# PlayCharge — Independent Expert Audit Brief

## Goal
Audit this repository as a production-oriented digital gaming top-up storefront preparing for Azerbaijani bank merchant/acquiring review.

Public brand: **PlayCharge**
Public domain: **playcharge.online**
Legal seller: **"NEXORA DİGİTAL STORE" MMC**
Primary currency: **AZN**
Languages: **AZ / EN / RU**

## Non-negotiable truthfulness rules
- Do not assume bank approval exists.
- Do not assume supplier/resale authorization exists.
- Do not mark `bank_approved=true` or `resale_verified=true` without evidence.
- Current non-sellable products may be publicly visible for catalogue/bank review.
- The bank-review checkout is deliberately non-transactional: it must not create orders, charge cards, or pretend a payment integration is live.
- Do not claim publisher partnership, endorsement, or official reseller status unless documentary evidence exists.

## What to audit

### 1. Security / Laravel
Review routes, controllers, middleware, model authorization boundaries, CSRF, IDOR, session handling, order tracking, login/register/password reset, Google OAuth, rate limits, mass assignment, validation, CSP/security headers, proxy/HTTPS assumptions, logging/redaction, idempotency, money handling and payment/webhook safety.

Report every issue with:
- severity: Critical / High / Medium / Low
- exact file + code location
- exploit/failure scenario
- recommended fix

### 2. Merchant / bank review readiness
Review whether a bank reviewer can understand:
- who the legal seller is
- what is being sold
- pricing/status
- customer support channels
- Terms / Privacy / Refund / Delivery / Security policies
- the full customer journey
- which features are live vs review-only

Flag misleading claims, missing disclosures and dead ends.

### 3. E-commerce UX / visual consistency
Audit desktop and mobile flows:
- homepage
- catalogue
- product detail
- bank-review checkout preview
- login/register
- account/orders
- order tracking
- help/contact
- legal pages

Look for inconsistent branding, duplicate components, placeholder imagery/text, poor hierarchy, accessibility issues, weak conversion patterns and layout breakpoints.

### 4. Localization
Check AZ/EN/RU for:
- hardcoded English text
- mixed languages
- incorrect terminology
- missing translations
- legal/checkout wording inconsistencies

### 5. Intellectual property / product presentation
The storefront references PUBG MOBILE, Free Fire and Mobile Legends: Bang Bang. Audit whether the current logo/brand presentation could imply official affiliation or create trademark/licensing risk. A disclaimer is present, but do not assume it is sufficient.

### 6. Code quality
Check for duplicated presentation logic, brittle product-name matching, hidden environment assumptions, dead code, missing tests and deployment-specific risks.

## Important current behavior
- `Product::isSellable()` is the real sales gate.
- Non-sellable products remain visible when `catalog_visible=true`.
- Live checkout must still reject non-sellable products server-side.
- `/review/checkout/{product}` is a display-only bank-review route when live payments are disabled.
- Hidden catalogue products must not be accessible through the review route.
- A feature test exists for review checkout safety.

## Output format requested
Return:
1. Executive verdict: GO / CONDITIONAL GO / NO-GO for bank submission.
2. Top 10 findings sorted by severity.
3. Security findings.
4. Bank/merchant findings.
5. UX/mobile findings.
6. Localization findings.
7. Legal/IP risks.
8. Exact remediation plan, smallest safe changes first.
9. Anything that should block ABB submission.

Be adversarial. Do not praise the implementation unless a claim is supported by inspected code.
