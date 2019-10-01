function setMenu(){
    let button = document.querySelector('#js_hamburgerButton')
    if (button != null) {
        button.addEventListener('click',function(e){
            e.preventDefault()
            document.querySelector('body').classList.toggle("menuOpen")
        })   
    }
}