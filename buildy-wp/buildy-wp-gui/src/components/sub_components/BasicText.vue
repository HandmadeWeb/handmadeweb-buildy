<template>
    <div class="attribute-editor module module-settings mt-0 flex items-center">
        <label :for="(component.id + 'text-input')" class="pr-4 w-24">{{label}}:</label>
        <textarea @change="handleChange" class="text-gray-800 w-full border-2 p-2" v-model="value" placeholder="Enter the content for your component"></textarea>
    </div>
</template>

<script>
import {setDeep, getDeep} from '../../functions/objectHelpers'
export default {
    name: 'basic-text',
    props: {
        path: String,
        label: String,
    },
    data() {
        return {
            value: ''
        }
    },
    methods: {
        handleChange() {
            setDeep(this.component, this.path, this.value)
        }
    },
    mounted() {
       this.value = getDeep(this.component, this.path)
    }
}
</script>

<style lang="scss">

</style>