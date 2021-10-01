import vue from 'vue';
import Router from 'vue-router';

vue.use(Router);

function verificarAcceso(to, from, next) {
    let authUser = JSON.parse(localStorage.getItem('authUser'));
    if (authUser) {
        let listRolPermisosByUsuario = JSON.parse(localStorage.getItem('listRolPermisosByUsuario'));
        if (listRolPermisosByUsuario.includes(to.name)) {
            next();
        } else {
            let listRolPermisosByUsuarioFilter = [];
            listRolPermisosByUsuario.map(function(x) {
                if (x.includes('index')) {
                    listRolPermisosByUsuarioFilter.push(x);
                }
            });
            if (to.name == 'dashboard.index') {
//                 next({ name: listRolPermisosByUsuarioFilter[0] });
            } else {
                next(from.path);
            }
        }
    } else {
        next('/');
    }
}
//concentracion de rutas
export const rutas = [
    {
        path: '/',
        name: 'login',
        component: require('./components/modulos/authenticate/login').default,
        beforeEnter: (to, from, next) => {
            let authUser = JSON.parse(localStorage.getItem('authUser'));
            if (authUser) {
                next({ name: 'dashboard.index' }); // o tambien puede ser next(false)
            } else {
                next();
            }
        }
    },
    {
        path: '/busqueda',
        name: 'home',
        component: require('./components/modulos/home/index').default,
        beforeEnter: (to, from, next) => {
            let authUser = JSON.parse(localStorage.getItem('authUser'));
            if (authUser) {
                next({ name: 'dashboard.index' }); // o tambien puede ser next(false)
            } else {
                next();
            }
        }
    },
    {
        path: '/registro',
        name: 'registro',
        component: require('./components/modulos/authenticate/registro').default,
        beforeEnter: (to, from, next) => {  //agregado para PSE-3
            let authUser = JSON.parse(localStorage.getItem('authUser'));
            if (authUser) {
                next({ name: 'dashboard.index' }); // o tambien puede ser next(false)
            } else {
                next();
            }
        }
    },
    {
        path: '/passrecovery',
        name: 'passrecovery',
        component: require('./components/modulos/authenticate/passrecovery').default
    },
    {
        path: '/dashboard',
        name: 'dashboard.index',
        component: require('./components/modulos/dashboard/Index').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
/*********      RUTAS MODULO ADMINISTRACION DE USUARIOS           *********/
    {
        path: '/usuarios',
        name: 'usuarios.index',
        component: require('./components/modulos/usuarios/Index').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
    {
        path: '/usuarios/crear',
        name: 'usuarios.crear',
        component: require('./components/modulos/usuarios/create').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
    {
        path: '/usuarios/editar/:id_user',
        name: 'usuarios.editar',
        component: require('./components/modulos/usuarios/edit').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        },
        props: true
    },
    {
        path: '/usuarios/ver/:id_user',
        name: 'usuarios.ver',
        component: require('./components/modulos/usuarios/view').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        },
        props: true
    },
    {
        path: '/usuarios/permisos/:id_user',
        name: 'usuarios.permisos',
        component: require('./components/modulos/usuarios/permission').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        },
        props: true
    },
    {
        path: '/usuarios/importar',
        name: 'usuarios.importar',
        component: require('./components/modulos/usuarios/import').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        },
        props: true
    },
/*********    RUTAS MODULO DE ADMINISTRACION DE ROLES      **********/
    {
        path: '/roles',
        name: 'roles.index',
        component: require('./components/modulos/roles/Index').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
     },
    {
        path: '/roles/crear',
        name: 'roles.crear',
        component: require('./components/modulos/roles/create').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
    {
        path: '/roles/editar/:id',
        name: 'roles.editar',
        component: require('./components/modulos/roles/edit').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        },
        props : true
     },
/******** RUTAS MODULO DE ADMINISTRACION DE  PERMISOS ********/
    {
        path: '/permisos',
        name: 'permisos.index',
        component: require('./components/modulos/permisos/Index').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
    {
        path: '/permisos/crear',
        name: 'permisos.crear',
        component: require('./components/modulos/permisos/create').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
    {
        path: '/permisos/editar/:id',
        name: 'permisos.editar',
        component: require('./components/modulos/permisos/edit').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        },
        props : true
    },
/******** RUTAS MODULO DE ADMINISTRACION DE ESCUELAS ********/
    {
        path: '/escuelas',
        name: 'escuelas.index',
        component: require('./components/modulos/escuelas/Index').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
    {
        path: '/escuelas/crear',
        name: 'escuelas.crear',
        component: require('./components/modulos/escuelas/create').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
    {
        path: '/escuelas/editar/:id',
        name: 'escuelas.editar',
        component: require('./components/modulos/escuelas/edit').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        },
        props : true
    },
    {
        path: '/escuelas/crearfacultad',
        name: 'escuelas.crearfacultad',
        component: require('./components/modulos/escuelas/crearfacultad').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
/******** RUTAS MODULO DE ADMINISTRACION DE  AREAS DE TESIS ********/
    {
        path: '/areatesis',
        name: 'areatesis.index',
        component: require('./components/modulos/areatesis/Index').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
    {
        path: '/areatesis/crear',
        name: 'areatesis.crear',
        component: require('./components/modulos/areatesis/create').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
    },
    {
        path: '/areatesis/editar/:id',
        name: 'areatesis.editar',
        component: require('./components/modulos/areatesis/edit').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        },
        props : true
    },
/******** RUTAS MODULO DE ADMINISTRACION DESARROLLO DE TESIS ********/
{
    path: '/tesis',
    name: 'tesis.fit',
    component: require('./components/modulos/tesis/fit').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/tesis/crear',
    name: 'tesis.crear',
    component: require('./components/modulos/tesis/create').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/tesis/ver/:id',
    name: 'tesis.ver',
    component: require('./components/modulos/tesis/view').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props: true
},
{
    path: '/tesis/editar/:id',
    name: 'tesis.editar',
    component: require('./components/modulos/tesis/edit').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props: true
},
{
    path: '/tesis/constancia',
    name: 'tesis.subirconstancia',
    component: require('./components/modulos/tesis/subirconstancia').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/tesis/revisiones',
        name: 'comision.verRevision',
        component: require('./components/modulos/tesis/verrevisioncomision').default,
        beforeEnter: (to, from, next) => {
            verificarAcceso(to, from, next);
        }
},
/********** RUTAS MODULO DE AVANCES DE TESIS *********/
{
    path: '/avances',
    name: 'avances.index',
    component: require('./components/modulos/avances/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/avances/crear',
    name: 'avances.crear',
    component: require('./components/modulos/avances/create').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/avances/subirfinalpdf',
    name: 'avances.subirfinalpdf',
    component: require('./components/modulos/avances/subirfinalpdf').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/avances/editar/:id',
    name: 'avances.editar',
    component: require('./components/modulos/avances/edit').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props: true
},
/******** RUTAS MODULO DE ADMINISTRACION NOTAS PENDIENTES********/
{
    path: '/notaspendientes',
    name: 'notaspendientes.index',
    component: require('./components/modulos/notaspendientes/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/notaspendientes/crear',
    name: 'notaspendientes.crear',
    component: require('./components/modulos/notaspendientes/create').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/notaspendientes/editar/:id',
    name: 'notaspendientes.editar',
    component: require('./components/modulos/notaspendientes/edit').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props: true
},
{
    path: '/notaspendientes/prorroga/:id',
    name: 'notaspendientes.prorroga',
    component: require('./components/modulos/notaspendientes/prorroga').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props: true
},
/******** RUTAS MODULO DE ADMINISTRACION VINCULACiONES DE TESIS ********/
{
    path: '/vinculacion',
    name: 'vinculacion.index',
    component: require('./components/modulos/vinculacion/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/vinculacion/crear',
    name: 'vinculacion.crear',
    component: require('./components/modulos/vinculacion/create').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/vinculacion/editar/:id',
    name: 'vinculacion.editar',
    component: require('./components/modulos/vinculacion/edit').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props : true
},
/******** RUTAS MODULO DE BITACORAS ********/
{
    path: '/bitacoras',
    name: 'bitacoras.index',
    component: require('./components/modulos/bitacoras/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/bitacoras/crear',
    name: 'bitacoras.crear',
    component: require('./components/modulos/bitacoras/create').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/bitacoras/editar/:id',
    name: 'bitacoras.editar',
    component: require('./components/modulos/bitacoras/edit').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props : true
},
/******** RUTAS MODULO DE COMISIONES ********/
{
    path: '/comisiones',
    name: 'comisiones.index',
    component: require('./components/modulos/comisiones/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/comisiones/crear/:id',
    name: 'comisiones.crear',
    component: require('./components/modulos/comisiones/create').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/comisiones/editar/:id',
    name: 'comisiones.editar',
    component: require('./components/modulos/comisiones/edit').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props : true
},
/******** RUTAS MODULO DE ADMINISTRACION DE REPORTES *********/
{
    path: '/reportes',
    name: 'reportes.reportefit',
    component: require('./components/modulos/reportes/reportefit').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
},
{
    path: '/reportes/detallesavances/:estado/:id',
    name: 'reportes.detallesavances',
    component: require('./components/modulos/avances/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props : true
},
{
    path: '/reportes/detallesbitacoras/:id',
    name: 'reportes.detallesbitacoras',
    component: require('./components/modulos/bitacoras/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props : true
},
{
    path: '/reportes/logs',
    name: 'reportes.logs',
    component: require('./components/modulos/reportes/logs').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props : true
},
/******** RUTAS MODULO DE ADMINISTRACION ACTA DE DEFENSA Y CALIFICACION *********/
{
    path: '/actadefensa',
    name: 'actadefensa.index',
    component: require('./components/modulos/actadefensa/Index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
 },
 {
    path: '/actadefensa/subiracta/:id',
    name: 'actadefensa.subiracta',
    component: require('./components/modulos/actadefensa/subiracta').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props : true
 },
 {
    path: '/actadefensa/subirnota/:id',
    name: 'actadefensa.subirnota',
    component: require('./components/modulos/actadefensa/subirnota').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props : true
 },
 /******** RUTAS MODULO DE ADMINISTRACION DE DOCUMENTOS *********/
 {
    path: '/documentos',
    name: 'documentos.index',
    component: require('./components/modulos/documentos/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
 },
 {
    path: '/documentosEscuela',
    name: 'escuelas.documentos.navegar',
    component: require('./components/modulos/documentosEscuela/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
 },
/******** RUTAS MODULO DE PARAMETROS DEL SISTEMA *********/

{
    path: '/parametros',
    name: 'parametros.index',
    component: require('./components/modulos/parametros/index').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
 },

/******** RUTAS MODULO DE REGISTRO DE TESIS FINALIZADAS *********/
 {
    path: '/registrodetesis',
    name: 'registrar.tesis',
    component: require('./components/modulos/registrodetesis/registrotesisfinalizadas').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
 },
 {
    path: '/indextesisfinalizada',
    name: 'index.tesisfinal',
    component: require('./components/modulos/registrodetesis/indextesisfinalizadas').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    }
 },
 {
    path: '/ediciondetesis/:id',
    name: 'editar.tesisfinal',
    component: require('./components/modulos/registrodetesis/ediciontesisfinalizadas').default,
    beforeEnter: (to, from, next) => {
        verificarAcceso(to, from, next);
    },
    props: true
 },
];//cierre concentracion de rutas

export default new Router({
    routes: rutas,
    mode: 'history',
    linkActiveClass: 'active'
});
