<?php

namespace Datapp\TableauExport;

use Datapp\TableauExport\Sorter\ListSorter;
use Datapp\TableauExport\CrossTable;
use PHPUnit\Framework\TestCase;
use Datapp\TableauExport\Sorter\NullSorter;
use Datapp\TableauExport\Sorter\AlphaSorter;
use Datapp\TableauExport\CrossTableException;

/**
 * @author Manuel Dimmler
 */
class CrossTableTest extends TestCase
{

    public function testShouldIgnoreTestColumn()
    {
        $order = [
            'orderDate',
            'orderID',
            'orderRevenue',
            'orderMargin',
            'orderValue',
        ];
        $sorter = new ListSorter($order);
        $crossTable = new CrossTable($sorter);
        $generator = $crossTable->fromFile(__DIR__ . '/data/feed.csv');

        $content = '';
        $i = 0;
        foreach ($generator as $data) {
            if ($i === 0) {
                $content .= implode(',', array_keys($data)) . PHP_EOL;
                $i++;
            }
            $content .= implode(',', $data) . PHP_EOL;
        }
        $expected = file_get_contents(__DIR__ . '/data/ignore-test.csv');
        $this->assertEquals($expected, $content);
    }

    public function testShouldReturnUnsortedColumns()
    {
        $sorter = new NullSorter();
        $crossTable = new CrossTable($sorter);
        $generator = $crossTable->fromFile(__DIR__ . '/data/feed.csv');

        $content = '';
        $i = 0;
        foreach ($generator as $data) {
            if ($i === 0) {
                $content .= implode(',', array_keys($data)) . PHP_EOL;
                $i++;
            }
            $content .= implode(',', $data) . PHP_EOL;
        }
        $expected = file_get_contents(__DIR__ . '/data/with-test-unsorted.csv');
        $this->assertEquals($expected, $content);
    }

    public function testShouldReturnAlphaSortedColumns()
    {
        $sorter = new AlphaSorter();
        $crossTable = new CrossTable($sorter);
        $generator = $crossTable->fromFile(__DIR__ . '/data/feed.csv');

        $content = '';
        $i = 0;
        foreach ($generator as $data) {
            if ($i === 0) {
                $content .= implode(',', array_keys($data)) . PHP_EOL;
                $i++;
            }
            $content .= implode(',', $data) . PHP_EOL;
        }
        $expected = file_get_contents(__DIR__ . '/data/with-test-alphasorted.csv');
        $this->assertEquals($expected, $content);
    }

    public function testShouldThrowFileNotFoundOrNotReadableException()
    {
        $this->expectException(CrossTableException::class);
        $this->expectExceptionCode(CrossTableException::FILE_NOT_FOUND_OR_NOT_READABLE);
        $crossTable = new CrossTable();
        $generator = $crossTable->fromFile(__DIR__ . '/data/nonexisiting-file.csv');
        $generator->current();
    }

    public function testShouldThrowErrorParsingCsvLineException()
    {
        $this->expectException(CrossTableException::class);
        $this->expectExceptionCode(CrossTableException::ERROR_PARSING_CSV_LINE);
        $crossTable = new CrossTable();
        $generator = $crossTable->fromFile(__DIR__ . '/data/corrupted.csv');
        $generator->current();
    }
}
