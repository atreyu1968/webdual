@extends('layouts.app')

@section('title', 'Notificaciones')

@section('styles')
<style>
    .notification-item {
        transition: all 0.3s ease;
        border-left: 4px solid #007bff;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    .notification-item.read {
        border-left-color: #6c757d;
        opacity: 0.7;
    }
    .notification-date {
        font-size: 0.85em;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Notificaciones</h2>
        @if($notificaciones->isNotEmpty())
        <button id="markAllRead" class="btn btn-outline-primary">
            Marcar todas como leídas
        </button>
        @endif
    </div>

    @if($notificaciones->isEmpty())
        <div class="alert alert-info" role="alert">
            No tienes notificaciones.
        </div>
    @else
        <div id="notificationList">
            @foreach($notificaciones as $notificacion)
                <div class="card mb-3 notification-item {{ $notificacion->leida ? 'read' : '' }}" data-id="{{ $notificacion->id }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $notificacion->titulo }}</h5>
                        <p class="card-text">{{ $notificacion->mensaje }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="notification-date">{{ $notificacion->created_at->diffForHumans() }}</small>
                            <div>
                                @if(!$notificacion->leida)
                                    <button class="btn btn-sm btn-outline-primary mark-read">Marcar como leída</button>
                                @endif
                                <button class="btn btn-sm btn-outline-danger delete-notification">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $notificaciones->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.mark-read').on('click', function() {
        var notificationItem = $(this).closest('.notification-item');
        var notificationId = notificationItem.data('id');
        
        $.ajax({
            url: '/notificaciones/' + notificationId + '/marcar-como-leida',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                notificationItem.addClass('read');
                notificationItem.find('.mark-read').remove();
            }
        });
    });

    $('.delete-notification').on('click', function() {
        var notificationItem = $(this).closest('.notification-item');
        var notificationId = notificationItem.data('id');
        
        if(confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
            $.ajax({
                url: '/notificaciones/' + notificationId,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    notificationItem.remove();
                    if($('.notification-item').length === 0) {
                        $('#notificationList').html('<div class="alert alert-info" role="alert">No tienes notificaciones.</div>');
                    }
                }
            });
        }
    });

    $('#markAllRead').on('click', function() {
        $.ajax({
            url: '/notificaciones/marcar-todas-como-leidas',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('.notification-item').addClass('read');
                $('.mark-read').remove();
            }
        });
    });
});
</script>
@endsection