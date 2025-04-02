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
emailjs.init("ww-qhiI6f_xWoMjgN");
const sendTicketEmail = async () => {
  const eventId = document.getElementById("eventSelect").value;
  const ticketSelect = document.getElementById("ticketSelect");
  const selectedTicket = ticketSelect.options[ticketSelect.selectedIndex];
  await fetch("./server/quantity.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: `event_id=${eventId}`,
  });

  const formData = {
    email: document.getElementById("email").value,
    first_name: document.getElementById("fname").value,
    last_name: document.getElementById("lname").value,
    event_name: document
      .getElementById("eventSelect")
      .options[document.getElementById("eventSelect").selectedIndex].text.split(
        " ("
      )[0],
    event_date: document.getElementById("eventDate").value,
    event_time: document.getElementById("eventTime").value,
    event_place: document.getElementById("eventPlace").value,
    event_organizer: document.getElementById("eventOrganizer").value,
    event_description: document.getElementById("eventDescription").value,
    ticket_name: selectedTicket.dataset.name,
    ticket_price: selectedTicket.dataset.price,
  };
  console.log(formData);
  emailjs
    .send("service_ju51z2l", "template_n9vmafp", formData)
    .then(() => alert("Билет успешно оформлен! Проверьте вашу почту."))
    .catch((error) => alert("Ошибка: " + error.text));
};
