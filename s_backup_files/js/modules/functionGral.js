function getoptionsCbo(idCboTo,classObject,idObject,chosen,options){    
    $("#div"+idCboTo).html('<img id="loader1" class="col-xs-offset-4" src="/images/loading.gif" alt="loading gif"/>');
    var classChosen = (chosen) ? 'chosen-select': '';
    var claseFind   = (options=='coloniaO') ? 'colonia': options;
    var optionSelect= (options!='') ? 'getoptionsCbo("'+options+'","'+claseFind+'",this.value,false,"");': '';
    var optsCP      = (idCboTo=='colonia' || idCboTo=='coloniaO') ? 'getCPdir(this.value,"'+idCboTo+'");': '';
    $.ajax({
        url: "/main/functions/getselect",
        type: "GET",
        data: { catId : idObject, 
                oprDb : classObject },
        success: function(data) { 
            $("#div"+idCboTo).html("");
            var dataCbo = '<select class="form-control input-sm" id="input'+idCboTo+'" name="input'+idCboTo+'" onChange=\''+optionSelect+' '+optsCP+'\'>';
            if(data!="no-info"){
                dataCbo += '<option value="">Seleccionar una opción</option>'+data+'</select>';
            }else{
                dataCbo += '<option value="">Sin Información</option>';
            }
            dataCbo += '</select>';
                                    
            $("#div"+idCboTo).html(dataCbo);
            /*$(".chosen-select").chosen({disable_search_threshold: 10});*/
        }
    });     
}
/*
function getCPdir(idObject,nameObject){
    var mun      = '';    
    var toObject = '';
    if(nameObject=='colonia'){
        mun      = $("#inputMunicipio").val();
        toObject = "inputCP";
    }else if(nameObject=='coloniaO'){
        mun = $("#inputMunicipioO").val();
        toObject = "inputCPO";
    }

    $.ajax({
        url: "/main/dashboard/getcp",
        type: "GET",
        dataType : 'json',
        data: { catId : idObject ,
                munId : mun},
        success: function(data) { 
            var result = data.answer; 
            $("#"+toObject).val(result);
        }
    });
}

function backToMain(){
    location.href='/callcenter/newservice/cancel';
}*/
function goToMainModule(){
    var moduleUrl = $("#inputModule").val();
    location.href = moduleUrl;  
}