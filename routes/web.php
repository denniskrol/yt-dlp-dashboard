<?php

use App\Livewire\Items\Items;
use App\Livewire\Items\ShowItem;
use App\Livewire\Playlists\Playlists;
use App\Livewire\Playlists\ShowPlaylist;
use Illuminate\Support\Facades\Route;

Route::get('/', Items::class)->name('items');
Route::get('/items/{item}', ShowItem::class)->name('item');

Route::get('/playlists', Playlists::class)->name('playlists');
Route::get('/playlists/{playlist}', ShowPlaylist::class)->name('playlist');
