<?php

namespace zkvprog\Import;

class Import
{
    protected $reader;
    protected $writer;
    protected $converter;

    public function __construct()
    {
        return $this;
    }

    public function addReader($reader)
    {
        $this->reader = $reader;
        return $this;
    }

    public function addWriter($writer)
    {
        $this->writer = $writer;
        return $this;
    }

    public function addConverter($converter)
    {
        $this->converter = $converter;
        return $this;
    }

    public function resetConverter()
    {
        $this->converter = null;
        return $this;
    }

    public function execute()
    {
        $dataReader = $this->reader->read();

        if (isset($this->converter)) {
            $data = $this->converter->convert($dataReader);
        }

        $this->writer->write($data);
    }
}
