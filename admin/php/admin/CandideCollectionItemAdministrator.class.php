<?php


class CandideCollectionItemAdministrator extends CandideCollectionItem {

    use Administrator;

    private $_collectionAdministrator;
    private $_fullStructure;
    private $_fullData = [];
    private $_newItem = false;

    public function __construct(String $page, $id) {
        $this->_collectionAdministrator = new CandideCollectionAdministrator($page);
        if ($id == 'newItem') {
            $id = $this->_collectionAdministrator->getNewId();
            $this->_newItem = true;
        }
        parent::__construct($page,$id);
        if (file_exists($this->getStructureUrl())){
            $this->_structure = json_decode(file_get_contents($this->getStructureUrl()),true);
        }
        $this->_fullStructure = array_merge($this->_collectionAdministrator->getStructure(),$this->_structure);
        if (!$this->_newItem) {
            $this->_fullData = array_merge($this->_data,$this->_collectionAdministrator->getDataForElementWithId($this->_id));
        }
    }

    public function getTitle(){
        if ($this->_newItem){
            echo 'Ajouter un élément à la collection "'.$this->formatTitle($this->_page).'"';
        } else {
            echo 'Modifier l\'élément de la collection "'.$this->formatTitle($this->_page).'"';
        }
    }
    public function getCallToActionText(){
        if ($this->_newItem){
            echo "Ajouter";
        } else {
            echo "Enregistrer";
        }
    }

    public function getFields() {
        foreach ($this->_fullStructure as $key => $value){
            $data = (key_exists($key,$this->_fullData) && key_exists('data',$this->_fullData[$key])) ? $this->_fullData[$key]['data'] : "";
            echo $this->getField($key,$value["type"],$data,$value);
        }
    }

    public function deleteThisItem(){
        // Supprimer le dossier de l'item
        $this->deleteFiles(self::DATA_DIRECTORY.$this->_page."/items/".$this->_id);
        // Supprimer l'item de $this->_collectionAdministrator->_data
        $this->_collectionAdministrator->removeItem($this->_id);
    }

    public function setData(Array $texts, Array $files){
        // Gestion information de l'item
        $this->_data = array_merge($this->_structure,$this->_data);
        $this->setTexts($texts);
        $newFiles = $this->setImages($files);
        $this->saveData();
        // Information de la collection
        $this->_collectionAdministrator->setData($texts,$newFiles,$this->_id);
        foreach (json_decode($texts["trixFilesToDelete"]) as $file){
            $this->deleteFiles("../".$file);
        }
        // Afficher le nouvel id
        echo $this->_id;
    }

    private function setTexts(Array $texts) {
        foreach ($this->_structure as $key => $value) {
            if (key_exists($key,$texts)) {
                $this->_data[$key]['data'] = $texts[$key];
            }
        }
    }

    private function setImages(Array $files) : Array {
        $newFiles = [];
        foreach ($files as $key => $file) {
            if ($file["size"] != 0) {                
                $dir = $this->getPage()."/".$this->_id;
                $url = $this->savePicture($key,$file,$dir,$this->_data[$key],$this->_fullStructure[$key]);
                $newFiles[$key] = $url;
                $this->_data[$key]['data'] = $url;
            }
        }
        return $newFiles;
    }

}