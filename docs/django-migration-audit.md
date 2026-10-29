# Django → PHP comparison

Checked on 23 October 2026 against `D:\WebSites\Backend Webs\Travelista-Django`.
Laravel is now at the root of `D:\WebSites\Backend Webs\Travelista-php`.

**The PHP conversion is incomplete.** The public page templates and core community
flows exist, but the full current Django feature set and saved content were not
transferred. This comparison does not import or modify source records or uploads.
No public UI was changed during this audit.

## Pages and static files

All 15 public page templates have corresponding Blade templates. The verification
email also exists under the Laravel name `emails/verification-code.blade.php`.
The Django custom 404 and four custom admin templates are absent. PHP currently
uses Laravel's fallback errors and the Filament dashboard instead.

Of the 110 source static files, 93 are byte-identical, 14 differ, and 3 are missing:

- `admin/travelista-admin.css`
- `js/common.js`
- `img/user-img/guest-avatar.svg`

The differing files include the catalog, profile, chat, and shared scripts, plus
several CSS files. Some differences are expected asset-path corrections
(`img/` → `images/`), while others reflect newer Django features that have not
been ported. Copying the Django scripts unchanged would retain Django routes,
CSRF/session conventions, and references to APIs missing from PHP.

The exact filenames are recorded in [the JSON inventory](django-migration-audit.json).

## Database content and uploads

These are actual record counts, inspected without printing personal records:

| Content | Django SQLite | PHP MySQL `travelista` |
| --- | ---: | --- |
| Site user profiles | 7 | 0 |
| Django admin users | 1 | No account imported |
| Hotels | 10 | Table missing |
| Destinations | 1 | Table missing |
| Amenities | 1 | Table missing |
| Blog posts | 3 | 0 |
| Tags | 13 | 0 |
| Blog comments | 1 | 0 |
| Contact/testimonial records | 0 | 0 |
| Chat threads/messages | 0 | 0 |
| WhatsApp records/settings | 0 | Tables missing |
| Uploaded media files | 3 | 0 |

The generated MySQL tables are present, but they do not contain the original
Django content. A content import must map user identities, post authors,
comments, tags, timestamps, images, and catalog relationships. Existing password
formats also need explicit compatibility handling; copying password strings
into Laravel's default bcrypt guard will not preserve every login. Django admin
privileges should be mapped explicitly rather than inferred from email addresses.

## Feature comparison

| Feature | PHP status |
| --- | --- |
| Registration, verification, login, profile setup | Implemented with Laravel authentication |
| Blog publishing, tags, search, pagination | Implemented; pagination and recent-post limits differ |
| Comments | Implemented; Django's moderation/approval filtering is missing |
| Post views | PHP counts requests; Django deduplicates per session |
| Contact forms/testimonials | Stored and approved through `is_published`; moderation is missing |
| Private chat | Authenticated chat exists; guest identities and message deletion are missing |
| Newsletter subscriptions | PHP implementation exists |
| Hotels/destinations/amenities | Source models, relationships, and REST endpoints are missing |
| Session-bound guest ownership | Missing |
| Mandatory profile-completion middleware | Missing |
| Gemini/local word-list moderation | Missing; key retained only as configuration |
| WhatsApp webhook, queue processing, AI replies, delivery states, human handoff | Missing; source credentials are blank |
| Custom admin content management, support inbox, trash and cleanup commands | Missing; only basic Filament access exists |
| Google sign-in | Laravel Socialite implementation exists; credentials are now copied |

Missing endpoint families include `/api/destinations/`, `/api/hotels/`,
`/api/posts/`, `/api/user-messages/`, `/api/user-data/`,
`/api/messages/delete/<id>/`, `/webhooks/whatsapp/`, and the custom WhatsApp
admin inbox. Django's `/pages/...` URLs are not compatibility aliases in PHP;
Laravel currently uses its root-level routes.

## Environment transfer completed

31 settings were copied or translated into the private root `.env`. Values are
deliberately excluded from this report and from `.env.example`.

| Source | Laravel mapping/status |
| --- | --- |
| `EMAIL_HOST_USER` | `MAIL_USERNAME` |
| `EMAIL_HOST_PASSWORD` | `MAIL_PASSWORD` |
| Django's Gmail host, port, TLS settings | `MAIL_MAILER=smtp`, Gmail SMTP, port 587, required STARTTLS |
| `DEFAULT_FROM_EMAIL` | `MAIL_FROM_ADDRESS`; empty source falls back to SMTP username |
| `EMAIL_TIMEOUT` | `MAIL_TIMEOUT` |
| `GOOGLE_CLIENT_ID` | `GOOGLE_CLIENT_ID` |
| `GOOGLE_SECRET_KEY` | `GOOGLE_CLIENT_SECRET` |
| Google callback | Translated to `${APP_URL}/auth/google/callback` |
| `GEMINI_API_KEY` | Same name; available in `services.gemini.key`, feature code still missing |
| All `WHATSAPP_*` fields | Same names; available in `services.whatsapp`, feature code still missing |
| `DEBUG` | `APP_DEBUG` |
| Django timezone | `APP_TIMEZONE=UTC` |
| Session/CSRF secure cookie flags | Combined into Laravel's `SESSION_SECURE_COOKIE` |
| Host/origin allowlists, HTTPS/HSTS flags | `config/travelista.php` and deployment middleware |
| `USE_X_FORWARDED_PROTO` | Requires an explicit `TRUSTED_PROXIES` list; arbitrary proxy headers are not trusted |

Laravel's existing `APP_KEY`, `APP_URL`, and MySQL credentials were preserved.
`SECRET_KEY` and `DJANGO_SECRET_KEY` were not reused as Laravel keys: Django and
Laravel signing/encryption formats are incompatible. The source `.env` remains
untouched. The prior PHP settings are retained in the ignored `.env.backup`.

The source contains real SMTP, Google, and Gemini values. Its WhatsApp phone,
token, verification, and app-secret fields are empty, so copying them cannot
activate WhatsApp. No email, Gemini request, WhatsApp message, or Google sign-in
was sent/performed during this transfer. Credential validity is not established
by copying settings. The Laravel callback must be registered in the Google OAuth
client before a real sign-in can complete.

The `public/storage` junction was also repaired after the move from `core/`, so
future Laravel uploads resolve under the current root. This does not copy the
three existing Django media files.
