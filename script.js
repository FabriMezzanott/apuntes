function validarFormulario(formulario) {
  var campos = formulario.querySelectorAll("input, select");

  for (var i = 0; i < campos.length; i++) {
    if (campos[i].required && campos[i].value.trim() == "") {
      alert("Completa todos los campos.");
      return false;
    }
  }
  return true;
}

function validarGrupo(formulario) {
  if (validarFormulario(formulario) == false) {
    return false;
  }

  var fecha = formulario.fecha.value;
  var hora = formulario.hora.value;
  var cupo = parseInt(formulario.cupo.value);

  var fechaGrupo = new Date(fecha + "T" + hora);
  var ahora = new Date();

  if (fechaGrupo <= ahora) {
    alert("La fecha y hora tienen que ser futuras.");
    return false;
  }

  if (cupo < 2) {
    alert("El cupo minimo es de 2 personas.");
    return false;
  }

  return true;
}

function validarApunte(formulario) {
  if (validarFormulario(formulario) == false) {
    return false;
  }

  var archivo = formulario.archivo.value;
  var enlace = formulario.enlace.value.trim();

  if (archivo == "" && enlace == "") {
    alert("Tenes que adjuntar un archivo o poner un enlace.");
    return false;
  }

  return true;
}

function filtrarApuntes() {
  var texto = document.getElementById("buscador").value.toLowerCase();
  var materia = document.getElementById("filtro_materia").value;
  var tarjetas = document.querySelectorAll(".tarjeta");
  var visibles = 0;

  for (var i = 0; i < tarjetas.length; i++) {
    var titulo = tarjetas[i].getAttribute("data-titulo").toLowerCase();
    var materiaTarjeta = tarjetas[i].getAttribute("data-materia");

    var coincideTitulo = titulo.indexOf(texto) != -1;
    var coincideMateria = (materia == "0" || materia == materiaTarjeta);

    if (coincideTitulo && coincideMateria) {
      tarjetas[i].style.display = "flex";
      visibles = visibles + 1;
    } else {
      tarjetas[i].style.display = "none";
    }
  }

  if (visibles == 0) {
    document.getElementById("sin_resultados").style.display = "block";
  } else {
    document.getElementById("sin_resultados").style.display = "none";
  }
}

var buscador = document.getElementById("buscador");
if (buscador) {
  buscador.addEventListener("keyup", filtrarApuntes);
  document.getElementById("filtro_materia").addEventListener("change", filtrarApuntes);
}
