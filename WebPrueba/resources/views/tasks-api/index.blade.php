<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Tareas (API)</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <style>
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .table-actions {
            white-space: nowrap;
        }
        .pagination-custom .page-link {
            color: #007bff;
            border: 1px solid #dee2e6;
        }
        .pagination-custom .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        .modal-backdrop {
            z-index: 1040 !important;
        }
        .modal {
            z-index: 1050 !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">Inicio</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    <span class="ml-1">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">
                        <p class="mb-0">{{ Auth::user()->name }}</p>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </span>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">Usuario</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Página Principal</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Tablas <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('tasks.index') }}" class="nav-link">
                                    <i class="fas fa-calendar nav-icon"></i>
                                    <p>Tareas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('api-tasks.index') }}" class="nav-link active">
                                    <i class="fas fa-satellite-dish"></i>
                                    <p>Tareas API</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Gestión de Tareas (Modo API)</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                            <li class="breadcrumb-item active">Tareas (API)</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div id="alert-container"></div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Tareas</h3>
                        <div class="card-tools">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createTaskModal">
                                <i class="fas fa-plus"></i> Nueva Tarea
                            </button>
                            <button class="btn btn-success btn-sm" onclick="loadTasks()">
                                <i class="fas fa-sync-alt"></i> Actualizar
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-0" id="pagination-info">
                                    Cargando información...
                                </p>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="form-inline justify-content-end">
                                    <label for="perPage" class="mr-2">Mostrar:</label>
                                    <select class="form-control form-control-sm" id="perPage" onchange="changeItemsPerPage()">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 5%">ID</th>
                                        <th style="width: 20%">Título</th>
                                        <th style="width: 25%">Descripción</th>
                                        <th style="width: 12%">Fecha Vencimiento</th>
                                        <th style="width: 12%">Estado</th>
                                        <th style="width: 12%">Urgencia</th>
                                        <th style="width: 14%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tasks-table-body">
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="loading-spinner mb-2"></div>
                                            <p class="text-muted mb-0">Cargando tareas...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p class="text-muted" id="page-info">
                                    Cargando información de página...
                                </p>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                    <ul class="pagination pagination-custom mb-0" id="pagination-controls">
                                        <li class="page-item disabled">
                                            <span class="page-link">Cargando...</span>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Crear Nueva Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createTaskForm">
                    <div id="create-error-container" class="alert alert-danger" style="display: none;"></div>

                    <div class="form-group">
                        <label for="create_title">Título *</label>
                        <input type="text" class="form-control" id="create_title" name="title" required>
                        <div class="invalid-feedback" id="create_title_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="create_description">Descripción</label>
                        <textarea class="form-control" id="create_description" name="description" rows="4"></textarea>
                        <div class="invalid-feedback" id="create_description_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="create_due_date">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" id="create_due_date" name="due_date">
                        <div class="invalid-feedback" id="create_due_date_error"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_status">Estado *</label>
                                <select class="form-control" id="create_status" name="status" required>
                                    <option value="">Seleccione...</option>
                                    <option value="pending">Pendiente</option>
                                    <option value="in_progress">En Progreso</option>
                                    <option value="completed">Completada</option>
                                </select>
                                <div class="invalid-feedback" id="create_status_error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="create_urgency">Urgencia *</label>
                                <select class="form-control" id="create_urgency" name="urgency" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Baja">Baja</option>
                                    <option value="Media">Media</option>
                                    <option value="Alta">Alta</option>
                                </select>
                                <div class="invalid-feedback" id="create_urgency_error"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCreateTask">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Editar Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <div id="edit-error-container" class="alert alert-danger" style="display: none;"></div>
                    <input type="hidden" id="edit_task_id" name="id">

                    <div class="form-group">
                        <label for="edit_title">Título *</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                        <div class="invalid-feedback" id="edit_title_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Descripción</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="4"></textarea>
                        <div class="invalid-feedback" id="edit_description_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit_due_date">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" id="edit_due_date" name="due_date">
                        <div class="invalid-feedback" id="edit_due_date_error"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_status">Estado *</label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="pending">Pendiente</option>
                                    <option value="in_progress">En Progreso</option>
                                    <option value="completed">Completada</option>
                                </select>
                                <div class="invalid-feedback" id="edit_status_error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_urgency">Urgencia *</label>
                                <select class="form-control" id="edit_urgency" name="urgency" required>
                                    <option value="Baja">Baja</option>
                                    <option value="Media">Media</option>
                                    <option value="Alta">Alta</option>
                                </select>
                                <div class="invalid-feedback" id="edit_urgency_error"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnUpdateTask">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

<script>
    const csrfToken = '{{ csrf_token() }}';
    const authToken = '{{ auth()->user()->tokens()->first()->token ?? "" }}';
    const baseUrl = '{{ url("/") }}';

    let currentPage = 1;
    let itemsPerPage = 10;
    let totalPages = 1;
    let totalTasks = 0;

    const STATUS_OPTIONS = {
        'pending': 'Pendiente',
        'in_progress': 'En Progreso',
        'completed': 'Completada'
    };

    const URGENCY_OPTIONS = {
        'Baja': 'Baja',
        'Media': 'Media',
        'Alta': 'Alta'
    };

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES');
        } catch (e) {
            return 'N/A';
        }
    }

    function getStatusBadge(status) {
        let badgeClass = 'badge-secondary';
        switch (status) {
            case 'pending': badgeClass = 'badge-warning'; break;
            case 'in_progress': badgeClass = 'badge-info'; break;
            case 'completed': badgeClass = 'badge-success'; break;
        }
        return `<span class="badge ${badgeClass}">${STATUS_OPTIONS[status] || 'Desconocido'}</span>`;
    }

    function getUrgencyBadge(urgency) {
        let badgeClass = 'badge-secondary';
        switch (urgency) {
            case 'Baja': badgeClass = 'badge-success'; break;
            case 'Media': badgeClass = 'badge-warning'; break;
            case 'Alta': badgeClass = 'badge-danger'; break;
        }
        return `<span class="badge ${badgeClass}">${URGENCY_OPTIONS[urgency] || 'Desconocida'}</span>`;
    }

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                ${message}
            </div>
        `;
        $('#alert-container').html(alertHtml);
        setTimeout(() => $('.alert').alert('close'), 5000);
    }

    $(document).ready(function() {
        $('#createTaskModal').on('hidden.bs.modal', function() {
            $('#createTaskForm')[0].reset();
            $('.invalid-feedback').empty();
            $('.form-control').removeClass('is-invalid');
            $('#create-error-container').hide().empty();
        });

        $('#editTaskModal').on('hidden.bs.modal', function() {
            $('.invalid-feedback').empty();
            $('.form-control').removeClass('is-invalid');
            $('#edit-error-container').hide().empty();
        });

        $('#btnCreateTask').click(function() {
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $('.invalid-feedback').empty();
            $('.form-control').removeClass('is-invalid');
            $('#create-error-container').hide().empty();

            const formData = {
                title: $('#create_title').val(),
                description: $('#create_description').val(),
                due_date: $('#create_due_date').val(),
                status: $('#create_status').val(),
                urgency: $('#create_urgency').val()
            };

            if (!formData.title || !formData.status || !formData.urgency) {
                showAlert('danger', 'Todos los campos marcados con * son obligatorios');
                btn.prop('disabled', false).html('Guardar');
                return;
            }

            $.ajax({
                url: baseUrl + '/api/tasks',
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': 'Bearer ' + authToken
                },
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message || 'Tarea creada exitosamente');
                        $('#createTaskModal').modal('hide');
                        loadTasks(currentPage);
                    } else {
                        $('#create-error-container').html(response.error || 'Error al crear la tarea').show();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '<ul>';
                        for (const field in errors) {
                            const input = $('#create_' + field);
                            const errorDiv = $('#create_' + field + '_error');
                            input.addClass('is-invalid');
                            errorDiv.text(errors[field][0]);
                            errorMessages += `<li>${errors[field][0]}</li>`;
                        }
                        errorMessages += '</ul>';
                        $('#create-error-container').html(errorMessages).show();
                    } else {
                        $('#create-error-container').html('Error de servidor: ' + xhr.statusText).show();
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('Guardar');
                }
            });
        });

        $(document).on('click', '.btn-edit', function() {
            const taskId = $(this).data('id');
            $('#edit_task_id').val(taskId);
            $('#edit_title').val($(this).data('title'));
            $('#edit_description').val($(this).data('description'));
            $('#edit_due_date').val($(this).data('due_date'));
            $('#edit_status').val($(this).data('status'));
            $('#edit_urgency').val($(this).data('urgency'));
        });

        $('#btnUpdateTask').click(function() {
            const btn = $(this);
            const taskId = $('#edit_task_id').val();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Actualizando...');

            $('.invalid-feedback').empty();
            $('.form-control').removeClass('is-invalid');
            $('#edit-error-container').hide().empty();

            const formData = {
                title: $('#edit_title').val(),
                description: $('#edit_description').val(),
                due_date: $('#edit_due_date').val(),
                status: $('#edit_status').val(),
                urgency: $('#edit_urgency').val()
            };

            if (!formData.title || !formData.status || !formData.urgency) {
                showAlert('danger', 'Todos los campos marcados con * son obligatorios');
                btn.prop('disabled', false).html('Actualizar');
                return;
            }

            $.ajax({
                url: baseUrl + '/api/tasks/' + taskId,
                type: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': 'Bearer ' + authToken
                },
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message || 'Tarea actualizada exitosamente');
                        $('#editTaskModal').modal('hide');
                        loadTasks(currentPage);
                    } else {
                        $('#edit-error-container').html(response.error || 'Error al actualizar la tarea').show();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Errores de validación
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '<ul>';
                        for (const field in errors) {
                            const input = $('#edit_' + field);
                            const errorDiv = $('#edit_' + field + '_error');
                            input.addClass('is-invalid');
                            errorDiv.text(errors[field][0]);
                            errorMessages += `<li>${errors[field][0]}</li>`;
                        }
                        errorMessages += '</ul>';
                        $('#edit-error-container').html(errorMessages).show();
                    } else {
                        $('#edit-error-container').html('Error de servidor: ' + xhr.statusText).show();
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('Actualizar');
                }
            });
        });
    });

    async function loadTasks(page = 1) {
        const tableBody = $('#tasks-table-body');
        try {
            tableBody.html(`
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="loading-spinner mb-2"></div>
                        <p class="text-muted mb-0">Cargando tareas...</p>
                    </td>
                </tr>
            `);

            const response = await fetch(`${baseUrl}/api/tasks?page=${page}&per_page=${itemsPerPage}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': 'Bearer ' + authToken
                }
            });

            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();

            if (result.success && result.data) {
                currentPage = result.pagination.current_page;
                itemsPerPage = result.pagination.per_page;
                totalPages = result.pagination.last_page;
                totalTasks = result.pagination.total;

                updatePaginationInfo(result.pagination);
                displayTasks(result.data);
                showAlert('info', `Se cargaron ${result.data.length} tareas correctamente`);
            } else {
                throw new Error(result.error || 'Error desconocido');
            }

        } catch (error) {
            console.error('Error:', error);
            tableBody.html(`
                <tr>
                    <td colspan="7" class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p class="mb-0">Error al cargar las tareas</p>
                        <small>${error.message}</small>
                        <br>
                        <button onclick="loadTasks()" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-redo"></i> Reintentar
                        </button>
                    </td>
                </tr>
            `);
        }
    }

    function displayTasks(tasks) {
        const tableBody = $('#tasks-table-body');

        if (!tasks || tasks.length === 0) {
            tableBody.html(`
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-tasks fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay tareas registradas</p>
                        <button class="btn btn-primary btn-sm mt-2" data-toggle="modal" data-target="#createTaskModal">
                            <i class="fas fa-plus"></i> Crear Primera Tarea
                        </button>
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        tasks.forEach(task => {
            const description = task.description ?
                (task.description.length > 50 ? task.description.substring(0, 50) + '...' : task.description) :
                '<span class="text-muted">Sin descripción</span>';

            html += `
                <tr id="task-row-${task.id}">
                    <td><strong>${task.id}</strong></td>
                    <td>${task.title}</td>
                    <td>${description}</td>
                    <td>${formatDate(task.due_date)}</td>
                    <td>${getStatusBadge(task.status)}</td>
                    <td>${getUrgencyBadge(task.urgency)}</td>
                    <td class="table-actions text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-warning btn-edit"
                                    data-id="${task.id}"
                                    data-title="${task.title}"
                                    data-description="${task.description || ''}"
                                    data-due_date="${task.due_date ? task.due_date.substring(0,10) : ''}"
                                    data-status="${task.status}"
                                    data-urgency="${task.urgency}"
                                    data-toggle="modal"
                                    data-target="#editTaskModal"
                                    title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" onclick="deleteTask(${task.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        tableBody.html(html);
    }

    async function deleteTask(taskId) {
        if (!confirm('¿Estás seguro de eliminar esta tarea?')) return;

        try {
            const response = await fetch(`${baseUrl}/api/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': 'Bearer ' + authToken
                }
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.error || 'Error al eliminar tarea');
            }

            showAlert('success', 'Tarea eliminada correctamente');
            loadTasks(currentPage);

        } catch (error) {
            console.error('Error eliminar:', error);
            showAlert('danger', 'Error al eliminar la tarea: ' + error.message);
        }
    }

    function updatePaginationInfo(pagination) {
        const startItem = pagination.from || 0;
        const endItem = pagination.to || 0;

        $('#pagination-info').html(`
            Mostrando <strong>${startItem}</strong> a <strong>${endItem}</strong> de <strong>${pagination.total}</strong> tareas
        `);

        $('#page-info').html(`
            Página <strong>${pagination.current_page}</strong> de <strong>${pagination.last_page}</strong>
        `);

        const paginationControls = $('#pagination-controls');
        let paginationHTML = '';

        paginationHTML += `
            <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadTasks(1)" aria-label="First">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
        `;

        paginationHTML += `
            <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadTasks(${pagination.current_page - 1})" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;

        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

        for (let page = startPage; page <= endPage; page++) {
            paginationHTML += `
                <li class="page-item ${page === pagination.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="loadTasks(${page})">${page}</a>
                </li>
            `;
        }

        paginationHTML += `
            <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadTasks(${pagination.current_page + 1})" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;

        paginationHTML += `
            <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadTasks(${pagination.last_page})" aria-label="Last">
                    <span aria-hidden="true">&raquo;&raquo;</span>
                </a>
            </li>
        `;

        paginationControls.html(paginationHTML);
    }

    function changeItemsPerPage() {
        itemsPerPage = parseInt($('#perPage').val());
        currentPage = 1;
        loadTasks(currentPage);
    }

    $(document).ready(function() {
        console.log('🚀 Iniciando aplicación Tareas API...');
        loadTasks();
    });
</script>
</body>
</html>
