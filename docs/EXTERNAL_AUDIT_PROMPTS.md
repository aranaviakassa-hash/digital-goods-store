# PlayCharge — External Expert Audit Prompts

Use these prompts with independent AI reviewers. Give each reviewer access to the GitHub repository and ask them to inspect the code, not only screenshots.

---

## Claude — Security / Architecture / Payment Safety

You are the principal application security engineer reviewing a Laravel digital-goods storefront before Azerbaijani bank merchant/acquiring review.

Repository: https://github.com/aranaviakassa-hash/digital-goods-store
Public site: https://playcharge.online

First read `docs/AI_AUDIT_BRIEF.md` and treat every rule there as mandatory.

Perform a code-level adversarial audit. Inspect routes, controllers, middleware, models, policies/authorization boundaries, validation, sessions, CSRF, order tracking authorization, OAuth, password reset, security headers, trusted proxies, logging/redaction, money representation, payment attempts/webhooks, idempotency, fulfillment, refund logic, rate limits, and review-only checkout behavior.

Do not infer safety from test names. Verify implementation. Do not assume ABB approval, supplier authorization, or live payment integration.

Return only:
1. Executive verdict: GO / CONDITIONAL GO / NO-GO for ABB submission.
2. Critical/High/Medium/Low findings with exact file paths and relevant code locations.
3. Exploit or failure scenario for each issue.
4. Smallest safe remediation for each issue.
5. Missing tests that should be added.
6. Final list of issues that MUST block ABB submission.

Be adversarial. Avoid generic best-practice filler.

---

## Gemini — Merchant Review / UX / Mobile / Localization

Act as a senior fintech merchant-onboarding reviewer and e-commerce UX lead reviewing a gaming top-up storefront before Azerbaijani acquiring-bank review.

Repository: https://github.com/aranaviakassa-hash/digital-goods-store
Public site: https://playcharge.online

First read `docs/AI_AUDIT_BRIEF.md`.

Review the live website at desktop and mobile widths and inspect the repository implementation behind it. Audit:
- legal seller identity and business transparency
- clear distinction between live vs bank-review-only behavior
- product catalogue and product-detail clarity
- review checkout preview
- pricing/status wording
- support/contact credibility
- Terms / Privacy / Refund / Delivery / Security discoverability
- homepage conversion hierarchy
- catalogue consistency
- login/register/account/order tracking/help/contact flows
- AZ / EN / RU consistency and hardcoded language leakage
- accessibility, contrast, spacing, responsiveness, dead ends and misleading CTAs

Do not reward visual polish if the bank reviewer journey is incomplete or misleading.

Return only:
1. Executive verdict: GO / CONDITIONAL GO / NO-GO.
2. Top 10 issues sorted by severity/business impact.
3. Exact pages/components/files affected.
4. Mobile-specific failures.
5. Localization failures.
6. Bank-review credibility risks.
7. Smallest remediation plan in priority order.
8. Explicit ABB submission blockers.

---

## Grok — Red-Team Merchant / Legal-IP / Trust Audit

Act as an adversarial merchant-risk, consumer-trust and IP/trademark reviewer. Your job is to find reasons an acquiring bank, publisher, customer, or compliance reviewer might reject or distrust this storefront.

Repository: https://github.com/aranaviakassa-hash/digital-goods-store
Public site: https://playcharge.online

First read `docs/AI_AUDIT_BRIEF.md`.

Focus on:
- claims that could imply bank approval, publisher partnership or authorized reseller status
- use of PUBG MOBILE, Free Fire and Mobile Legends branding/logos
- whether disclaimers are sufficient or potentially misleading
- merchant identity and seller disclosure
- consumer refund/delivery wording
- support credibility
- product availability/status claims
- fake-looking or unfinished review-mode UX
- legal-page contradictions
- anything that could create chargeback, merchant-risk, or reputational concerns
- anything visible publicly that looks temporary, internal, technical, or unprofessional

Do not assume any license, supplier contract, reseller authorization or bank acceptance unless evidence exists in the repository.

Return only:
1. Executive verdict: GO / CONDITIONAL GO / NO-GO.
2. Top merchant-risk concerns.
3. Legal/IP/trademark concerns.
4. Misleading or risky wording with exact page/file locations.
5. Trust/conversion problems visible to a normal customer.
6. What must be removed, rewritten or documented before ABB submission.
7. Final ABB-blocker list.

---

## Merge rule

After collecting all three reports, compare only findings supported by concrete code/page evidence. Deduplicate overlapping findings, reject speculative claims that cannot be reproduced, and prioritize fixes in this order:

1. Critical security / authorization / payment integrity
2. Bank-submission blockers
3. Misleading legal or merchant claims
4. Trademark / supplier authorization risk
5. Broken customer journey
6. Localization / mobile / accessibility
7. Cosmetic polish
