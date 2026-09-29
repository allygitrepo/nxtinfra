<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.container {
  position: relative;
  text-align: center;
  color: white;
}

.bottom-left {
  position: absolute;
  bottom: 8px;
  left: 20%;
}

.top-left {
  position: absolute;
  top: 8px;
  left: 20%;
}

.top-right {
  position: absolute;
  top: 8px;
  right: 20%;
}

.bottom-right {
  position: absolute;
  bottom: 8px;
  right: 20%;
}

.centered {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
</head>
<body>

<h2>Image Text</h2>
<p>How to place text over an image:</p>

<div class="container">
  <img src="img_snow_wide.jpg" alt="Snow" style="width:80%;">
  <div class="bottom-left">Bottom Left</div>
  <div class="top-left">Top Left</div>
  <div class="top-right">Top Right</div>
  <div class="bottom-right">Bottom Right</div>
  <div class="centered">Centered</div>
</div>

</body>
</html> 
