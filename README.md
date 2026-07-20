<!-- markdownlint-disable MD013 -->
# php-fwf

[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B%20%2F%208.5-blue?logo=php)](https://www.php.net/)
[![FWF Compliance](https://img.shields.io/badge/FWF_Compliance-5%2F5_cases_passed-brightgreen?logo=github)](https://github.com/fixed-width-file/fwf-compliance-tests)
[![QA](https://github.com/fixed-width-file/php-fwf/actions/workflows/qa.yml/badge.svg)](https://github.com/fixed-width-file/php-fwf/actions/workflows/qa.yml)
[![Coverage](https://codecov.io/gh/fixed-width-file/php-fwf/branch/main/graph/badge.svg)](https://codecov.io/gh/fixed-width-file/php-fwf)
[![Docs](https://img.shields.io/badge/docs-GitHub%20Pages-blue)](https://fixed-width-file.github.io/php-fwf/)
[![pre-commit](https://img.shields.io/badge/pre--commit-enabled-brightgreen?logo=pre-commit)](https://github.com/pre-commit/pre-commit)

**php-fwf** is a fast, type-safe PHP library for parsing, validating, hydrating, and exporting **Fixed Width Files (FWF)**.

It is part of the [Fixed Width File Ecosystem](https://fixed-width-file.github.io/) and fully implements the **[fwf-compliance-tests v1.0.0](https://github.com/fixed-width-file/fwf-compliance-tests)** specification.

---

## 🌟 Key Features

- **Type-Safe Columns**: `CharColumn`, `RightCharColumn`, `PositiveIntegerColumn`, `PositiveDecimalColumn`, `DateColumn`, `TimeColumn`, and `DateTimeColumn`.
- **Flexible Descriptors**: Structured records with `HeaderRowDescriptor`, `DetailRowDescriptor`, and `FooterRowDescriptor`.
- **Cross-Language Hydration**: Dehydrate/Hydrate descriptors to/from JSON representations compatible with Python `pyfwf` and Java `java-fwf`.
- **Multiple Output Renders**: Export layout specifications to Markdown, ReStructuredText (RST), or HTML tables via `RenderUtils`.
- **Full Test Suite & Compliance**: 100% compliant with `fwf-compliance-tests` v1.0.0.
- **Git Hooks Ready**: Pre-configured `pre-commit` and `pre-push` hooks.

---

## 🚀 Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require kelsoncm/php-fwf
```

---

## 💡 Quickstart

```php
<?php

use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Descriptors\DetailRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FileDescriptor;
use Kelsoncm\Fwf\Readers\Reader;

// 1. Define columns
$nameCol = new CharColumn('name', 20, 'User Name');
$ageCol = new PositiveIntegerColumn('age', 3, 'Age in years');

// 2. Define row and file descriptors
$detail = new DetailRowDescriptor([$nameCol, $ageCol]);
$fileDescriptor = new FileDescriptor([$detail]);

// 3. Read fixed-width file content
$content = "KELSON MEDEIROS     045\nMARIA SILVA         030\n";
$reader = new Reader($content, $fileDescriptor, "\n");

foreach ($reader as $row) {
    echo "Name: {$row['name']} | Age: {$row['age']}\n";
}
```

---

## 📖 phpDocumentor & GitHub Pages

Generated documentation is ready for GitHub Pages publication in the `docs/` directory:

- **Landing Page**: [docs/index.html](docs/index.html)
- **API Documentation**: [docs/apidocs/index.html](docs/apidocs/index.html)

---

## 🧪 Testing & Compliance

Run all unit tests and compliance checks using PHPUnit:

```bash
vendor/bin/phpunit
```

---

## ⚓ Pre-Commit & Pre-Push Setup

Set up pre-commit and pre-push hooks:

```bash
pre-commit install
pre-commit install --hook-type pre-push
```

Run checks manually:

```bash
pre-commit run --all-files
```

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
