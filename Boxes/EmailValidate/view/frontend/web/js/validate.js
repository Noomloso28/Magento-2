define(['jquery'],
    function ($) {
        $(document).ready(function() {

            $(document).on('change',"#addData input[name='email']",function(){

                let email_value = $(this).val();

                $.ajax({

                    url : 'emailvalidate/index/ajaxvalidateform',
                    type : 'GET',
                    data: {
                        format: 'json',
                        email: email_value
                    },
                    dataType:'json',
                    success : function(data) {

                        if( data.data === true ){

                            $('#error-message').show().fadeIn( 1000 );

                        }else{

                            $('#error-message').hide();
                        }

                        return data.data; //return
                    },
                    error : function(request,error)
                    {
                        alert("Error");
                    }
                });

            });


        });
    });
