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
            { name: 'quote', icon: '“ ”' },
            { name: 'bold', icon:"<strong>B</strong>"},
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
    let pellContent = pellEditor.querySelector('.pell-content')
    pellContent.innerHTML = output.value
    pellContent.addEventListener('drop', handleDropFileInWysiwyg, false);
}

function handleDropFileInWysiwyg(evt) {
    if (evt.dataTransfer.files.length > 0) {
        evt.stopPropagation();
        evt.preventDefault();
        manageWysiwygImageInputEdition(evt.dataTransfer.files,evt.currentTarget)
    }
}

function manageClassicImageInputEdition(input){
    if (input.files[0] != null) {
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
}

let newWysiwygFiles = []
function manageWysiwygImageInputEdition(files,pellContent){
    if (files[0] != null) {
        let fr = new FileReader
        fr.onload = (e) => {
            pellContent.focus()
            newWysiwygFiles.push({
                'pellOutput':pellContent.parentElement.parentElement.querySelector('.wysiwyg-output'),
                'imageSrc':e.target.result,
                'file':files[0]
            })
            document.execCommand('insertImage', false, e.target.result)
        }
        fr.readAsDataURL(files[0])
    }
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
    allowCmdS()
    submitContainer = document.querySelector('.submitContainer')
    textareas = document.querySelectorAll('textarea')
    textareas.forEach(textarea => {
        textarea.style.height = (textarea.scrollHeight) + 'px'
        textarea.addEventListener('input',function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            submitContainer.classList.add('clickable')
        })
    })
    wysiwygInputs = document.querySelectorAll('.pell-input-box')
    wysiwygInputs.forEach(input => {
        setPellEditorFor(input)
    })
    numberInputs = document.querySelectorAll('input[name^=number_]')
    numberInputs.forEach(numberInput => {
        numberInput.addEventListener('input',function(){
            if (isNaN(this.value)) {
                this.value = this.value.replace(",",".")
                this.value = this.value.replace(/[^\d\.]+/g,"")
            }
            submitContainer.classList.add('clickable')
        })  
    })
    filesInput = document.querySelectorAll('input[type="file"]')
    filesInput.forEach(input => {
        input.addEventListener('change',function () {
            input.classList.contains('classic-image-input') ? manageClassicImageInputEdition(this) : manageWysiwygImageInputEdition(this.files,this.querySelector('pell-content'))
        })  
    })
}

function setEditPage() {
    setSubmitEvent(document.querySelector("#editPageForm"))
    setForm()
}

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

let editCollectionItemForm
function setEditCollectionItem() {
    editCollectionItemForm = document.querySelector("#editCollectionItemForm");
    setSubmitEvent(editCollectionItemForm,function(id){
        if (editCollectionItemForm.getAttribute("data-id") == "newItem"){
            let pageName = editCollectionItemForm.getAttribute("data-page")
            window.location.href= "editCollectionItem?page="+pageName+"&id="+id
        }
    })
    setForm()
}

function allowCmdS(){
    document.addEventListener("keydown", function(e) {
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)  && e.keyCode == 83) {
            e.preventDefault()
            if (submitContainer.classList.contains('clickable')){
                submitContainer.querySelector("input").click()
            }
        }
    }, false);
}

function setMenu(){
    document.querySelector('#js_hamburgerButton').addEventListener('click',function(e){
        e.preventDefault()
        document.querySelector('body').classList.toggle("menuOpen")
    })
}

function setSubmitEvent(form,successCallback = function(r){} ){
    form.addEventListener('submit',function (e) {
        e.preventDefault()
        submitContainer.classList.add('loading')
        fetch(this.getAttribute('action'), {
            method: 'POST',
            body: getFormData(this)
        })
            .then(function (response) {
                submitContainer.classList.remove('loading')
                if (newWysiwygFiles.length > 0) {
                    window.location.reload();
                } else if (response.status == 200) {
                    submitContainer.classList.remove('clickable')
                }
                return response.text()
            })
            .then(function (response) {
                console.log(response)
                successCallback(response)
            })
    })
}

function getFormData(form){
    newWysiwygFiles.forEach(item => {
        item.id = generateGUID()
        item.pellOutput.value = item.pellOutput.value.replace(item.imageSrc,item.id)
    })
    let formData = new FormData(form)
    newWysiwygFiles.forEach(item => {
        formData.append(item.id,item.file)
    })
    return formData
}

function generateGUID(){
    let u = Date.now().toString(16) + Math.random().toString(16) + '0'.repeat(16);
    let guid = [u.substr(0,8), u.substr(8,4), '4000-8' + u.substr(13,3), u.substr(16,12)].join('-');
    return guid
}

if (document.URL.indexOf("editPage") != -1){
    setEditPage()
} else if (document.URL.indexOf("editCollectionItem") != -1){
    setEditCollectionItem()
} else if (document.URL.indexOf("editCollection") != -1){
    setEditCollection()
} else {
    setHome()
}
setMenu()