<?php

declare(strict_types=1);

namespace Datapp\TableauExport\Sorter;

/**
 * @author Manuel Dimmler
 */
interface SorterInterface
{

    /**
     * @param array<string, string> $data
     * @return array<string, string>
     */
    public function sort(array $data): array;
}
