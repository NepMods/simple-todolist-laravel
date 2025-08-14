<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <center>

        <h1>
            ToDoList
        </h1>
        <form class="form" action="{{ route("add") }}" method="post">
            <h2>Add A New ToDo item</h2>
            @csrf
            <div>
                <label for="title">Title: </label>
                <input name="title" type="text">
            </div>
            <br>
            <div>
                <label for="body">Body:</label>
                <textarea type="text" name="body" style="height: 100px; width: 200px;"></textarea>
            </div>
            <br>
            <button type="submit">Submit</button>
        </form>

        @if ($data)

            <table>
                <tbody>
                    @foreach($data as $item)
                        <tr class="{{ $item['is_done'] ? 'done' : '' }}">
                            <td>{{ $item['title'] }}</td>
                            <td>{{ $item['body'] }}</td>
                            <td>{{ $item['is_done'] ? 'Done' : 'Pending' }}</td>
                            <td>

                                @if(!$item['is_done'])
                                    <form action="{{ route("finish") }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item['id'] }}">
                                        <button>Finish</a>
                                    </form>
                                @endif


                                <form action="{{ route("edit", $item['id'] ) }}" method="get">
                                    <button>Edit</a>
                                </form>

                                <form action="{{ route("delete") }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                    <button>Delete</a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif
    </center>
</body>

</html>