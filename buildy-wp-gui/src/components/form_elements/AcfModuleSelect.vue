<template>
  <div
    class="select-box-module module-settings mt-0 flex relative"
    :class="[inline ? 'flex-row' : 'flex-col']">
    <label class="pr-4 pb-1 setting-label">{{ label }}:</label>
    <select
      class="select-box rounded p-2"
      v-model="value"
      @change="handleChange">
      <option :value="defaultVal">{{ defaultVal }}</option>
      <option
        v-for="(option, i) in optionsArr"
        :key="option + i"
        class="select-choice flex"
        :value="option.field_group_id">
        {{ option.field_group_title }}
      </option>
    </select>
  </div>
</template>

<script>
import { EventBus } from '../../EventBus'
import { getDeep, setDeep } from '../../functions/objectHelpers'
import { stripTrailingSlash } from '../../functions/helpers'

export default {
  props: {
    label: String,
    range: Number,
    options: String,
    endpoint: String,
    action: String,
    path: String,
    inline: Boolean,
    selected: String,
    defaultVal: {
      type: String,
      default: 'None',
    },
  },
  data() {
    return {
      value: this.defaultVal,
      api_options: null,
    }
  },
  computed: {
    optionsArr() {
      if (this.range) {
        return Array.from(Array(this.range).keys())
      }

      if (this.api_options) {
        if (typeof this.api_options === 'object') {
          return this.api_options
        } else {
          if (this.api_options.includes(',')) {
            return this.api_options.split(',').map((el) => el.trim())
          }
          return this.api_options.split('\n').map((el) => el.trim())
        }
      }

      return this.options
        ? this.options
            .replace(/[[\]']+/g, '')
            .split(',')
            .map((el) => el.trim())
        : null
    },
    valueClean() {
      return this.value.toLowerCase().trim().replace(/ /g, '-')
    },
  },
  methods: {
    // On select change
    handleChange(e) {
      // Get select value and label
      let selectValue = this.value,
        selectLabel = e.target.options[e.target.options.selectedIndex].text
      // If select is "Load Existing"
      if (this.action === 'load') {
        EventBus.$emit('showSelect', false)
        // Get post ID from Label - Match numbers on end of string
        let strippedLabel = selectLabel.match(/[0-9]+$/)
        strippedLabel = parseInt(strippedLabel[0], 10)
        // Remove post ID from Label - Split hyphen from end of string
        selectLabel = selectLabel
          .substr(0, selectLabel.lastIndexOf('-'), 1)
          .trim()
        // Set title - Used for admin label only
        EventBus.$emit('setTitle', selectLabel)
        // Emit event to load existing form
        EventBus.$emit('loadExisting', {
          post_id: strippedLabel,
          field_groups: selectValue,
        })
      }
      // If select is "Create New"
      if (this.action === 'create') {
        // Set title - Used for post title and admin label
        EventBus.$emit('setTitle', selectLabel)
        // Emit event to create new form
        EventBus.$emit('createForm', selectValue)
      }
      if (this.component && this.path) {
        setDeep(this.component, this.path, selectValue)
      }
    },
    // Function to fetch options from Rest API
    async fetchOptions() {
      EventBus.$emit('isLoading', true)
      if (window.global_vars) {
        try {
          let res = await fetch(
            `${stripTrailingSlash(window.global_vars.rest_api_base)}/${
              this.endpoint
            }`
          )
          let data = await res.json()
          this.api_options = data.body
        } catch {
          this.defaultVal =
            'Something went wrong fetching the options from the API. Please try again!'
        }
        EventBus.$emit('isLoading', false)
      } else {
        this.defaultVal =
          'Something went wrong connecting to the API. Please try again!'
      }
    },
  },
  mounted() {
    if (this.selected) {
      this.value = this.selected.trim()
    }

    if (!this.selected && this.path) {
      this.value = getDeep(this.component, this.path) || this.defaultVal
    }

    if (!this.options && this.endpoint) {
      this.fetchOptions().then(() => {
        if (!this.value) {
          this.value = getDeep(this.component, this.path) || this.defaultVal
        }
      })
    }

    this.$emit('change', this.value)
  },
  inject: ['component'],
}
</script>

<style lang="scss" scoped>
select {
  width: 100%;
  background: #f1f1f1;
  border-radius: 0.3rem;
  box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);
  cursor: pointer;
  margin: 0 auto;
  flex-grow: 1;
  padding: 0.5rem;
  position: relative;
}
</style>
