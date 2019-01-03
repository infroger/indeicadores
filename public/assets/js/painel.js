document.getElementById("asalvar_quantitativo").onclick = submete_quantitativo;
//document.getElementById("asalvar_clientes").onclick = submete_clientes;

function submete_quantitativo() {
    var fdias_quantitiativo = document.getElementById("fdias_quantitiativo").value;
    //alert(fdias);
    window.location = "painel_csv?fdias_quantitativo=" + encodeURIComponent(fdias_quantitiativo);
    return false; // not entirely necessary, but just in case
}

/*
function submete_clientes() {
    var fdias_clientes = document.getElementById("fdias_clientes").value;
    //alert(fdias);
    window.location = "painel_csv?fdias_clientes=" + encodeURIComponent(fdias_clientes);
    return false; // not entirely necessary, but just in case
}
*/

