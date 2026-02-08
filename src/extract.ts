import { createRequire } from "node:module";
import path from "node:path";
import fs from "node:fs";

const require = createRequire(import.meta.url);
const wpExtract = require("wpress-extract") as (options: {
  inputFile: string;
  outputDir: string;
  onStart?: (totalSize: number) => void;
  onUpdate?: (value: number) => void;
  onFinish?: (totalFiles: number) => void;
  override?: boolean;
}) => Promise<void>;

export interface ExtractOptions {
  /** Output directory. Defaults to same name as file (without .wpress) */
  outputDir?: string;
  /** Overwrite if output directory exists */
  force?: boolean;
  /** Progress callback: (extracted, total) */
  onProgress?: (extracted: number, total: number) => void;
}

export interface ExtractResult {
  success: boolean;
  outputPath: string;
  fileCount: number;
  hasDatabase: boolean;
  hasWpContent: boolean;
  error?: string;
}

/**
 * Extract a WPRESS file using wpress-extract
 */
export async function extractWpress(
  wpressPath: string,
  options: ExtractOptions = {}
): Promise<ExtractResult> {
  const resolvedPath = path.resolve(wpressPath);

  if (!fs.existsSync(resolvedPath)) {
    return {
      success: false,
      outputPath: "",
      fileCount: 0,
      hasDatabase: false,
      hasWpContent: false,
      error: `File not found: ${resolvedPath}`,
    };
  }

  const defaultOutput = path.join(
    path.dirname(resolvedPath),
    path.basename(resolvedPath, path.extname(resolvedPath))
  );
  const outputDir = options.outputDir
    ? path.resolve(options.outputDir)
    : defaultOutput;

  try {
    let totalSize = 0;
    let fileCount = 0;

    await wpExtract({
      inputFile: resolvedPath,
      outputDir,
      override: options.force ?? false,
      onStart: (size) => {
        totalSize = size;
      },
      onUpdate: (offset) => {
        options.onProgress?.(offset, totalSize);
      },
      onFinish: (count) => {
        fileCount = count;
      },
    });

    const dbPath = path.join(outputDir, "database.sql");
    const wpContentPath = path.join(outputDir, "wp-content");

    return {
      success: true,
      outputPath: outputDir,
      fileCount,
      hasDatabase: fs.existsSync(dbPath),
      hasWpContent: fs.existsSync(wpContentPath),
    };
  } catch (err) {
    return {
      success: false,
      outputPath: outputDir,
      fileCount: 0,
      hasDatabase: false,
      hasWpContent: false,
      error: err instanceof Error ? err.message : String(err),
    };
  }
}
