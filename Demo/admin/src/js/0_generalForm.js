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