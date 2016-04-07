<?php

namespace {

    class AutoLoader
    {
        protected $namespacesMap = array();

        public function addNamespace($namespace, $rootDir)
        {
            if (is_dir($rootDir)) {
                $this->namespacesMap[$namespace] = $rootDir;
                return true;
            }

            return false;
        }

        public function register()
        {
            spl_autoload_register(array($this, 'autoload'));
        }


        protected function autoload($class)
        {
            $pathParts = explode('\\', $class);
            if (is_array($pathParts)) {
                $namespace = array_pop($pathParts);
                $path = (implode('\\', $pathParts));

                if (!empty($this->namespacesMap[$path])) {
                    $filePath = $this->namespacesMap[$path] . $namespace . '.php';
                    var_dump($filePath);
                    require_once $filePath;
                    return true;
                }
            }

            return false;
        }
    }
}



