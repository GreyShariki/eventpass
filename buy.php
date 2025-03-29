<?php
require_once("./server/db.php");

$events_sql = "SELECT * FROM events ORDER BY date DESC";
$events_res = $conn->query($events_sql);

$tickets_sql = "SELECT * FROM tickets";


$tickets_res = $conn->query($tickets_sql);
$all_tickets = [];

while ($ticket = $tickets_res->fetch_assoc()) {
  $all_tickets[$ticket['event_id']][] = $ticket; 
}
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Оформление билета - EventPass</title>
    <link rel="stylesheet" href="styles.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
  </head>
  <body>
    <header class="sticky-top shadow-sm">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
          <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="bi bi-ticket-perforated fs-3 text-primary me-2"></i>
            <span class="fw-bold fs-4"
              >Event<span class="text-primary">Pass</span></span
            >
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="#"
                  ><i class="bi bi-house-door me-1"></i> Главная</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"
                  ><i class="bi bi-calendar-event me-1"></i> Мероприятия</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"
                  ><i class="bi bi-info-circle me-1"></i> О нас</a
                >
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>
    <section class="ticket-header">
      <div class="container text-center">
        <h1 class="display-5 fw-bold mb-3">Оформление билета</h1>
        <p class="lead">Свои желания надо исполнять</p>
      </div>
    </section>
    <main class="container">
      <div class="container row justify-content-center">
        <div class="col-11 col-md-5">
        <form
          action=""
          class="form-section border border-dark m-5"
          method="post"
        >
          <legend class="mb-3">
            <i class="bi bi-person-lines-fill me-2"></i>Оформление билета
          </legend>
          
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">
              <i class="bi bi-envelope-fill"></i> Email
            </label>
            <input
              oninput="isChange()"
              id="email"
              name="email"
              type="email"
              class="form-control border border-dark form-ticket"
            />
          </div>
          
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">
              <i class="bi bi-person"></i> Имя
            </label>
            <input
              oninput="isChange()"
              id="fname"
              name="fname"
              type="text"
              class="form-control form-ticket border border-dark"
            />
          </div>
          
          <div class="mb-3">
            <label for="" class="form-label">
              <i class="bi bi-person-fill"></i> Фамилия
            </label>
            <input
              oninput="isChange()"
              id="lname"
              name="lname"
              type="text"
              class="form-control form-ticket border border-dark"
            />
          </div>
          
          <div class="mb-3">
            <label for="" class="form-label">
              <i class="bi bi-stars me-2"></i> Выберите мероприятие
            </label>
            <select class="form-select border border-dark" id="eventSelect" name="event_id">
              <option value="">-- Выберите мероприятие --</option>
              <?php 
              $events_res->data_seek(0); 
              while ($event = $events_res->fetch_assoc()): ?>
                <option 
                  value="<?= $event['id'] ?>" 
                  data-date="<?= $event['date'] ?>"
                  data-time="<?= $event['time'] ?>"
                  data-place="<?= htmlspecialchars($event['place']) ?>"
                  data-organizer="<?= htmlspecialchars($event['organizer']) ?>"
                  data-description="<?= htmlspecialchars($event['description']) ?>"
                  data-image="<?= $event['image'] ?>"
                >
                  <?= $event['title'] ?> (<?= $event['date'] ?>)
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          
          <input type="hidden" id="eventDate" name="event_date">
          <input type="hidden" id="eventTime" name="event_time">
          <input type="hidden" id="eventPlace" name="event_place">
          <input type="hidden" id="eventOrganizer" name="event_organizer">
          <input type="hidden" id="eventDescription" name="event_description">

          
          <div class="mb-3">
            <div id="ticketContainer" style="display: none;">
              <label for="" class="form-label">
                <i class="bi bi-ticket-fill"></i> Выберите вид билета
              </label>
              <select class="form-select border border-dark" name="ticket" id="ticketSelect"></select>
            </div>
          </div>
          
          <div class="mb-3">
            <button
              type="button"
              id="btn-ticket"
              onclick="sendTicketEmail()"
              class="btn-ticket btn-lg text-white disabled"
            >
              <i class="bi bi-cart3 me-2"></i>
              Купить билет
            </button>
          </div>
        </form>
        </div>
      </div>
    </main>
    <footer class="bg-dark text-white pt-5 pb-4">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 mb-4">
            <h5 class="text-uppercase fw-bold mb-4">
              <i class="bi bi-ticket-perforated me-2"></i>EventPass
            </h5>
            <p>
              Мы создаем незабываемые впечатления от мероприятий. Билеты на
              лучшие концерты, фестивали и события в вашем городе.
            </p>
          </div>
          <div class="col-lg-4 mb-4">
            <h5 class="text-uppercase fw-bold mb-4">Контакты</h5>
            <ul class="list-unstyled">
              <li class="mb-2">
                <i class="bi bi-geo-alt-fill me-2"></i> г. Барнаул, ул.
                Крупской, 103
              </li>
              <li class="mb-2">
                <i class="bi bi-telephone-fill me-2"></i> +7 (3852) 123-456
              </li>
              <li>
                <i class="bi bi-envelope-fill me-2"></i> info@eventpass.ru
              </li>
            </ul>
          </div>
          <div class="col-lg-4 mb-4">
            <h5 class="text-uppercase fw-bold mb-4">Подписаться</h5>
            <div class="input-group mb-3">
              <input
                type="email"
                class="form-control"
                placeholder="Ваш email"
              />
              <button class="btn btn-primary" type="button">
                <i class="bi bi-send"></i>
              </button>
            </div>
          </div>
        </div>
        <hr class="my-4 bg-light" />
        <div class="text-center">
          <p class="mb-0">© 2024 EventPass. Все права защищены.</p>
        </div>
      </div>
    </footer>
    <script src="script.js"></script>
    <script>
      const allTickets = <?= json_encode($all_tickets) ?>;
      document.getElementById('eventSelect').addEventListener('change', function() {
  const eventId = this.value;
  const ticketContainer = document.getElementById('ticketContainer');
  const ticketSelect = document.getElementById('ticketSelect');
  
  if (eventId) {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('eventDate').value = selectedOption.dataset.date;
    document.getElementById('eventTime').value = selectedOption.dataset.time;
    document.getElementById('eventPlace').value = selectedOption.dataset.place;
    document.getElementById('eventOrganizer').value = selectedOption.dataset.organizer;
    document.getElementById('eventDescription').value = selectedOption.dataset.description;
  }
  
  ticketSelect.innerHTML = '';
  
  if (!eventId) {
    ticketContainer.style.display = 'none';
    return;
  }
  
  const tickets = allTickets[eventId] || [];
  
  if (tickets.length > 0) {
    tickets.forEach(ticket => {
      const option = document.createElement('option');
      option.value = ticket.name;
      option.textContent = `${ticket.name} - ${ticket.price} ₽`;
      option.className = `text-${ticket.color}`;
      option.dataset.price = ticket.price;
      option.dataset.color = ticket.color;
      ticketSelect.appendChild(option);
    });
    ticketContainer.style.display = 'block';
  } else {
    const option = document.createElement('option');
    option.textContent = 'Нет доступных билетов';
    option.disabled = true;
    ticketSelect.appendChild(option);
    ticketContainer.style.display = 'block';
  }
});
</script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
