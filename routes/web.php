<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;
Route::get('/', [TodoListController::class, "getUI"]);

Route::post("/add_item", [TodoListController::class, "addItem"])->name("add");
Route::post("/finish_item", [TodoListController::class, "finishItem"])->name("finish");
Route::post("/delete_item", [TodoListController::class, "daleteItem"])->name("delete");
Route::get("/edit_item/{id}", [TodoListController::class, "editItem"])->name("edit");
Route::post("/save_item", [TodoListController::class, "saveItem"])->name("save");