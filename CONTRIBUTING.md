# Contributing to `wpress-extractor`

First off, thank you for considering contributing to `wpress-extractor`! It's people like you that make open source such a great community space.

## Table of Contents

- [How Can I Contribute?](#how-can-i-contribute)
  - [Reporting Bugs](#reporting-bugs)
  - [Suggesting Enhancements](#suggesting-enhancements)
  - [Your First Code Contribution](#your-first-code-contribution)
  - [Pull Requests](#pull-requests)
- [Local Development Setup](#local-development-setup)

## How Can I Contribute?

### Reporting Bugs

If you find a bug, please help us by submitting an issue using our [Bug Report Template](.github/ISSUE_TEMPLATE/bug_report.md). Be sure to include steps to reproduce the bug so we can investigate and fix it.

### Suggesting Enhancements

If you have ideas for new features or improvements, submit an issue using our [Feature Request Template](.github/ISSUE_TEMPLATE/feature_request.md).

### Your First Code Contribution

Unsure where to begin contributing? You can start by looking through the `good first issue` and `help wanted` labels in the issue tracker.

### Pull Requests

1. Fork the repo and create your branch from `main`.
2. If you've added code that should be tested, add tests.
3. Ensure the test suite passes (if applicable).
4. Make sure your code lints and is formatted correctly.
5. Create a Pull Request using the provided PR template.

## Local Development Setup

To develop `wpress-extractor` locally, follow these steps:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/SShehan716/wpress-extractor.git
   cd wpress-extractor
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Run the build:**
   ```bash
   npm run build
   ```

4. **Run the CLI in development mode:**
   ```bash
   npm run dev -- extract <path-to-test.wpress>
   ```

By contributing to this repository, you agree that your contributions will be licensed under its MIT License.
