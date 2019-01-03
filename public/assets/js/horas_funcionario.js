
$('#horas_funcionario').on('submit', function() {
    
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
    //var fnome = document.getElementById("fnome").value;
    var fdatai = document.getElementById("fdatai").value;
    var fdataf = document.getElementById("fdataf").value;
    if (document.getElementById("fdetalhe").checked)
        fdetalhe =  document.getElementById("fdetalhe").value;
    else
        fdetalhe = 'N';
    //alert(fdetalhe);
    window.location = "horas_funcionario_csv?&fdatai=" + encodeURIComponent(fdatai) +
                      "&fdataf=" + encodeURIComponent(fdataf) +
                      "&fdetalhe=" + encodeURIComponent(fdetalhe);
    return false; // not entirely necessary, but just in case
}

