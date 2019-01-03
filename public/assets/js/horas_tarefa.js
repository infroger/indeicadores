
$('#horas_tarefa').on('submit', function() {
    
    fdatai = document.getElementById('fdatai');
    fdataf = document.getElementById('fdataf');

    //alert(fdatai.value +' - ' +fdataf.value);
    
    datai = new Date(fdatai.value);
    dataf = new Date(fdataf.value);   
        
    // do validation here
    if(datai > dataf) {
        alert('A data inicial deve ser menor do que a data final.');
        return false;
    }
    return true;
});

document.getElementById("asalvar").onclick = submete;

function submete() {
    var fcliente = document.getElementById("fcliente").value;
    var fmatricula = document.getElementById("fmatricula").value;
    var fdatai = document.getElementById("fdatai").value;
    var fdataf = document.getElementById("fdataf").value;
    window.location = "horas_tarefa_csv?&fdatai=" + encodeURIComponent(fdatai) +
                      "&fdataf=" + encodeURIComponent(fdataf) +
                      "&fmatricula=" + encodeURIComponent(fmatricula) +
                      "&fcliente=" + encodeURIComponent(fcliente);
    return false; // not entirely necessary, but just in case
}

