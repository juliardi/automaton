<?php

namespace automaton;

use Exception;

/**
 *
 */
abstract class Base
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function __get($name)
    {
        if ($name == 'config') {
            return $this->config;
        } elseif (array_key_exists($name, $this->config)) {
            return $this->config[$name];
        } else {
            return;
        }
    }

    protected function readFile($file_path)
    {
        try {
            $fhandle = fopen($file_path, 'r');
            $content = fread($fhandle, filesize($file_path));
            fclose($fhandle);

            return $content;
        } catch (Exception $exc) {
            echo $exc->getMessage().'\n';
            echo $exc->getTraceAsString();

            return false;
        }
    }

    protected function appendFile($file_path, $content)
    {
        try {
            $fhandle = fopen($file_path, 'a+');
            fwrite($fhandle, $content);
            fclose($fhandle);

            return true;
        } catch (Exception $exc) {
            echo $exc->getMessage().'\n';
            echo $exc->getTraceAsString();

            return false;
        }
    }

    protected function writeFile($file_path, $content)
    {
        try {
            $fhandle = fopen($file_path, 'w+');
            fwrite($fhandle, $content);
            fclose($fhandle);

            return file_exists($file_path);
        } catch (Exception $exc) {
            echo $exc->getMessage().'\n';
            echo $exc->getTraceAsString();

            return false;
        }
    }

    abstract public function run();
}
