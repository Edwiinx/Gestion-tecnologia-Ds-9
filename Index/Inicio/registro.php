<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../../Css/login-register.css">
</head>
<body>
    <div class="main-container">
        <div class="image-container">
            <img class="image-nature" src="../../Assets/casalago.png" alt="imagen">
        </div>
        <div class="form-container">
            <h2 class="title">Crear Cuenta</h2>
            <p class="wellcometext">Crea tu cuenta en segundos. Es rápido y fácil.</p>
            <input class="input-comun user" type="text" placeholder="Ingresar usuario">
            <img class="image-user imageregisteruser" src="../../Assets/user.png" alt="imagen">
            <input  class="input-comun lock" type="password" placeholder="Ingresar contraseña">
            <img class="image-lock imageregisterlock" src="../../Assets/lock.png" alt="imagen">
            <img class="image-lock imageregistereyeclosed " src="../../Assets/eyecrosed.svg" alt="imagen">
            <img class="image-lock imageregistereyeopen" src="../../Assets/eyeopen.svg" alt="imagen">
             <!--<input type="checkbox">-->
            <button class="btn">Registrar</button>
            <label class="bottomtext">Ahora si puedes<a href="login.php">  iniciar sesion</a></label>

        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    let inputuser = document.querySelector('.user');
    let inputlock = document.querySelector('.lock');
    let imageuser=document.querySelector('.image-user');
    let imagelock=document.querySelector('.image-lock');
    let imageeyeopen=document.querySelector('.imageregistereyeopen');
    let imageeyeclosed=document.querySelector('.imageregistereyeclosed');

        inputuser.addEventListener('click', ()=>{
            imageuser.style.opacity='0.5';
        });

        inputuser.addEventListener('blur', ()=>{
            imageuser.style.opacity='1';
        });

        inputlock.addEventListener('click', ()=>{
            imagelock.style.opacity='0.5';
        });

        inputlock.addEventListener('blur', ()=>{
            imagelock.style.opacity='1';
        });

        imageeyeopen.addEventListener('click', ()=>{
            imageeyeopen.style.display='none'
            imageeyeclosed.style.display='block';
            inputlock.type='text';
            
        });

        imageeyeclosed.addEventListener('click', ()=>{
            imageeyeclosed.style.display='none'
            imageeyeopen.style.display='block';
            inputlock.type='password';

        });

//ajax 
    let btnregister = document.querySelector('.btn');

    btnregister.addEventListener('click', ()=>{

        let usertext = document.querySelector('.user').value;
        let passwordtext = document.querySelector('.lock').value;
        let usertext2 = document.querySelector('.user');
        let passwordtext2 = document.querySelector('.lock');
        let parametros ={
            'user':usertext,
            'password': passwordtext,
            'type':'COMPRADOR'
        }

        console.log(usertext);
        console.log(passwordtext);
        $.ajax({
            data:parametros,
            url:('../Captura/registrarajax.php'),
            datatype:'json',
            type:'post',

            success:function(respuesta){
                if(respuesta.mensaje_mostrar=='exito'){
                Swal.fire({
                title: "Bien Hecho",
                text: "Todo se envio correctamente",
                icon: "success"
                });
                    usertext2.value='';
                    passwordtext2.value='';
                }
                else if(respuesta.status=='error'){
                    Swal.fire({
                    title: "Error",
                    text: "Este usuario ya existe",
                    icon: "error"
                    });
                    usertext2.value='';
                    passwordtext2.value='';
                }
            },
        });
    });


</script>
</html>
