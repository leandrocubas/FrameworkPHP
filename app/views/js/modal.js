$(function(){
    var loading = $('#loadbar').hide();
    $(document)
    .ajaxStart(function () {
        loading.show();
    }).ajaxStop(function () {
    	loading.hide();
    });
    
    $("label.btn").on('click',function () {
    	var choice = $(this).find('input:radio').val();
    	$('#loadbar').show();
    	$('#quiz').fadeOut();
    	setTimeout(function(){
           $( "#answer" ).html(  $(this).checking(choice) );      
            $('#quiz').show();
            $('#loadbar').fadeOut();
           /* something else */
    	}, 1500);
    });

    $ans = 3;

    $.fn.checking = function(ck) {
        //if (ck != $ans)
          //  return 'INCORRECT';
       // else 
            //return 'CORRECT';
        switch(ck) {
            case 'tudo':
            return location.href="/mvc-ouvidoria/admin/export/relatorio/tudo";
            break;

            case 'protocolos':
            return location.href="/mvc-ouvidoria/admin/export/relatorio/protocolos";
            break;

            case 'manifestacao':
            return location.href="/mvc-ouvidoria/admin/export/relatorio/manifestacao";
            break;

            case 'users':
            return location.href="/mvc-ouvidoria/admin/export/relatorio/users";
            break;

            case DEFAULT:
            return location.href="/mvc-ouvidoria/admin";
            break;
        }
        
    }; 
});	
