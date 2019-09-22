<?php

class CandideIndex extends CandideIndexBasic {

    const PLUGINS_URL = ROOT_DIR."/admin/plugins/plugins.json";

    protected $_visual_plugins;

    public function __construct(){
        parent::__construct();
        $this->_visual_plugins = (file_exists(self::PLUGINS_URL)) ? json_decode(file_get_contents(self::PLUGINS_URL),true) : [];
        $this->_visual_plugins = array_filter($this->_visual_plugins,function($plugin) {
            return $plugin["is_visual_interface"];
        });
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