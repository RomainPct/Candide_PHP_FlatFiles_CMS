<?php
/**
 * CandideCollectionReorder.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Collection reorder which is used only to reorganize collection items
 * 
 * @since 1.0
 * Basic < CandideBasic < CandideCollectionBasic < CandideCollection < CandideCollectionReorder
 * 
 */
class CandideCollectionReorder extends CandideCollection {

    use BackendPluginNotifier;

    /**
     * Reorder a collection item (Folders and data)
     *
     * @param Int $reorderedItemIndex [Current item index]
     * @param Int $newIndex [New item index]
     * @return Void
     */
    public function reorder(Int $reorderedItemIndex, Int $newIndex){
        $reorganizedIds = $this->reorderIds($reorderedItemIndex,$newIndex);
        $this->reorderFolders($reorganizedIds);
        $this->reorderData($reorganizedIds);
        $this->sendNotification(
            Notification::COLLECTION_HAS_BEEN_REORGANIZED,
            [
                'collection_name' => $this->_instanceName,
                'reorganized_ids' => $reorganizedIds
            ]
        );
    }

    /**
     * Reorder ids according to the reoerdered item
     *
     * @param Int $reorderedItemIndex [Current item index]
     * @param Int $newIndex [New item index]
     * @return Array [New organisation of ids, key is new id & value is old id]
     */
    private function reorderIds(Int $reorderedItemIndex, Int $newIndex):Array {
        $moveForward = ($reorderedItemIndex < $newIndex);
        $offset = 0;
        $currentIds = array_keys($this->_data);
        $reorganizedIds = [];
        for ($i=0; $i < count($currentIds); $i++) { 
            if ($newIndex == $i) {
                $reorganizedIds[] = $currentIds[$reorderedItemIndex];
                $offset = $moveForward ? 0 : -1;
            } else {
                if ($reorderedItemIndex == ($i + $offset) ) {
                    $offset = $moveForward ? 1 : 0;
                }
                $reorganizedIds[] = $currentIds[$i + $offset];
            }   
        }
        return $reorganizedIds;
    }

    /**
     * Update ids in global data
     *
     * @param Array $reorganizedIds [New organisation of ids, key is new id & value is old id]
     * @return void
     */
    private function reorderData(Array $reorganizedIds) {
        $newData = [];
        foreach ($reorganizedIds as $newId => $currentId) {
            $newData[$newId] = $this->_data[$currentId];
            $newData[$newId]["id"] = $newId;
        }
        $this->_data = $newData;
        file_put_contents($this->getInstanceUrl(),json_encode($this->_data));
    }

    /**
     * Update folders name
     *
     * @param Array $reorganizedIds [New organisation of ids, key is new id & value is old id]
     * @return void
     */
    private function reorderFolders(Array $reorganizedIds){
        $tmpFolders = [];
        foreach ($reorganizedIds as $newId => $currentId) {
            $this->tryToRenameFolder(self::DATA_DIRECTORY.$this->_instanceName."/items",$currentId,$newId,$tmpFolders);
            $this->tryToRenameFolder(self::FILES_DIRECTORY.$this->_instanceName,$currentId,$newId,$tmpFolders);
        }
        foreach ($tmpFolders as $tmpFolder => $destFolder) {
            rename($tmpFolder,$destFolder);
        }
    }

    /**
     * Rename folder or save it in tmpFolders if his renaming would have involved data loss
     *
     * @param String $mainFolder [Folder path without id]
     * @param Int $currentId [Current folder id]
     * @param Int $newId [Desired new id]
     * @param Array $tmpFolders [By reference, an array wich contains folders which hasn't been renamed]
     * @return void
     */
    public function tryToRenameFolder(String $mainFolder, Int $currentId, Int $newId, Array &$tmpFolders) {
        if ($currentId == $newId) { return; }
        $currentFolder = $mainFolder."/".$currentId;
        if (is_dir($currentFolder)) {
            $newFilesFolderUrl = $mainFolder."/".$newId;
            if ( is_dir($newFilesFolderUrl)) {
                $tmpFolder = $newFilesFolderUrl."_tmp";
                rename($currentFolder,$tmpFolder);
                $tmpFolders[$tmpFolder] = $newFilesFolderUrl;
            } else {
                rename($currentFolder,$newFilesFolderUrl);
            }
        }
    }

}