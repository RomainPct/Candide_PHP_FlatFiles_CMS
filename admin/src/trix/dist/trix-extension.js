addEventListener("trix-initialize", function(event) {
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

    let h2ButtonHTML = newButton("heading2","heading","Titre")
    let h3ButtonHTML = newButton("heading3","heading","Sous-titre")
    const groupElement = event.target.toolbarElement.querySelectorAll('.trix-button-group--block-tools')[0];

    groupElement.insertBefore(h2ButtonHTML,groupElement.children[0])
    groupElement.insertBefore(h3ButtonHTML,groupElement.children[1])
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