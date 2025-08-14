<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNumeric;

class TodoListController_old extends Controller
{

    private function getConn()
    {
        $conn = mysqli_connect("localhost:3306", "root", "testpass", "todoList");
        if (!$conn) {
            throw new Exception("Database connection error");
        } else {
            return $conn;
        }
    }
    private function getItem($id = -1)
    {
        $condition = "";
        if (isNumeric($id)) {
            $condition = $id == -1 ? "" : "WHERE id=$id";
        } else {
            throw new Exception("Id must be int");
        }
        try {

            $conn = $this->getConn();

            $sql = "SELECT * FROM todo_list_item " . $condition;
            $statement = $conn->prepare($sql);
            // $statement->bind_param("s",$condition);

            $statement->execute();

            $result = $statement->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "error" => "Database Error",
                ]
            );
        }
    }
    private function updateListItem($id, $column, $data, $bind)
    {
        try {
            $conn = $this->getConn();
            $sql = "UPDATE todo_list_item SET $column = ? WHERE id = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param($bind, $data, $id);
            $statement->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    private function deleteListItem($id) {
        try {
            $conn = $this->getConn();
            $sql = "DELETE FROM todo_list_item WHERE id = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param("d", $id);
            $statement->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getUI()
    {
        try {

            $data = $this->getItem();

            return view('ToDoList', ["data" => $data]);
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "error" => "Database Error",
                ]
            );
        }
        ;

    }
    public function addItem(Request $request)
    {
        try {

            $conn = $this->getConn();
            $title = $request->title;
            $body = $request->body;

            $insert_sql = "INSERT INTO todo_list_item(title, body) VALUES(?, ?)";
            $statement = $conn->prepare($insert_sql);
            $statement->bind_param("ss", $title, $body);
            $statement->execute();

            return redirect("/");

        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "error" => $e->getMessage()
                ]
            );
        }
        ;

    }
    public function finishItem(Request $req)
    {
        if ($this->updateListItem($req->id, "is_done", true, "sd")) {
            return redirect("/");
        }
        return response()->json([
            "success" => false,
            "message" => "Database Error"
        ]);
    }
    public function editItem($id)
    {
        try {
            $data = $this->getItem($id);
            return view("EditToDoList", ["id" => $id, "data" => $data[0]]);

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
