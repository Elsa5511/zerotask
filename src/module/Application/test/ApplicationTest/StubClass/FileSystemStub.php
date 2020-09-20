<?php

namespace ApplicationTest\StubClass;

class FileSystemStub
{
    /**
     * Get all file names for a given directory
     * 
     * @param  string $path source folder
     * @return array
     */
    public static function getFileNames($path)
    {
        return array();
    }

    public static function recursiveCopyInto($src, $target) 
    {
    }

    public static function recursiveCopy($src, $dest) 
    {
    }

    public static function copyFileIntoFile($src, $dest)
    {
    }

    public static function unzipToFolder($zipFilePath, $unzipPath) 
    {
        return true;
    }

    public static function createFolder($path, $parentPath = '') 
    {
        return true;
    }

    public static function createFolderOrFail($path, $parentPath = '') 
    {
    }

    public static function deleteFolder($folder) 
    {
    }

    private static function isDeletable($folder) 
    {
        return true;
    }

    public static function getFolderNameFromPath($path) 
    {
        return "path";
    }

    public static function cleanFolder($folder) 
    {
        return true;
    }

    public static function is_dir_empty($dir) 
    {
        return true;
    }

    public function isValidFolderStructure($path, $requiredFiles, $requiredFolders = array())
    {      
        return true;
    }

}