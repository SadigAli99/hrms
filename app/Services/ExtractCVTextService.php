<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

class ExtractCVTextService
{

    public function extract(string $filePath, string $extension): array
    {
        try {
            if (!$filePath || !file_exists($filePath)) {
                return [
                    'success' => false,
                    'text' => null,
                    'extension' => $extension,
                    'error_message' => 'File not found',
                ];
            }

            return match (strtolower($extension)) {
                'pdf' => [
                    'success' => true,
                    'text' => $this->pdfTextExtractor($filePath),
                    'extension' => 'pdf',
                    'error_message' => null,
                ],
                'docx' => [
                    'success' => true,
                    'text' => $this->docxTextExtractor($filePath),
                    'extension' => 'docx',
                    'error_message' => null,
                ],
                default => [
                    'success' => false,
                    'text' => null,
                    'extension' => $extension,
                    'error_message' => 'Unsupported file format',
                ]
            };
        } catch (\Throwable $ex) {
            Log::error('CV text extraction failed', [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'message' => $ex->getMessage(),
            ]);

            return [
                'success' => false,
                'text' => null,
                'extension' => $extension,
                'error_message' => $ex->getMessage(),
            ];
        }
    }

    private function pdfTextExtractor(string $filePath): string
    {
        if (!class_exists(Parser::class)) {
            throw new \RuntimeException('smalot/pdfparser package is not available');
        }

        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();
        $text = $this->normalizeText($text);

        if ($text === '') {
            throw new \RuntimeException('PDF text could not be extracted');
        }

        if (mb_strlen($text) < 50) {
            throw new \RuntimeException('Extracted text is too short');
        }

        return $text;
    }


    private function docxTextExtractor(string $filePath): string
    {
        $zip = new \ZipArchive();

        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('DOCX file could not be opened');
        }

        $documentXml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($documentXml === false) {
            throw new \RuntimeException('DOCX document.xml could not be read');
        }

        $text = strip_tags($documentXml);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $text = $this->normalizeText($text);

        if ($text === '') {
            throw new \RuntimeException('DOCX text could not be extracted');
        }

        if (mb_strlen($text) < 50) {
            throw new \RuntimeException('Extracted text is too short');
        }

        return $text;
    }

    protected function normalizeText(?string $text): string
    {
        $text = (string) $text;

        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
        }
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace("/[ \t]+/", ' ', $text) ?? $text;
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;

        return trim($text);
    }
}
