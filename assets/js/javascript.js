// Chargement dynamique de l'image côté back
document
  .getElementById("photo-input")
  .addEventListener("change", function (event) {
    var file = event.target.files[0];
    var imageURL = URL.createObjectURL(file);
    document.getElementById("article-image").src = imageURL;
  });


