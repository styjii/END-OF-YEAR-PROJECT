window.addEventListener('load', function(){
  // --------------------- change class of span --------------------------
  document.querySelectorAll('.password span:last-child').forEach(element => {
    element.addEventListener('click', function(){
      if (this.parentNode.childNodes[3].type === 'password'){
        this.parentNode.childNodes[3].type = 'text'
        this.childNodes[0].className = 'bi bi-eye-slash-fill'
      } else {
        this.parentNode.childNodes[3].type = 'password'
        this.childNodes[0].className = 'bi bi-eye-fill'
      }
    })
  })

  /*
  // ----------------------------- submit ------------------------------
  document.querySelector('#information > .form-control').addEventListener("submit", function(event){
    const spans = [...document.querySelectorAll('#information input + span > .bi')]

    spans.forEach(span => {
      if (span.className === "bi bi-x" || document.querySelector('#information textarea').value === '') {
        event.preventDefault()
      }
    })
  })
  document.querySelector('.sing-in').addEventListener("submit", function(event){
    const spans = [...document.querySelectorAll('.sing-in input + span > .bi')]

    spans.forEach(span => {
      if (span.className === "bi bi-x") {
        event.preventDefault()
      }
    })
  })
  document.querySelector('.sing-up').addEventListener("submit", function(event){
    const spans = [...document.querySelectorAll('.sing-up input + span > .bi')]
    
    spans.forEach(span => {
      if (span.className === "bi bi-x") {
        event.preventDefault()
      }
    })
  })
  */
})