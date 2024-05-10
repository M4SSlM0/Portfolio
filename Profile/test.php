<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Select Option Echo</title>
<script src="https://cdn.jsdelivr.net/npm/htmx.org@1.7.0/dist/htmx.min.js"></script>
</head>
<body>

<form hx-get="/echo" hx-trigger="change" hx-target="#selected-option">
  <label for="projects-state">Seleziona un'opzione:</label>
  <select name="projects-state" id="projects-state">
    <option value="">In corso</option>
    <option value="">In pausa</option>
    <option value="">Finito</option>
    <option value="">Scartato</option>
    <option value="" selected>All</option>
  </select>
</form>

<div id="selected-option">
  Opzione selezionata: <span hx-swap="innerHTML"></span>
</div>

<form hx-get="/echo" hx-trigger="change" hx-target="#selected-option2">
  <label for="projects-type">Seleziona un'opzione:</label>
  <select name="projects-type" id="projects-type">
    <option value="">Illustrazione</option>
    <option value="">Icona</option>
    <option value="">Animazione</option>
    <option value="">Asset</option>
    <option value="" selected>All</option>
  </select>
</form>

<div id="selected-option2">
  Opzione selezionata: <span hx-swap="innerHTML"></span>
</div>

</body>
</html>
