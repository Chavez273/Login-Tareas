<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Tareas</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <style>
        .pagination-custom .page-link {
            color: #007bff;
            border: 1px solid #dee2e6;
        }
        .pagination-custom .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        .pagination-custom .page-link:hover {
            color: #0056b3;
            background-color: #e9ecef;
        }
        .table-actions {
            white-space: nowrap;
        }
        .status-badge {
            font-size: 0.85em;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
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
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notificaciones</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 nuevos mensajes
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 solicitudes de amistad
                        <span class="float-right text-muted text-sm">12 horas</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 nuevos reportes
                        <span class="float-right text-muted text-sm">2 días</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">Ver Todas</a>
                </div>
            </li>

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
                    <a href="#" class="dropdown-item"><i class="fas fa-user mr-2"></i> Perfil</a>
                    <a href="#" class="dropdown-item"><i class="fas fa-cog mr-2"></i> Configuración</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
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
                                <a href="{{ route('tasks.index') }}" class="nav-link active">
                                    <i class="fas fa-calendar nav-icon"></i>
                                    <p>Tareas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('api-tasks.index') }}" class="nav-link">
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
                        <h1 class="m-0">Gestión de Tareas</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                            <li class="breadcrumb-item active">Tareas</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Tareas</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="btnOpenCreateModal" data-toggle="modal" data-target="#createTaskModal">
                                <i class="fas fa-plus"></i> Nueva Tarea
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-0">
                                    Mostrando
                                    <strong>{{ $tasks->firstItem() ?? 0 }}</strong>
                                    a
                                    <strong>{{ $tasks->lastItem() ?? 0 }}</strong>
                                    de
                                    <strong>{{ $tasks->total() }}</strong>
                                    tareas
                                </p>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="form-inline justify-content-end">
                                    <label for="perPage" class="mr-2">Mostrar:</label>
                                    <select class="form-control form-control-sm" id="perPage" onchange="changePerPage(this.value)">
                                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
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
                                <tbody>
                                    @forelse($tasks as $task)
                                        <tr>
                                            <td><strong>{{ $task->id }}</strong></td>
                                            <td>{{ $task->title }}</td>
                                            <td>
                                                @if($task->description)
                                                    <span title="{{ $task->description }}">
                                                        {{ Str::limit($task->description, 50) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Sin descripción</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($task->due_date)
                                                    <span class="badge badge-light border">
                                                        {{ $task->due_date->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge status-badge badge-@switch($task->status)
                                                    @case('pending')warning @break
                                                    @case('in_progress')info @break
                                                    @case('completed')success @break
                                                @endswitch">
                                                    {{ $task->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge status-badge badge-@switch($task->urgency)
                                                    @case('Baja')success @break
                                                    @case('Media')warning @break
                                                    @case('Alta')danger @break
                                                @endswitch">
                                                    {{ $task->urgency_text }}
                                                </span>
                                            </td>
                                            <td class="table-actions text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-warning btn-edit"
                                                            data-id="{{ $task->id }}"
                                                            data-title="{{ $task->title }}"
                                                            data-description="{{ $task->description }}"
                                                            data-due_date="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                                            data-status="{{ $task->status }}"
                                                            data-urgency="{{ $task->urgency }}"
                                                            data-toggle="modal"
                                                            data-target="#editTaskModal"
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-tasks fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">No hay tareas registradas.</p>
                                                <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm mt-2">
                                                    <i class="fas fa-plus"></i> Crear Primera Tarea
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($tasks->hasPages())
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p class="text-muted">
                                    Página <strong>{{ $tasks->currentPage() }}</strong>
                                    de <strong>{{ $tasks->lastPage() }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                    <ul class="pagination pagination-custom mb-0">

                                        <li class="page-item {{ $tasks->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $tasks->url(1) }}" aria-label="First">
                                                <span aria-hidden="true">&laquo;&laquo;</span>
                                            </a>
                                        </li>

                                        <li class="page-item {{ $tasks->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $tasks->previousPageUrl() }}" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>

                                        @foreach ($tasks->getUrlRange(max(1, $tasks->currentPage() - 2), min($tasks->lastPage(), $tasks->currentPage() + 2)) as $page => $url)
                                            <li class="page-item {{ $page == $tasks->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endforeach

                                        <li class="page-item {{ $tasks->hasMorePages() ? '' : 'disabled' }}">
                                            <a class="page-link" href="{{ $tasks->nextPageUrl() }}" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>

                                        <li class="page-item {{ $tasks->currentPage() == $tasks->lastPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $tasks->url($tasks->lastPage()) }}" aria-label="Last">
                                                <span aria-hidden="true">&raquo;&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        @endif
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
                <form id="createTaskForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="create_title">Título *</label>
                        <input type="text" class="form-control" id="create_title" name="title" required>
                        <div class="invalid-feedback" id="create_title_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="create_description">Descripción</label>
                        <textarea class="form-control" id="create_description" name="description" rows="3"></textarea>
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
                <form id="editTaskForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_task_id" name="id">

                    <div class="form-group">
                        <label for="edit_title">Título *</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                        <div class="invalid-feedback" id="edit_title_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Descripción</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
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
function changePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    window.location.href = url.toString();
}

$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    $('.alert').click(function() {
        $(this).fadeOut('slow');
    });
});
</script>

<script>
$(document).ready(function() {
    $('#createTaskModal').on('hidden.bs.modal', function() {
        $('#createTaskForm')[0].reset();
        $('.invalid-feedback').empty();
        $('.form-control').removeClass('is-invalid');
    });

    $('#editTaskModal').on('hidden.bs.modal', function() {
        $('.invalid-feedback').empty();
        $('.form-control').removeClass('is-invalid');
    });

    $('#btnCreateTask').click(function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        $('.invalid-feedback').empty();
        $('.form-control').removeClass('is-invalid');

        var formData = {
            title: $('#create_title').val(),
            description: $('#create_description').val(),
            due_date: $('#create_due_date').val(),
            status: $('#create_status').val(),
            urgency: $('#create_urgency').val(),
            _token: $('input[name="_token"]').val()
        };

        $.ajax({
            url: '{{ route("tasks.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                showSuccessAlert('Tarea creada exitosamente.');

                $('#createTaskModal').modal('hide');

                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('Guardar');

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        var input = $('#create_' + field);
                        var errorDiv = $('#create_' + field + '_error');
                        input.addClass('is-invalid');
                        errorDiv.text(errors[field][0]);
                    }
                } else {
                    showErrorAlert('Error al crear la tarea.');
                }
            }
        });
    });

    $(document).on('click', '.btn-edit', function() {
        var taskId = $(this).data('id');
        $('#edit_task_id').val(taskId);
        $('#edit_title').val($(this).data('title'));
        $('#edit_description').val($(this).data('description'));
        $('#edit_due_date').val($(this).data('due_date'));
        $('#edit_status').val($(this).data('status'));
        $('#edit_urgency').val($(this).data('urgency'));
    });

    $('#btnUpdateTask').click(function() {
        var btn = $(this);
        var taskId = $('#edit_task_id').val();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Actualizando...');

        $('.invalid-feedback').empty();
        $('.form-control').removeClass('is-invalid');

        var formData = {
            title: $('#edit_title').val(),
            description: $('#edit_description').val(),
            due_date: $('#edit_due_date').val(),
            status: $('#edit_status').val(),
            urgency: $('#edit_urgency').val(),
            _token: $('input[name="_token"]').val(),
            _method: 'PUT'
        };

        $.ajax({
            url: '{{ url("tasks") }}/' + taskId,
            type: 'POST',
            data: formData,
            success: function(response) {
                showSuccessAlert('Tarea actualizada exitosamente.');

                $('#editTaskModal').modal('hide');

                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('Actualizar');

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        var input = $('#edit_' + field);
                        var errorDiv = $('#edit_' + field + '_error');
                        input.addClass('is-invalid');
                        errorDiv.text(errors[field][0]);
                    }
                } else {
                    showErrorAlert('Error al actualizar la tarea.');
                }
            }
        });
    });

    function showSuccessAlert(message) {
        var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        '<i class="fas fa-check-circle"></i> ' + message +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>';
        $('.content').prepend(alertHtml);

        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    function showErrorAlert(message) {
        var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<i class="fas fa-exclamation-circle"></i> ' + message +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>';
        $('.content').prepend(alertHtml);
    }
});
</script>

</body>
</html>
