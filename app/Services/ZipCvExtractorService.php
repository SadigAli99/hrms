<?php

namespace App\Services;

use ZipArchive;

class ZipCvExtractorService
{
    public function extractCandidatesFromZip(string $zipPath): array
    {
        try {
            $processedFiles = [];
            $skippedFiles = [];
            $tempPaths = [];
            $zip = $this->openZip($zipPath);

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $entryName = $stat['name'] ?? '';
                $size = $stat['size'] ?? 0;
                if (str_ends_with($entryName, '/')) {
                    continue;
                }

                if (!$this->isSupportedFile($entryName)) {
                    $skippedFiles[] = $this->buildSkippedFile($entryName, 'unsupported_format');
                    continue;
                }

                if (!$this->isAllowedSize($size)) {
                    $skippedFiles[] = $this->buildSkippedFile($entryName, 'file_too_large');
                    continue;
                }

                $tempPath = $this->extractEntryToTemp($zip, $i, $entryName);
                $tempPaths[] = $tempPath;
                $processedFiles[] = $this->buildProcessedFile($entryName, $tempPath, $this->getExtension($entryName), $size);
            }

            $zip->close();

            if (count($processedFiles) == 0) {
                $this->cleanupTempFiles($tempPaths);
                return [
                    'success' => false,
                    'processed_files' => [],
                    'skipped_files' => $skippedFiles,
                    'error_message' => 'No supported CV files found in zip',
                ];
            }

            return [
                'success' => true,
                'processed_files' => $processedFiles,
                'skipped_files' => $skippedFiles,
            ];
        } catch (\Exception $ex) {
            $this->cleanupTempFiles($tempPaths);
            return [
                'success' => false,
                'processed_files' => [],
                'skipped_files' => [],
                'error_message' => $ex->getMessage(),
            ];
        }
    }

    protected function openZip(string $zipPath): ZipArchive
    {
        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException('Zip file could not be opened');
        }
        return $zip;
    }

    protected function isSupportedFile(string $fileName): bool
    {
        return in_array($this->getExtension($fileName), ['pdf', 'docx'], true);
    }

    protected function isAllowedSize(int $size): bool
    {
        return $size <= (10 * 1024 * 1024);
    }

    protected function getExtension(string $fileName): string
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }

    protected function extractEntryToTemp(ZipArchive $zip, int $index, string $entryName): string
    {
        $content = $zip->getFromIndex($index);

        if ($content === false) {
            throw new \RuntimeException("Zip entry could not be read : {$entryName}");
        }

        $extension = $this->getExtension($entryName);

        $tempPath = tempnam(sys_get_temp_dir(), 'cv_zip_');

        if ($tempPath === false) {
            throw new \RuntimeException('Temporary file could not be created');
        }

        $finalPath = $tempPath . '.' . $extension;

        if (!@rename($tempPath, $finalPath)) {
            @unlink($tempPath);
            throw new \RuntimeException('Temporary file could not be prepared');
        }

        if (file_put_contents($finalPath, $content) === false) {
            @unlink($finalPath);
            throw new \RuntimeException("Zip entry could not be written : {$entryName}");
        }
        return $finalPath;
    }

    protected function buildSkippedFile(string $fileName, string $reason): array
    {
        return [
            'file_name' => $fileName,
            'reason' => $reason,
        ];
    }

    protected function buildProcessedFile(string $fileName, string $path, string $extension, int $size): array
    {
        return [
            'file_name' => $fileName,
            'path' => $path,
            'extension' => $extension,
            'size' => $size,
        ];
    }

    protected function cleanupTempFiles(array $paths): void
    {
        foreach ($paths as $path) {
            if ($path && file_exists($path)) {
                @unlink($path);
            }
        }
    }
}
