<?php
/**
 * CandideIndex.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Reader for Pages/Collections/Plugins indexes
 * 
 * @since 1.0
 * Basic < CandideIndexBasic < CandideIndex
 * 
 */
class CandideIndex extends CandideIndexBasic {

    protected $_visual_plugins = [];

    /**
     * CandideIndex constructor which initialize _visual_plugins
     */
    public function __construct(){
        parent::__construct();
        foreach (glob(self::PLUGINS_DIRECTORY."*", GLOB_ONLYDIR) as $pluginFolder) {
            $plugin = $this->readJsonFile($pluginFolder."/config.json");
            if ($plugin["is_visual_interface"]) {
                $this->_visual_plugins[] = $plugin;   
            }
        }
    }

    /**
     * Return nonformatted page name
     *
     * @param Int $index [Page index]
     * @return String [Nonformatted page name]
     */
    public function getPageName(Int $index):String {
        return $this->_pages[$index];
    }
    /**
     * Return formatted page name
     *
     * @param Int $index [Page index]
     * @return String [Formatted page name]
     */
    public function getFormattedPageName(Int $index):String {
        return $this->formatTitle($this->getPageName($index));
    }
    /**
     * Count indexed pages
     *
     * @return Int [Number of indexed pages]
     */
    public function countPages():Int {
        return count($this->_pages);
    }

    /**
     * Return nonformatted collection name
     *
     * @param Int $index [Collection index]
     * @return String [Nonformatted collection name]
     */
    public function getCollectionName(Int $index):String {
        return $this->_collections[$index];
    }

    /**
     * Return formatted collection name
     *
     * @param Int $index [Collection index]
     * @return String [Formatted collection name]
     */
    public function getFormattedCollectionName(Int $index):String {
        return $this->formatTitle($this->getCollectionName($index));
    }

    /**
     * Count indexed collections
     *
     * @return Int [Number of indexed collections]
     */
    public function countCollections():Int {
        return count($this->_collections);
    }

    /**
     * Return nonformatted plugin name
     *
     * @param Int $index [Plugin index]
     * @return String [Nonformatted plugin name]
     */
    public function getPluginName(Int $index):String {
        return $this->_visual_plugins[$index]["name"];
    }

    /**
     * Return formatted plugin name
     *
     * @param Int $index [Plugin index]
     * @return String [Formatted plugin name]
     */
    public function getPluginNameFormatted(Int $index):String {
        return $this->formatTitle($this->getPluginName($index));
    }

    /**
     * Count visual plugins
     *
     * @return Int [Number of visual plugins]
     */
    public function countPlugins():Int {
        return count($this->_visual_plugins);
    }

}