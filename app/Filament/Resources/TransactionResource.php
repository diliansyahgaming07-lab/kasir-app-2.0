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
                    ->label('Invoice Number')
                    ->disabled(),
                Forms\Components\TextInput::make('customer_name')
                    ->label('Customer Name')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('No. Telepon')
                    ->disabled(),
                Forms\Components\Textarea::make('shipping_address')
                    ->label('Alamat Pengiriman')
                    ->disabled(),
                Forms\Components\Select::make('member_id')
                    ->relationship('member', 'name')
                    ->nullable()
                    ->label('Member'),
                Forms\Components\TextInput::make('total_amount')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Total Amount'),
                Forms\Components\TextInput::make('paid_amount')
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
                    ->default('pending')
                    ->label('Payment Status'),
                Forms\Components\Select::make('order_status')
                    ->options([
                        'pending' => '🕐 Menunggu Konfirmasi',
                        'processing' => '⚙️ Sedang Diproses',
                        'shipped' => '🚚 Dikirim',
                        'delivered' => '✅ Selesai',
                        'cancelled' => '❌ Dibatalkan',
                    ])
                    ->required()
                    ->default('pending')
                    ->label('Order Status'),
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
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_address')
                    ->label('Alamat')
                    ->limit(30),
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
                Tables\Columns\TextColumn::make('order_status')
                    ->label('Order Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Payment Status')
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
                Tables\Filters\SelectFilter::make('order_status')
                    ->options([
                        'pending' => 'Menunggu',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
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