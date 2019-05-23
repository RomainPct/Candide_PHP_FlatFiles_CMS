addEventListener("trix-initialize", function(event) {
    Trix.config.attachments.preview.caption = {
        name: false
    };
    Trix.config.blockAttributes.heading1 = {
        tagName: "h1",
        terminal: true,
        breakOnReturn: true,
        group: false
    }
    Trix.config.blockAttributes.heading2 = {
        tagName: "h2",
        terminal: true,
        breakOnReturn: true,
        group: false
    }
    Trix.config.blockAttributes.heading3 = {
        tagName: "h3",
        terminal: true,
        breakOnReturn: true,
        group: false
    }

    let h1ButtonHTML = newButton("heading1","heading","Titre 1")
    let h2ButtonHTML = newButton("heading2","heading","T2")
    let h3ButtonHTML = newButton("heading3","heading","T3")
    const groupElement = event.target.toolbarElement.querySelectorAll('.trix-button-group--block-tools')[0];

    groupElement.insertBefore(h1ButtonHTML,groupElement.children[0])
    groupElement.insertBefore(h2ButtonHTML,groupElement.children[1])
    groupElement.insertBefore(h3ButtonHTML,groupElement.children[2])
    groupElement.children[3].remove()
})

function newButton(trixAttribute,title,text) {
    let button = document.createElement("button")
    button.setAttribute("type","button")
    button.setAttribute("data-trix-attribute",trixAttribute)
    button.setAttribute("title",title)
    button.classList.add("trix-button")
    button.innerText = text
    return button
}

addEventListener('trix-attachment-add',function (e) {
    let attachment = e.attachment
    if (attachment.file) {
        uploadFileAttachment(attachment)
    }
})

function uploadFileAttachment(attachment) {
    uploadFile(attachment.file, setAttributes)
    function setAttributes(respUrl) {
        let attributes = {
            url: "../"+respUrl,
            href: "../"+respUrl + "?content-disposition=attachment"
        }
        console.log(attributes)
        attachment.setAttributes(attributes)
    }
}

function uploadFile(file, successCallback) {
    let formData = new FormData()
    formData.append("file", file)
    // GERER DESTINATION DU FICHIER
    formData.append("destination","directory")
    const url = "actions/savePictureFromTrix.php"

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(function (response) {
        return response.text()
    }).then(function (text) {
        successCallback(text)
    })

}

addEventListener('trix-attachment-remove',function (e) {
    console.log("remove")
    console.log(e.attachment)
    // GERER SUPPRESSION MEDIA
})