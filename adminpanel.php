<?php
require_once("server/db.php");
session_start();
if ($_SESSION["logged"] !== true){
    header('location: adminauth.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель EventPass</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --accent-color: #fd7e14;
        }
        .admin-sidebar {
            background: #212529;
            min-height: 100vh;
        }
        .sidebar-link {
            color: rgba(255,255,255,0.7);
            transition: all 0.3s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .ticket-card {
            border-left: 4px solid var(--primary-color);
        }
        .dynamic-form-item {
            transition: all 0.3s;
        }
        .btn-add-ticket {
            background: var(--accent-color);
            border: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 admin-sidebar p-0">
                <div class="p-4 text-white">
                    <h4><i class="bi bi-ticket-perforated"></i> EventPass Admin</h4>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link sidebar-link active" href="#"><i class="bi bi-plus-circle me-2"></i>Добавить мероприятие</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebar-link" href="#"><i class="bi bi-list-ul me-2"></i>Все мероприятия</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebar-link" href="#"><i class="bi bi-people me-2"></i>Пользователи</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebar-link" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Выйти</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-10 p-4">
                <h2 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Добавить мероприятие</h2>
                
                <form action="./server/save_event.php" method="POST" enctype="multipart/form-data">
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            Основная информация
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Название мероприятия</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Дата</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Время</label>
                                    <input type="time" name="time" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Место проведения</label>
                                <input type="text" name="place" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Организатор</label>
                                <input type="text" name="organizer" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Описание</label>
                                <textarea name="description" class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Изображение</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            Что ждет посетителей
                        </div>
                        <div class="card-body" id="benefits-container">
                            <div class="dynamic-form-item mb-3">
                                <div class="row g-2">
                                    <div class="col-md-1">
                                        <select name="benefit[0][icon]" class="form-select">
                                            <option value="bi-lightning-charge">⚡</option>
                                            <option value="bi-music-note-list">🎵</option>
                                            <option value="bi-magic">🎩</option>
                                            <option value="bi-gift">🎁</option>
                                        </select>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="benefit[0][text]" class="form-control" placeholder="Описание преимущества">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-sm btn-primary" onclick="addBenefit()">
                                <i class="bi bi-plus"></i> Добавить пункт
                            </button>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            Программа мероприятия
                        </div>
                        <div class="card-body" id="program-container">
                            <div class="dynamic-form-item mb-3">
                                <div class="row g-2">
                                    <div class="col-md-2">
                                        <input type="time" name="program[0][time]" class="form-control">
                                    </div>
                                    <div class="col-md-1">
                                        <select name="program[0][icon]" class="form-select">
                                            <option value="bi-door-open">🚪</option>
                                            <option value="bi-mic">🎤</option>
                                            <option value="bi-boombox">🔊</option>
                                            <option value="bi-emoji-smile">😊</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="program[0][text]" class="form-control" placeholder="Описание этапа">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-sm btn-primary" onclick="addProgramItem()">
                                <i class="bi bi-plus"></i> Добавить пункт
                            </button>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            Билеты
                        </div>
                        <div class="card-body" id="tickets-container">
                            <div class="dynamic-form-item mb-3 ticket-card p-3">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <select name="tickets[0][type]" class="form-select">
                                            <option value="primary">VIP</option>
                                            <option value="info">Партер</option>
                                            <option value="success">Трибуны</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="tickets[0][name]" class="form-control" placeholder="Название билета">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="tickets[0][price]" class="form-control" placeholder="Цена">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                                <div class="mt-2" id="ticket-benefits-container-0">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-10">
                                            <input type="text" name="tickets[0][benefits][0]" class="form-control form-control-sm" placeholder="Преимущество билета">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeBenefit(this)">×</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-add-ticket text-white mt-2" onclick="addTicketBenefit(0)">
                                    <i class="bi bi-plus"></i> Добавить преимущество
                                </button>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-sm btn-primary" onclick="addTicket()">
                                <i class="bi bi-plus"></i> Добавить билет
                            </button>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-lg btn-success">
                            <i class="bi bi-save"></i> Сохранить мероприятие
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let ticketCounter = 1;
    let benefitCounter = 1;
    let programCounter = 1;
    
    function addBenefit() {
    const container = document.getElementById('benefits-container');
    const newItem = document.createElement('div');
    newItem.className = 'dynamic-form-item mb-3';
    newItem.innerHTML = `
        <div class="row g-2">
            <div class="col-md-1">
                <select name="benefit_icon[]" class="form-select">
                    <option value="bi-lightning-charge">⚡</option>
                    <option value="bi-music-note-list">🎵</option>
                    <option value="bi-magic">🎩</option>
                    <option value="bi-gift">🎁</option>
                </select>
            </div>
            <div class="col-md-10">
                <input type="text" name="benefit[${benefitCounter}][text]" class="form-control" placeholder="Описание преимущества">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    benefitCounter++;
}
    
    function addProgramItem() {
        const container = document.getElementById('program-container');
        const newItem = document.createElement('div');
        newItem.className = 'dynamic-form-item mb-3';
        newItem.innerHTML = `
            <div class="row g-2">
                <div class="col-md-2">
                    <input type="time" name="program[${programCounter}][time]" class="form-control">
                </div>
                <div class="col-md-1">
                    <select name="program[${programCounter}][icon]" class="form-select">
                        <option value="bi-door-open">🚪</option>
                        <option value="bi-mic">🎤</option>
                        <option value="bi-boombox">🔊</option>
                        <option value="bi-emoji-smile">😊</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <input type="text" name="program[${programCounter}][text]" class="form-control" placeholder="Описание этапа">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        programCounter++;
    }
    
    function addTicket() {
        const container = document.getElementById('tickets-container');
        const newItem = document.createElement('div');
        newItem.className = 'dynamic-form-item mb-3 ticket-card p-3';
        newItem.innerHTML = `
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="tickets[${ticketCounter}][type]" class="form-select">
                        <option value="primary">VIP</option>
                        <option value="info">Партер</option>
                        <option value="success">Трибуны</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="tickets[${ticketCounter}][name]" class="form-control" placeholder="Название билета">
                </div>
                <div class="col-md-3">
                    <input type="number" name="tickets[${ticketCounter}][price]" class="form-control" placeholder="Цена">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)"><i class="bi bi-trash"></i></button>
                </div>
            </div>
            <div class="mt-2" id="ticket-benefits-container-${ticketCounter}">
                <div class="row g-2 mb-2">
                    <div class="col-md-10">
                        <input type="text" name="tickets[${ticketCounter}][benefits][0]" class="form-control form-control-sm" placeholder="Преимущество билета">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeBenefit(this)">×</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-add-ticket text-white mt-2" onclick="addTicketBenefit(${ticketCounter})">
                <i class="bi bi-plus"></i> Добавить преимущество
            </button>
        `;
        container.appendChild(newItem);
        ticketCounter++;
    }
    
    function addTicketBenefit(ticketId) {
        const container = document.getElementById(`ticket-benefits-container-${ticketId}`);
        const benefits = container.querySelectorAll('[name^="tickets[' + ticketId + '][benefits]"]').length;
        
        const newItem = document.createElement('div');
        newItem.className = 'row g-2 mb-2';
        newItem.innerHTML = `
            <div class="col-md-10">
                <input type="text" name="tickets[${ticketId}][benefits][${benefits}]" class="form-control form-control-sm" placeholder="Преимущество билета">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeBenefit(this)">×</button>
            </div>
        `;
        container.appendChild(newItem);
    }
    
    function removeItem(button) {
        button.closest('.dynamic-form-item').remove();
    }
    
    function removeBenefit(button) {
        button.closest('.row').remove();
    }
</script>
</body>
</html>