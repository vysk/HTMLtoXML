<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Innovatia HTML to XML converter</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $(function() {
  $( "#accordion" ).accordion({
    active: false,heightStyle: "content",collapsible: true
  });
  $('#accordion select').click(function(e) {
    e.stopPropagation();
  });
  } );
  </script>
</head>
<body>
 <form action="index.php" method="POST">
 <div style="width:100%;float:left">
<div id="accordion" style="width:100%;float:left;">
  <?php foreach ($contentarray as $acontent) {?>
<div style="width:100%;float:left;box-sizing: border-box;">
    <?php echo strip_tags($acontent['title']);?>
          <span style="float: right;width:10%">
        <select name="filetypes[]">
          <option value="">Select type</option>
          <option value="Topic">Topic</option>
          <option value="Task">Task</option>
          <option value="Concept">Concept</option>
          <option value="Reference">Reference</option>
        </select>
      </span>
</div>
  <div style="width:100%;float:left;box-sizing: border-box;">
    <p>
      <?php echo strip_tags($acontent['cnt']);?>
    </p>
  </div>
  
  <?php } ?>

</div>
  <button style="float: right; padding: 15px;margin-top: 20px;">Convert</button>
  </div>
</form>
</body>
</html>