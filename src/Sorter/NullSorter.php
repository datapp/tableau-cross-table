<?php

declare(strict_types=1);

namespace Datapp\TableauExport\Sorter;

use Datapp\TableauExport\Sorter\SorterInterface;

/**
 * @author Manuel Dimmler
 */
class NullSorter implements SorterInterface
{

    /**
     * @param array<string, string> $data
     * @return array<string, string>
     */
    public function sort(array $data): array
    {
        return $data;
    }
}
