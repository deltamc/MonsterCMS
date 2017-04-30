<?php

defined('MCMS_ACCESS') or die('No direct script access.');

/**
 * Класс автозагрузки классов
 */
class Autoload
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Register loader with SPL autoloader stack.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * @param string $base_dir A base directory for class files in the     *
     * namespace.     *
     *
     *
     * %class:uf% - class name first letter uppercase
     * %class:upper% - class name uppercase
     * %class:lower% - class name in lowercase register
     * %class% - class name
     *
     * Monstercms\Core - /core/%class:lower%.class.php
     * Monstercms\Module\$1 - /module/$1/%class:lower%.class.php
     *
     *
     *
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {



        // normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // normalize the base directory with a trailing separator
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR);

        // initialize the namespace prefix array
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        // retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }



    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix The namespace prefix.
     * @param string $class The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $class)
    {

        foreach ($this->prefixes as $ns => $base_dir)
        {
            $ns_pattern = str_replace('*', '(.*)', $ns);
            $ns_pattern = str_replace('\\', '\\\\', $ns_pattern);
            $ns_pattern = trim($ns_pattern, '\\');




            if (preg_match('~'.$ns_pattern.'~',$prefix))
            {

                foreach($base_dir as $dir)
                {
                    $dir = str_replace('%class:lower%', strtolower($class), $dir);
                    $dir = str_replace('%class:upper%', strtoupper($class), $dir);
                    $dir = str_replace('%class:uf%',    ucfirst($class),    $dir);
                    $dir = str_replace('%class%',       $class,             $dir);

                    preg_match_all('~'.$ns_pattern.'~',$prefix, $finds);

                    for($i=0, $s = count($finds); $i < $s; $i++)
                    {
                        $dir = str_replace('$'.$i, $finds[$i][0], $dir);
                    }

                    $this->requireFile($dir);
                    return $dir;
                }


            }
            //$dir_pattern = preg_replace('~\*~', '(*.)', $ns);


        }
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @param string $file The file to require.
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        //print $file .'<br />';
        if (file_exists($file)) {

            require_once $file;
            return true;
        }
        return false;
    }

    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class_full)
    {
        /* 1 отделить spacename от class*/
        $spacename = '';

        $class_full_parts = explode('\\',$class_full);
        $class  = $class_full_parts[count($class_full_parts) -1];

        $class_len = strlen($class);
        $class_full_len = strlen($class_full);
        $spacename = substr($class_full, 0, $class_full_len - $class_len);
        $spacename = trim($spacename, '\\');

        $this->loadMappedFile($spacename, $class);



        return false;
    }
}



