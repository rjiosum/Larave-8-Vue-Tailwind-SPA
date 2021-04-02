<?php

namespace App\Traits;


use Exception;

trait Writable
{
    /**
     * @param $path
     * @param $filename
     * @param $data
     * @return bool|Exception
     */
    public function writeFile(string $path, string $filename, string $data)
    {
        $file = $path . DIRECTORY_SEPARATOR . $filename;
        $handle = fopen($file, "w");
        if (!$handle) {
            return new Exception('Failed to open file.');
        }
        try {
            if (flock($handle, LOCK_EX)) {
                fwrite($handle, $data);
                fflush($handle);
                flock($handle, LOCK_UN);
            } else {
                return new Exception('Failed to obtain lock.');
            }
        } finally {
            fclose($handle);
        }
        return true;
    }
}