# 📊 Code Metrics with cloc

The cloc report is automatically generated and attached during the GitHub Actions release workflow. However, if you want to run the analysis manually on your local machine, follow the instructions below.

## 🧰 Installation

### 🐧 Linux (Debian/Ubuntu)
```
sudo apt update
sudo apt install cloc
```

### 🍎 macOS (Homebrew)
```
brew install cloc
```

### 🪟 Windows (Chocolatey)
```
choco install cloc
```

Or via Scoop:
```
scoop install cloc
```

## 📈 Project analysis (recommended scope)

To get an accurate breakdown of the project (PHP, JavaScript, and CSS only), excluding `index.php`, `*.min.js`, and `*.min.css`, use:

```
cloc src assets wpcn-terms-enhancer.php --include-lang=PHP,JavaScript,CSS --not-match-f="(\.min\.(js|css)|index\.php)$"
```

## 🧠 What this does

- `src` → counts plugin source code
- `assets` → includes JS and CSS assets
- `wpcn-terms-enhancer.php` → includes main plugin file
- `--include-lang=PHP,JavaScript,CSS` → only relevant languages
- `(\.min\.(js|css)|index\.php)$` → excludes index files, *.min.js, and *.min.css

## 🚀 Recommended usage

Run from plugin root:

```
cloc src assets wpcn-terms-enhancer.php --include-lang=PHP,JavaScript,CSS --not-match-f="(\.min\.(js|css)|index\.php)$"
```

## ⚡ Tip

Keep the scope fixed for consistent CI/CD and release comparisons.