# Security review

The first version of this app was written quickly for a course. Before publishing it, I reviewed
it the way I would review someone else's code and fixed what I found.

| # | Finding | Impact | Fix |
|---|---|---|---|
| 1 | **SQL injection** — every query built by string concatenation (`WHERE email='$email' AND password='$password'`) | Login bypass with `admin' OR '1'='1`, full database read/modify | All queries use PDO prepared statements; IDs cast to `int`; `ATTR_EMULATE_PREPARES` off |
| 2 | **Plaintext passwords** stored and compared in SQL | A DB leak exposes every password | `password_hash()` / `password_verify()` (bcrypt); seed data hashed |
| 3 | **Password echoed back** into the profile form's `value` | Password visible in page source | Field left empty; blank = keep current password |
| 4 | **Stored / reflected XSS** — DB values and session data echoed without escaping | Script injection via car names, user names, descriptions | `e()` helper (`htmlspecialchars`, `ENT_QUOTES`) on every output |
| 5 | **No CSRF protection** on any form; car deletion via a plain GET link | An attacker page could delete cars or change a profile | Per-session CSRF token on every form; delete moved to POST |
| 6 | **Session fixation** — session ID kept after login | Pre-set session ID could be hijacked | `session_regenerate_id(true)` on login |
| 7 | **Error leakage** — `print_r($pdo->errorInfo())` printed SQL errors to the page | Helps attackers craft injections | PDO exception mode, no raw errors shown |
| 8 | **Hard-coded DB credentials** in `config.php` | Secrets in source control | Read from `DB_DSN` / `DB_USER` / `DB_PASS` env vars |
| 9 | **Unvalidated input** — rental dates, car image path | Garbage bookings, path tricks in `<img src>` | Date format + "not in the past" check; image must be a bare file name with an image extension |
| 10 | Missing `exit` after redirects | Code kept running after `header('location: …')` | `exit` added |

Verified after the fixes: the `admin' OR '1'='1` login bypass now returns "Invalid email or password."

## Still out of scope

No rate limiting on login, no email verification, no HTTPS config (that belongs to the web server).
