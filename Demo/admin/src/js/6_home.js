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