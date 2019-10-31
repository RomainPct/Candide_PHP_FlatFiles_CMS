document.querySelectorAll(".youtube_video_input").forEach( input => {
    input.addEventListener('input',() => {
        console.log(input.value)
    })
})