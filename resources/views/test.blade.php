<html>
    <body>
        <form method="post" enctype="multipart/form-data">
            @csrf
            <input name="file" type="file" />
            <button type="submit">Submit</button>
        </form>
    </body>
</html>