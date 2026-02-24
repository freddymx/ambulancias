<?php

namespace App\Filament\Widgets;

use App\Enums\ShiftStatus;
use App\Models\AmbulanceShift;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Filament\Actions\DeleteAction;
use Guava\Calendar\Filament\Actions\CreateAction;
use Guava\Calendar\Filament\Actions\EditAction;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Guava\Calendar\ValueObjects\FetchInfo;
use Filament\Schemas\Schema;

class ShiftCalendarWidget extends CalendarWidget
{
    protected string $view = 'guava-calendar::widgets.calendar-widget';
    protected bool $dateClickEnabled = true;
    protected bool $eventClickEnabled = true;

    public function onDateClick(DateClickInfo $info): void
    {
        // Check if user already has a shift on this date
        $existingShift = AmbulanceShift::where('user_id', Auth::id())
            ->where('date', $info->date->toDateString())
            ->first();

        if ($existingShift) {
            // If shift exists, open edit modal
            $this->mountAction('edit', [
                'record' => $existingShift,
            ]);
            return;
        }

        $this->mountAction('create', [
            'start' => $info->date,
            'end' => $info->date,
            'allDay' => $info->allDay,
        ]);
    }

    public function onEventClick(EventClickInfo $info, Model $record, ?string $action = null): void
    {
        // Override parent logic to force edit action if it's the user's shift
        if ($record instanceof AmbulanceShift && $record->user_id === Auth::id()) {
            $this->mountAction('edit', [
                'record' => $record,
            ]);
            return;
        }

        // Fallback to default behavior
        parent::onEventClick($info, $record, $action);
    }

    public function editAction(): EditAction
    {
        return EditAction::make()
            ->model(AmbulanceShift::class)
            ->form(function ($record) {
                $user = Auth::user();
                $isAdminOrGestor = in_array($user->role, ['admin', 'gestor']);

                return [
                    DatePicker::make('date')
                        ->label('Fecha')
                        ->required()
                        ->readOnly(),
                    Checkbox::make('is_reserve')
                        ->label('Reserva')
                        ->disabled(!$isAdminOrGestor)
                        ->dehydrated($isAdminOrGestor),
                    \Filament\Forms\Components\Select::make('status')
                        ->label('Estado')
                        ->options(ShiftStatus::class)
                        ->required()
                        ->disabled(!$isAdminOrGestor)
                        ->dehydrated($isAdminOrGestor),
                ];
            })
            ->modalFooterActions(function (EditAction $action) {
                $record = $action->getRecord();
                $user = Auth::user();
                $isAdminOrGestor = in_array($user->role, ['admin', 'gestor']);
                
                // User can delete their own pending/rejected shifts
                $canDelete = $record && $record->user_id === $user->id &&
                    in_array($record->status, [ShiftStatus::Pending, ShiftStatus::Rejected]);
                
                // Admins/Gestors can delete any shift
                if ($isAdminOrGestor) {
                    $canDelete = true;
                }

                $actions = [];

                if ($canDelete) {
                    $actions[] = DeleteAction::make('delete')
                        ->requiresConfirmation()
                        ->action(function (DeleteAction $deleteAction) use ($action) {
                            $action->getRecord()->delete();
                            
                            Notification::make()
                                ->title('Solicitud eliminada')
                                ->success()
                                ->send();
                                
                            $this->refreshRecords(); 
                            $action->cancel();
                        })
                        ->after(fn () => $action->cancel()); // Ensure parent closes
                }

                // If admin/gestor, add standard save button
                if ($isAdminOrGestor) {
                    $actions[] = \Filament\Actions\Action::make('save')
                        ->label('Guardar')
                        ->color('primary')
                        ->action(function (array $data) use ($action) {
                            $action->getRecord()->update($data);
                            $this->refreshRecords();
                            $action->cancel();
                            
                            Notification::make()
                                ->title('Turno actualizado')
                                ->success()
                                ->send();
                        });
                }

                $actions[] = $action->getModalCancelAction();

                return $actions;
            });
    }

    public function getEvents(FetchInfo $fetchInfo): Collection | array
    {
        return AmbulanceShift::query()
            ->with('user')
            ->whereBetween('date', [$fetchInfo->start, $fetchInfo->end])
            ->get()
            ->map(function (AmbulanceShift $shift) {
                $isMe = $shift->user_id === Auth::id();
                $name = $shift->user->name;

                $title = $name;
                if ($shift->is_reserve) {
                    $title .= ' (Reserva)';
                }

                // Status indicator in title if not accepted
                if ($shift->status !== ShiftStatus::Accepted) {
                    $title .= ' [' . $shift->status->getLabel() . ']';
                }

                $color = match ($shift->status) {
                    ShiftStatus::Accepted => $shift->is_reserve ? '#fbbf24' : ($isMe ? '#3b82f6' : '#10b981'), // Amber (reserve), Blue (me), Green (others)
                    ShiftStatus::Pending => '#9ca3af', // Gray/Zinc for pending
                    ShiftStatus::Rejected => '#ef4444', // Red for rejected
                    default => '#9ca3af',
                };

                // If rejected, maybe lighter color or strike-through style?
                // For now, just red.

                return CalendarEvent::make($shift)
                    ->title($title)
                    ->start($shift->date)
                    ->end($shift->date)
                    ->backgroundColor($color);
            });
    }

    public function createAction(string $model = null, ?string $name = null): CreateAction
    {
        return CreateAction::make('create')
            ->model($model ?? AmbulanceShift::class)
            ->mountUsing(function ($form, array $arguments) {
                $date = Carbon::parse($arguments['start'] ?? now());

                // Check current shifts on this day
                $shiftsCount = AmbulanceShift::where('date', $date->toDateString())
                    ->where('status', ShiftStatus::Accepted)
                    ->where('is_reserve', false)
                    ->count();

                $maxPerDay = 2; // Hardcoded for now
                $mustBeReserve = $shiftsCount >= $maxPerDay;

                $form->fill([
                    'date' => $date->toDateString(),
                    'user_id' => Auth::id(),
                    'is_reserve' => $mustBeReserve,
                    'must_be_reserve' => $mustBeReserve, // Helper field
                ]);
            })
            ->form([
                DatePicker::make('date')
                    ->label('Fecha')
                    ->required()
                    ->readOnly(),

                Checkbox::make('is_reserve')
                    ->label('Solicitar como Reserva')
                    ->helperText(fn($get) => $get('must_be_reserve') ? 'El día está completo. Solo puedes solicitar reserva.' : 'Marca esta casilla si deseas ser reserva.')
                    ->disabled(fn($get) => $get('must_be_reserve'))
                    ->dehydrated()
                    ->default(false),

                // Hidden field to store the logic result
                Checkbox::make('must_be_reserve')
                    ->hidden()
                    ->dehydrated(false),
            ])
            ->action(function (array $data) {
                $user = Auth::user();
                $date = Carbon::parse($data['date']);

                // 1. Check if user already has a shift on this day (pending or accepted)
                if (AmbulanceShift::where('user_id', $user->id)
                    ->where('date', $date->toDateString())
                    ->whereIn('status', [ShiftStatus::Pending, ShiftStatus::Accepted])
                    ->exists()
                ) {

                    Notification::make()
                        ->title('Error')
                        ->body('Ya tienes una solicitud o turno asignado para este día.')
                        ->danger()
                        ->send();
                    return;
                }

                // 2. Check monthly limit (only count Accepted and Pending?)
                // Requirement: "El personal tiene un límite global... El personal no puede superar este límite."
                // Usually this applies to assigned shifts. But maybe pending counts towards "potential"?
                // Let's count Accepted + Pending to be safe, or just Accepted.
                // If we only count Accepted, user might spam requests.
                // Let's count both for now to prevent spamming.
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                $shiftsThisMonth = AmbulanceShift::where('user_id', $user->id)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->whereIn('status', [ShiftStatus::Pending, ShiftStatus::Accepted])
                    ->count();

                if ($user->monthly_shift_limit && $shiftsThisMonth >= $user->monthly_shift_limit) {
                    Notification::make()
                        ->title('Límite Mensual Alcanzado')
                        ->body("Has alcanzado tu límite de {$user->monthly_shift_limit} solicitudes/turnos para este mes.")
                        ->danger()
                        ->send();
                    return;
                }

                AmbulanceShift::create([
                    'user_id' => $user->id,
                    'date' => $data['date'],
                    'is_reserve' => $data['is_reserve'],
                    'status' => ShiftStatus::Pending,
                ]);

                Notification::make()
                    ->title('Solicitud Enviada')
                    ->body('Tu solicitud ha sido registrada y está pendiente de aprobación.')
                    ->success()
                    ->send();
            });
    }
}
