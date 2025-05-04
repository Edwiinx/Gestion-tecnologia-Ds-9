<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../Css/login-register.css">
</head>
<body>
    <div class="main-container">
        <div class="image-container">
            <img class="image-nature" src="../../Assets/natureimg.png" alt="imagen">
        </div>
        <div class="form-container">
            <h2 class="title">Bienvenido</h2>
            <p class="wellcometext">Bienvenido de nuevo. Nos alegra verte por aquí.</p>
            <input class="input-comun user" type="text" placeholder="Ingresar usuario">
            <img class="image-user" src="../../Assets/user.png" alt="imagen">
            <input class="input-comun lock" type="password" placeholder="Ingresar contraseña">
            <img class="image-lock" src="../../Assets/lock.png" alt="imagen">
            <img class="image-lock imagelogineyeclosed" src="../../Assets/eyecrosed.svg" alt="imagen" style="cursor: pointer;">
            <img class="image-lock imagelogineyeopen" src="../../Assets/eyeopen.svg" alt="imagen" style="cursor: pointer; display: none;">
            <button class="btn">Ingresar</button>
            <label class="bottomtext">¿No tienes una cuenta? <a href="registro.php">Regístrate</a></label>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let inputuser = document.querySelector('.user');
        let inputlock = document.querySelector('.lock');
        let imageuser = document.querySelector('.image-user');
        let imagelock = document.querySelector('.image-lock');
        let imageeyeopen = document.querySelector('.imagelogineyeopen');
        let imageeyeclosed = document.querySelector('.imagelogineyeclosed');
        let btnlogin = document.querySelector('.btn');

        inputuser.addEventListener('click', () => {
            imageuser.style.opacity = '0.5';
        });

        inputuser.addEventListener('blur', () => {
            imageuser.style.opacity = '1';
        });

        inputlock.addEventListener('click', () => {
            imagelock.style.opacity = '0.5';
        });

        inputlock.addEventListener('blur', () => {
            imagelock.style.opacity = '1';
        });

        imageeyeclosed.addEventListener('click', () => {
            imageeyeclosed.style.display = 'none';
            imageeyeopen.style.display = 'block';
            inputlock.type = 'text'; // Mostrar contraseña
        });

        imageeyeopen.addEventListener('click', () => {
            imageeyeopen.style.display = 'none';
            imageeyeclosed.style.display = 'block';
            inputlock.type = 'password'; // Ocultar contraseña
        });

        btnlogin.addEventListener('click', () => {
            let usertext = document.querySelector('.user').value.trim();
            let passwordtext = document.querySelector('.lock').value.trim();

            if (usertext === '' || passwordtext === '') {
                Swal.fire({
                    title: "Error",
                    text: "Por favor completa todos los campos",
                    icon: "warning"
                });
                return;
            }

            let parametros = {
                'user': usertext,
                'password': passwordtext
            }

            $.ajax({
                data: parametros,
                url: ('../Captura/loginajax.php'),
                dataType: 'json',
                type: 'post',

                success: function(respuesta) {
                    if (respuesta.mensaje_mostrar === 'exito') {
                        localStorage.setItem('ID_USUARIO', respuesta.ID_USUARIO); // 👈 Guardamos el ID

                        Swal.fire({
                            title: "Bien Hecho",
                            text: "Todo se envió correctamente",
                            icon: "success"
                        });

                        if (respuesta.type === 'ADMINISTRADOR') {
                            setTimeout(() => {
                                window.location.href = '../Captura/captura.php';
                            }, 2000);
                        } else {
                            setTimeout(() => {
                                window.location.href = '../Catalogo/Catalogo.php';
                            }, 2000);
                        }
                    } else if (respuesta.status === 'error') {
                        Swal.fire({
                            title: "Error",
                            text: respuesta.mensaje,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error grave",
                        text: "No se pudo procesar la solicitud",
                        icon: "error"
                    });
                }
            });
        });
    </script>
</body>
</html>
