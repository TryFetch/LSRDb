<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Manage</title>
  </head>
  <body>
    <h1>Add New LSR</h1>
    <form action="/manage" method="post" enctype="multipart/form-data">
      <input type="text" name="title" value="" placeholder="title">
      <input type="text" name="year" value="" placeholder="year">
      <input type="file" name="lsr" value="">
      <input type="submit" value="save">
    </form>
  </body>
</html>
