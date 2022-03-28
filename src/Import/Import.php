<?php

namespace zkvprog\Import;

use zkvprog\Interfaces\ReaderInterface;
use zkvprog\Interfaces\SourceInterface;
use zkvprog\Interfaces\WriterInterface;

class Import
{
    protected $source;
    protected $reader;
    protected $writer;
    protected $converter;

    /**
     * Import constructor.
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * @param $source
     * @return $this
     */
    public function addSource(SourceInterface $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @param $reader
     * @return $this
     */
    public function addReader(ReaderInterface $reader)
    {
        $this->reader = $reader;
        return $this;
    }

    /**
     * @param $writer
     * @return $this
     */
    public function addWriter(WriterInterface $writer)
    {
        $this->writer = $writer;
        return $this;
    }

    /**
     * @param $converter
     * @return $this
     */
    public function addConverter($converter)
    {
        $this->converter = $converter;
        return $this;
    }

    /**
     * @return $this
     */
    public function resetConverter()
    {
        $this->converter = null;
        return $this;
    }

    /**
     *
     */
    public function execute()
    {
        $dataReader = $this->reader->read($this->source->get());

        if (isset($this->converter)) {
            $data = $this->converter->convert($dataReader);
        }

        $this->writer->write($data);
    }
}
