<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>My Finances | Nova senha</title><!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="AdminLTE 4 | Register Page v2">
    <meta name="author" content="ColorlibHQ">
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"><!--end::Primary Meta Tags--><!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../css/adminlte.css"><!--end::Required Plugin(AdminLTE)-->
</head> <!--end::Head--> <!--begin::Body-->

<body class="register-page bg-body-secondary">
    <div class="register-box"> <!-- /.register-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header"> <a href="#" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"> <b>My</b>Finances
                    </h1>
                </a> </div>
            <div class="card-body register-card-body">
                <p class="register-box-msg">Cadastrar nova senha</p>
                <form action="/new-password" method="post" id="newUser">
                @csrf
                    <input type="hidden" name="token" id="token" value="{{$token}}">
                    <input type="hidden" name="user" id="user" value="{{$user}}">
                    <div class="input-group mb-1">
                        <div class="form-floating"> 
                            <input id="password" type="password" class="form-control" placeholder="" name="password" required> 
                            <label for="password">Senha:</label> 
                        </div>
                        <div class="input-group-text"> <span class="bi bi-lock-fill"> </span> </div>
                    </div>
                    <p id="password-strength" style="color: red;"></p>
                    <div class="input-group mb-1">
                        <div class="form-floating"> 
                            <input id="password2" type="password" class="form-control" placeholder="" name="password2" required> 
                            <label for="password2">Confirmar senha:</label> 
                        </div>
                        <div class="input-group-text"> <span class="bi bi-lock-fill"> </span> </div>
                    </div>
                    <p id="password-strength2" style="color: red;"></p>
                    <div class="row">
                            <div class="d-grid gap-2"> <button type="submit" class="btn btn-primary">Cadastrar</button> </div>
                        </div> <!-- /.col -->
                    </div> <!--end::Row-->
                </form>
                <div class="form-group mb-3 ms-4">
                <p class="mb-0"> <a href="/login" class="text-center">
                        Fazer login
                    </a> </p>
                </div>
                
        
            </div> <!-- /.register-card-body -->
        </div>



        


    </div> <!-- /.register-box --> <!--begin::Third Party Plugin(OverlayScrollbars)-->

    <input type="hidden" value="{{session('msg')}}" id='msg'>
    <input type="hidden" value="{{session('icon')}}" id='icon'>
    <input type="hidden" value="{{session('colorB')}}" id='colorB'>
    <input type="hidden" value="{{session('textB')}}" id='textB'>
    <input type="hidden" value="{{session('title')}}" id='title'>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js" integrity="sha256-rTq0xiLu1Njw5mB3ky3DZhpI5WhYdkNlQbGXUc0Si6E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css" integrity="sha256-KIZHD6c6Nkk0tgsncHeNNwvNU1TX8YzPrYn01ltQwFg=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="../js/adminlte.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
   
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('newUser');
            const passwordInput = document.getElementById('password');
            const password2Input = document.getElementById('password2');
            const passwordStrengthText = document.getElementById('password-strength');
            const passwordMatchText = document.getElementById('password-strength2');

            // Função de validação de força da senha
            function checkPasswordStrength(password) {
                const minLength = 8;
                if (password.length < minLength) {
                    return 'Senha muito curta';
                } else if (!/[A-Z]/.test(password)) {
                    return 'Adicione uma letra maiúscula';
                } else if (!/[a-z]/.test(password)) {
                    return 'Adicione uma letra minúscula';
                } else if (!/\d/.test(password)) {
                    return 'Adicione um número';
                } else if (!/[@$!%*?&]/.test(password)) {
                    return 'Adicione um caractere especial';
                } else {
                    return 'Senha forte';
                }
            }

            // Verificar força da senha enquanto digita
            passwordInput.addEventListener('input', function () {
                const password = passwordInput.value;
                const strengthMessage = checkPasswordStrength(password);
                passwordStrengthText.textContent = strengthMessage;
                passwordStrengthText.style.color = strengthMessage === 'Senha forte' ? 'green' : 'red';
            });

            // Verificar se as senhas conferem enquanto digita
            password2Input.addEventListener('input', function () {
                const password = passwordInput.value;
                const confirmPassword = password2Input.value;

                if (confirmPassword === password) {
                    passwordMatchText.textContent = 'Senhas conferem';
                    passwordMatchText.style.color = 'green';
                } else {
                    passwordMatchText.textContent = 'Senhas não conferem';
                    passwordMatchText.style.color = 'red';
                }
            });

            // Bloquear envio do formulário se as senhas não conferirem
            form.addEventListener('submit', function (event) {
                const password = passwordInput.value;
                const confirmPassword = password2Input.value;

                if (confirmPassword !== password) {
                    event.preventDefault(); // Impede o envio do formulário
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'As senhas não conferem!',
                    });
                } else if (checkPasswordStrength(password) !== 'Senha forte') {
                    event.preventDefault(); // Impede o envio do formulário se a senha não for forte
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Sua senha não é forte o suficiente!',
                    });
                }
            });
        });
    </script>


    @if(session('msg'))
  <script type="text/javascript">
    //const ModalAviso =document.getElementById('exampleModal')
    //$(document).ready(function() {
    //    $('#exampleModal').modal('show');
    //})]
    

    Swal.fire({
        title: document.getElementById('title').value,
        text: document.getElementById('msg').value,
        icon: document.getElementById('icon').value,
        confirmButtonText: document.getElementById('textB').value,
        confirmButtonColor: document.getElementById('colorB').value
        })

    
    
  </script>

@php 
    session()->forget('msg');
    session()->forget('icon');
    session()->forget('colorB');
    session()->forget('textB');
    session()->forget('title');

  
  @endphp
@endif
    
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script> <!--end::OverlayScrollbars Configure--> <!--end::Script-->
</body><!--end::Body-->

</html>