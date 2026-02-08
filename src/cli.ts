#!/usr/bin/env node

import { program } from "commander";
import chalk from "chalk";
import path from "node:path";
import fs from "node:fs";
import { extractWpress } from "./extract.js";
import {
  extractContent,
  exportToJson,
  exportToMarkdown,
  type PostContent,
} from "./parser.js";

program
  .name("wpress-extractor")
  .description(
    "Extract content from All-in-One WP Migration WPRESS backup files"
  )
  .version("1.0.0");

program
  .command("extract <file>")
  .description("Extract a WPRESS file to a directory")
  .option("-o, --output <dir>", "Output directory")
  .option("-f, --force", "Overwrite existing directory")
  .action(async (file: string, opts: { output?: string; force?: boolean }) => {
    console.log(chalk.cyan(`\nExtracting ${file}...\n`));
    const result = await extractWpress(file, {
      outputDir: opts.output,
      force: opts.force,
      onProgress: (extracted, total) => {
        const pct = total > 0 ? Math.round((extracted / total) * 100) : 0;
        process.stdout.write(`\r  Progress: ${pct}%`);
      },
    });

    if (result.success) {
      console.log(chalk.green(`\n\n✓ Extracted to: ${result.outputPath}`));
      console.log(`  Files: ${result.fileCount}`);
      console.log(`  Database: ${result.hasDatabase ? "✓" : "✗"}`);
      console.log(`  wp-content: ${result.hasWpContent ? "✓" : "✗"}`);
    } else {
      console.error(chalk.red(`\n✗ ${result.error}`));
      process.exit(1);
    }
  });

program
  .command("content <extracted-dir>")
  .description("Extract post/page content from an extracted backup")
  .option("-o, --output <file>", "Output JSON file for content")
  .option(
    "-m, --markdown <dir>",
    "Export each post/page to a Markdown file in the given directory"
  )
  .option("--include-drafts", "Include draft and trashed posts")
  .option(
    "--types <types>",
    "Comma-separated post types (default: post,page)",
    "post,page"
  )
  .action(
    async (
      dir: string,
      opts: {
        output?: string;
        markdown?: string;
        includeDrafts?: boolean;
        types?: string;
      }
    ) => {
      const resolvedDir = path.resolve(dir);
      if (!fs.existsSync(resolvedDir)) {
        console.error(chalk.red(`Directory not found: ${resolvedDir}`));
        process.exit(1);
      }

      console.log(chalk.cyan(`\nExtracting content from ${resolvedDir}...\n`));

      try {
        const postTypes = (opts.types ?? "post,page").split(",").map((t) => t.trim());
        const posts = await extractContent(resolvedDir, {
          includeDrafts: opts.includeDrafts,
          postTypes,
        });

        console.log(chalk.green(`✓ Found ${posts.length} posts/pages\n`));

        if (posts.length > 0) {
          const byType = posts.reduce(
            (acc, p) => {
              acc[p.type] = (acc[p.type] || 0) + 1;
              return acc;
            },
            {} as Record<string, number>
          );
          console.log("  By type:");
          for (const [type, count] of Object.entries(byType)) {
            console.log(`    ${type}: ${count}`);
          }
        }

        if (opts.output) {
          exportToJson(posts, path.resolve(opts.output));
          console.log(chalk.green(`\n✓ Exported to JSON: ${opts.output}`));
        }

        if (opts.markdown) {
          const mdDir = path.resolve(opts.markdown);
          exportToMarkdown(posts, mdDir);
          console.log(chalk.green(`✓ Exported to Markdown: ${mdDir}`));
        }

        if (!opts.output && !opts.markdown) {
          console.log("\nContent preview (first 3 items):\n");
          for (const post of posts.slice(0, 3)) {
            console.log(chalk.bold(`  ${post.title}`));
            console.log(`    Type: ${post.type} | Date: ${post.date}`);
            const preview = post.content.slice(0, 100).replace(/\n/g, " ");
            console.log(`    Preview: ${preview}${post.content.length > 100 ? "..." : ""}\n`);
          }
          console.log(
            chalk.dim(
              "  Use -o output.json to export all content to JSON, or -m ./markdown to export to Markdown files."
            )
          );
        }
      } catch (err) {
        console.error(chalk.red(`\n✗ ${err instanceof Error ? err.message : err}`));
        process.exit(1);
      }
    }
  );

program
  .command("all <file>")
  .description("Extract WPRESS file and content in one step")
  .option("-o, --output <dir>", "Extraction output directory")
  .option("-j, --json <file>", "Export content to JSON")
  .option("-m, --markdown <dir>", "Export content to Markdown files")
  .option("-f, --force", "Overwrite existing files")
  .action(
    async (
      file: string,
      opts: {
        output?: string;
        json?: string;
        markdown?: string;
        force?: boolean;
      }
    ) => {
      console.log(chalk.cyan(`\nExtracting and parsing ${file}...\n`));

      const extractResult = await extractWpress(file, {
        outputDir: opts.output,
        force: opts.force,
        onProgress: (extracted, total) => {
          const pct = total > 0 ? Math.round((extracted / total) * 100) : 0;
          process.stdout.write(`\r  Extract progress: ${pct}%`);
        },
      });

      if (!extractResult.success) {
        console.error(chalk.red(`\n✗ ${extractResult.error}`));
        process.exit(1);
      }

      console.log(chalk.green(`\n✓ Extracted to: ${extractResult.outputPath}\n`));

      if (!extractResult.hasDatabase) {
        console.log(chalk.yellow("  No database.sql found, skipping content extraction."));
        return;
      }

      try {
        const posts = await extractContent(extractResult.outputPath);

        console.log(chalk.green(`✓ Found ${posts.length} posts/pages\n`));

        if (opts.json) {
          exportToJson(posts, path.resolve(opts.json));
          console.log(chalk.green(`✓ Exported to JSON: ${opts.json}`));
        }

        if (opts.markdown) {
          const mdDir = path.resolve(opts.markdown);
          exportToMarkdown(posts, mdDir);
          console.log(chalk.green(`✓ Exported to Markdown: ${mdDir}`));
        }

        if (!opts.json && !opts.markdown) {
          exportToJson(posts, path.join(extractResult.outputPath, "content.json"));
          console.log(
            chalk.green(`✓ Content saved to: ${extractResult.outputPath}/content.json`)
          );
        }
      } catch (err) {
        console.error(chalk.red(`\n✗ ${err instanceof Error ? err.message : err}`));
        process.exit(1);
      }
    }
  );

program.parse();
