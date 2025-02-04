const checkDateInputValue = (input) => {
  if (input.value) {
    input.parentNode.classList.add('has-value')
  } else {
    input.parentNode.classList.remove('has-value')
  }
}

const datepicker = () => {
  const dateInputs = document.querySelectorAll('input[type="date"]')

  if (!dateInputs) return

  dateInputs.forEach((input) => {
    checkDateInputValue(input)

    input.addEventListener('change', (e) => {
      checkDateInputValue(e.target)
    })
  })
}

export default datepicker
