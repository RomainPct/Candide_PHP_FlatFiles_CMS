const content = document.querySelector("#content")
const navLinks = document.querySelectorAll("#navLinks li a")
const header = document.querySelector("#header")
let trixFilesToDelete = [], trixEditorsChanges = 0

function loadContent(url, callback) {
    trixEditorsChanges = 0
    console.log(url)
    fetch(url)
        .then(function (response) {
            return response.text()
        }).then(function(html) {
        content.innerHTML = html
        callback()
    })
}

header.addEventListener('click',function (e) {
    e.preventDefault()
    loadContent("pages/home.php",setHome)
})

for (let i = 0; i < navLinks.length; i++){
    navLinks[i].addEventListener('click',function (e) {
        e.preventDefault()
        let pageName = navLinks[i].getAttribute("href").substring(1);
        switch (navLinks[i].getAttribute("data-type")) {
            case "page":
                loadContent("pages/editPage.php?page="+pageName,setEditPage)
                break
            case "collection":
                loadContent("pages/editCollection.php?page="+pageName,setEditCollection)
                break
        }
    })
}

let updateAdminPlatform
function setHome(){
    updateAdminPlatform = document.querySelector('#updateAdminPlatform')
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

let textareas, filesInput, submitContainer
function setForm() {
    textareas = document.querySelectorAll('textarea')
    filesInput = document.querySelectorAll('input[type="file"]')
    submitContainer = document.querySelector('.submitContainer')
    for (let i = 0; i < textareas.length; i++) {
        textareas[i].style.height = (textareas[i].scrollHeight) + 'px'
        textareas[i].addEventListener('input',function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
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

let newElementButton, editCollectionItemButtons, deleteCollectionItemButtons
function setEditCollection() {
    newElementButton = document.querySelector('#newElement')
    newElementButton.addEventListener('click',function (e) {
        e.preventDefault()
        let pageName = this.getAttribute("href").substring(1);
        loadContent('pages/editCollectionItem.php?page='+pageName+'&id=newItem',setEditCollectionItem)
    })
    editCollectionItemButtons = document.querySelectorAll('.editButton')
    for (let i = 0; i < editCollectionItemButtons.length; i++) {
        editCollectionItemButtons[i].addEventListener('click',function (e) {
            e.preventDefault()
            let pageName = newElementButton.getAttribute("href").substring(1);
            let id = this.getAttribute("href").substring(1);
            loadContent('pages/editCollectionItem.php?page='+pageName+'&id='+id,setEditCollectionItem)
        })
    }
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

let backButton, editCollectionItemForm
function setEditCollectionItem() {
    backButton = document.querySelector('#backButton')
    backButton.addEventListener('click',function (e) {
        e.preventDefault()
        let pageName = this.getAttribute("href").substring(1);
        loadContent("pages/editCollection.php?page="+pageName,setEditCollection)
    })
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
        }).then(function (text) {
            console.log(text)
            if (editCollectionItemForm.getAttribute("data-id") == "newItem"){
                let pageName = backButton.getAttribute("href").substring(1);
                let id = text
                loadContent('pages/editCollectionItem.php?page='+pageName+'&id='+id,setEditCollectionItem)
            }
        });
    })
    setForm()
}

setHome()