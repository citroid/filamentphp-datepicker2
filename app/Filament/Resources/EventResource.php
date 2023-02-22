<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('event_date')
                    ->label('Event Date')
                    ->displayFormat('d.m.Y')
                    ->closeOnDateSelection()
                    ->minDate(now())
                    ->reactive()
                    ->required(),
                DatePicker::make('arrival_date')
                    ->label('Arrival Date')
                    ->displayFormat('d.m.Y')
                    ->closeOnDateSelection()
                    ->minDate(now())
                    ->maxDate(function (callable $get) {
                        // Calculate max of arrival date depending on event date
                        $eventDate = $get('event_date');
                        if ($eventDate == null) {
                            Log::info("Retrieved value of event_date is null.");
                            return null;
                        } else {
                            Log::info("Retrieved value of event_date: " . Carbon::parse($eventDate)->toDateString());
                            Log::info("Set maxDate for 'arrival_date' to: " . Carbon::parse($eventDate)->subDays(1)->toDateString());
                            return Carbon::parse($eventDate)->subDays(1);
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
