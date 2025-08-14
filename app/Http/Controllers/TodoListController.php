<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Log;
use function PHPUnit\Framework\isNumeric;
use App\Models\TodoListItem;
class TodoListController extends Controller
{


    private function getItem($id = -1)
    {
        if ($id == -1) {
            return TodoListItem::all();
        }
        return TodoListItem::find($id);
    }
    private function updateListItem($id, $column, $data, $bind)
    {

        $item = TodoListItem::find($id);
        $item->{$column} = $data;

        return $item->save();
    }
    private function deleteListItem($id)
    {
        return TodoListItem::destroy($id);
    }
    public function getUI()
    {
        $data = $this->getItem();

        return view('ToDoList', ["data" => $data]);


    }
    public function addItem(Request $request)
    {
        $newItem = new TodoListItem;
        $newItem->title = $request->title;
        $newItem->body = $request->body;
        if ($newItem->save()) {

            return redirect("/");

        }
        return response()->json(
            [
                "success" => false,
                "error" => "Error adding data"
            ]
        );

    }
    public function finishItem(Request $req)
    {
        $item = $this->getItem($req->id);
        $item->is_done = true;
        if($item->save()) {
            return redirect("/");
        } else {
            return response().json([
                "success"=>false, 
                "message"=>"Database Error",
            ]);
        }
    }
    public function editItem($id)
    {
        try {
            $data = $this->getItem($id);
            return view("EditToDoList", ["id" => $id, "data" => $data]);

        } catch (Exception $e) {
            return response()->setContent("Database Error");
        }
    }
    private function saveItemReq(Request $req)
    {
        if (!$this->updateListItem($req->id, "is_done", intval($req->is_done == "on"), "sd")) {
            throw new Exception("Problem updating status");
        }

        if (!$this->updateListItem($req->id, "title", ($req->title), "ss")) {
            throw new Exception("Problem updating status");
        }

        if (!$this->updateListItem($req->id, "body", ($req->body), "ss")) {
            throw new Exception("Problem updating status");
        }
        return true;
    }
    public function saveItem(Request $req)
    {
        try {
            if ($this->saveItemReq($req)) {
                return redirect("/");
            }
        } catch (Exception $e) {

            return response()->json([
                "success" => false,
                "message" => "Database Error"
            ]);
        }
    }

    public function daleteItem(Request $req)
    {
        if ($this->deleteListItem($req->id)) {
            return redirect("/");
        }

        return response()->json([
            "success" => false,
            "message" => "Database Error"
        ]);
    }



}
