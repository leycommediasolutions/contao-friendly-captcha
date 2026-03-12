# contao-friendly-captcha

Friendly Captcha Form Widget für Contao 5.6.

## Installation

1. Paket einbinden (z. B. via Path-Repository oder Git-Repository).
2. `composer require leycommediasolutions/contao-friendly-captcha:dev-main`
3. Secret setzen (z. B. in `.env.local`):

```dotenv
FRIENDLY_CAPTCHA_SECRET=dein_secret
```

## Verwendung

1. In **Formulargenerator → Formularfeld hinzufügen** den Typ **Friendly Captcha** auswählen.
2. Feld `Sitekey` setzen.
3. Optional Theme, Sprache oder Puzzle-Endpunkt setzen.
