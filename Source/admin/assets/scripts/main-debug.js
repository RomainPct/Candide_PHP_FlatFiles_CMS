let filesInput = [], numberInputs, submitContainer
function setForm() {
    allowCmdS()
    submitContainer = document.querySelector('.submitContainer')
    // All Inputs
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input',allowUserToSubmitUpdates)
    })
    // All Textareas
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.style.height = (textarea.scrollHeight) + 'px'
        textarea.addEventListener('input',function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            allowUserToSubmitUpdates()
        })
    })
    // Wysiwyg inputs
    document.querySelectorAll('.pell-input-box').forEach(input => {
        setPellEditorFor(input)
    })
    // Number input
    document.querySelectorAll('input[name^=number_]').forEach(numberInput => {
        numberInput.addEventListener('input',function(){
            if (isNaN(this.value)) {
                this.value = this.value.replace(",",".")
                this.value = this.value.replace(/[^\d\.]+/g,"")
            }
        })  
    })
    // Files input
    filesInput = document.querySelectorAll('input[type="file"]')
    filesInput.forEach(input => {
        setImagePreviewRatio(input)
        input.addEventListener('change',function () {
            input.classList.contains('classic-image-input') ? manageClassicImageInputEdition(this) : manageWysiwygImageInputEdition(this.files,this.parentElement.querySelector('.pell-content'))
        })  
    })
}

function manageClassicImageInputEdition(input){
    if (input.files[0] != null) {
        let img = document.querySelector('#image_'+input.getAttribute('name'))
        let newImgRatio = img.parentElement.offsetHeight / img.parentElement.offsetWidth
        let cropping = img.getAttribute('data-cropping-enable') == 1
        img.onload = function(){
            let naturalRatio = this.naturalHeight / this.naturalWidth
            if ( (newImgRatio > naturalRatio && cropping) || (newImgRatio <= naturalRatio && !cropping) ) {
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
    }
}

function allowUserToSubmitUpdates(){
    submitContainer.classList.add('clickable')
}

function setImagePreviewRatio(input) {
    let preview = input.parentElement
    if (preview.classList.contains('image_input_preview')) {
        preview.style.maxHeight = preview.offsetWidth * (parseInt(preview.style.height)/parseInt(preview.style.width)) + "px"   
    }
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

function setSubmitEvent(form,successCallback = function(r){} ){
    form.addEventListener('submit',function (e) {
        e.preventDefault()
        if (submitContainer.classList.contains('clickable') && !submitContainer.classList.contains('loading')) {
            submitContainer.classList.add('loading')
            fetch(this.getAttribute('action'), {
                method: 'POST',
                body: getFormData(this)
            })
                .then(function (response) {
                    if (newWysiwygFiles.length > 0) {
                        window.location.reload();
                    } else if (response.status == 200) {
                        successfulUpdate()
                    } else {
                        submitContainer.classList.remove('loading')
                    }
                    return response.text()
                })
                .then(function (response) {
                    console.log(response)
                    successCallback(response)
                })
        }
    })
}

function successfulUpdate(){
    filesInput.forEach(input => {
        input.value = null
    })
    submitContainer.classList.add('success')
    setTimeout(function(){
        submitContainer.classList.remove('clickable','success')
        setTimeout(function(){
            submitContainer.classList.remove('loading')
        },300)
    },1500);
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

window.addEventListener("resize", function(){
    filesInput.forEach(input => {
        setImagePreviewRatio(input)
    })
})
let newWysiwygFiles = []

function setPellEditorFor(input){
    let pellEditor = input.querySelector('.pell'),
        output = input.querySelector('.wysiwyg-output')
    pell.init({
        element: pellEditor,
        onChange: html => {
            output.value = html
            allowUserToSubmitUpdates()
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
        evt.stopPropagation()
        evt.preventDefault()
        setCaret(evt)
        manageWysiwygImageInputEdition(evt.dataTransfer.files,evt.currentTarget)
    }
}

function setCaret(evt){
    var sel = document.getSelection();
        let range;
        if(document.caretRangeFromPoint) { // Chrome
            range = document.caretRangeFromPoint(evt.clientX,evt.clientY);
            sel.removeAllRanges();
        } else if(evt.rangeParent) { // Firefox
            range = document.createRange();
            range.setStart(evt.rangeParent, evt.rangeOffset);
            sel.removeAllRanges();
        } else if(sel.rangeCount == 0) { // Default to at least not completely failing
            range = document.createRange();
        }
        sel.addRange(range);
}

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
function setMenu(){
    let button = document.querySelector('#js_hamburgerButton')
    if (button != null) {
        button.addEventListener('click',function(e){
            e.preventDefault()
            document.querySelector('body').classList.toggle("menuOpen")
        })   
    }
}
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
let updateAdminPlatform
function setHome(){
    updateAdminPlatform = document.querySelector('#updateAdminPlatform')
    if (updateAdminPlatform != null) {
        updateAdminPlatform.addEventListener('click',function (e) {
            e.preventDefault()
            fetch(updateAdminPlatform.getAttribute('href'))
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
function setEditPage() {
    setSubmitEvent(document.querySelector("#editPageForm"))
    setForm()
}
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