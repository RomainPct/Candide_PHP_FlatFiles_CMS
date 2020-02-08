let isReorderingInBackend = false
let deleteCollectionItemButtons

function setEditCollection() {
    deleteCollectionItemButtons = document.querySelectorAll('.deleteButton')
    deleteCollectionItemButtons.forEach(button => {
        button.addEventListener('click',function (e) {
            e.preventDefault()
            if (window.confirm("Voulez vous vraiment supprimer cet élément ?")){
                let container = button.parentNode
                container.classList.add("waiting")
                fetch(this.getAttribute('href'))
                    .then(function (response) {
                        if (response.status == 200){
                            container.parentNode.removeChild(container)
                        } else {
                            container.classList.remove("waiting")
                        }
                    })
            }
        })  
    })
}

function setSlipCollection(){
    let collection = document.querySelector('#collectionItems');
    if (collection) {
        new Slip(collection);

        collection.addEventListener('slip:beforeswipe', function(e) { e.preventDefault() })
    
        collection.addEventListener('slip:swipe', function(e) { e.preventDefault() })
    
        collection.addEventListener('slip:beforereorder', function(e) {
            if (e.target.tagName == 'A' || isReorderingInBackend) {
                e.preventDefault()
            }
        })
    
        collection.addEventListener('slip:reorder', function(e) {
            reorderItem(e)
        })
    }
}

function reorderItem(e) {
    e.target.parentNode.insertBefore(e.target,e.detail.insertBefore)
    if (e.detail.originalIndex == e.detail.spliceIndex) { return }
    let formData = new FormData()
    formData.append("reorderedItemIndex",e.detail.originalIndex)
    formData.append("insertBeforeIndex",e.detail.spliceIndex)
    formData.append("candide_instance_name",e.target.getAttribute('data-collection-name'))
    isReorderingInBackend = true
    fetch("php/actions/reorderCollectionItem.php", {
        method: 'POST',
        body: formData
    })
    .then(function(result) {
        return result.text()
    })
    .then(function(text) {
        console.log(text)
        window.location.reload()
    })
}