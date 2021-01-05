<?php

declare(strict_types=1);

namespace Datapp\TableauExport;

/**
 * @author Manuel Dimmler
 */
class CrossTableException extends \RuntimeException
{

    const FILE_NOT_FOUND_OR_NOT_READABLE = 1;
    const ERROR_READING_CSV_LINE = 2;
    const ERROR_PARSING_CSV_LINE = 3;

    public static function fileNotFoundOrNotReadable(string $fileName): self
    {
        return new self(sprintf('file "%s" not found or not readable', $fileName), self::FILE_NOT_FOUND_OR_NOT_READABLE);
    }

    public static function errorReadingCsvLine(): self
    {
        // @codeCoverageIgnoreStart
        return new self('error reading csv line', self::ERROR_READING_CSV_LINE);
        // @codeCoverageIgnoreEnd
    }

    public static function errorParsingCsvLine(): self
    {
        return new self('error parsing csv line', self::ERROR_PARSING_CSV_LINE);
    }
}
