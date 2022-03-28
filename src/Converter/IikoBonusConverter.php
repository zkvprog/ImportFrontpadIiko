<?php

namespace zkvprog\Converter;

use zkvprog\Formatter\TelephoneFormatter;
use zkvprog\Interfaces\ConverterInterface;

class IikoBonusConverter implements ConverterInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function convert(array $data) : array
    {
        $result = [];

        foreach ($data as $item) {
            if ($telephone = sanitizePhone($item['telephone'])) {
                $result[] = [
                    'telephone' => TelephoneFormatter::format($telephone),
                    'track' => '',
                    'number' => '',
                    'bonus' => (int) $item['bonus'],
                ];
            }
        }

        return $result;
    }
}
