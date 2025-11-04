<?php
$server_data = $_SERVER;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rabbitlite4 - Server Info</title>
  <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64,AAABAAEAEBAAAAAAAABoBQAAFgAAACgAAAAQAAAAIAAAAAEACAAAAAAAAAEAAAAAAAAAAAAAAAEAAAAAAAAAAAAA7OzsAO3t7QD09PQA8fHxAPj4+AD9/f0A+vr6APb29gD4+/sA+fn5APv7+wD6/f0A/f7+AP///wD+/v4A/Pz8APv+/gD5/PwA7u7uAPn5+gD8/v4A+/z8AP7//wD8/f0A/v7/AP3+/wD29vUA+Pj3APT09QD+//4A8/PzAP3//wD7+/wA+Pj5AP39/AD9/v4A/f39AP///gD7/v4A+vr7APf39wD6+vwA+Pn5APv8/QD4+PoA+/v+APr7/AD5+fsA+vr5APn6+gD8/P0A+vv7APz8/gD6+/0A+fr7APv7/QD5+fwA7e3uAO7u7wD19fUA/v/9APr6/QD5+v0A+Pn6APj4+gD4+PsA+Pf4APn5/AD5+f0A+vn6APr6+gD7+v0A8PDwAPn4+QD6+fkA/Pz+AO/v7wD9/f4A+/v8APv8/AD6+vsA/P3+APv8/gD7+/4A+/z+APr7/gD///8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=" />
  <style>
    body {
      background-color: #111;
      color: #eee;
      font-family: 'Courier New', monospace;
      padding: 40px;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background-color: #222;
      padding: 30px;
      border-radius: 10px;
      border: 1px solid #333;
    }
    h1 {
      color: #00ffff;
      margin-bottom: 20px;
      border-bottom: 2px solid #00ffff;
      padding-bottom: 8px;
      font-size: 24px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      overflow-x: auto;
    }
    th, td {
      border: 1px solid #444;
      padding: 8px;
      text-align: left;
      vertical-align: top;
      word-break: break-all;
    }
    th {
      background-color: #333;
      color: #ffcc00;
    }
    tr:nth-child(even) {
      background-color: #1b1b1b;
    }
    .footer {
      font-size: 13px;
      text-align: center;
      margin-top: 30px;
      color: #999;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Rabbitlite4 - Server Diagnostics</h1>
    <h3>PHP <?= phpversion() ?></h3>
    <table>
      <thead>
        <tr>
          <th style="width: 25%">Key</th>
          <th style="width: 25%">Value</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($server_data as $key => $value): ?>
        <tr>
          <td ><?= htmlspecialchars($key) ?></td>
          <td><?= is_array($value) ? htmlspecialchars(json_encode($value)) : htmlspecialchars($value) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="footer">
      PHP <?= phpversion() ?> • <?= date("Y-m-d H:i:s") ?> GMT <br>
      Copyright © 2018-2025 iQuipe Digital Enterprise. All rights reserved.
    </div>
  </div>
</body>
</html>
