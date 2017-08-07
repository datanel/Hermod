<?php

namespace AppBundle\Util;

class Csv
{
    /**
     * Parses the given csv content and return associative arrays indexed by the headers.
     * The first line of the content will be used as the headers
     *
     * @param $csvContent
     * @param array $mandatoryHeaders an array of header that MUST be on the first line
     *
     * @return array
     *
     * @throws \InvalidArgumentException when one of the specified mandatory headers is missing from the csv file
     */
    public static function parse($csvContent, array $mandatoryHeaders = [])
    {
        $rows = array_map(function ($row) {
            return array_map('trim', str_getcsv($row, ';'));
        }, preg_split('/\r\n|\n|\r/', $csvContent));
        $headers = array_shift($rows);
        $missingHeaders = array_diff($mandatoryHeaders, array_intersect($headers, $mandatoryHeaders));

        if ($missingHeaders) {
            throw new \InvalidArgumentException(sprintf(
                "missing headers: '%s'",
                implode("', '", $missingHeaders)
            ));
        }
        $csv = array();
        foreach ($rows as $row) {
            // skip empty rows
            if (!count($row) || empty($row[0])) {
                continue;
            }
            $row = array_pad($row, count($headers), ''); // handle missing column by padding
            $csv[] = array_combine($headers, $row);
        }

        return $csv;
    }
}
