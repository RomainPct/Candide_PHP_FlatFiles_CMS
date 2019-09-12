let trixFilesToDelete = [], trixEditorsChanges = 0

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
    textareas = document.querySelectorAll('textarea')
    filesInput = document.querySelectorAll('input[type="file"]')
    numberInputs = document.querySelectorAll('input[name^=number_]')
    submitContainer = document.querySelector('.submitContainer')
    for (let i = 0; i < textareas.length; i++) {
        textareas[i].style.height = (textareas[i].scrollHeight) + 'px'
        textareas[i].addEventListener('input',function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            submitContainer.classList.add('clickable')
        })
    }
    for (let i = 0; i < numberInputs.length; i++){
        numberInputs[i].addEventListener('input',function(){
            if (isNaN(this.value)) {
                this.value = this.value.replace(",",".")
                this.value = this.value.replace(/[^\d\.]+/g,"")
            }
            submitContainer.classList.add('clickable')
        })
    }
    for (let i = 0; i < filesInput.length ; i++) {
        filesInput[i].addEventListener('change',function () {
            let img = document.querySelector('#image_'+this.getAttribute('name'))
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
            fr.onload = (e) => {
                img.src = e.target.result
            }
            fr.readAsDataURL(this.files[0])
            submitContainer.classList.add('clickable')
        })
    }
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