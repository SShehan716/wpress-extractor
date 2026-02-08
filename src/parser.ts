import fs from "node:fs";
import path from "node:path";

export interface PostContent {
  id: number;
  title: string;
  content: string;
  excerpt: string;
  type: "post" | "page" | "attachment" | string;
  status: string;
  date: string;
  modified: string;
  slug: string;
  authorId: number;
  guid: string;
}

// WordPress wp_posts column order (standard schema)
const POST_COLUMNS = [
  "ID",
  "post_author",
  "post_date",
  "post_date_gmt",
  "post_content",
  "post_title",
  "post_excerpt",
  "post_status",
  "comment_status",
  "ping_status",
  "post_password",
  "post_name",
  "to_ping",
  "pinged",
  "post_modified",
  "post_modified_gmt",
  "post_content_filtered",
  "post_parent",
  "guid",
  "menu_order",
  "post_type",
  "post_mime_type",
  "comment_count",
] as const;

function parseSqlValue(value: string): string | number | null {
  const trimmed = value.trim();
  if (trimmed.toUpperCase() === "NULL") return null;
  if (trimmed.startsWith("'") && trimmed.endsWith("'")) {
    return trimmed
      .slice(1, -1)
      .replace(/\\'/g, "'")
      .replace(/\\"/g, '"')
      .replace(/\\n/g, "\n")
      .replace(/\\r/g, "\r")
      .replace(/\\t/g, "\t")
      .replace(/\\\\/g, "\\");
  }
  const num = Number(trimmed);
  return Number.isNaN(num) ? trimmed : num;
}

/**
 * Parse MySQL INSERT VALUES into array of value arrays.
 * Handles escaped strings, NULL, and nested commas correctly.
 */
function parseInsertValues(valuesStr: string): string[][] {
  const rows: string[][] = [];
  let currentRow: string[] = [];
  let currentValue = "";
  let i = 0;
  let inString = false;
  let stringChar = "'";
  let depth = 0;

  while (i < valuesStr.length) {
    const char = valuesStr[i];
    const nextChar = valuesStr[i + 1];

    if (!inString) {
      if (char === "(") {
        depth++;
        if (depth === 1) {
          currentRow = [];
          currentValue = "";
        }
        i++;
        continue;
      }
      if (char === ")") {
        depth--;
        if (depth === 0) {
          if (currentValue.trim()) {
            currentRow.push(currentValue);
          }
          if (currentRow.length > 0) {
            rows.push(currentRow);
          }
        }
        i++;
        continue;
      }
      if (depth === 1 && char === ",") {
        currentRow.push(currentValue);
        currentValue = "";
        i++;
        continue;
      }
      if ((char === "'" || char === '"') && depth >= 1) {
        inString = true;
        stringChar = char;
        currentValue += char;
        i++;
        continue;
      }
    } else {
      if (char === "\\" && nextChar) {
        currentValue += char + nextChar;
        i += 2;
        continue;
      }
      if (char === stringChar) {
        inString = false;
        currentValue += char;
        i++;
        continue;
      }
    }

    if (depth >= 1) {
      currentValue += char;
    }
    i++;
  }

  return rows;
}

/**
 * Extract posts table name and VALUES from INSERT statement
 */
// parseInsertStatement is unused - we use manual parsing for robustness

export interface ContentOptions {
  /** Include draft/trash posts */
  includeDrafts?: boolean;
  /** Post types to include (default: post, page) */
  postTypes?: string[];
}

/**
 * Parse database.sql and extract post/page content
 */
export async function extractContent(
  extractedDir: string,
  options: ContentOptions = {}
): Promise<PostContent[]> {
  const dbPath = path.join(extractedDir, "database.sql");
  if (!fs.existsSync(dbPath)) {
    throw new Error(`database.sql not found in ${extractedDir}`);
  }

  const sql = fs.readFileSync(dbPath, "utf-8");
  const includeDrafts = options.includeDrafts ?? false;
  const postTypes = options.postTypes ?? ["post", "page"];

  const posts: PostContent[] = [];
  const tablePattern = /^(\w*_)?posts$/i;

  let pos = 0;
  while (pos < sql.length) {
    const insertIdx = sql.indexOf("INSERT INTO", pos);
    if (insertIdx === -1) break;

    const valuesIdx = sql.indexOf("VALUES", insertIdx);
    if (valuesIdx === -1) {
      pos = insertIdx + 1;
      continue;
    }

    const tableMatch = sql.slice(insertIdx, valuesIdx).match(/`?(\w+_posts)`?/i);
    if (!tableMatch || !tablePattern.test(tableMatch[1])) {
      pos = insertIdx + 1;
      continue;
    }

    let valuesStart = valuesIdx + 6;
    while (valuesStart < sql.length && /\s/.test(sql[valuesStart])) valuesStart++;

    let depth = 0;
    let inString = false;
    let stringChar = "'";
    let i = valuesStart;
    let escapeNext = false;

    while (i < sql.length) {
      const char = sql[i];
      if (escapeNext) {
        escapeNext = false;
        i++;
        continue;
      }
      if (inString) {
        if (char === "\\") escapeNext = true;
        else if (char === stringChar) inString = false;
        i++;
        continue;
      }
      if (char === "'" || char === '"') {
        inString = true;
        stringChar = char;
        i++;
        continue;
      }
      if (char === "(") depth++;
      else if (char === ")") {
        depth--;
        if (depth === 0) {
          const valuesStr = sql.slice(valuesStart, i + 1);
          const rows = parseInsertValues(valuesStr);

          for (const row of rows) {
            const getCol = (name: string): string | number | null => {
              const idx = POST_COLUMNS.indexOf(name as (typeof POST_COLUMNS)[number]);
              if (idx >= 0 && idx < row.length) {
                return parseSqlValue(row[idx]);
              }
              return null;
            };

            const type = String(getCol("post_type") ?? "");
            if (!postTypes.includes(type)) continue;

            const status = String(getCol("post_status") ?? "");
            if (!includeDrafts && !["publish", "inherit"].includes(status)) continue;

            posts.push({
              id: Number(getCol("ID")) || 0,
              title: String(getCol("post_title") ?? ""),
              content: String(getCol("post_content") ?? ""),
              excerpt: String(getCol("post_excerpt") ?? ""),
              type,
              status,
              date: String(getCol("post_date") ?? ""),
              modified: String(getCol("post_modified") ?? ""),
              slug: String(getCol("post_name") ?? ""),
              authorId: Number(getCol("post_author")) || 0,
              guid: String(getCol("guid") ?? ""),
            });
          }
          break;
        }
      }
      i++;
    }
    pos = i + 1;
  }

  return posts;
}

/**
 * Export content to JSON file
 */
export function exportToJson(
  posts: PostContent[],
  outputPath: string
): void {
  fs.writeFileSync(outputPath, JSON.stringify(posts, null, 2), "utf-8");
}

/**
 * Export content to Markdown files (one per post/page)
 */
export function exportToMarkdown(
  posts: PostContent[],
  outputDir: string
): void {
  fs.mkdirSync(outputDir, { recursive: true });

  for (const post of posts) {
    const safeName = post.slug || `post-${post.id}`;
    const filename = post.type === "attachment" ? `${safeName}.txt` : `${safeName}.md`;
    const filepath = path.join(outputDir, filename);

    const md = `# ${post.title}

- **ID:** ${post.id}
- **Type:** ${post.type}
- **Status:** ${post.status}
- **Date:** ${post.date}
- **Modified:** ${post.modified}
- **Slug:** ${post.slug}

${post.excerpt ? `## Excerpt\n\n${post.excerpt}\n\n` : ""}## Content\n\n${post.content}
`;

    fs.writeFileSync(filepath, md, "utf-8");
  }
}
