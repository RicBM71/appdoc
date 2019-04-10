/*
|--------------------------------------------------------------------------
| Mutation Types
|--------------------------------------------------------------------------
*/
export const SET_MENU = 'SET_MENU';
export const UNSET_MENU = 'UNSET_MENU';

/*
|--------------------------------------------------------------------------
| Initial State
|--------------------------------------------------------------------------
*/
const initialState = {
    showMenu: false,
};

/*
|--------------------------------------------------------------------------
| Mutations
|--------------------------------------------------------------------------
*/
const mutations = {
	[SET_MENU](state, payload) {
        state.showMenu = payload.showMenu;
	},
	[UNSET_MENU](state, payload) {
        state.showMenu = false;
	}
};

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/
const actions = {
	setShowMenu: (context, showMenu) => {
		context.commit(SET_MENU, {showMenu})
	},
	unsetShowMenu: (context) => {
		context.commit(UNSET_MENU);
	}
};

/*
|--------------------------------------------------------------------------
| Getters
|--------------------------------------------------------------------------
*/
const getters = {
	getShowMenu: (state) => {
		return state.showMenu
    }
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
