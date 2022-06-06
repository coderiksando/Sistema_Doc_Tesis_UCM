<template>
<div>
    <!-- Header -->
    <!-- <img style="max-width: -webkit-fill-available;position: absolute; height: 91.7%;" src="https://lms.ucm.cl/pluginfile.php/1/theme_moove/sliderimage2/1628434472/EdificioUCM2020.jpg"> -->
    <div class="row noPadNoMar">

    <header style="opacity: 90%; background-color: #001740 !important;" class="col-md-6 py-5" id="title_message">
        <div class="container h-150">
        <div class="row h-150 d-flex justify-content-center">
            <div class="col-md-12 mt-4">
              <tr class="col-md-12 d-flex justify-content-center">
                <img class="mt-3 float-center" src="/sigad/img/logoucm.png" >
              </tr>
              <br>
              <tr class="col-md-12 d-flex justify-content-center">
                  <h1 class="display-5 text-white mb-2" style="text-align:justify">Repositorio académico de la Facultad de Ciencias de la Ingeniería Universidad Católica del Maule</h1>
              </tr>
              <p class="lead mb-5 text-white-50" ></p>
            </div>
        </div>
        </div>
    </header>

    <div class="col-md-6 login-page">
    <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
            <a class="navbar-brand">SIGAD UCM</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                <router-link  :to="'/busqueda'">
                <li class="nav-item">
                    <a class="nav-link">Búsqueda</a>
                </li>
                </router-link>
                <router-link  :to="'/'">
                <li class="nav-item">
                    <a class="nav-link">Ingresar</a>
                </li>
                </router-link>
                </ul>
            </div>
            </div>
        </nav>
        <div class="login-box">
            <div class="login-logo">
                <router-link :to="{name:'login'}">
                    <b>Iniciar sesión</b>
                </router-link>
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Ingresa tus credenciales para iniciar sesión</p>

                    <form method="post">
                        <div class="input-group mb-3">
                        <input type="email" @keyup.enter="login" v-model="fillLogin.cEmail" class="form-control" placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" @keyup.enter="login" v-model="fillLogin.cContrasena" class="form-control" placeholder="Contraseña">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <span v-if="error">
                                <div v-for="(e, index) in mensajeError" :key="index" v-text="e" class="callout callout-danger"></div>
                            </span>
                        </div>
                    </div>
                    <div class="social-auth-links text-center mb-3">
                        <button class="btn btn-flat btn-block btn-primary" @click.prevent="login" v-loading.fullscreen.lock="fullscreenLoading">
                        Iniciar sesión
                        </button>
                    </div>
                    <template v-if="enableReg">
                        <p class="mb-0">
                            <a href="#" @click.prevent="modalRegistro = true">Registrarme como alumno</a>
                        </p>
                    </template>
                    <p class="mb-0">
                        <router-link :to="{name:'passrecovery'}">
                            <b>Recuperar contraseña</b>
                        </router-link>
                    </p>
                    <!-- /.social-auth-links -->
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>

    </div>
    <!-- Footer -->
    <footer class="py-3 text-center text-lg-start bg-dark text-muted">
        <div class="container" style="text-align: center">
          <!-- Por favor por respeto a los colaboradores de este proyecto no eliminar las referencias de las personas que han participado-->
        <strong >Sistema de gestión y administración de documentos <a href="https://www.ucm.cl">UCM</a>.</strong>&nbsp; All rights reserved.
        </div>
        <!-- Por favor por respeto a los colaboradores de este proyecto no eliminar las referencias de las personas que han participado-->
        <!-- /.container -->
    </footer>

    <template v-if="modalRegistro">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" v-on:mousedown.prevent="modalRegistro=false">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex; z-index: 2; min-width: 360px !important" v-on:mousedown.stop>
          <div class="swal2-header">
            <div class="modal fade" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Sistema de documentos UCM</h5>
                    <button class="close" @click="modalShow = !modalShow"></button>
                  </div>
                  <div class="modal-body">
                    <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in registroError" :key="index" v-text="item"></div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalShow = !modalShow">{{globVar.btnClose}}</button>
                  </div>
                </div>
              </div>
            </div>
            <h2 class="swal2-title mb-3" id="swal2-title" style="display: flex;">Formulario de registro</h2>
          </div>
          <div class="swal2-content text-justify">
            <form method="post" class="mt-3">
                <div>Nombres</div>
                <div class="input-group mb-3">
                    <input type="text" maxlength="30" v-model="fillRegistrarAlumno.cNombre" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Nombres" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div>Apellidos</div>
                <div class="input-group mb-3">
                    <input type="text" maxlength="30" v-model="fillRegistrarAlumno.cApellido" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Apellidos" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div>Rut</div>
                <div class="input-group mb-3">
                    <input type="text" maxlength="30" v-model="fillRegistrarAlumno.cRut" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Rut: 11111111-1" @blur="validarIngresoUsuarioRut($event.target.value)">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-user"></span>
                        </div>
                    </div>
                    <p style="margin-top: 0rem; margin-bottom: 0rem;" class="invalid-feedback" v-show="addUserErrorMessage.rut === 1">Rut inválido</p>
                </div>
                <div>Correo</div>
                <div class="input-group mb-3">
                    <input type="email" maxlength="50" v-model="fillRegistrarAlumno.email" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Email" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div>Contraseña</div>
                <div class="input-group mb-3">
                    <input :type="(lockPassword) ? 'password' : 'text'" v-model="fillRegistrarAlumno.cContrasena" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Contraseña" >
                    <div class="input-group-append" @click.prevent="lockPassword = !lockPassword">
                        <div class="input-group-text iconBtn">
                        <span :class="(lockPassword) ? 'fas fa-lock' : 'fas fa-unlock'"></span>
                        </div>
                    </div>
                </div>
                <div>Confirmar Contraseña</div>
                <div class="input-group mb-3">
                    <input :type="(lockPassword) ? 'password' : 'text'" v-model="fillRegistrarAlumno.cContrasena2" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Repita su contraseña" >
                    <div class="input-group-append" @click.prevent="lockPassword = !lockPassword">
                        <div class="input-group-text iconBtn">
                        <span :class="(lockPassword) ? 'fas fa-lock' : 'fas fa-unlock'"></span>
                        </div>
                    </div>
                </div>
            </form>
          </div>
          <div class="swal2-actions">
            <button type="button" class="swal2-confirm swal2-styled" style="display: inline-block; background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214); --darkreader-inline-bgcolor:#2166a7; --darkreader-inline-border-left:#1d5a93; --darkreader-inline-border-right:#1d5a93;"  @click.prevent="setRegistrarAlumno">Registrarse</button>
            <button type="button" class="swal2-cancel swal2-styled" style="display: inline-block; background-color: rgb(221, 51, 51); --darkreader-inline-bgcolor:#a61c1c;" @click.prevent="modalRegistro=false">Cancelar</button>
          </div>
        </div>
      </div>
    </template>
</div>
</template>


<script>
import globVar from '../../../services/globVar';
export default {
    data(){
        return{
            globVar: new globVar(),
            fillLogin: {
                cEmail: '',
                cContrasena: ''
            },
            fillRegistrarAlumno:{
            cNombre: '',
            cApellido: '',
            cRut: '',
            email: '',
            cContrasena: '',
            cContrasena2: '',
            nIdRol:  2
            },
            listRolPermisosByUsuario:[],
            listRolPermisosByUsuarioFilter: [],
            RolByUser:[],
            fullscreenLoading: false,
            error : 0,
            terminoDeTitulo: '',
            terminoDeTituloExtendido: '',
            mensajeError:[],
            registroError:[],
            enableReg: 0,
            modalRegistro: false,
            modalShow: false,
            mostrarModal: {
                display: 'block',
                background: '#0000006b',
            },
            ocultarModal: {
                display: 'none',
            },
            addUserErrorMessage: {
            boton: true,
            nombre: 2,
            email: 2,
            rut: 2
            },
            lockPassword: true
        }
    },
    mounted(){
        this.getParametros();
    },
    methods: {
        login(){
            if (this.validarLogin()){
                return;
            }
            this.fullscreenLoading = true;
            var url = '/authenticate/login'
            axios.post(url, {
                    'cEmail' : this.fillLogin.cEmail,
                    'cContrasena' : this.fillLogin.cContrasena
            }).then(response => {
                if(response.data.code == 401){
                    this.loginFailed();
                }
                if(response.data.code == 200){
                    this.getListarRolPermisosByUsuario(response.data.authUser);
                    //this.loginSuccess();
                }
            })
        },
        validarLogin(){
            this.error = 0;
            this.mensajeError = [];

            if(!this.fillLogin.cEmail){
                this.mensajeError.push('El correo electronico es un campo obligatorio')
            }
            if(!this.fillLogin.cContrasena){
                this.mensajeError.push('La contraseña es un campo obligatorio')
            }
            if(this.mensajeError.length){
                this.error = 1;
            }
            return this.error;
        },
        loginFailed(){
            this.error = 0;
            this.mensajeError = [];
            this.mensajeError.push('Estas credenciales no coinciden con nuestros registros');
            this.fillLogin.cContrasena = '';
            this.fullscreenLoading = false;
            if(this.mensajeError.length){
                this.error = 1;
            }
            return this.error;
        },
        loginSuccess(){
            window.location.href = '/dashboard';
            this.fullscreenLoading = false;
            // location.reload();
            // this.$router.push({name: 'dashboard.index'})

        },
        getListarRolPermisosByUsuario(authUser){
            var ruta = '/administracion/usuario/getListarRolPermisosByUsuario'
            axios.get(ruta, {
                params: {
                    'nIdUsuario' : authUser.id_user
                }
            }).then( response => {
                this.listRolPermisosByUsuario = response.data;
                this.getRolByUser(authUser);
            })
        },
        getRolByUser(authUser){
            var ruta = '/administracion/usuario/getRolByUsuario'
            axios.get(ruta, {
                params: {
                    'nIdUsuario' : authUser.id_user
                }
            }).then( response => {
                this.RolByUser = response.data;
                this.filterListarRolPermisosByUsuario(authUser);
            })
        },
        filterListarRolPermisosByUsuario(authUser){
            let me = this;
            me.listRolPermisosByUsuario.map(function(x,y){
                me.listRolPermisosByUsuarioFilter.push(x.slug)
            })
            localStorage.setItem('listRolPermisosByUsuario', JSON.stringify(me.listRolPermisosByUsuarioFilter));
            localStorage.setItem('authUser', JSON.stringify(authUser));
            localStorage.setItem('rolUser', JSON.stringify(me.RolByUser));
            localStorage.setItem('rolActivo', JSON.stringify(me.RolByUser[0].name));
            this.loginSuccess();
        },
        validarRegistrarAlumno(){
            this.error = 0;
            this.registroError = [];
            if(!this.fillRegistrarAlumno.cNombre){
                this.registroError.push("El nombre es un campo obligatorio")
            }
            if(!this.fillRegistrarAlumno.cApellido){
                this.registroError.push("El apellido es un campo obligatorio")
            }
            if(!this.fillRegistrarAlumno.email){
                this.registroError.push("El correo es un campo obligatorio")
            }
            if(!this.fillRegistrarAlumno.cContrasena){
                this.registroError.push("La contraseña es un campo obligatorio")
            }
            if((this.fillRegistrarAlumno.cContrasena && this.fillRegistrarAlumno.cContrasena2) && (this.fillRegistrarAlumno.cContrasena != this.fillRegistrarAlumno.cContrasena2)){
                this.registroError.push("Las contraseñas no coinciden")
            }
            if(!this.fillRegistrarAlumno.cRut){
                this.registroError.push("El RUT es un campo obligatorio")
            }
            if (this.addUserErrorMessage.rut === 1) {
                this.registroError.push("El RUT no es válido")
            }
            if(this.registroError.length){
                this.error = 1;
            }
            return this.error;
        },
        setRegistrarAlumno(){
            if (this.validarRegistrarAlumno()){
                this.modalShow = true;
                return;
            }
            this.setGuardarAlumno();
        },
        setGuardarAlumno(nIdFile){
            var url = 'authenticate/registro/setRegistrarAlumno'
            axios.post(url, {
                'cNombre'       : this.fillRegistrarAlumno.cNombre,
                'cApellido'     : this.fillRegistrarAlumno.cApellido,
                'cRut'          : this.fillRegistrarAlumno.cRut,
                'email'         : this.fillRegistrarAlumno.email,
                'cContrasena'   : this.fillRegistrarAlumno.cContrasena
            }).then(response => {
                // console.log(response.data);
                // this.setEditarRolAlumno(response.data);
                this.fullscreenLoading = false;
                this.$router.push('/');
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario registrado con exito',
                    text: 'Para activar su cuenta es necesario que inicie sesión y complete sus datos.',
                    showConfirmButton: true,

                })
            }).catch(error => {
                this.error = 0;
                this.mensajeError = [];
                if (error.response.status == 422){
                    this.mensajeError.push("Este correo ya esta registrado en nuestro sistema.")
                    this.error = 1;
                    this.modalShow = true;
                    return;
                }
                else{
                    this.registroError.push(error.response.data.error);
                    this.error = 1;
                    this.modalShow = true;
                    return;
                }
            })
        },
        validarIngresoUsuarioRut(rut){
            if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rut )) {
                this.addUserErrorMessage.rut = true;
            }
            var tmp 	= rut.split('-');
            var digv	= tmp[1];
            var rut 	= tmp[0];
            if ( digv == 'K' ) digv = 'k' ;
            if (this.digitoVerificador(rut) == digv) {
                this.addUserErrorMessage.rut = 0;
            } else {
                this.addUserErrorMessage.rut = 1;
            }
        },
        digitoVerificador(T){
            var M=0,S=1;
            for(;T;T=Math.floor(T/10))
                S=(S+T%10*(9-M++%6))%11;
            return S?S-1:'k';
        },
        getParametros(){
            var url = '/admin/parametros';
            axios.post(url,{'params': ['HabilitarRegistro', 'TerminoDeTitulo', 'TerminoDeTituloExtendido']}).then(response => {
                this.enableReg = parseInt(response.data[0][0]);
                this.terminoDeTitulo = response.data[1][0];
                this.terminoDeTituloExtendido = response.data[2][0];
                if (this.terminoDeTitulo) {
                    localStorage.setItem('TerminoDeTitulo', JSON.stringify(this.terminoDeTitulo));
                } else {
                    localStorage.setItem('TerminoDeTitulo', JSON.stringify('Fit'));

                }
                if (this.terminoDeTitulo) {
                    localStorage.setItem('TerminoDeTituloExtendido', JSON.stringify(this.terminoDeTituloExtendido));
                } else {
                    localStorage.setItem('TerminoDeTituloExtendido', JSON.stringify('Formulario de ingreso de tesis'));
                }
            })
        }
    },

}
</script>

<style>
@media screen and (max-width: 767px) {
  #title_message {
    visibility: hidden;
    clear: both;
    float: left;
    margin: 10px auto 5px 20px;
    width: 28%;
    display: none;
  }
}

.iconBtn{
    background-color: rgb(48, 133, 214);
    color: white;
}
.iconBtn:hover {
  border: 2px solid rgb(48, 97, 170);
}
</style>
