if (document.URL.indexOf("editCollection") != -1) {
    let collection = document.querySelector('#collectionItems');
    new Slip(collection);

    collection.addEventListener('slip:beforeswipe', function(e) { e.preventDefault() })

    collection.addEventListener('slip:swipe', function(e) { e.preventDefault() })

    collection.addEventListener('slip:beforereorder', function(e) {
        if (e.target.tagName == 'A') {
            e.preventDefault()
        }
    })

    collection.addEventListener('slip:reorder', function(e) {
        e.target.parentNode.insertBefore(e.target, e.detail.insertBefore)
        reorderItem(e.target.getAttribute('data-item-id'),e.detail.insertBefore.getAttribute('data-item-id'))
    })
}

function reorderItem(reorderedItemIndex,insertBeforeIndex) {
    console.log(reorderedItemIndex+" was insert before "+insertBeforeIndex)
    let formData = new FormData()
    formData.append("reorderedItemIndex",reorderedItemIndex)
    formData.append("insertBeforeIndex",insertBeforeIndex)
    fetch("php/actions/reorderCollectionItem.php", {
        method: 'POST',
        body: formData
    })
        .then(function (response) {
            return response.text()
        })
        .then(function (response) {
            console.log(response)
        })
}