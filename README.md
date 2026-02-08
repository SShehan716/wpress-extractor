# WPRESS Extractor

Extract content from **All-in-One WP Migration** backup files (.wpress). Unpacks the archive, parses the WordPress database, and exports posts and pages as JSON or Markdown.

## Features

- **Extract** – Unpack .wpress archives (database.sql + wp-content)
- **Parse** – Extract posts and pages from the database with full content
- **Export** – Save to JSON or individual Markdown files
- **CLI & API** – Use from command line or import in Node.js

## Installation

```bash
npm install
npm run build
```

## Usage

### Extract only (unpack the archive)

```bash
npx wpress-extractor extract backup.wpress
# Output: ./backup/ (database.sql, wp-content/, package.json)

# Custom output directory
npx wpress-extractor extract backup.wpress -o ./my-output

# Overwrite existing directory
npx wpress-extractor extract backup.wpress --force
```

### Extract content (from already extracted backup)

```bash
npx wpress-extractor content ./backup

# Export to JSON
npx wpress-extractor content ./backup -o content.json

# Export to Markdown (one file per post/page)
npx wpress-extractor content ./backup -m ./markdown-output

# Include drafts and other post types
npx wpress-extractor content ./backup --include-drafts --types post,page,custom_post
```

### All-in-one (extract + parse + export)

```bash
npx wpress-extractor all backup.wpress

# With export options
npx wpress-extractor all backup.wpress -j content.json -m ./markdown
```

## Programmatic API

```typescript
import { extractWpress, extractContent, exportToJson } from "wpress-extractor";

// Extract archive
const result = await extractWpress("backup.wpress", { force: true });

// Parse content
const posts = await extractContent(result.outputPath, {
  postTypes: ["post", "page"],
  includeDrafts: false,
});

// Export
exportToJson(posts, "output.json");
```

## Output

### Extracted files

- `database.sql` – WordPress database dump
- `wp-content/` – Themes, plugins, uploads
- `package.json` – Site metadata

### Content JSON

```json
[
  {
    "id": 1,
    "title": "Hello World",
    "content": "<p>Post content in HTML...</p>",
    "excerpt": "Short excerpt...",
    "type": "post",
    "status": "publish",
    "date": "2024-01-15 10:00:00",
    "slug": "hello-world",
    "authorId": 1,
    "guid": "https://..."
  }
]
```

### Markdown export

Each post/page becomes a `.md` file with frontmatter-style metadata and the content.

## Requirements

- Node.js 18+
- .wpress files from [All-in-One WP Migration](https://wordpress.org/plugins/all-in-one-wp-migration/)

## License

MIT
