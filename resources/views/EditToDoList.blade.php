<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit ToDoList</title>

    <link rel="stylesheet" href="/style.css?o=1">
</head>

<body>
    <center>

        <h1>
            Edit ToDoList
        </h1>

        <form action="{{ route("save") }}" method="post">
            @csrf
            <input type="hidden" value="{{ $id }}" name="id">
            <div>
                <label for="title">Title: </label>
                <input name="title" type="text" value="{{ $data->title }}">
            </div>
            <br>
            <div>
                <label for="body">Body:</label>
                <textarea type="text" name="body" style="height: 100px; width: 200px;">{{ $data->body }}</textarea>
            </div>
            <br>

            <div>
                <label for="body">Finished:</label>
                <input type="checkbox" name="is_done" {{ $data->is_done == 0 ?"":"checked" }}>
            </div>
            <br>
            <button type="submit">Save</button>
        </form>

    </center>
</body>

</html>