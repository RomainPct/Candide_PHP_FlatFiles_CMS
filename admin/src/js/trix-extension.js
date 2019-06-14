addEventListener("trix-initialize", function(event) {
    Trix.config.attachments.preview.caption = {
        name: false
    }

    let h1ButtonHTML = newButton("heading1","heading","Titre 1")
    let h2ButtonHTML = newButton("heading2","heading","T2")
    let h3ButtonHTML = newButton("heading3","heading","T3")
    const groupElement = event.target.toolbarElement.querySelectorAll('.trix-button-group--block-tools')[0];

    groupElement.insertBefore(h1ButtonHTML,groupElement.children[0])
    groupElement.insertBefore(h2ButtonHTML,groupElement.children[1])
    groupElement.insertBefore(h3ButtonHTML,groupElement.children[2])
    setAddImageButton(event.target,groupElement)
    groupElement.children[3].remove()
})
addEventListener('trix-attachment-add',function (e) {
    let attachment = e.attachment
    if (attachment.file) {
        uploadFileAttachment(attachment)
    }
})
addEventListener('trix-attachment-remove',function (e) {
    console.log(e.attachment)
    let url = e.attachment.attachment.attributes.values.url
    trixFilesToDelete.push(url)
})
addEventListener('trix-change',function (e) {
    trixEditorsChanges++
    if (trixEditorsChanges > document.querySelectorAll('trix-editor').length) {
        submitContainer.classList.add("clickable")
    }
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

function setAddImageButton(trix,toolBar) {
    // Creation of the button
    let button = newButton('','Attach a file','Attach a file')
    button.setAttribute("data-trix-action", "x-attach");
    button.setAttribute("tabindex", "-1");

    // Attachment of the button to the toolBar
    console.log(toolBar)
    uploadButton = toolBar.appendChild(button);

    // When the button is clicked
    uploadButton.addEventListener('click', function() {
        // Create a temporary file input
        fileInput = document.createElement("input");
        fileInput.setAttribute("type", "file");
        fileInput.setAttribute("multiple", "");
        // Add listener on change for this file input
        fileInput.addEventListener("change", function(event) {
            var file, _i, _len, _ref, _results;
            _ref = this.files;
            _results = [];
            // Getting files
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                file = _ref[_i];
                // pushing them to Trix
                _results.push(trix.editor.insertFile(file));
            }
            return _results;
        }),
            // Then virtually click on it
            fileInput.click()
    });
}

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
    let pageName = document.querySelector('#pageName').getAttribute('data-url')
    let formData = new FormData()
    formData.append("file", file)
    formData.append("destination","files/"+pageName)
    fetch("actions/savePictureFromTrix.php", {
        method: 'POST',
        body: formData
    }).then(function (response) {
        return response.text()
    }).then(function (text) {
        successCallback(text)
    })

}