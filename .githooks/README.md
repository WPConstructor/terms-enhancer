# Git Hooks (Pre-Commit & Pre-Push)

This project includes custom Git hooks to improve code quality and prevent broken commits or pushes.

## 🚀 What they do

### Pre-Commit Hook

Runs automatically before every commit and checks:

* ❌ Prevents commits with unstaged changes
* ⚠️ Warns about untracked files
* ❌ Ensures something is actually staged
* ⚠️ Warns if committing directly to `main`
* 📦 Shows detected plugin version (sanity check)

---

### Pre-Push Hook

Runs automatically before every push and enforces CI-like checks locally:

* ❌ Ensures working tree is clean
* 📦 Installs Composer dependencies if missing
* 🔎 Runs PHPCS (WordPress Coding Standards)
* 🧠 Runs PHPStan (static analysis)
* 🧪 Runs PHPUnit tests
* 📦 Runs build process and verifies ZIP output
* 🏷️ Validates plugin version matches Git tag (for release pushes)

---

## ⚙️ Installation

Configure Git to use the repository hooks directory:

```bash
git config core.hooksPath .githooks
```

This tells Git to load hooks directly from:

```text
.githooks/
```

instead of `.git/hooks/`.

Then make them executable:

```bash
chmod +x .githooks/pre-commit
chmod +x .githooks/pre-push
```

---

## 🚫 Bypassing hooks (not recommended)

You can bypass hooks using:

```bash id="bypass-hooks"
git commit --no-verify
git push --no-verify
```

⚠️ Use only in emergencies.

---

## 🧠 Notes

* Hooks run locally and are not shared automatically via Git, but in this git via `git-hooks/` folder.
* They help catch issues before CI/CD
* CI (GitHub Actions) remains the final validation layer

---

## 🎯 Purpose

These hooks ensure:

* Higher code quality
* Safer releases
* Fewer broken builds
* Early detection of issues before GitHub CI runs