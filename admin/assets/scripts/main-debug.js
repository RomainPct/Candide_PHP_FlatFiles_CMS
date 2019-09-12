let trixFilesToDelete = []

function setPellEditorFor(input){
    let pellEditor = input.querySelector('.pell'),
        output = input.querySelector('.wysiwyg-output')
    pell.init({
        element: pellEditor,
        onChange: html => {
            output.value = html
            submitContainer.classList.add('clickable')
        },
        defaultParagraphSeparator: 'p',
        actions: [
            { name: 'heading1', icon: 'H1' },
            { name: 'heading2', icon: 'H2' },
            { name: 'paragraph', icon: 'P' },
            { name: 'bold', icon:"<strong>B</strong>"},
            { name: 'quote', icon: '“ ”' },
            { name: 'olist', icon: '1.' },
            { name: 'ulist', icon: '•' },
            { name: 'link', icon: '🔗' },
            {
                name: 'Photo',
                icon: '📷',
                title: 'Insert a photo',
                result: () => input.querySelector('.pell-file-input').click()
                },
        ]
    })
    pellEditor.querySelector('.pell-content').innerHTML = output.value
}

function manageClassicImageInputEdition(input){
    let img = document.querySelector('#image_'+input.getAttribute('name'))
    let width = img.parentElement.offsetWidth
    let height = img.parentElement.offsetHeight
    img.onload = function(){
        if ( height/width > this.naturalHeight/this.naturalWidth ) {
            img.classList.add('fullHeight')
            img.classList.remove('fullWidth')
        } else {
            img.classList.remove('fullHeight')
            img.classList.add('fullWidth')
        }
    }
    let fr = new FileReader
    fr.onload = (e) => img.src = e.target.result
    fr.readAsDataURL(input.files[0])
    submitContainer.classList.add('clickable')
}

function manageWysiwygImageInputEdition(input){
    if (input.files[0] != null) {
        input.parentElement.querySelector('.pell-content').focus()
        uploadFile(input.files[0], (url) => {
            input.parentElement.querySelector('.pell-content').focus()
            document.execCommand('insertImage', false, "/"+url)
            submitContainer.classList.add('clickable')
        })
    }
}

function uploadFile(file, successCallback) {
    let pageName = document.querySelector('#pageName').getAttribute('data-url')
    let formData = new FormData()
    formData.append("file", file)
    formData.append("destination","files/"+pageName)
    fetch("php/actions/savePictureForWysiwyg.php", {
        method: 'POST',
        body: formData
    }).then(function (response) {
        return response.text()
    }).then(function (text) {
        successCallback(text)
    })

}

let updateAdminPlatform
function setHome(){
    updateAdminPlatform = document.querySelector('#updateAdminPlatform')
    if (updateAdminPlatform != null) {
        updateAdminPlatform.addEventListener('click',function (e) {
            e.preventDefault()
            fetch("updateAdminPlatform.php")
                .then(function (response) {
                    return response.text()
                })
                .then(function (text) {
                    console.log(text)
                    window.location.reload()
                })
        })
    }
}

let textareas, filesInput, numberInputs, submitContainer
function setForm() {
    submitContainer = document.querySelector('.submitContainer')
    textareas = document.querySelectorAll('textarea')
    for (let i = 0; i < textareas.length; i++) {
        textareas[i].style.height = (textareas[i].scrollHeight) + 'px'
        textareas[i].addEventListener('input',function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            submitContainer.classList.add('clickable')
        })
    }
    wysiwygInputs = document.querySelectorAll('.pell-input-box')
    wysiwygInputs.forEach(input => {
        setPellEditorFor(input)
    });
    numberInputs = document.querySelectorAll('input[name^=number_]')
    for (let i = 0; i < numberInputs.length; i++){
        numberInputs[i].addEventListener('input',function(){
            if (isNaN(this.value)) {
                this.value = this.value.replace(",",".")
                this.value = this.value.replace(/[^\d\.]+/g,"")
            }
            submitContainer.classList.add('clickable')
        })
    }
    filesInput = document.querySelectorAll('input[type="file"]')
    filesInput.forEach(input => {
        input.addEventListener('change',function () {
            input.classList.contains('classic-image-input') ? manageClassicImageInputEdition(this) : manageWysiwygImageInputEdition(this)
        })  
    })
}

let editPageForm
function setEditPage() {
    editPageForm = document.querySelector("#editPageForm");
    editPageForm.addEventListener('submit',function (e) {
        e.preventDefault()
        submitContainer.classList.add('loading')
        let formData  = new FormData(this);
        formData.append("trixFilesToDelete",JSON.stringify(trixFilesToDelete))
        trixFilesToDelete = []
        fetch(this.getAttribute('action'), {
            method: 'POST',
            body: formData
        }).then(function (response) {
            submitContainer.classList.remove('loading')
            if (response.status == 200){
                submitContainer.classList.remove('clickable')
            }
            return response.text()
        }).then(function (text) {
            console.log(text)
        });
    })
    setForm()
}

let deleteCollectionItemButtons
function setEditCollection() {
    deleteCollectionItemButtons = document.querySelectorAll('.deleteButton')
    for (let i = 0; i < deleteCollectionItemButtons.length; i++) {
        deleteCollectionItemButtons[i].addEventListener('click',function (e) {
            e.preventDefault()
            if (window.confirm("Voulez vous vraiment supprimer cet élément ?")){
                let container = deleteCollectionItemButtons[i].parentNode
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
    }
}

let editCollectionItemForm
function setEditCollectionItem() {
    editCollectionItemForm = document.querySelector("#editCollectionItemForm");
    editCollectionItemForm.addEventListener('submit',function (e) {
        e.preventDefault()
        submitContainer.classList.add('loading')
        let formData  = new FormData(this);
        formData.append("trixFilesToDelete",trixFilesToDelete)
        fetch(this.getAttribute('action'), {
            method: 'POST',
            body: formData
        }).then(function (response) {
            submitContainer.classList.remove('loading')
            if (response.status == 200){
                submitContainer.classList.remove('clickable')
            }
            return response.text()
        }).then(function (id) {
            if (editCollectionItemForm.getAttribute("data-id") == "newItem"){
                let pageName = editCollectionItemForm.getAttribute("data-page")
                window.location.href= "editCollectionItem?page="+pageName+"&id="+id
            }
        });
    })
    setForm()
}

if (document.URL.indexOf("editPage") != -1){
    setEditPage()
} else if (document.URL.indexOf("editCollection") != -1){
    setEditCollection()
} else if (document.URL.indexOf("editCollectionItem") != -1){
    setEditCollectionItem()
} else {
    setHome()
}
// addEventListener("trix-initialize", function(event) {
//     Trix.config.attachments.preview.caption = {
//         name: false
//     }

//     let h1ButtonHTML = newButton("heading1","heading","Titre 1")
//     let h2ButtonHTML = newButton("heading2","heading","T2")
//     let h3ButtonHTML = newButton("heading3","heading","T3")
//     const groupElement = event.target.toolbarElement.querySelectorAll('.trix-button-group--block-tools')[0];

//     groupElement.insertBefore(h1ButtonHTML,groupElement.children[0])
//     groupElement.insertBefore(h2ButtonHTML,groupElement.children[1])
//     groupElement.insertBefore(h3ButtonHTML,groupElement.children[2])
//     setAddImageButton(event.target,groupElement)
//     groupElement.children[3].remove()
// })
// addEventListener('trix-attachment-add',function (e) {
//     let attachment = e.attachment
//     if (attachment.file) {
//         uploadFileAttachment(attachment)
//     }
// })
// addEventListener('trix-attachment-remove',function (e) {
//     console.log(e.attachment)
//     let url = e.attachment.attachment.attributes.values.url
//     trixFilesToDelete.push(url)
// })
// addEventListener('trix-change',function (e) {
//     trixEditorsChanges++
//     if (trixEditorsChanges > document.querySelectorAll('trix-editor').length) {
//         submitContainer.classList.add("clickable")
//     }
// })

// function newButton(trixAttribute,title,text) {
//     let button = document.createElement("button")
//     button.setAttribute("type","button")
//     button.setAttribute("data-trix-attribute",trixAttribute)
//     button.setAttribute("title",title)
//     button.classList.add("trix-button")
//     button.innerText = text
//     return button
// }

// function setAddImageButton(trix,toolBar) {
//     // Creation of the button
//     let button = newButton('','Attach a file','Attach a file')
//     button.setAttribute("data-trix-action", "x-attach");
//     button.setAttribute("tabindex", "-1");

//     // Attachment of the button to the toolBar
//     uploadButton = toolBar.appendChild(button);

//     // When the button is clicked
//     uploadButton.addEventListener('click', function() {
//         // Create a temporary file input
//         fileInput = document.createElement("input");
//         fileInput.setAttribute("type", "file");
//         fileInput.setAttribute("multiple", "");
//         // Add listener on change for this file input
//         fileInput.addEventListener("change", function(event) {
//             var file, _i, _len, _ref, _results;
//             _ref = this.files;
//             _results = [];
//             // Getting files
//             for (_i = 0, _len = _ref.length; _i < _len; _i++) {
//                 file = _ref[_i];
//                 // pushing them to Trix
//                 _results.push(trix.editor.insertFile(file));
//             }
//             return _results;
//         }),
//             // Then virtually click on it
//             fileInput.click()
//     });
// }

// function uploadFileAttachment(attachment) {
//     uploadFile(attachment.file, setAttributes)
//     function setAttributes(respUrl) {
//         let attributes = {
//             url: "../"+respUrl,
//             href: "../"+respUrl + "?content-disposition=attachment"
//         }
//         attachment.setAttributes(attributes)
//     }
// }

// function uploadFile(file, successCallback) {
//     let pageName = document.querySelector('#pageName').getAttribute('data-url')
//     let formData = new FormData()
//     formData.append("file", file)
//     formData.append("destination","files/"+pageName)
//     fetch("php/actions/savePictureFromTrix.php", {
//         method: 'POST',
//         body: formData
//     }).then(function (response) {
//         return response.text()
//     }).then(function (text) {
//         successCallback(text)
//     })

// }