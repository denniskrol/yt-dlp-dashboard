<?php

use App\Livewire\Items;
use Illuminate\Support\Facades\Route;

Route::get('/', Items::class)->name('items');
