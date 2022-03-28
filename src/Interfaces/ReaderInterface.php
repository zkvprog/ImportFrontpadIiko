<?php

namespace zkvprog\Interfaces;

interface ReaderInterface
{
    public function read(array $filePaths) : array;
}
