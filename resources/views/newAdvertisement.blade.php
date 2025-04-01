<form action="/NewAdvertisement" method="POST">
    @csrf

    <label for="title">Title</label>
    <input type="text" id="title" name="title"><br>

    <label for="price">Prijs</label>
    <input type="text" id="price" name="price"><br>

    <label for="information">Informatie over het product</label>
    <input type="text" id="information" name="information"><br>
    <br>
    
    <button type="submit">Maak advertentie</button>
</form>