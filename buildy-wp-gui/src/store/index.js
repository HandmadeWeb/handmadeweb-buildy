import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    isWP: false,
    config: {},
    colours: [],
    globals: [],
    dragDisabled: false,
    validComponents: [],
    imageSizes: []
  },
  mutations: {
    SET_CONFIG(state, payload) {
      state.config = payload
    },
    SET_VALID_COMPONENTS(state, payload) {
      Vue.set(state, 'validComponents', payload)
    },
    DRAG_TOGGLE(state, payload) {
      state.dragDisabled = payload
    },
    SET_GLOBALS(state, globals) {
      state.globals = globals
    },
    SET_COLOURS(state, colours) {
      state.colours = colours;
    },
    SET_REGISTERED_IMAGE_SIZES(state, sizes) {
      state.imageSizes = sizes;
    },
    FLAG_WP(state, payload) {
      state.isWP = payload
    }
  },
  actions: {
    config(context, payload) {
      // Set the config options globally
      context.commit('SET_CONFIG', payload)

      if (!payload.is_admin) {
        // If you're not admin, immediately disable dragging.
        context.commit('DRAG_TOGGLE', true)
      }

      if (payload.theme_colours && payload.theme_colours.length) {
        let colours = payload.theme_colours.map(colour => colour.name)
        context.commit('SET_COLOURS', colours)
      }

      if (payload.registered_image_sizes) {
        context.commit('SET_REGISTERED_IMAGE_SIZES', payload.registered_image_sizes)
      }

      // if (payload.global_api) {
      //   context.dispatch('fetchGlobals')
      // }

      // Config object only exists in wordpress scenarios at the moment
      context.commit('FLAG_WP', true)
    },
    async fetchGlobals({ commit, state }) {
      if (state.config.global_api) {
        let res = await fetch(state.config.global_api);
        let globals = await res.json();
        commit('SET_GLOBALS', globals)
      }
    },
    dragToggle(context, payload) {
      if (context.state.config.is_admin) {
        // If you're an admin, function as normal
        context.commit('DRAG_TOGGLE', payload)
      } else {
        // If you're not an admin, remain disabled
        context.commit('DRAG_TOGGLE', true)
      }
    },
    validComponents({ commit }, payload) {
      if (payload.length) {
        let clean = payload.filter(el => {
          return el
        })
        commit('SET_VALID_COMPONENTS', clean)
      }
    }
  },
  getters: {
    isWP: state => state.isWP,
    config: state => state.config,
    spacers: state => state.config.spacers || null,
    colours: state => state.colours,
    globals: state => state.globals,
    dragDisabled: state => state.dragDisabled,
    imageSizes: state => Object.keys(state.imageSizes).join(','),
    validComponents: state => state.validComponents
  }
})
