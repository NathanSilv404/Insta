function myFunction(y) {
  if (y === undefined) {
     y = 1;
  }
  var x = document.getElementById('comm');
  if (x.style.display === 'block') {
    x.style.display = 'none';
  } else {
    x.style.display = 'block';
  }
}