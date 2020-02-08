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