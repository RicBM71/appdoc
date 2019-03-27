/*
|--------------------------------------------------------------------------
| Mutation Types
|--------------------------------------------------------------------------
*/
export const SET_USER = 'SET_USER';
export const UNSET_USER = 'UNSET_USER';

/*
|--------------------------------------------------------------------------
| Initial State
|--------------------------------------------------------------------------
*/
const initialState = {
	name: null,
    username: null,
    avatar: null,
    roles: [],
    permisos: []
};

/*
|--------------------------------------------------------------------------
| Mutations
|--------------------------------------------------------------------------
*/
const mutations = {
	[SET_USER](state, payload) {
		state.name = payload.user.name;
        state.username = payload.user.username;
        state.avatar = payload.user.avatar;
        state.roles = payload.user.roles;
        state.permisos = payload.user.permisos;
	},
	[UNSET_USER](state, payload) {
		state.name = null;
        state.username = null;
        state.avatar = null;
        state.roles = [];
        state.permisos = [];
	}
};

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/
const actions = {
	setAuthUser: (context, user) => {
		context.commit(SET_USER, {user})
	},
	unsetAuthUser: (context) => {
		context.commit(UNSET_USER);
	}
};

/*
|--------------------------------------------------------------------------
| Getters
|--------------------------------------------------------------------------
*/
const getters = {
	isLoggedIn: (state) => {
		return !!(state.name && state.username);
    },
    getRoles: (state) =>{
        return state.roles
    },
    isRoot: (state) =>{
        return (state.roles.indexOf('Root') >= 0) ? true : false;

    },
    isAdmin: (state) =>{
        return (state.roles.indexOf('Admin') >= 0) ? true : false;

    },

};

/*
|--------------------------------------------------------------------------
| Export the module
|--------------------------------------------------------------------------
*/
export default {
	state: initialState,
	mutations,
	actions,
	getters
}
