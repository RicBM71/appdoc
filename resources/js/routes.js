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
                name: 'users',
                component: UserIndex,
            },
            {
                path: '/users/create',
                name: 'users_create',
                component: UserCreate,
            },
            {
                path: '/users/:id/edit',
                name: 'users_edit',
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
