<?php
/**
 * Template engine
 *
 * Implements the same interface as Savant3 and Smarty, but is much more
 * lightweight.
 *
 * PHP version 4 and 5
 *
 * Usage
 * $tpl = new Template('/path/to/templates');
 * $tpl->set('variable', 'some value');
 * $tpl->display('template-tpl.php');
 *
 * @category  Template
 * @package   Template
 * @version   @package-version@
 * @author    Brian E. Lozier <brian@massassi.net>
 * @author    Lars Olesen <lars@legestue.net>
 * @copyright 2007 The authors
 * @license   MIT Open Source License http://opensource.org/osi3.0/licenses/mit-license.php
 * @link      http://www.sitepoint.com/article/beyond-template-engine
 */

class Template
{
    var $vars; /// Holds all the template variables
    var $path; /// Path to the templates

    /**
     * Constructor
     *
     * @param string $path the path to the templates
     *
     * @return void
     */
    function Template($path = null)
    {
        $this->path = $path;
        $this->vars = array();
    }

    /**
     * Set the path to the template files.
     *
     * @param string $path path to template files
     *
     * @return void
     */
    function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Set a template variable.
     *
     * @param string $name  name of the variable to set
     * @param mixed  $value the value of the variable
     *
     * @return void
     */
    function assign($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * Set a template variable.
     *
     * @param string $name  name of the variable to set
     * @param void   $value the value of the variable
     *
     * @deprecated This will probably be deprecated in next version
     *
     * @return void
     */
    function set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * Set a bunch of variables at once using an associative array.
     *
     * @param array $vars  array of vars to set
     * @param bool  $clear whether to completely overwrite the existing vars
     *
     * @return void
     */
    function setVars($vars, $clear = false)
    {
        if ($clear) {
            $this->vars = $vars;
        } else {
            if (is_array($vars)) $this->vars = array_merge($this->vars, $vars);
        }
    }

    /**
     * Open, parse, and return the template file.
     *
     * @param string $file the template file name
     *
     * @return string
     */
    function fetch($file)
    {
        extract($this->vars);          // Extract the vars to local namespace
        ob_start();                    // Start output buffering
        include $this->path . $file;  // Include the file
        $contents = ob_get_contents(); // Get the contents of the buffer
        ob_end_clean();                // End buffering and discard
        return $contents;              // Return the contents
    }

    /**
     * Displays the template directly
     *
     * @param string $file the template file name
     *
     * @return string
     */
    function display($file)
    {
        echo $this->fetch($file);
    }
}

/**
 * An extension to Template providing a cached template
 *
 * This extension will probably be deprecated soon.
 *
 * PHP version 4 og 5
 *
 * Usage
 * $tpl = & new CachedTemplate('/path/to/templates/', '/path/to/cache/', $cache_identifier_for_page);
 * if (!($tpl->is_cached())) {
 *     $tpl->set('title', 'some value');
 * }
 * echo $tpl->fetch_cache('main-tpl.php');
 *
 * @category  Template
 * @package   Template
 * @version   @package-version@
 * @author    Brian E. Lozier <brian@massassi.net>
 * @author    Lars Olesen <lars@legestue.net>
 * @copyright 2007 The authors
 * @license   MIT Open Source License http://opensource.org/osi3.0/licenses/mit-license.php
 * @link      http://www.sitepoint.com/article/beyond-template-engine
 */
class Template_Cache extends Template
{
    var $cache_id;
    var $expire;
    var $cached;

    /**
     * Constructor
     *
     * @param string $path             path to template files
     * @param string $path_cache_files where to save the cache files
     * @param string $cache_id         unique cache identifier
     * @param int    $expire           number of seconds the cache will live
     *
     * @return void
     */
    function Template_Cache($path, $path_cache_files = 'cache/', $cache_id = null, $expire = 900)
    {
        $this->Template($path);
        $this->cache_id = $cache_id ? $path_cache_files . md5($cache_id) : $cache_id;
        $this->expire   = $expire;
    }

    /**
     * Test to see whether the currently loaded cache_id has a valid
     * corrosponding cache file.
     *
     * @return bool
     */
    function isCached()
    {
        if ($this->cached) return true;

        // Passed a cache_id?
        if (!$this->cache_id) return false;

        // Cache file exists?
        if (!file_exists($this->cache_id)) return false;

        // Can get the time of the file?
        if (!($mtime = filemtime($this->cache_id))) return false;

        // Cache expired?
        if (($mtime + $this->expire) < time()) {
            @unlink($this->cache_id);
            return false;
        } else {
            /**
             * Cache the results of this is_cached() call.  Why?  So
             * we don't have to double the overhead for each template.
             * If we didn't cache, it would be hitting the file system
             * twice as much (file_exists() & filemtime() [twice each]).
             */
            $this->cached = true;
            return true;
        }
    }

    /**
     * Returns a cached copy of a template (if it exists),
     * otherwise, it parses it as normal and caches the content.
     *
     * @param string $file string the template file
     *
     * @return string
     */
    function fetch($file)
    {
        if ($this->is_cached()) {
            $fp = @fopen($this->cache_id, 'r');
            $contents = fread($fp, filesize($this->cache_id));
            fclose($fp);
            return $contents;
        } else {
            $contents = $this->fetch($file);
            // Write the cache
            if ($fp = @fopen($this->cache_id, 'w')) {
                fwrite($fp, $contents);
                fclose($fp);
            } else {
                die('Unable to write cache.');
            }
            return $contents;
        }
    }
}
?>