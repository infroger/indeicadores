
$('#horas_cliente').on('submit', function() {
    
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
    //alert(document.getElementById("fmatricula"));
    if (document.getElementById("fmatricula")) 
        var fmatricula = document.getElementById("fmatricula").value;
    var fcliente   = document.getElementById("fcliente").value;
    var fdatai     = document.getElementById("fdatai").value;
    var fdataf     = document.getElementById("fdataf").value;
    if (document.getElementById("fdetalhe").checked)
        fdetalhe =  document.getElementById("fdetalhe").value;
    else
        fdetalhe = 'N';
    //alert(fdetalhe);
    
    s = "horas_cliente_csv?&fdatai=" + encodeURIComponent(fdatai) +
            "&fdataf=" + encodeURIComponent(fdataf) +
            "&fcliente=" + encodeURIComponent(fcliente) +
            "&fdetalhe=" + encodeURIComponent(fdetalhe);

    if (typeof document.getElementById("fmatricula") != 'undefined') 
        s.concat("&fmatricula=" + encodeURIComponent(fmatricula));
                      
    window.location = s;                      
    return false; // not entirely necessary, but just in case
}

