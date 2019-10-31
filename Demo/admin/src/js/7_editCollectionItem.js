let editCollectionItemForm
function setEditCollectionItem() {
    editCollectionItemForm = document.querySelector("#editCollectionItemForm");
    setSubmitEvent(editCollectionItemForm,function(id){
        if (editCollectionItemForm.getAttribute("data-id") == "newItem"){
            let collectionName = editCollectionItemForm.getAttribute("data-collection-name")
            window.location.href= "editCollectionItem?collection_name="+collectionName+"&id="+id
        }
    })
    setForm()
}