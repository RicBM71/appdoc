import Home from './components/Home.vue';
import Dash from './components/Dash.vue';
import Main from './components/Main.vue';
import st404 from './components/shared/404.vue';

import UserIndex from './components/admin/users/UserIndex.vue';
import UserCreate from './components/admin/users/UserCreate.vue';
import UserEdit from './components/admin/users/UserEdit.vue';
import RolesIndex from './components/admin/roles/RolesIndex.vue';
import RolesCreate from './components/admin/roles/RolesCreate.vue';
import RolesEdit from './components/admin/roles/RolesEdit.vue';

import RetIndex from './components/admin/retenciones/RetIndex.vue';
import RetCreate from './components/admin/retenciones/RetCreate.vue';
import RetEdit from './components/admin/retenciones/RetEdit.vue';

import IvaIndex from './components/admin/iva/IvaIndex.vue';
import IvaCreate from './components/admin/iva/IvaCreate.vue';
import IvaEdit from './components/admin/iva/IvaEdit.vue';

import CarpetaIndex from './components/admin/carpetas/CarpetaIndex.vue';
import CarpetaCreate from './components/admin/carpetas/CarpetaCreate.vue';
import CarpetaEdit from './components/admin/carpetas/CarpetaEdit.vue';

import EmpresaIndex from './components/admin/empresas/EmpresaIndex.vue';
import EmpresaCreate from './components/admin/empresas/EmpresaCreate.vue';
import EmpresaEdit from './components/admin/empresas/EmpresaEdit.vue';

import FpagoIndex from './components/admin/fpago/FpagoIndex.vue';
import FpagoCreate from './components/admin/fpago/FpagoCreate.vue';
import FpagoEdit from './components/admin/fpago/FpagoEdit.vue';

import ContadorIndex from './components/admin/contador/ContadorIndex.vue';
import ContadorCreate from './components/admin/contador/ContadorCreate.vue';
import ContadorEdit from './components/admin/contador/ContadorEdit.vue';


import ClienteIndex from './components/clientes/ClienteIndex.vue';
import ClienteCreate from './components/clientes/ClienteCreate.vue';
import ClienteEdit from './components/clientes/ClienteEdit.vue';
import ProductoIndex from './components/productos/ProductoIndex.vue';
import ProductoCreate from './components/productos/ProductoCreate.vue';
import ProductoEdit from './components/productos/ProductoEdit.vue';

import AlbaranIndex from './components/albaranes/AlbaranIndex.vue';
import AlbaranCreate from './components/albaranes/AlbaranCreate.vue';
import AlbaranEdit from './components/albaranes/AlbaranEdit.vue';

import EditPassword from './components/profile/edit-password/EditPassword.vue';

export default [
	{
		path: '/',
		name: 'index',
		component: Home
    },
	{
		path: '/login',
		name: 'login'
    },
	{
		path: '/home',
        component: Dash,
        children: [
			{
				path: '',
				name: 'dash',
                component: Main,
            },
            {
                path: '/users',
                name: 'users.index',
                component: UserIndex,
            },
            {
                path: '/users/create',
                name: 'users.create',
                component: UserCreate,
            },
            {
                path: '/users/:id/edit',
                name: 'users.edit',
                component: UserEdit,
            },
            {
                path: '/roles',
                name: 'roles',
                component: RolesIndex,
            },
            {
                path: '/roles/create',
                name: 'roles_create',
                component: RolesCreate,
            },
            {
                path: '/roles/:id/edit',
                name: 'roles_edit',
                component: RolesEdit,
            },
            {
                path: '/users/password',
                name: 'edit.password',
                component: EditPassword,
            },
            {
                path: '/retenciones',
                name: 'ret.index',
                component: RetIndex,
            },
            {
                path: '/retenciones/create',
                name: 'ret.create',
                component: RetCreate,
            },
            {
                path: '/retenciones/:id/edit',
                name: 'ret.edit',
                component: RetEdit,
            },
            {
                path: '/ivas',
                name: 'iva.index',
                component: IvaIndex,
            },
            {
                path: '/ivas/create',
                name: 'iva.create',
                component: IvaCreate,
            },
            {
                path: '/ivas/:id/edit',
                name: 'iva.edit',
                component: IvaEdit,
            },
            {
                path: '/carpetas',
                name: 'carpeta.index',
                component: CarpetaIndex,
            },
            {
                path: '/carpetas/create',
                name: 'carpeta.create',
                component: CarpetaCreate,
            },
            {
                path: '/carpetas/:id/edit',
                name: 'carpeta.edit',
                component: CarpetaEdit,
            },
            {
                path: '/empresas',
                name: 'empresa.index',
                component: EmpresaIndex,
            },
            {
                path: '/empresas/create',
                name: 'empresa.create',
                component: EmpresaCreate,
            },
            {
                path: '/empresas/:id/edit',
                name: 'empresa.edit',
                component: EmpresaEdit,
            },
            {
                path: '/clientes',
                name: 'cliente.index',
                component: ClienteIndex,
            },
            {
                path: '/clientes/create',
                name: 'cliente.create',
                component: ClienteCreate,
            },
            {
                path: '/clientes/:id/edit',
                name: 'cliente.edit',
                component: ClienteEdit,
            },
            {
                path: '/fpagos',
                name: 'fpago.index',
                component: FpagoIndex,
            },
            {
                path: '/fpagos/create',
                name: 'fpago.create',
                component: FpagoCreate,
            },
            {
                path: '/fpagos/:id/edit',
                name: 'fpago.edit',
                component: FpagoEdit,
            },
            {
                path: '/productos',
                name: 'producto.index',
                component: ProductoIndex,
            },
            {
                path: '/productos/create',
                name: 'producto.create',
                component: ProductoCreate,
            },
            {
                path: '/productos/:id/edit',
                name: 'producto.edit',
                component: ProductoEdit,
            },
            {
                path: '/contadores',
                name: 'contador.index',
                component: ContadorIndex,
            },
            {
                path: '/contadores/create',
                name: 'contador.create',
                component: ContadorCreate,
            },
            {
                path: '/contadores/:id/edit',
                name: 'contador.edit',
                component: ContadorEdit,
            },
            {
                path: '/albaranes',
                name: 'albaran.index',
                component: AlbaranIndex,
            },
            {
                path: '/albaranes/create',
                name: 'albaran.create',
                component: AlbaranCreate,
            },
            {
                path: '/albaranes/:id/edit',
                name: 'albaran.edit',
                component: AlbaranEdit,
            },

            // {
            //     path: '*',
            //     redirect: {
            //         name: 'index'
            //     }
            // }
        ]
    },
    {
        path: '*',
        name: '404',
        component: st404,
    }
];
