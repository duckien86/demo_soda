<?php
    /**
     * CFile provides common methods to manipulate filesystem objects (files and
     * directories) from Yii Framework (http://www.yiiframework.com).
     *
     * Please use CFileHelper class to access CFile functionality if not using Yii.
     *
     * @version   1.0
     *
     * @author    idle sign <idlesign@yandex.ru>
     * @link      http://www.yiiframework.com/extension/cfile/
     * @copyright Copyright &copy; 2009-2013 Igor 'idle sign' Starikov
     * @license   LICENSE
     */
    /* Exception type raised by CFile. */

    class CFileException extends Exception
    {
    }


    /**
     * Base CFile class built to work with Yii.
     */
    class CFile extends CApplicationComponent
    {
        /**
         * @var array object instances array with key set to $_filepath
         */
        private static $_instances = array();
        /**
         * @var string filesystem object path submitted by user
         */
        private $_filepath;
        /**
         * @var string real filesystem object path figured by script on the basis of $_filepath
         */
        private $_realpath;
        /**
         * @var boolean 'True' if filesystem object described by $_realpath exists
         */
        private $_exists;
        /**
         * @var boolean 'True' if filesystem object described by $_realpath is a regular file
         */
        private $_is_file = false;
        /**
         * @var boolean 'True' if filesystem object described by $_realpath is a directory
         */
        private $_is_dir = false;
        /**
         * @var boolean 'True' if file described by $_realpath is uploaded
         */
        private $_is_uploaded = false;
        /**
         * @var boolean 'True' if filesystem object described by $_realpath is readable
         */
        private $_readable;
        /**
         * @var boolean 'True' if filesystem object described by $_realpath writeable
         */
        private $_writeable;
        /**
         * @var string basename of the file (e.g.: 'myfile.htm' for '/var/www/htdocs/files/myfile.htm')
         */
        private $_basename;
        /**
         * @var string name of the file (e.g.: 'myfile' for '/var/www/htdocs/files/myfile.htm')
         */
        private $_filename;
        /**
         * @var string directory name of the filesystem object
         * (e.g.: '/var/www/htdocs/files' for '/var/www/htdocs/files/myfile.htm')
         */
        private $_dirname;
        /**
         * @var string file extension(e.g.: 'htm' for '/var/www/htdocs/files/myfile.htm')
         */
        private $_extension;
        /**
         * @var string file extension(e.g.: 'text/html' for '/var/www/htdocs/files/myfile.htm')
         */
        private $_mime_type;
        /**
         * @var integer the time the filesystem object was last modified (Unix timestamp e.g.: '1213760802')
         */
        private $_time_modified;
        /**
         * @var string filesystem object size formatted (e.g.: '70.4 KB') or in bytes (e.g.: '72081')
         * see {@link getSize} parameters
         */
        private $_size;
        /**
         * @var boolean filesystem object has contents flag
         */
        private $_is_empty;
        /**
         * @var int|string filesystem object owner name (e.g.: 'idle') or in ID (e.g.: '1000')
         *     see {@link getOwner} parameters
         */
        private $_owner;
        /**
         * @var int|string filesystem object group name (e.g.: 'apache') or in ID (e.g.: '127')
         *     see {@link getGroup} parameters
         */
        private $_group;
        /**
         * @var string filesystem object permissions (considered octal e.g.: '0755')
         */
        private $_permissions;
        /**
         * @var resource file pointer resource (for {@link open} & {@link close})
         */
        private $_handle = null;
        /**
         * @var CUploadedFile object instance
         */
        private $_uploaded_inst = null;


        /**
         * Returns the instance of CFile for the specified file.
         *
         * @param string $filepath   Path to file specified by user.
         * @param string $class_name Class name to spawn object for.
         *
         * @return object CFile instance
         * @throws CFileException
         */
        public static function getInstance($filepath, $class_name = __CLASS__)
        {
            if ($class_name != __CLASS__ && !is_subclass_of($class_name, __CLASS__)) {
                throw new CFileException('Unable to spawn CFile object from `' . $class_name . '` class');
            }
            if (!array_key_exists($filepath, self::$_instances)) {
                self::$_instances[$filepath] = new $class_name($filepath);
            }

            return self::$_instances[$filepath];
        }

        /**
         * Logs a message.
         *
         * @param string $message Message to be logged
         * @param string $level   Level of the message (e.g. 'trace', 'warning', 'error', 'info',
         *                        see CLogger constants definitions)
         */
        protected function addLog($message, $level = 'info')
        {
            Yii::log($message . ' (obj: ' . $this->getRealPath() . ')', $level, 'ext.file');
        }

        /**
         * Returns filepath for a given alias.
         *
         * @param string $alias
         *
         * @return bool|string
         */
        protected function getPathOfAlias($alias)
        {
            return Yii::getPathOfAlias($alias);
        }

        /**
         * Formats a given number into a given format and returns it.
         *
         * See {@link CNumberFormatter}.
         *
         * @param number $number
         * @param string $format
         *
         * @return string
         */
        protected function formatNumber($number, $format)
        {
            return Yii::app()->numberFormatter->format($format, $number);
        }


        /**
         * Basic CFile method. Sets CFile object to work with specified filesystem object.
         * Essentially path supplied by user is resolved into real path (see {@link getRealPath}), all the other property
         * getting methods should use that real path.
         * Uploaded files are supported through {@link CUploadedFile} Yii class.
         * Path aliases are supported through {@link getPathOfAlias} Yii method.
         *
         * @param string $filepath Path to the file specified by user, if not set exception is raised
         * @param bool   $greedy   If True file properties (such as 'Size', 'Owner', 'Permission', etc.) would be autoloaded
         *
         * @return object CFile instance for the specified filesystem object
         * @throws CFileException
         */
        public function set($filepath, $greedy = false)
        {
            if (trim($filepath) != '') {

                $uploaded = null;

                if (strpos($filepath, '\\') === false && strpos($filepath, '/') === false) {
                    $uploaded = CUploadedFile::getInstanceByName($filepath);
                    if ($uploaded) {
                        $filepath = $uploaded->getTempName();
                        $this->addLog('File "' . $filepath . '" is identified as uploaded', 'trace');
                    } elseif ($aliased_path = $this->getPathOfAlias($filepath)) {
                        $this->addLog('The string supplied to set() - "' . $filepath . '" is identified as the alias to "' . $aliased_path . '"', 'trace');
                        $filepath = $aliased_path;
                    }
                }

                clearstatcache();
                $realPath        = $this->realPath($filepath);
                $inst            = $this->getInstance($realPath);
                $inst->_filepath = $filepath;
                $inst->_realpath = $realPath;

                if ($inst->exists()) {
                    $inst->_uploaded_inst = $uploaded;

                    $inst->pathInfo();
                    $inst->readable;
                    $inst->writeable;

                    if ($greedy) {
                        $inst->isempty;
                        $inst->size;
                        $inst->owner;
                        $inst->group;
                        $inst->permissions;
                        $inst->timeModified;
                        if ($inst->isFile) {
                            $inst->mimeType;
                        }
                    }
                }

                return $inst;
            }

            throw new CFileException('Path to filesystem object is not specified within set() method');
        }

        /**
         * Populates basic CFile properties (i.e. 'Dirname', 'Basename', etc.) using values resolved by pathinfo()
         * php function.
         * Detects filesystem object type (file, directory).
         */
        private function pathInfo()
        {

            if (is_file($this->_realpath)) {
                $this->_is_file = true;
            } elseif (is_dir($this->_realpath)) {
                $this->_is_dir = true;
            }

            if ($this->_uploaded_inst) {
                $this->_is_uploaded = true;
            }

            $pathinfo = pathinfo($this->_is_uploaded ? $this->_uploaded_inst->getName() : $this->_realpath);

            $this->_dirname  = $pathinfo['dirname'];
            $this->_basename = $pathinfo['basename'];

            // PHP version < 5.2 workaround
            if (!isset($pathinfo['filename'])) {
                $this->_filename = substr($pathinfo['basename'], 0, strrpos($pathinfo['basename'], '.'));
            } else {
                $this->_filename = $pathinfo['filename'];
            }

            if (array_key_exists('extension', $pathinfo)) {
                $this->_extension = $pathinfo['extension'];
            } else {
                $this->_extension = null;
            }
        }

        /**
         * Returns real filesystem object path figured by script (see {@link realPath}) on the basis of user
         * supplied $_filepath.
         * If $_realpath property is set, returned value is read from that property.
         *
         * @param string $dir_separator Directory separator char (depends upon OS)
         *
         * @return string Real file path
         */
        public function getRealPath($dir_separator = DIRECTORY_SEPARATOR)
        {
            if (!isset($this->_realpath)) {
                $this->_realpath = $this->realPath($this->_filepath, $dir_separator);
            }

            return $this->_realpath;
        }

        /**
         * Base real filesystem object path resolving method.
         * Returns real path resolved from the supplied path.
         *
         * @param string $supplied_path Path from which real filesystem object path should be resolved
         * @param string $dir_separator Directory separator char (depends upon OS)
         *
         * @return string Real file path
         */
        private function realPath($supplied_path, $dir_separator = DIRECTORY_SEPARATOR)
        {
            $current_path = $supplied_path;

            if (!strlen($current_path)) {
                return $dir_separator;
            }

            $win_drive = '';

            // Windows OS path type detection
            if (!strncasecmp(PHP_OS, 'win', 3)) {
                $current_path = preg_replace('/[\\\\\/]/', $dir_separator, $current_path);
                if (preg_match('/([a-zA-Z]\:)(.*)/', $current_path, $matches)) {
                    $win_drive    = $matches[1];
                    $current_path = $matches[2];
                } else {
                    $workingDir = getcwd();
                    $win_drive  = substr($workingDir, 0, 2);
                    if ($current_path{0} !== $dir_separator{0}) {
                        $current_path = substr($workingDir, 3) . $dir_separator . $current_path;
                    }
                }
            } elseif ($current_path{0} !== $dir_separator) {
                $current_path = getcwd() . $dir_separator . $current_path;
            }

            $paths = array();
            foreach (explode($dir_separator, $current_path) as $path) {
                if (strlen($path) && $path !== '.') {
                    if ($path == '..') {
                        array_pop($paths);
                    } else {
                        $paths[] = $path;
                    }
                }
            }

            $realpath = $win_drive . $dir_separator . implode($dir_separator, $paths);

            if ($current_path != $supplied_path) {
                $this->addLog('Path "' . $supplied_path . '" resolved into "' . $realpath . '"', 'trace');
            }

            return $realpath;
        }

        /**
         * Tests current filesystem object existence and returns boolean (see {@link exists}).
         * If $_exists property is set, returned value is read from that property.
         *
         * @return boolean 'True' if file exists, otherwise 'False'
         */
        public function getExists()
        {
            if (!isset($this->_exists)) {
                $this->exists();
            }

            return $this->_exists;
        }

        /**
         * Returns filesystem object type for the current file (see {@link pathInfo}).
         * Tells whether filesystem object is a regular file.
         *
         * @return boolean 'True' if filesystem object is a regular file, otherwise 'False'
         */
        public function getIsFile()
        {
            return $this->_is_file;
        }

        /**
         * Returns filesystem object type for the current file (see {@link pathInfo}).
         * Tells whether filesystem object is a directory.
         *
         * @return boolean 'True' if filesystem object is a directory, otherwise 'False'
         */
        public function getIsDir()
        {
            return $this->_is_dir;
        }

        /**
         * Tells whether file is uploaded through a web form.
         *
         * @return boolean 'True' if file is uploaded, otherwise 'False'
         */
        public function getIsUploaded()
        {
            return $this->_is_uploaded;
        }

        /**
         * Returns filesystem object has-contents flag.
         * Directory considered empty if it doesn't contain descendants.
         * File considered empty if its size is 0 bytes.
         *
         * @return boolean 'True' if file is a directory, otherwise 'False'
         */
        public function getIsEmpty()
        {
            if (!isset($this->_is_empty)) {
                if (($this->getIsFile() && $this->getSize(false) == 0) || (!$this->getIsFile() && count($this->dirContents($this->_realpath)) == 0)) {
                    $this->_is_empty = true;
                } else {
                    $this->_is_empty = false;
                }
            }

            return $this->_is_empty;
        }

        /**
         * Tests whether the current filesystem object is readable and returns boolean.
         * If $_readable property is set, returned value is read from that property.
         *
         * @return boolean 'True' if filesystem object is readable, otherwise 'False'
         */
        public function getReadable()
        {
            if (!isset($this->_readable)) {
                $this->_readable = is_readable($this->_realpath);
            }

            return $this->_readable;
        }

        /**
         * Tests whether the current filesystem object is readable and returns boolean.
         * If $_writeable property is set, returned value is read from that property.
         *
         * @return boolean 'True' if filesystem object is writeable, otherwise 'False'
         */
        public function getWriteable()
        {
            if (!isset($this->_writeable)) {
                $this->_writeable = is_writable($this->_realpath);
            }

            return $this->_writeable;
        }

        /**
         * Base filesystem object existence resolving method.
         * Tests current filesystem object existence and returns boolean.
         *
         * @return boolean 'True' if filesystem object exists, otherwise 'False'
         */
        private function exists()
        {
            $this->addLog('Filesystem object availability test: ' . $this->_realpath, 'trace');

            if (file_exists($this->_realpath)) {
                $this->_exists = true;
            } else {
                $this->_exists = false;
            }

            if ($this->_exists) {
                return true;
            }

            $this->addLog('Filesystem object not found');

            return false;
        }

        /**
         * Creates empty file if the current file doesn't exist.
         *
         * @return CFile|bool Updated the current CFile object on success, 'False' on fail.
         */
        public function create()
        {
            if (!$this->getExists()) {
                if ($this->open('w')) {
                    $this->close();

                    return $this->set($this->_realpath);
                }

                $this->addLog('Unable to create empty file');

                return false;
            }

            $this->addLog('File creation failed. File already exists');

            return false;
        }

        /**
         * Creates empty directory defined either through {@link set} or through the $directory parameter.
         *
         * @param int|string  $permissions Access permissions for the directory
         * @param null|string $directory   Parameter used to create directory other than supplied by {@link set} method
         *                                 of the CFile
         *
         * @return bool|CFile Updated the current CFile object on success, 'False' on fail.
         */
        public function createDir($permissions = 0754, $directory = null)
        {
            if ($directory === null) {
                $dir = $this->_realpath;
            } else {
                $dir = $directory;
            }

            if (@mkdir($dir, $permissions, true)) {
                if (!$directory) {
                    return $this->set($dir);
                } else {
                    return true;
                }
            }

            $this->addLog('Unable to create empty directory "' . $dir . '"');

            return false;
        }

        /**
         * Opens (if not already opened) the current file using certain mode.
         * See fopen() php function for more info.
         *
         * For now used only internally.
         *
         * @param string $mode Type of access required to the stream
         *
         * @return bool|CFile Current CFile object on success, 'False' on fail.
         */
        private function open($mode)
        {
            if ($this->_handle === null) {
                if ($this->_handle = fopen($this->_realpath, $mode)) {
                    return $this;
                }

                $this->addLog('Unable to open file using mode "' . $mode . '"');

                return false;
            }

            return $this;
        }

        /**
         * Closes (if opened) the current file pointer.
         * See fclose() php function for more info.
         *
         * For now used only internally.
         */
        private function close()
        {
            if ($this->_handle !== null) {
                fclose($this->_handle);
                $this->_handle = null;
            }
        }

        /**
         * Returns owner of current filesystem object (UNIX systems).
         * Returned value depends upon $getName parameter value.
         * If $_owner property is set, returned value is read from that property.
         *
         * @param boolean $get_name Defaults to 'True', meaning that owner name instead of ID should be returned.
         *
         * @return int|string|bool Owner name, or ID if $getName set to 'False'
         */
        public function getOwner($get_name = true)
        {
            if (!isset($this->_owner)) {
                $this->_owner = $this->getExists() ? fileowner($this->_realpath) : null;
            }

            if (is_int($this->_owner) && function_exists('posix_getpwuid') && $get_name == true) {
                $this->_owner = posix_getpwuid($this->_owner);
                $this->_owner = $this->_owner['name'];
            }

            return $this->_owner;
        }

        /**
         * Returns group of current filesystem object (UNIX systems).
         * Returned value depends upon $getName parameter value.
         * If $_group property is set, returned value is read from that property.
         *
         * @param boolean $get_name Defaults to 'True', meaning that group name instead of ID should be returned.
         *
         * @return int|string|bool Group name, or ID if $getName set to 'False'
         */
        public function getGroup($get_name = true)
        {
            if (!isset($this->_group)) {
                $this->_group = $this->getExists() ? filegroup($this->_realpath) : null;
            }

            if (is_int($this->_group) && function_exists('posix_getgrgid') && $get_name == true) {
                $this->_group = posix_getgrgid($this->_group);
                $this->_group = $this->_group['name'];
            }

            return $this->_group;
        }

        /**
         * Returns permissions of current filesystem object (UNIX systems).
         * If $_permissions property is set, returned value is read from that property.
         *
         * @return string Filesystem object permissions in octal format (i.e. '0755')
         */
        public function getPermissions()
        {
            if (!isset($this->_permissions)) {
                $this->_permissions = $this->getExists() ? substr(sprintf('%o', fileperms($this->_realpath)), -4) : null;
            }

            return $this->_permissions;
        }

        /**
         * Returns size of current filesystem object.
         * Returned value depends upon $format parameter value.
         * If $_size property is set, returned value is read from that property.
         * Uses {@link dirSize} method for directory size calculation.
         *
         * @param string|bool $format Number format or 'False'
         *
         * @return string|int Filesystem object size formatted (e.g.: '70.4 KB') or in bytes (e.g.: '72081') if $format
         *     set to 'False'
         */
        public function getSize($format = '0.00')
        {

            if (!isset($this->_size)) {
                if ($this->getIsFile()) {
                    $this->_size = $this->getExists() ? sprintf('%u', filesize($this->_realpath)) : null;
                } else {
                    $this->_size = $this->getExists() ? sprintf('%u', $this->dirSize()) : null;
                }
            }
            $size = $this->_size;

            if ($format !== false) {
                $size = $this->formatFileSize($this->_size, $format);
            }

            return $size;
        }

        /**
         * Calculates the current directory size recursively fetching sizes of all descendant files.
         *
         * This method is used internally and only for folders.
         * See {@link getSize} method params for detailed information.
         *
         * @return int
         */
        private function dirSize()
        {
            $size = 0;
            foreach ($this->dirContents($this->_realpath, true) as $item) {
                if (is_file($item)) {
                    $size += (int)sprintf('%u', filesize($item));
                }
            }

            return $size;
        }

        /**
         * Base filesystem object size format method.
         * Converts file size in bytes into human readable format (i.e. '70.4 KB')
         *
         * @param integer $bytes  Filesystem object size in bytes
         * @param string  $format Number format (see {@link CNumberFormatter})
         *
         * @return string Filesystem object size in human readable format
         */
        private function formatFileSize($bytes, $format = '0.00')
        {
            $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');

            $bytes = max($bytes, 0);
            $expo  = floor(($bytes ? log($bytes) : 0) / log(1024));
            $expo  = min($expo, count($units) - 1);

            $bytes /= pow(1024, $expo);

            return $this->formatNumber($bytes, $format) . ' ' . $units[$expo];
        }

        /**
         * Returns the current file last modified time.
         * Returned Unix timestamp could be passed to php date() function.
         *
         * @return integer Last modified time Unix timestamp (e.g.: '1213760802')
         */
        public function getTimeModified()
        {
            if (empty($this->_time_modified)) {
                $this->_time_modified = $this->getExists() ? filemtime($this->_realpath) : null;
            }

            return $this->_time_modified;
        }

        /**
         * Returns the current file extension from $_extension property set by {@link pathInfo}
         * (e.g.: 'htm' for '/var/www/htdocs/files/myfile.htm').
         *
         * @return string Current file extension without the leading dot
         */
        public function getExtension()
        {
            return $this->_extension;
        }

        /**
         * Returns the current file basename (file name plus extension) from $_basename property set by {@link pathInfo}
         * (e.g.: 'myfile.htm' for '/var/www/htdocs/files/myfile.htm').
         *
         * @return string Current file basename
         */
        public function getBasename()
        {
            return $this->_basename;
        }

        /**
         * Returns the current file name (without extension) from $_filename property set by {@link pathInfo}
         * (e.g.: 'myfile' for '/var/www/htdocs/files/myfile.htm')
         *
         * @return string Current file name
         */
        public function getFilename()
        {
            return $this->_filename;
        }

        /**
         * Returns the current file directory name (without final slash) from $_dirname property set by {@link pathInfo}
         * (e.g.: '/var/www/htdocs/files' for '/var/www/htdocs/files/myfile.htm')
         *
         * @return string Current file directory name
         */
        public function getDirname()
        {
            return $this->_dirname;
        }

        /**
         * Returns the current filesystem object contents.
         * Reads data from filesystem object if it is a regular file.
         * List files and directories inside the specified path if filesystem object is a directory.
         *
         * @param boolean $recursive If True method would return all directory descendants.
         * @param string  $filter    Filter to be applied to all directory descendants. Could be a string, or an array
         *                           of strings (perl regexp are supported, if started with '~').
         *
         * @return string|array|bool Data read for files, or directory contents names array. False on fail.
         */
        public function getContents($recursive = false, $filter = null)
        {
            if ($this->getReadable()) {
                if ($this->getIsFile()) {
                    if ($contents = file_get_contents($this->_realpath)) {
                        return $contents;
                    }
                } else {
                    return $this->dirContents($this->_realpath, $recursive, $filter);
                }
            }
            $this->addLog('Unable to get filesystem object contents' . ($filter !== null ? ' *using supplied filter*' : ''));

            return false;
        }

        /**
         * Gets directory contents (descendant files and folders).
         *
         * @param null|string $directory Initial directory to get descendants for
         * @param bool        $recursive If 'True' method would return all descendants recursively, otherwise just immediate
         *                               descendants
         * @param null|string $filter    Filter to be applied to all directory descendants. Could be a string, or an array
         *                               of strings (perl regexp are supported, if started with '~').
         *                               See {@link filterPassed} method for further information on filters.
         *
         * @return array Array of descendants filepaths
         * @throws CFileException
         */
        private function dirContents($directory = null, $recursive = false, $filter = null)
        {
            $descendants = array();

            if (!$directory) {
                $directory = $this->_realpath;
            }

            if ($filter !== null) {
                if (is_string($filter)) {
                    $filter = array($filter);
                }
            }

            if ($contents = @scandir($directory . DIRECTORY_SEPARATOR)) {
                foreach ($contents as $key => $item) {
                    $contents[$key] = $directory . DIRECTORY_SEPARATOR . $item;
                    if (!in_array($item, array('.', '..'))) {
                        if ($this->filterPassed($contents[$key], $filter)) {
                            $descendants[] = $contents[$key];
                        }

                        if (is_dir($contents[$key]) && $recursive) {
                            $descendants = array_merge($descendants, $this->dirContents($contents[$key], $recursive, $filter));
                        }
                    }
                }
            } else {
                throw new CFileException('Unable to get directory contents for "' . $directory . DIRECTORY_SEPARATOR . '"');
            }

            return $descendants;
        }

        /**
         * Applies an array of filter rules to the string representing filepath.
         * Used internally by {@link dirContents} method.
         *
         * @param string $str    String representing filepath to be filtered
         * @param array  $filter An array of filter rules, where each rule is a string, supposing that the string
         *                       starting with '~' is a regular expression. Any other string treated as an extension part of the given
         *                       filepath (e.g.: file extension)
         *
         * @return boolean Returns 'True' if the supplied string matched one of the filter rules.
         */
        private function filterPassed($str, $filter)
        {

            if ($filter !== null) {
                foreach ($filter as $rule) {
                    if ($rule[0] != '~') {
                        $passed = (bool)substr_count($str, $rule);
                    } else {
                        $passed = (bool)preg_match($rule, $str);
                    }

                    if (!$passed) {
                        return false;
                    }
                }
            }

            return true;
        }

        /**
         * Writes contents (data) into the current file.
         * This method works only for files.
         *
         * @param string  $contents   Contents to be written
         * @param boolean $autocreate If 'True' file will be created automatically
         * @param integer $flags      Flags for file_put_contents(). E.g.: FILE_APPEND to append data to file instead
         *                            of overwriting.
         *
         * @return CFile|bool Current CFile object on success, 'False' on fail.
         */
        public function setContents($contents = null, $autocreate = true, $flags = 0)
        {
            if ($this->getIsFile()) {
                if ($autocreate && !$this->getExists()) {
                    $this->create();
                }

                if ($this->getWriteable() && file_put_contents($this->_realpath, $contents, $flags) !== false) {
                    return $this;
                }

                $this->addLog('Unable to put file contents');

                return false;
            } else {
                $this->addLog('setContents() method is available only for files', 'warning');

                return false;
            }
        }

        /**
         * Returns filesystem object filepath.
         *
         * @return string
         */
        public function __toString()
        {
            return $this->_realpath;
        }

        /**
         * Sets basename for the current file.
         * Lazy wrapper for {@link rename}.
         * This method works only for files.
         *
         * @param null|string $basename New file basename (e.g.: 'mynewfile.txt')
         *
         * @return bool|CFile Current CFile object on success, 'False' on fail.
         */
        public function setBasename($basename = null)
        {
            if ($this->getIsFile()) {
                if ($this->getIsUploaded()) {
                    $this->addLog('setBasename() is unavailable for uploaded files. Please copy/move uploaded file from temporary directory', 'warning');

                    return false;
                }

                if ($this->getWriteable() && $basename && $this->rename($basename)) {
                    return $this;
                }

                $this->addLog('Unable to set file basename "' . $basename . '"');

                return false;
            }

            $this->addLog('setBasename() is available only for files', 'warning');

            return false;
        }

        /**
         * Sets the current file name.
         * Lazy wrapper for {@link rename}.
         * This method works only for files.
         *
         * @param null|string $filename New file name (e.g.: 'mynewfile')
         *
         * @return bool|CFile Current CFile object on success, 'False' on fail.
         */
        public function setFilename($filename = null)
        {
            if ($this->getIsFile()) {
                if ($this->getIsUploaded()) {
                    $this->addLog('setFilename() is unavailable for uploaded files. Please copy/move uploaded file from temporary directory', 'warning');

                    return false;
                }

                if ($this->getWriteable() && $filename && $this->rename(str_replace($this->getFilename(), $filename, $this->getBasename()))) {
                    return $this;
                }

                $this->addLog('Unable to set file name "' . $filename . '"');

                return false;
            }

            $this->addLog('setFilename() method is available only for files', 'warning');

            return false;
        }

        /**
         * Sets the current file extension.
         * If new extension is 'null' or 'False' current file extension is dropped.
         * Lazy wrapper for {@link rename}.
         * This method works only for files.
         *
         * @param null|bool|string $extension New file extension (e.g.: 'txt'). Pass null to drop current extension.
         *
         * @return bool|CFile Current CFile object on success, 'False' on fail.
         */
        public function setExtension($extension = false)
        {
            if ($this->getIsFile()) {
                if ($this->getIsUploaded()) {
                    $this->addLog('setExtension() is unavailable for uploaded files. Please copy/move uploaded file from temporary directory', 'warning');

                    return false;
                }

                if ($this->getWriteable() && $extension !== false) {
                    $extension = trim($extension);
                    // Drop current extension.
                    if ($extension === null || $extension == '') {
                        $new_base_name = $this->getFilename();
                    } else {
                        // Apply new extension.
                        $extension = ltrim($extension, '.');
                        if ($this->getExtension() === null) {
                            $new_base_name = $this->getFilename() . '.' . $extension;
                        } else {
                            $new_base_name = str_replace($this->getExtension(), $extension, $this->getBasename());
                        }
                    }

                    if ($this->rename($new_base_name)) {
                        return $this;
                    }
                }

                $this->addLog('Unable to set file extension "' . $extension . '"');

                return false;
            }

            $this->addLog('setExtension() is available only for files', 'warning');

            return false;
        }

        /**
         * Sets the current filesystem object owner, updates $_owner property on success.
         *
         * For POSIX systems.
         *
         * Asserts that user exists before process if posix_ functions are available.
         *
         * @param string|int $owner     New owner name or ID
         * @param bool       $recursive Apply owner to directory contents flag.
         *
         * @return CFile|bool Current CFile object on success, 'False' on fail.
         * @throws CFileException When the given user is not found, if posix_ functions are available.
         */
        public function setOwner($owner, $recursive = false)
        {

            if (function_exists('posix_getpwnam') && function_exists('posix_getpwuid')) {
                if (posix_getpwnam($owner) == false xor (is_numeric($owner) && posix_getpwuid($owner) == false)) {
                    throw new CFileException('Unable to set owner for filesystem object. User "' . $owner . '" is not found.');
                }
            }

            if ($this->getExists()) {

                $success = @chown($this->_realpath, $owner);
                if ($success) {
                    $this->_owner = $owner;
                }

                if ($success && $this->getIsDir() && $recursive) {
                    $contents = $this->getContents(true);
                    foreach ($contents as $filepath) {
                        if (!@chown($filepath, $owner)) {
                            $this->addLog('Unable to set owner for "' . $filepath . '" to "' . $owner . '"');
                            $success = false;
                        }
                    }
                }

                if ($success) {
                    return $this;
                }
            }

            $this->addLog('Unable to set owner for filesystem object to "' . $owner . '"');

            return false;
        }

        /**
         * Sets the current filesystem object group, updates $_group property on success.
         *
         * For POSIX systems.
         *
         * Asserts that group exists before process if posix_ functions are available.
         *
         * @param string|int $group     New group name or ID
         * @param bool       $recursive Apply group to directory contents flag.
         *
         * @return CFile|bool Current CFile object on success, 'False' on fail.
         * @throws CFileException When the given group is not found, if posix_ functions are available.
         */
        public function setGroup($group, $recursive = false)
        {

            if (function_exists('posix_getgrnam') && function_exists('posix_getgrgid')) {
                if (posix_getgrnam($group) == false xor (is_numeric($group) && posix_getgrgid($group) == false)) {
                    throw new CFileException('Unable to set group for filesystem object. Group "' . $group . '" is not found.');
                }
            }

            if ($this->getExists()) {

                $success = @chgrp($this->_realpath, $group);
                if ($success) {
                    $this->_group = $group;
                }

                if ($success && $this->getIsDir() && $recursive) {
                    $contents = $this->getContents(true);
                    foreach ($contents as $filepath) {
                        if (!@chgrp($filepath, $group)) {
                            $this->addLog('Unable to set group for "' . $filepath . '" to "' . $group . '"');
                            $success = false;
                        }
                    }
                }

                if ($success) {
                    return $this;
                }
            }

            $this->addLog('Unable to set group for filesystem object to "' . $group . '"');

            return false;
        }

        /**
         * Sets the current filesystem object permissions, updates $_permissions property on success.
         *
         * For UNIX systems.
         *
         * @param string $permissions New filesystem object permissions in numeric (octal, i.e. '0755') format
         * @param bool   $recursive   Apply permissions to directory contents flag.
         *
         * @return CFile|bool Current CFile object on success, 'False' on fail.
         */
        public function setPermissions($permissions, $recursive = false)
        {
            if ($this->getExists() && is_numeric($permissions)) {
                // '755' normalize to octal '0755'
                $perms_oct = octdec(str_pad($permissions, 4, '0', STR_PAD_LEFT));

                $success = @chmod($this->_realpath, $perms_oct);
                if ($success) {
                    $this->_permissions = $permissions;
                }

                if ($success && $this->getIsDir() && $recursive) {
                    $contents = $this->getContents(true);
                    foreach ($contents as $filepath) {
                        if (!@chmod($filepath, $perms_oct)) {
                            $this->addLog('Unable to set permissions for "' . $filepath . '" to "' . $permissions . '"');
                            $success = false;
                        }
                    }
                }

                if ($success) {
                    return $this;
                }
            }

            $this->addLog('Unable to change permissions for filesystem object to "' . $permissions . '"');

            return false;
        }

        /**
         * Resolves destination path for the current filesystem object.
         * This method enables short calls for {@link copy} & {@link rename} methods (i.e. copy('mynewfile.htm') makes
         * a copy of the current filesystem object in the same directory, named 'mynewfile.htm')
         *
         * @param string $dest Destination filesystem object name (with or w/o path)
         *
         * @return string Resolved real destination path for the current filesystem object
         */
        private function resolveDestPath($dest)
        {
            if (strpos($dest, DIRECTORY_SEPARATOR) === false) {
                return $this->getDirname() . DIRECTORY_SEPARATOR . $dest;
            }

            return $this->realPath($dest);
        }

        /**
         * Copies the current filesystem object to specified destination.
         * Destination path supplied by user resolved to real destination path with {@link resolveDestPath}
         *
         * @param string $dest Destination path for the current filesystem object to be copied to
         *
         * @return CFile|bool New CFile object for newly created filesystem object on success, 'False' on fail.
         */
        public function copy($dest)
        {
            $dest_realpath = $this->resolveDestPath($dest);

            if ($this->getIsFile()) {
                if ($this->getReadable() && @copy($this->_realpath, $dest_realpath)) {
                    return $this->set($dest_realpath);
                }
            } else {
                $this->addLog('Copying directory "' . $this->_realpath . '" to "' . $dest_realpath . '"', 'trace');
                $contents = $this->dirContents($this->_realpath, true);
                foreach ($contents as $item) {
                    $item_dest = $dest_realpath . str_replace($this->_realpath, '', $item);
                    if (is_file($item)) {
                        @copy($item, $item_dest);
                    } elseif (is_dir($item)) {
                        $this->createDir(0754, $item_dest);
                    }
                }

                return $this->set($dest_realpath);
            }

            $this->addLog('Unable to copy filesystem object into "' . $dest_realpath . '"');

            return false;
        }

        /**
         * Renames/moves the current filesystem object to specified destination.
         * Destination path supplied by user resolved to real destination path with {@link resolveDestPath}
         *
         * @param string $dest Destination path for the current filesystem object to be renamed/moved to
         *
         * @return CFile|bool Updated current CFile object on success, 'False' on fail.
         */
        public function rename($dest)
        {
            $dest_realpath = $this->resolveDestPath($dest);

            if ($this->getWriteable() && @rename($this->_realpath, $dest_realpath)) {
                $this->_filepath = $dest;
                $this->_realpath = $dest_realpath;
                // Update pathinfo properties.
                $this->pathInfo();

                return $this;
            }

            $this->addLog('Unable to rename/move filesystem object into "' . $dest_realpath . '"');

            return false;
        }

        /**
         * Purges (makes empty) the current filesystem object.
         * If the current filesystem object is a file its contents set to ''.
         * If the current filesystem object is a directory all its descendants are deleted.
         *
         * @param null|string $path Filesystem path to object to purge.
         *
         * @return bool|CFile Current CFile object on success, 'False' on fail.
         */
        public function purge($path = null)
        {
            if (!$path) {
                $path = $this->_realpath;
            }

            if ($this->getIsFile()) {
                if ($this->getWriteable()) {
                    $this->setContents('');

                    return true;
                }
            } else {
                $this->addLog('Purging directory "' . $path . '"', 'trace');
                $contents = $this->dirContents($path, true);
                foreach ($contents as $item) {
                    if (is_file($item)) {
                        @unlink($item);
                    } elseif (is_dir($item)) {
                        $this->purge($item);
                        @rmdir($item);
                    }
                }

                // TODO hey, still need a valid check here
                return true;
            }

            return false;
        }

        /**
         * Deletes the current filesystem object.
         * For folders purge parameter can be supplied.
         *
         * @param boolean $purge If 'True' folder would be deleted with all the descendants
         *
         * @return boolean 'True' if successfully deleted, 'False' on fail
         */
        public function delete($purge = true)
        {
            if ($this->getWriteable()) {
                if (($this->getIsFile() && @unlink($this->_realpath)) || (!$this->getIsFile() && ($purge ? $this->purge() : true) && @rmdir($this->_realpath))) {
                    $this->_exists = $this->_readable = $this->_writeable = false;

                    return true;
                }
            }

            $this->addLog('Unable to delete filesystem object');

            return false;
        }

        /**
         * Sends the current file to browser as a download with real or faked file name.
         * Browser caching is prevented.
         * This method works only for files.
         *
         * @param null|string $fake_name      New filename (e.g.: 'myfileFakedName.htm')
         * @param bool        $server_handled Whether file contents delivery is handled by server internals (cf. when file
         *                                    contents is read and sent by php).
         *                                    E.g.: lighttpd and Apache with mod-sendfile can use X-Senfile header to speed up file delivery blazingly.
         *                                    Note: If you want to serve big or even huge files you are definetly advised to turn this option on and setup
         *                                    your server software appropriately, if not to say that it is your only alternative :).
         * @param null|string $content_type   Should be used to override content type on demand.
         *
         * @return bool|null Returns bool or outputs file contents with headers.
         */
        public function send($fake_name = null, $server_handled = false, $content_type = null)
        {
            if ($this->getIsFile()) {
                if ($this->getReadable() && !headers_sent()) {

                    if ($content_type) {
                        $ctype = $content_type;
                    } else {
                        $ctype = $this->getMimeType();
                        if (!$ctype) {
                            $ctype = 'application/octet-stream';
                        }
                    }

                    if ($fake_name) {
                        $filename = $fake_name;
                    } else {
                        $filename = $this->getBasename();
                    }

                    // Disable browser caching.
                    header('Cache-control: private');
                    header('Pragma: private');
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

                    header('Content-Type: ' . $ctype);
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . $this->getSize(false));
                    header('Content-Disposition: attachment;filename="' . $filename . '"');

                    if ($server_handled) {
                        header('X-Sendfile: ' . $this->_realpath);
                    } else {
                        if ($contents = $this->getContents()) {
                            echo $contents;
                        }
                    }
                    exit;
                }

                $this->addLog('Unable to prepare file for download. Headers already sent or file doesn\'t not exist');

                return false;
            } else {
                $this->addLog('send() and download() methods are available only for files', 'warning');

                return false;
            }
        }

        /**
         * Alias for {@link rename}.
         *
         * @param string $dest
         *
         * @return CFile|bool
         */
        public function move($dest)
        {
            return $this->rename($dest);
        }

        /**
         * Alias for {@link send}.
         *
         * @param null|string $fake_name
         * @param bool        $server_handled
         * @param null|string $content_type
         *
         * @return bool|null
         */
        function download($fake_name = null, $server_handled = false, $content_type = null)
        {
            return $this->send($fake_name, $server_handled, $content_type);
        }

        // Modified methods taken from Yii CFileHelper.php are listed below
        // ===================================================

        /**
         * Returns the MIME type of the current file.
         * If $_mime_type property is set, returned value is read from that property.
         *
         * This method will attempt the following approaches in order:
         * 1. finfo
         * 2. mime_content_type
         * 3. {@link getMimeTypeByExtension}
         *
         * This method works only for files.
         *
         * @return string|bool the MIME type on success, 'False' on fail.
         */
        public function getMimeType()
        {

            if ($this->_mime_type) {
                return $this->_mime_type;
            }

            if ($this->getIsFile()) {
                if ($this->getReadable()) {

                    if ($this->_is_uploaded) {
                        return $this->_mime_type = $this->_uploaded_inst->getType();
                    }

                    if (function_exists('finfo_open')) {
                        if (($info = @finfo_open(FILEINFO_MIME)) && ($result = finfo_file($info, $this->_realpath)) !== false) {
                            return $this->_mime_type = $result;
                        }
                    }

                    if (function_exists('mime_content_type') && ($result = @mime_content_type($this->_realpath)) !== false) {
                        return $this->_mime_type = $result;
                    }

                    return $this->_mime_type = $this->getMimeTypeByExtension($this->_realpath);

                }

                $this->addLog('Unable to get mime type for file');

                return false;
            } else {
                $this->addLog('getMimeType() method is available only for files', 'warning');

                return false;
            }
        }

        /**
         * Determines the MIME type based on the extension of the current file.
         * This method will use a local map between extension name and MIME type.
         * This method works only for files.
         *
         * @return string the MIME type. False is returned if the MIME type cannot be determined.
         */
        public function getMimeTypeByExtension()
        {
            if ($this->getIsFile()) {
                $this->addLog('Trying to get MIME type for "' . $this->_realpath . '" from extension "' . $this->_extension . '"', 'trace');
                static $exts;

                if ($exts === null) {
                    $exts = require($this->getPathOfAlias('system.utils.mimeTypes') . '.php');
                }

                $ext = strtolower($this->_extension);
                if (!empty($ext) && isset($exts[$ext])) {
                    return $exts[$ext];
                }

                return false;
            } else {
                $this->addLog('getMimeTypeByExtension() is available only for files', 'warning');

                return false;
            }
        }

    }
