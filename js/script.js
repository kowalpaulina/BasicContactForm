'use strict';
var form = document.querySelector(".form");
var type;
var messageBox;
var msg;

function showMessage(type, msg) {
    messageBox = document.querySelector(type);
    messageBox.innerHTML = msg;
    messageBox.style.display = 'block';
};

function sendEmail(e) {

        e.preventDefault();
        var fields = form.querySelectorAll("input, textarea"),
        data = {};    

    $(".form").serializeArray().map(function(x){data[x.name] = x.value;}); 
    
    //doesn't work in IE, Edge
    //fields.forEach(field => data[field.name] = field.value);
    
        $('.error').css("display","none");
        //document.querySelectorAll(".error").style.display = "none";
    
        $.ajax({
            type:"POST",
            cache: false,
            dataType:'json',
            url:"mail.php",
            data:data}).done(function(data){ 
            console.log(data);
        if(data.error){
            
            if(data.error.fnameError){
                    type = '.error-fname';
                    msg = data.error.fnameError;
                    showMessage(type, msg);
            }
    
            if(data.error.emailError){
                    type = '.error-email';
                    msg = data.error.emailError;
                    showMessage(type, msg);
                };
    
            if(data.error.messageError){
                    type = '.error-message';
                    msg = data.error.messageError;
                    showMessage(type, msg);
                }
            
        } else if(data.success){
            type = '.valid-information';
            msg = "Dziękuję " + data.dataPerson.fname + ', ' + data.success;
            showMessage(type, msg);
        
            form.removeEventListener("submit", sendEmail, false);
            form.querySelector("button").setAttribute("disabled", "disabled");
        
        }
            
        else if(data.errorSentMail){
            type = '.valid-information';
            msg = data.errorSentMail;
            showMessage(type, msg);
        }
           
    });
};

form.addEventListener("submit", sendEmail, false);
    
    
