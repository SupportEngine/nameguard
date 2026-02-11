# NameGuard 1.1 for ExpressionEngine 7

Blocks suspicious mixed-case screen names and usernames during member registration. Reduces spam and bot accounts that use random-looking character strings like `bpnUPGwotiovkSgjkowAIa` or `myIbjcHzlhTylOThedq`.

## Features

- **Screen name validation** — Validates the screen name field during registration
- **Username validation** — Also validates username when it's not an email (e.g. when EE uses username as display name)
- **Smart detection** — Three heuristic checks:
  - Mixed-case spam (excessive alternation between upper/lower)
  - Gibberish (very few vowels in long strings)
  - Unreadable consonant clusters (4+ consonants in a row)
- **No false positives** — Normal names like "John Smith", "Alice", "Mary Jane" pass through
- **Zero configuration** — Works out of the box, no settings required

## Requirements

- ExpressionEngine 7.x

## Installation

1. Download or clone this repository
2. Copy the `nameguard` folder to `system/user/addons/`
3. In the ExpressionEngine Control Panel, go to **Add-ons** → **NameGuard** → **Install**

## How It Works

NameGuard hooks into the `member_member_register_errors` extension point. When a user submits the registration form, it analyzes the screen name (and username when applicable) for suspicious patterns:

| Pattern | Example | Action |
|---------|---------|--------|
| Mixed-case spam | `bpnUPGwotiovkSgjkowAIa` | Blocked |
| Gibberish | `XyZqRpLmNk` (no vowels) | Blocked |
| Consonant clusters | `strngthxyz` | Blocked |
| Normal name | `John Smith`, `Alice` | Allowed |
| Email as username | `user@example.com` | Skipped (allowed) |

## Error Messages

Customizable via the language file. Default messages:

- *Please use a normal name format.* — Mixed-case spam
- *Please use a real name or nickname.* — Gibberish
- *Please use a readable screen name.* — Consonant clusters

Edit `language/english/nameguard_lang.php` to customize.

## Uninstallation

In the Control Panel: **Add-ons** → **NameGuard** → **Uninstall**

## License

MIT License

## Author

**Elonovo**  
[https://www.elonovo.com](https://www.elonovo.com)
