<?php

namespace zkvprog\Import;

use zkvprog\Interfaces\SourceInterface;

class ImportFilePathsSource implements SourceInterface
{
    protected $sourceDir;
    protected $fileNames = [];
    protected $files = [];

    public function __construct($sourceDir, array $fileNames = [])
    {
        $this->setImportDir($sourceDir);

        if (!empty($fileNames)) {
            $this->setFileNames($fileNames);
        }
    }

    /**
     * @return array
     */
    public function get() : array
    {
        if (!empty($this->fileNames)) {
            return $this->getFilePaths($this->fileNames);
        } else {
            return $this->getFilePaths($this->scanFilesInSourceDir());
        }
    }

    /**
     * @param string $importDir
     */
    public function setImportDir($sourceDir)
    {
        if (is_dir($sourceDir)) {
            $this->sourceDir = $sourceDir;
        }
    }

    /**
     * @param array $fileNames
     */
    public function setFileNames(array $fileNames)
    {
        $this->fileNames = $fileNames;
    }

    /**
     * @param array $fileNames
     * @return array
     */
    public function getFilePaths(array $fileNames) : array
    {
        return preg_filter('/^/', $this->sourceDir . DIRECTORY_SEPARATOR, $fileNames);
    }

    /**
     * @return array
     */
    public function scanFilesInSourceDir() : array
    {
        return array_diff(scandir($this->sourceDir), array('.', '..'));
    }
}