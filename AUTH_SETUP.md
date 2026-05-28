# SkillSwap — Authentication & Onboarding Setup Guide

This document covers the authentication refinement implemented for SkillSwap, including Google OAuth, email verification, 2FA, session management, and onboarding flows.

---

## Table of Contents

1. [Google OAuth Setup](#1-google-oauth-setup)
2. [Email Verification](#2-email-verification)
3. [Two-Factor Authentication](#3-two-factor-authentication)
4. [Environment Variables](#4-environment-variables)
5. [Migration](#5-migration)
6. [Architecture Overview](#6-architecture-overview)
7. [Testing](#7-testing)
8. [Troubleshooting](#8-troubleshooting)

---

## 1. Google OAuth Setup

### Prerequisites

- A Google Cloud Platform account
- A Google Cloud project

### Steps

1. **Go to** [Google Cloud Console — Credentials](https://console.cloud.google.com/apis/credentials)
2. **Create a new OAuth 2.0 Client ID:**
   - Application type: **Web application**
   - Name: `SkillSwap` (or any name)
   - Authorized redirect URIs: Add `{YOUR_APP_URL}/auth/google/callback`
     - Local: `http://localhost:8000/auth/google/callback`
     - Production: `https://yourdomain.com/auth/google/callback`
3. **Copy the Client ID and Client Secret** into your `.env` file (see section 4)
4. **Enable the Google+ API** (or People API) in your Google Cloud project if not already enabled

### How It Works

- **New users** signing in with Google are automatically created as `learner` accounts with a verified email
- **Existing users** (matched by email) get their Google ID linked — their existing role is preserved
- **Returning Google users** are logged in directly
- OAuth errors redirect to the login page with a user-friendly message

### Routes

| Route | Method | Description |
|-------|--------|-------------|
| `/auth/google/redirect` | GET | Redirects to Google consent screen |
| `/auth/google/callback` | GET | Handles Google's OAuth callback |

---

## 2. Email Verification

Email verification is now **enabled** via Laravel Fortify. After registration:

1. Users are redirected to the verification notice page
2. A verification link is emailed to them
3. Until verified, users **can still access their dashboard** but cannot:
   - Book sessions
   - Cancel bookings
   - Leave reviews
4. Dashboards show a gentle verification reminder banner

### Mail Configuration

The mail driver is configured in `.env`. For local development, use `log` (emails appear in `storage/logs/laravel.log`):

```env
MAIL_MAILER=log
```

For production, configure a real mail driver:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@skillswap.com"
MAIL_FROM_NAME="SkillSwap"
```

---

## 3. Two-Factor Authentication

2FA is **optional but encouraged**. The profile settings page includes:

- An encouragement banner recommending 2FA
- QR code setup with styled cards
- Recovery codes in a grid layout with copy instructions
- Clear enable/disable flow via `x-confirms-password`

2FA uses TOTP (Time-based One-Time Password) compatible with:
- Google Authenticator
- Authy
- 1Password
- Any TOTP-compatible app

---

## 4. Environment Variables

Add these to your `.env` file:

```env
# Google OAuth (required for "Continue with Google")
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

---

## 5. Migration

After pulling the changes, run:

```bash
php artisan migrate
```

This adds a `google_id` column (nullable, unique) to the `users` table.

---

## 6. Architecture Overview

### Modified Files

| File | Changes |
|------|---------|
| `app/Models/User.php` | Added `MustVerifyEmail`, `google_id` to fillable |
| `config/fortify.php` | Enabled `emailVerification()` |
| `config/services.php` | Added Google OAuth config |
| `routes/web.php` | Added OAuth routes, `verified` middleware on sensitive actions |
| `bootstrap/app.php` | Added `verified` middleware alias |
| `app/Http/Middleware/RoleMiddleware.php` | Redirects to own dashboard instead of 403 |
| `app/Http/Responses/RegisterResponse.php` | Redirects to verification notice |
| `app/Http/Controllers/LearnerDashboardController.php` | Passes onboarding data |
| `app/Http/Controllers/TutorDashboardController.php` | Passes profile completion data |

### New Files

| File | Purpose |
|------|---------|
| `app/Http/Controllers/SocialAuthController.php` | Google OAuth controller |
| `database/migrations/xxxx_add_google_id_to_users_table.php` | Google ID column |
| `tests/Feature/GoogleOAuthTest.php` | OAuth tests |
| `tests/Feature/OnboardingRedirectTest.php` | Onboarding tests |

### View Changes

All auth pages (login, register, forgot-password, reset-password, confirm-password, verify-email, two-factor-challenge) share a consistent SkillSwap-branded design with:
- Outfit + Plus Jakarta Sans fonts
- Glassmorphism cards
- Grid pattern backgrounds
- Loading spinners
- Mobile responsive layouts

### Middleware Flow

```
Guest → Login/Register → Email Verification Notice → Dashboard
                                                        ↓
                                              Booking/Reviews (requires `verified`)
```

---

## 7. Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suites

```bash
# Google OAuth tests
php artisan test --filter=GoogleOAuthTest

# Onboarding tests
php artisan test --filter=OnboardingRedirectTest

# Registration tests (updated for email verification)
php artisan test --filter=RegistrationTest

# Email verification tests
php artisan test --filter=EmailVerificationTest
```

### Manual Testing Checklist

- [ ] Visit `/login` — Google button visible, form works
- [ ] Visit `/register` — Google button visible, role selector works
- [ ] Register new user — redirected to email verification page
- [ ] Check `storage/logs/laravel.log` for verification email (if using `log` driver)
- [ ] Visit `/forgot-password` — styled correctly, sends reset link
- [ ] Login as learner — onboarding banner shows for new users
- [ ] Login as tutor — profile completion checklist shows
- [ ] Try booking without verified email — redirected to verification notice
- [ ] Visit `/user/profile` — security sections organized, 2FA encouragement shows
- [ ] Enable 2FA — QR code, recovery codes display correctly
- [ ] Login with wrong role URL — redirected to correct dashboard with flash message

---

## 8. Troubleshooting

### "Continue with Google" doesn't work

- Ensure `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` are set in `.env`
- Ensure the redirect URI in Google Cloud Console matches exactly: `{APP_URL}/auth/google/callback`
- Clear config cache: `php artisan config:clear`

### Verification email not received

- Check mail driver in `.env` — use `log` for local testing
- Check `storage/logs/laravel.log` for the verification URL
- Ensure `APP_URL` is set correctly in `.env`

### 403 errors on routes

- The `RoleMiddleware` now redirects instead of showing 403
- If you still see 403, check that the user's `role` column matches the expected value
- Clear route cache: `php artisan route:clear`

### Migration issues

- If `google_id` column already exists, the migration will fail — check with `php artisan migrate:status`
- To rollback: `php artisan migrate:rollback --step=1`
