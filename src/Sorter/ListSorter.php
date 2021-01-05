<?php

declare(strict_types=1);

namespace Datapp\TableauExport\Sorter;

use Datapp\TableauExport\Sorter\SorterInterface;

/**
 * @author Manuel Dimmler
 */
class ListSorter implements SorterInterface
{

    /** @var array<string> */
    private array $sortList = [];

    /**
     * @param array<string> $sortList
     */
    public function __construct(array $sortList)
    {
        $this->sortList = $sortList;
    }

    /**
     * @param array<string, string> $data
     * @return array<string, string>
     */
    public function sort(array $data): array
    {
        // ignore columns which aren't in sort list
        foreach (array_keys($data) as $key) {
            if (!in_array($key, $this->sortList)) {
                unset($data[$key]);
            }
        }
        uksort($data, function (string $a, string $b) {
            return (int) array_search($a, $this->sortList) - (int) array_search($b, $this->sortList);
        });
        return $data;
    }
}
