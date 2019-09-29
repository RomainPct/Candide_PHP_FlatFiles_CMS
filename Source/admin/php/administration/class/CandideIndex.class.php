<?php

// Basic < CandideIndexBasic < CandideIndex

class CandideIndex extends CandideIndexBasic {

    protected $_visual_plugins = [];

    public function __construct(){
        parent::__construct();
        foreach (glob(self::PLUGINS_DIRECTORY."*", GLOB_ONLYDIR) as $pluginFolder) {
            $plugin = $this->readJsonFile($pluginFolder."/config.json");
            if ($plugin["is_visual_interface"]) {
                $this->_visual_plugins[] = $plugin;   
            }
        }
    }

    public function getPageName($index):String {
        return $this->_pages[$index];
    }
    public function getPage($index):String {
        return $this->formatTitle($this->_pages[$index]);
    }
    public function countPages():Int {
        return count($this->_pages);
    }

    public function getCollectionName($index):String {
        return $this->_collections[$index];
    }
    public function getCollection($index):String {
        return $this->formatTitle($this->_collections[$index]);
    }
    public function countCollections():Int {
        return count($this->_collections);
    }

    public function getPluginName($index):String {
        return $this->_visual_plugins[$index]["name"];
    }
    public function getPluginNameFormatted($index):String {
        return $this->formatTitle($this->_visual_plugins[$index]["name"]);
    }
    public function countPlugins():Int {
        return count($this->_visual_plugins);
    }

}