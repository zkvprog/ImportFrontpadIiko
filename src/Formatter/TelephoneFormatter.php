<?php

namespace zkvprog\Formatter;

use \libphonenumber\PhoneNumberUtil;
use \libphonenumber\NumberParseException;
use \libphonenumber\PhoneNumberFormat;

class TelephoneFormatter
{
    public static function format($telephone)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $numberProto = $phoneUtil->parse($telephone, "RU");
        } catch (NumberParseException $e) {
            var_dump($e);
        }

        return $phoneUtil->format($numberProto, PhoneNumberFormat::E164);
    }
}
