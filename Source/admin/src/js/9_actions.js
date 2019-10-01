if (document.URL.indexOf("editPage") != -1){
    setEditPage()
} else if (document.URL.indexOf("editCollectionItem") != -1){
    setEditCollectionItem()
} else if (document.URL.indexOf("editCollection") != -1){
    setEditCollection()
    setSlipCollection()
} else {
    setHome()
}
setMenu()