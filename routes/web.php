<?php

use App\Livewire\Items\Items;
use App\Livewire\Items\ShowItem;
use Illuminate\Support\Facades\Route;

Route::get('/', Items::class)->name('items');
Route::get('/items/{item}', ShowItem::class)->name('item');
