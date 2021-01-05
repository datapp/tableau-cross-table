# Tableau Cross Table CSV Converter

[![Software License](https://img.shields.io/github/license/datapp/tableau-cross-table?style=flat-square)](LICENSE)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/datapp/tableau-cross-table/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/datapp/tableau-cross-table/main?style=flat-square)](https://scrutinizer-ci.com/g/datapp/tableau-cross-table/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/datapp/tableau-cross-table.svg?style=flat-square)](https://scrutinizer-ci.com/g/datapp/tableau-cross-table)

If you are using [tabcmd](https://help.tableau.com/current/server/en-us/tabcmd.htm) for exporting csv data, you will receive a csv file like this:

|Measure Names|orderDate |orderID   |Measure Values|
|-------------|----------|----------|--------------|
|orderRevenue |04.01.2021|1000020524|1440.308923420|
|orderMargin  |04.01.2021|1000020524|111.195593420 |
|orderValue   |04.01.2021|1000020524|73.197238031  |
|test         |04.01.2021|1000020524|1             |
|orderRevenue |04.01.2021|1000020525|58.955987718  |
|orderMargin  |04.01.2021|1000020525|18.555457718  |
|orderValue   |04.01.2021|1000020525|9.565160950   |
|test         |04.01.2021|1000020525|2             |
|orderRevenue |04.01.2021|1000020526|116.125430353 |
|orderMargin  |04.01.2021|1000020526|31.855430353  |
|orderValue   |04.01.2021|1000020526|16.105103067  |
|test         |04.01.2021|1000020526|3             |
|orderRevenue |04.01.2021|1000020527|44.095886357  |
|orderMargin  |04.01.2021|1000020527|15.860276357  |
|orderValue   |04.01.2021|1000020527|8.056526658   |
|test         |04.01.2021|1000020527|4             |
|orderRevenue |04.01.2021|1000020528|64.442142646  |
|orderMargin  |04.01.2021|1000020528|12.258812646  |
|orderValue   |04.01.2021|1000020528|4.524036746   |
|test         |04.01.2021|1000020528|5             |
|orderRevenue |04.01.2021|1000020529|68.363421131  |
|orderMargin  |04.01.2021|1000020529|18.583421131  |
|orderValue   |04.01.2021|1000020529|5.707280904   |
|test         |04.01.2021|1000020529|6             |
|orderRevenue |04.01.2021|1000020536|113.717076058 |
|orderMargin  |04.01.2021|1000020536|26.255256058  |
|orderValue   |04.01.2021|1000020536|12.938223279  |
|test         |04.01.2021|1000020536|7             |
|orderRevenue |04.01.2021|1000020537|139.834270494 |
|orderMargin  |04.01.2021|1000020537|66.834270494  |
|orderValue   |04.01.2021|1000020537|44.526677630  |
|test         |04.01.2021|1000020537|8             |
|orderRevenue |04.01.2021|1000020540|68.918319029  |
|orderMargin  |04.01.2021|1000020540|20.426099029  |
|orderValue   |04.01.2021|1000020540|9.571335402   |
|test         |04.01.2021|1000020540|9             |

But the [Tableau Desktop](https://www.tableau.com/products/desktop) shows something like that:

|orderDate |orderID   |orderRevenue  |orderMargin  |orderValue  |test|
|----------|----------|--------------|-------------|------------|----|
|04.01.2021|1000020524|1440.308923420|111.195593420|73.197238031|1   |
|04.01.2021|1000020525|58.955987718  |18.555457718 |9.565160950 |2   |
|04.01.2021|1000020526|116.125430353 |31.855430353 |16.105103067|3   |
|04.01.2021|1000020527|44.095886357  |15.860276357 |8.056526658 |4   |
|04.01.2021|1000020528|64.442142646  |12.258812646 |4.524036746 |5   |
|04.01.2021|1000020529|68.363421131  |18.583421131 |5.707280904 |6   |
|04.01.2021|1000020536|113.717076058 |26.255256058 |12.938223279|7   |
|04.01.2021|1000020537|139.834270494 |66.834270494 |44.526677630|8   |
|04.01.2021|1000020540|68.918319029  |20.426099029 |9.571335402 |9   |

With this library you can convert the csv output file to the cross table version.


## Example

```php
<?php

use Datapp\TableauExport\CrossTable;
use Datapp\TableauExport\Sorter\NullSorter;

$crosstable = new CrossTable(new NullSorter());
$dataGenerator = $crosstable->fromFile(__DIR__ . '/your-file.csv');

foreach($dataGenerator as $data) {
    echo implode(';', $data) . PHP_EOL;
}
```

If you prefer a special sort order of the columns, please take a look to the classes:
* Datapp\TableauExport\Sorter\AlphaSorter
* Datapp\TableauExport\Sorter\ListSorter

or create an own implementation with the Datapp\TableauExport\Sorter\SorterInterface