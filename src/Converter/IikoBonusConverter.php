<?php

namespace zkvprog\Converter;

use zkvprog\Formatter\TelephoneFormatter;

class IikoBonusConverter
{
    public function convert($data) : array
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