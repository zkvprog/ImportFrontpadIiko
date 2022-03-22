<?php

namespace zkvprog\FrontpadReader;

class FrontpadImportFiles
{
    protected $importDir = 'import';
    protected $fileNames = [];
    protected $files = [];

    public function __construct($importDir = false, $files = false)
    {
        if (!empty($importDir)) {
            $this->setImportDir($importDir);
        }

        if (is_array($files)) {
            $this->setFiles($files);
        }
    }

    public function setImportDir($importDir)
    {
        if (is_dir($importDir)) {
            $this->importDir = $importDir;
        } else {
            new \Exception('unexpected dir');
        }
    }

    public function getImportDir()
    {
        return $this->importDir;
    }

    public function setFiles(array $fileNames)
    {
        $this->fileNames = $fileNames;
        $this->setFilesFull($this->fileNames);
    }

    public function getFiles() : array
    {
        return $this->fileNames;
    }

    public function setFilesFull($fileNames)
    {
        $this->files = preg_filter('/^/', $this->importDir . DIRECTORY_SEPARATOR, $fileNames);
    }

    public function getFilesFull() : array
    {
        return $this->files;
    }

    public function scanImportFiles()
    {
        $this->fileNames = array_diff(scandir($this->importDir), array('.', '..'));
        $this->setFilesFull($this->fileNames);
    }
}