import { mount, createLocalVue } from '@vue/test-utils'
import ContainerModule from '@/components/layout/ContainerModule.vue'
import SectionModule from '@/components/layout/SectionModule.vue'
import RowModule from '@/components/layout/rows/RowModule.vue'
import ColumnSelector from '@/components/layout/rows/ColumnSelector.vue'
import ColumnModule from '@/components/layout/ColumnModule.vue'
import SettingsModal from '@/components/shared/SettingsModal.vue'
import ModuleSettingsBar from '@/components/shared/ModuleSettingsBar.vue'
import AttributeEditor from '@/components/sub_components/AttributeEditor.vue'
import ToggleSwitch from '@/components/form_elements/ToggleSwitch.vue'
import VModal from 'vue-js-modal'
import store from '@/store'

const localVue = createLocalVue();

localVue.use(VModal)
localVue.component('section-module', SectionModule)
localVue.component('row-module', RowModule)
localVue.component('column-module', ColumnModule)
localVue.component('column-selector', ColumnSelector)
localVue.component('settings-modal', SettingsModal)
localVue.component('module-settings-bar', ModuleSettingsBar)
localVue.component('attribute-editor', AttributeEditor)
localVue.component('toggle-switch', ToggleSwitch)


describe('ContainerModule.vue', () => {
    let wrapper = null;

    // SETUP - run before to each unit test
    beforeEach(() => {
        // render the component
        wrapper = mount(ContainerModule, {
            store,
            localVue,
            data: function () {
                return {
                    dragArray: []
                }
            }
        })
    })

    // TEARDOWN - run after unit test
    afterEach(() => {
        wrapper.destroy()
    })

    it('Has initial page builder array', () => {
        wrapper.setProps({
            pageBuilder: [],
            // dragArray: vm.pageBuilder
        })
        expect(wrapper.vm.pageBuilder).toEqual([])
    })

    it('Renders the empty add section controls', () => {
        expect(wrapper.find('.empty-controls').exists()).toBe(true)
    })

    it('Clicking add section adds an empty section/row/column to the array and renders it', async () => {
        wrapper.setProps({
            pageBuilder: [],
            dragArray: []
        })

        // When clicking add section
        wrapper.findAll('.empty-controls a').at(0).trigger('click')

        // Adds to the array
        expect(wrapper.vm.pageBuilder.length).toBe(1)
        // Has a type of section module
        expect(wrapper.vm.pageBuilder[0].type).toMatch('section-module')
        // Contains a row module
        expect(wrapper.vm.pageBuilder[0].content[0].type).toMatch('row-module')
        // Contains a column module
        expect(wrapper.vm.pageBuilder[0].content[0].content[0].type).toMatch('column-module')

        wrapper.vm.dragArray = wrapper.vm.pageBuilder

        await wrapper.vm.$nextTick();

        // Renders a default empty section / row / column correctly onto the page
        const section = wrapper.find('.section')
        const row = wrapper.find('.row')
        const column = wrapper.find('.column')

        expect(section.exists()).toBe(true)
        expect(row.exists()).toBe(true)
        expect(column.exists()).toBe(true)
    })
})

