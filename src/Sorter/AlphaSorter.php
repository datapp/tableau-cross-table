<?php

declare(strict_types=1);

namespace Datapp\TableauExport\Sorter;

use Datapp\TableauExport\Sorter\SorterInterface;

/**
 * @author Manuel Dimmler
 */
class AlphaSorter implements SorterInterface
{

    const ASC = 1;
    const DESC = -1;

    /** @var int */
    private $sorting;

    public function __construct(int $sorting = self::ASC)
    {
        $this->sorting = ((int) ($sorting >= 0)) * 2 - 1;
    }

    /**
     * @param array<string, string> $data
     * @return array<string, string>
     */
    public function sort(array $data): array
    {
        uksort($data, function (string $a, string $b): int {
            return strcmp($a, $b) * $this->sorting;
        });
        return $data;
    }
}
