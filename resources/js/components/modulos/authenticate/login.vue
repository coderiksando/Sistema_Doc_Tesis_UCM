<template>
<div>
    <div class="login-page">
    <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
            <a class="navbar-brand">SGYAD UCM</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                <router-link  :to="'/'">
                <li class="nav-item">
                    <a class="nav-link">Home</a>
                </li>
                </router-link>
                <router-link  :to="'/login'">
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
                            <router-link :to="{name:'registro'}">
                                <b>Registrarme como alumno</b>
                            </router-link>
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
    <!-- Footer -->
    <footer class="py-4 text-center text-lg-start bg-dark text-muted">
        <div class="container" style="text-align: center">
          <!-- Por favor por respeto a los colaboradores de este proyecto no eliminar las referencias de las personas que han participado-->
        <strong >Sistema de gestión y administración de documentos <a href="https://www.ucm.cl">UCM</a>.</strong>&nbsp; All rights reserved.
        </div>
        <!-- Por favor por respeto a los colaboradores de este proyecto no eliminar las referencias de las personas que han participado-->
        <!-- /.container -->
    </footer>
</div>
</template>


<script>
export default {
    data(){
        return{
            fillLogin: {
                cEmail: '',
                cContrasena: ''
            },
            listRolPermisosByUsuario:[],
            listRolPermisosByUsuarioFilter: [],
            RolByUser:[],
            fullscreenLoading: false,
            error : 0,
            terminoDeTitulo: '',
            terminoDeTituloExtendido: '',
            mensajeError:[],
            enableReg: 0
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

</style>
