<?php

namespace App\Filament\Widgets;

use App\Enums\ShiftStatus;
use App\Models\AmbulanceShift;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Guava\Calendar\Filament\Actions\CreateAction;
use Guava\Calendar\Filament\Actions\EditAction;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ShiftCalendarWidget extends CalendarWidget
{
    protected string $view = 'guava-calendar::widgets.calendar-widget';

    protected bool $dateClickEnabled = true;

    protected bool $eventClickEnabled = true;

    public function onDateClick(DateClickInfo $info): void
    {
        $existingShift = AmbulanceShift::where('user_id', Auth::id())
            ->where('date', $info->date->toDateString())
            ->first();

        if ($existingShift) {
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
        $user = Auth::user();
        $isAdminOrGestor = in_array($user->role ?? '', ['admin', 'gestor']);

        if ($record instanceof AmbulanceShift && ($isAdminOrGestor || $record->user_id === Auth::id())) {
            $this->mountAction('edit', [
                'record' => $record,
            ]);

            return;
        }

        parent::onEventClick($info, $record, $action);
    }

    public function editAction(): \Guava\Calendar\Filament\Actions\EditAction
    {
        return EditAction::make('editShift')
            ->recordTitle(fn ($record) => $record->user->name.' - '.$record->date)
            ->fillForm(fn (AmbulanceShift $record) => [
                'date' => $record->date,
                'status' => $record->status->value,
            ])
            ->action(function (array $data, AmbulanceShift $record) {
                $user = Auth::user();
                $isAdminOrGestor = in_array($user->role ?? '', ['admin', 'gestor']);

                if (! $isAdminOrGestor) {
                    Notification::make()
                        ->title(__('app.shifts.error'))
                        ->body(__('No tienes permiso para editar turnos.'))
                        ->danger()
                        ->send();

                    return;
                }

                try {
                    $statusValue = $data['status'];

                    if ($statusValue instanceof ShiftStatus) {
                        $newStatus = $statusValue;
                    } else {
                        $newStatus = ShiftStatus::from($statusValue);
                    }

                    $date = $record->date->toDateString();

                    $conflictingShift = AmbulanceShift::where('date', $date)
                        ->where('status', $newStatus)
                        ->where('id', '!=', $record->id)
                        ->first();

                    if ($conflictingShift) {
                        $type = $newStatus === ShiftStatus::EnReserva ? 'reserva' : 'aceptado';
                        Notification::make()
                            ->title(__('app.shifts.error'))
                            ->body(__("Ya existe un turno $type para esta fecha."))
                            ->danger()
                            ->send();

                        return;
                    }

                    $record->status = $newStatus;
                    $record->save();

                    Notification::make()
                        ->title(__('app.shifts.updated'))
                        ->success()
                        ->send();
                } catch (\Illuminate\Validation\ValidationException $e) {
                    Notification::make()
                        ->title(__('app.shifts.error'))
                        ->body($e->validator->errors()->first())
                        ->danger()
                        ->send();
                }
            })
            ->form(function ($record) {
                $user = Auth::user();
                $isAdminOrGestor = in_array($user->role ?? '', ['admin', 'gestor']);

                return [
                    \Filament\Forms\Components\DatePicker::make('date')
                        ->label(__('app.shifts.date'))
                        ->required()
                        ->disabled(),
                    \Filament\Forms\Components\Select::make('status')
                        ->label(__('app.shifts.status'))
                        ->options(ShiftStatus::class)
                        ->required()
                        ->disabled(! $isAdminOrGestor),
                ];
            });
    }

    public function getEvents(FetchInfo $fetchInfo): Collection|array
    {
        return AmbulanceShift::query()
            ->with('user')
            ->whereBetween('date', [$fetchInfo->start, $fetchInfo->end])
            ->get()
            ->map(function (AmbulanceShift $shift) {
                $isMe = $shift->user_id === Auth::id();
                $name = $shift->user->name;

                $title = $name;
                if ($shift->status === ShiftStatus::EnReserva) {
                    $title .= ' (Reserva)';
                }

                if ($shift->status !== ShiftStatus::Accepted) {
                    $title .= ' ['.$shift->status->getLabel().']';
                }

                $color = match ($shift->status) {
                    ShiftStatus::Accepted => $isMe ? '#0ea5e9' : '#10b981',
                    ShiftStatus::EnReserva => '#f59e0b',
                    ShiftStatus::Pending => '#8b5cf6',
                    ShiftStatus::Rejected => '#ef4444',
                };

                $textColor = '#ffffff';

                return CalendarEvent::make($shift)
                    ->title($title)
                    ->start($shift->date)
                    ->end($shift->date)
                    ->backgroundColor($color)
                    ->textColor($textColor);
            });
    }

    public function createAction(?string $model = null, ?string $name = null): CreateAction
    {
        return CreateAction::make('create')
            ->model($model ?? AmbulanceShift::class)
            ->mountUsing(function ($form, array $arguments) {
                $date = Carbon::parse($arguments['start'] ?? now());

                $hasRegular = AmbulanceShift::where('date', $date->toDateString())
                    ->where('status', ShiftStatus::Accepted)
                    ->exists();

                $hasReserve = AmbulanceShift::where('date', $date->toDateString())
                    ->where('status', ShiftStatus::EnReserva)
                    ->exists();

                $form->fill([
                    'date' => $date->toDateString(),
                    'user_id' => Auth::id(),
                    'status' => ShiftStatus::Pending,
                    '_has_regular' => $hasRegular,
                    '_has_reserve' => $hasReserve,
                ]);
            })
            ->form([
                \Filament\Forms\Components\DatePicker::make('date')
                    ->label(__('app.shifts.date'))
                    ->required()
                    ->readOnly(),

                \Filament\Forms\Components\Select::make('status')
                    ->label(__('app.shifts.status'))
                    ->options(function ($get) {
                        $options = [];

                        if (! $get('_has_regular')) {
                            $options[ShiftStatus::Accepted->value] = ShiftStatus::Accepted->getLabel();
                        }

                        if (! $get('_has_reserve')) {
                            $options[ShiftStatus::EnReserva->value] = ShiftStatus::EnReserva->getLabel();
                        }

                        if (! $get('_has_regular') || ! $get('_has_reserve')) {
                            $options[ShiftStatus::Pending->value] = ShiftStatus::Pending->getLabel();
                        }

                        return $options;
                    })
                    ->required()
                    ->default(ShiftStatus::Pending),

                \Filament\Forms\Components\Hidden::make('_has_regular'),
                \Filament\Forms\Components\Hidden::make('_has_reserve'),
            ])
            ->action(function (array $data) {
                $user = Auth::user();
                $date = Carbon::parse($data['date']);
                $status = ShiftStatus::from($data['status']);

                if (AmbulanceShift::where('user_id', $user->id)
                    ->where('date', $date->toDateString())
                    ->exists()
                ) {
                    Notification::make()
                        ->title(__('app.shifts.error'))
                        ->body(__('app.shifts.already_assigned'))
                        ->danger()
                        ->send();

                    return;
                }

                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                $shiftsThisMonth = AmbulanceShift::where('user_id', $user->id)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->whereIn('status', [ShiftStatus::Pending, ShiftStatus::Accepted, ShiftStatus::EnReserva])
                    ->count();

                if ($user->monthly_shift_limit && $shiftsThisMonth >= $user->monthly_shift_limit) {
                    Notification::make()
                        ->title(__('app.shifts.monthly_limit_reached'))
                        ->body(__('app.shifts.monthly_limit_reached', ['limit' => $user->monthly_shift_limit]))
                        ->danger()
                        ->send();

                    return;
                }

                try {
                    AmbulanceShift::create([
                        'user_id' => $user->id,
                        'date' => $data['date'],
                        'status' => $status,
                    ]);

                    Notification::make()
                        ->title(__('app.shifts.sent'))
                        ->body(__('app.shifts.pending_approval'))
                        ->success()
                        ->send();
                } catch (\Illuminate\Validation\ValidationException $e) {
                    Notification::make()
                        ->title(__('app.shifts.error'))
                        ->body($e->validator->errors()->first())
                        ->danger()
                        ->send();
                }
            });
    }
}
