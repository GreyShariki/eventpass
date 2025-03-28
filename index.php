<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="styles.css" />
    <link
      rel="shortcut icon"
      href="./img/ticket-perforated-fill.svg"
      type="image/x-icon"
    />
    <title>Eventify</title>
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
                <a class="nav-link active" href="#">
                  <i class="bi bi-house-door me-1"></i> Главная
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-calendar-event me-1"></i> Мероприятия
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-music-note-beamed me-1"></i> Концерты
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-people me-1"></i> Сходки
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./about.html">
                  <i class="bi bi-info-circle me-1"></i> О нас
                </a>
              </li>
            </ul>

            <div class="d-flex align-items-center">
              <div class="input-group me-3 d-none d-lg-flex">
                <input
                  type="text"
                  class="form-control border-end-0"
                  placeholder="Поиск..."
                />
                <button
                  class="btn btn-outline-light border-start-0"
                  type="button"
                >
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <section class="about-hero text-center">
      <div class="container">
        <h1 class="display-4 fw-bold mb-4">EventPass</h1>
        <p class="lead">
          Мы создаем незабываемые моменты, соединяя людей с их любимыми
          артистами и событиями
        </p>
      </div>
    </section>
    <main class="container-fluid py-4">
      <div class="row g-4">
        <div class="col-lg-2">
          <div class="sidebar p-3">
            <button class="btn sidebar-btn w-100 mb-2 active">
              <i class="bi bi-calendar-event"></i> Все мероприятия
            </button>
            <button class="btn sidebar-btn w-100 mb-2">
              <i class="bi bi-music-note-beamed"></i> Концерты
            </button>
            <button class="btn sidebar-btn w-100">
              <i class="bi bi-people"></i> Сходки фанатов
            </button>
          </div>
        </div>

        <div class="col-lg-10">
        <?php 
        require_once('./server/db.php');
        $sql = 'SELECT * FROM events ORDER BY id DESC';
        $events = $conn->query($sql);

        while ($event = $events->fetch_assoc()) {
            echo '<div class="event-card bg-white">
                <img src="./img/'.$event["image"].'" class="card-img-top" alt="'.$event["title"].'" />
                <div class="card-body p-4">
                    <h1 class="card-title">'.$event["title"].'</h1>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <ul class="list-group mb-4">
                                <li class="list-group-item">
                                    <i class="bi bi-calendar-date me-2"></i>
                                    <strong>Дата:</strong> '.$event["date"].'
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-clock me-2"></i>
                                    <strong>Время:</strong> '.$event["time"].'
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    <strong>Место:</strong> '.$event["place"].'
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-person-badge me-2"></i>
                                    <strong>Организатор:</strong> '.$event["organizer"].'
                                </li>
                            </ul>
                        </div>
                    </div>

                    <p class="card-text main-text mb-4">
                        '.$event["description"].'
                    </p>
                    
                    <div class="mb-4">
                        <h4 class="mb-3">
                            <i class="bi bi-stars me-2"></i>Что вас ждет:
                        </h4>
                        <ul class="list-group">';
            $event_id = (int)$event['id'];
            $content_sql = "SELECT * FROM content WHERE event_id = '$event_id'";
            $content_res = $conn->query($content_sql);
            if ($content_res-> num_rows > 0){
              while ($content = $content_res->fetch_assoc()) {
                echo '<li class="list-group-item d-flex align-items-center">
                        <i class="bi '.$content["icon"].' text-warning me-2"></i>
                        '.$content["description"].'
                      </li>';
            };
            } else {
              echo "SQL запрос не выдал результатов";
            }
            
            
            echo '</ul>
                </div>
                <div class="mb-4">
                    <h4 class="mb-3">
                        <i class="bi bi-alarm me-2"></i>Программа мероприятия:
                    </h4>
                    <div class="timeline">';
            
            $program_sql = "SELECT * FROM program WHERE event_id = '".$event['id']."'";
            $program_res = $conn->query($program_sql);
            while ($program = $program_res->fetch_assoc()) {
                echo '<div class="timeline-item d-flex mb-2">
                        <div class="timeline-badge bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px">
                            <i class="bi '.$program["icon"].'"></i>
                        </div>
                        <div class="ms-3">
                            <strong>'.$program["time"].'</strong> — '.$program["description"].'
                        </div>
                      </div>';
            };
            
            echo '</div>
                </div>
                <div class="mb-4">
                    <h4 class="mb-3">
                        <i class="bi bi-ticket-perforated me-2"></i>Билеты:
                    </h4>
                    <div class="row">';
            
            $tickets_sql = "SELECT * FROM tickets WHERE event_id = '".$event['id']."'";
            $tickets_res = $conn->query($tickets_sql);
            while ($ticket = $tickets_res->fetch_assoc()) {
                echo '<div class="col-md-4 mb-3">
                        <div class="card h-100 border-'.$ticket["color"].'">
                            <div class="card-body text-center">
                                <h5 class="card-title text-'.$ticket["color"].'">'.$ticket["name"].'</h5>
                                <h3 class="card-text">'.$ticket["price"].' ₽</h3>
                                <ul class="list-unstyled text-start">';
                
                $benefits_sql = "SELECT * FROM tickets_ul WHERE ticket_id = '".$ticket["id"]."'";
                $benefits_res = $conn->query($benefits_sql);
                while ($benefit = $benefits_res->fetch_assoc()) {
                    echo '<li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            '.$benefit["description"].'
                          </li>';
                }
                
                echo '</ul>
                            </div>
                        </div>
                      </div>';
            }
            
            echo '</div>
                    <div class="text-center mt-4">
                        <button class="btn btn-ticket btn-lg text-white">
                            <i class="bi bi-cart3 me-2"></i>Купить билеты
                        </button>
                    </div>
                </div>
            </div>
        </div>';
        }
        ?>
         
        </div>
      </div>
    </main>
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
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
            <div class="social-icons mt-3">
              <a href="#" class="text-white me-3"
                ><i class="bi bi-facebook fs-4"></i
              ></a>
              <a href="#" class="text-white me-3"
                ><i class="bi bi-instagram fs-4"></i
              ></a>
              <a href="#" class="text-white me-3"
                ><i class="bi bi-twitter-x fs-4"></i
              ></a>
              <a href="#" class="text-white"
                ><i class="bi bi-telegram fs-4"></i
              ></a>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 mb-4">
            <h5 class="text-uppercase fw-bold mb-4">Меню</h5>
            <ul class="list-unstyled">
              <li class="mb-2">
                <a href="#" class="text-white text-decoration-none">Главная</a>
              </li>
              <li class="mb-2">
                <a href="#" class="text-white text-decoration-none"
                  >Мероприятия</a
                >
              </li>
              <li class="mb-2">
                <a href="#" class="text-white text-decoration-none">Артисты</a>
              </li>
              <li class="mb-2">
                <a href="#" class="text-white text-decoration-none">Галерея</a>
              </li>
              <li>
                <a href="#" class="text-white text-decoration-none">Контакты</a>
              </li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 mb-4">
            <h5 class="text-uppercase fw-bold mb-4">Контакты</h5>
            <ul class="list-unstyled">
              <li class="mb-2">
                <i class="bi bi-geo-alt-fill me-2"></i> г. Барнаул, ул. Ленина,
                42
              </li>
              <li class="mb-2">
                <i class="bi bi-telephone-fill me-2"></i> +7 (3852) 123-456
              </li>
              <li class="mb-2">
                <i class="bi bi-envelope-fill me-2"></i> info@eventpass.ru
              </li>
              <li><i class="bi bi-clock-fill me-2"></i> Пн-Пт: 9:00 - 20:00</li>
            </ul>
          </div>

          <div class="col-lg-3 mb-4">
            <h5 class="text-uppercase fw-bold mb-4">Подписаться</h5>
            <p>Получайте уведомления о новых мероприятиях:</p>
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
            <small class="text-muted"
              >Мы не спамим. Только важные анонсы.</small
            >
          </div>
        </div>

        <hr class="my-4 bg-light" />

        <div class="row">
          <div class="col-md-6 text-center text-md-start">
            <p class="mb-0">© 2024 EventPass. Все права защищены.</p>
          </div>
          <div class="col-md-6 text-center text-md-end">
            <a href="#" class="text-white text-decoration-none me-3"
              >Политика конфиденциальности</a
            >
            <a href="#" class="text-white text-decoration-none"
              >Условия использования</a
            >
          </div>
        </div>
      </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.querySelectorAll(".sidebar-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
          document
            .querySelectorAll(".sidebar-btn")
            .forEach((b) => b.classList.remove("active"));
          this.classList.add("active");
        });
      });
    </script>
  </body>
</html>
