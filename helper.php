<?php


function sanitizePhone($telephone)
{
    $telephone = preg_replace('/\D/', '', $telephone);

    if (empty($telephone)) {
        return false;
    }

    $telephoneLength = strlen($telephone);
    if ($telephoneLength == 11) {
        return $telephone;
    } elseif ($telephoneLength < 11) {
        if ($telephoneLength == 10 && substr($telephone, 1) == 9) {
            return $telephone;
        } else {
            return false;
        }
    } else {
        if ($telephoneLength == 22) {
            return substr($telephone, 0, 11);
        } else {
            return false;
        }
    }
}