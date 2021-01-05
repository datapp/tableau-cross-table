<?php

declare(strict_types=1);

namespace Datapp\TableauExport;

use Datapp\TableauExport\Sorter\SorterInterface;
use Datapp\TableauExport\Sorter\NullSorter;
use Datapp\TableauExport\CrossTableException;
use Generator;

class CrossTable
{

    const MEASURE_NAMES = 'Measure Names';
    const MEASURE_VALUES = 'Measure Values';

    private array $cache = [];
    private SorterInterface $sorter;

    /**
     * @param SorterInterface $sorter
     */
    public function __construct(SorterInterface $sorter = null)
    {
        $this->sorter = $sorter ?? new NullSorter();
    }

    /**
     * @param string $fileName
     * @return Generator<int, array <string, string>, mixed, void>
     * @throws CrossTableException
     */
    public function fromFile(string $fileName): Generator
    {
        $lineGenerator = $this->readLineByLine($fileName);
        if (!$lineGenerator->valid()) {
            throw CrossTableException::errorParsingCsvLine();
        }
        /** @var array <int, string> */
        $headers = $lineGenerator->current();
        $idColums = $this->getIdColums($headers, [self::MEASURE_NAMES, self::MEASURE_VALUES]);
        $lineGenerator->next();

        while ($lineGenerator->valid()) {
            /** @var array <int, string> */
            $line = $lineGenerator->current();
            $data = array_combine($headers, $line);
            if ($data === false) {
                throw CrossTableException::errorParsingCsvLine();
            }

            if (!$this->hasSameValuesInIdColumsAsRowBefore($data, $idColums, $this->cache)) {
                if (count($this->cache) > 0) {
                    yield $this->sorter->sort($this->cache);
                }
                $this->cache = $data;
                unset($this->cache[self::MEASURE_NAMES]);
                unset($this->cache[self::MEASURE_VALUES]);
            }
            $this->cache[(string) $data[self::MEASURE_NAMES]] = (string) $data[self::MEASURE_VALUES];
            $lineGenerator->next();
        }
        // don't forget last line
        yield $this->sorter->sort($this->cache);
    }

    /**
     * @param string $fileName
     * @return \Generator<int, array <int, string>, mixed, void>
     * @throws CrossTableException
     */
    private function readLineByLine(string $fileName): \Generator
    {
        if (!is_readable($fileName)) {
            throw CrossTableException::fileNotFoundOrNotReadable($fileName);
        }
        $fileHandle = fopen($fileName, 'r');
        if ($fileHandle === false) {
            // @codeCoverageIgnoreStart
            throw CrossTableException::fileNotFoundOrNotReadable($fileName);
            // @codeCoverageIgnoreEnd
        }
        while ($row = fgetcsv($fileHandle, 0, ';')) {
            yield $row;
        }
    }

    /**
     * @param array<int, string> $headers
     * @param array<int, string> $measureColums
     * @return array<int, string>
     * @throws CrossTableException
     */
    private function getIdColums(array $headers, array $measureColums): array
    {
        $idColums = array_diff($headers, $measureColums);
        if (count($idColums) + 2 !== count($headers)) {
            throw CrossTableException::errorParsingCsvLine();
        }
        return $idColums;
    }

    /**
     * @param array<string, string> $row
     * @param array<int, string> $idCols
     * @param array<string, string> $cache
     * @return bool
     */
    private function hasSameValuesInIdColumsAsRowBefore(array $row, array $idCols, array $cache): bool
    {
        // first line?
        if (count($cache) === 0) {
            return false;
        }
        // compare values in the id columns
        /** @var string $id */
        foreach ($idCols as $id) {
            if ($cache[$id] !== $row[$id]) {
                return false;
            }
        }
        return true;
    }
}
