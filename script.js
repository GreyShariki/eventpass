const isChange = () => {
  const button = document.getElementById("btn-ticket");
  const inputs = document.getElementsByClassName("form-ticket");
  let allFilled = true;
  for (let input of inputs) {
    if (!input.value.trim()) {
      allFilled = false;
      break;
    }
  }
  if (allFilled) {
    button.classList.remove("disabled");
  } else {
    button.classList.add("disabled");
  }
};
