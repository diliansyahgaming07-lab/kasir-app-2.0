<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_number')
                    ->required()
                    ->maxLength(255)
                    ->label('Invoice Number'),
                Forms\Components\TextInput::make('customer_name')
                    ->required()
                    ->maxLength(255)
                    ->default('Umum')
                    ->label('Customer Name'),
                Forms\Components\Select::make('member_id')
                    ->relationship('member', 'name')
                    ->nullable()
                    ->label('Member'),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Total Amount'),
                Forms\Components\TextInput::make('paid_amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Paid Amount'),
                Forms\Components\TextInput::make('change_amount')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp')
                    ->label('Change Amount'),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'cash' => 'Tunai',
                        'qris' => 'QRIS',
                        'debit' => 'Debit',
                        'credit' => 'Kredit',
                    ])
                    ->required()
                    ->default('cash')
                    ->label('Payment Method'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('completed')
                    ->label('Status'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Cashier'),
                Forms\Components\Textarea::make('notes')
                    ->nullable()
                    ->label('Notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment')
                    ->formatStateUsing(fn ($state) => [
                        'cash' => '💵 Tunai',
                        'qris' => '📱 QRIS',
                        'debit' => '💳 Debit',
                        'credit' => '💎 Kredit',
                    ][$state] ?? $state),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cashier'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Tunai',
                        'qris' => 'QRIS',
                        'debit' => 'Debit',
                        'credit' => 'Kredit',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}