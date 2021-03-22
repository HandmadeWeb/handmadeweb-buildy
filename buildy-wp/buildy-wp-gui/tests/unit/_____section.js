import { mount, createLocalVue } from '@vue/test-utils'
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


describe('SectionModule.vue', () => {
    let wrapper = null;

    // SETUP - run before to each unit test
    beforeEach(() => {
        // render the component
        wrapper = mount(SectionModule, {
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

    // it('Has initial page builder array', () => {
    //     wrapper.setProps({
    //         pageBuilder: [],
    //         // dragArray: vm.pageBuilder
    //     })
    //     expect(wrapper.vm.pageBuilder).toEqual([])
    // })

    // it('Renders the empty add section controls', () => {
    //     expect(wrapper.find('.empty-controls').exists()).toBe(true)
    // })

    it('Clicking add row adds an empty row/column to the array and renders it', async () => {
        wrapper.setProps({
            component: [{ "id": "section-f81fc5cc-920b-4d19-a624-5f338b6c6f09", "type": "section-module", "content": [{ "id": "row-e01b9000-f3be-4bf0-837e-851bb6065771", "type": "row-module", "content": [{ "id": "column-51ff242c-2165-4abd-bad2-0a1bcd3e479b", "type": "column-module", "content": [], "options": { "isEditable": true, "admin_label": "Column", "columns": { "xs": "", "sm": "", "md": "", "lg": "", "xl": "" } } }], "options": { "isEditable": true, "admin_label": "Row" } }], "options": { "isEditable": true, "admin_label": "Section", "layout_boxed": true } }]
        })

        // When clicking add section
        wrapper.findAll('.empty-controls__row .add-row').trigger('click')

        // Adds to the array
        // expect(wrapper.vm.component.content.length).toBe(2)
        expect(wrapper.vm.component.content[1].type).toMatch('row-module')

        // Contains a column module
        expect(wrapper.vm.component.content[1].content[0].type).toMatch('column-module')

        // wrapper.vm.dragArray = wrapper.vm.pageBuilder

        // await wrapper.vm.$nextTick();

        // // Renders a default empty section / row / column correctly onto the page
        // const section = wrapper.find('.section')
        // const row = wrapper.find('.row')
        // const column = wrapper.find('.column')

        // expect(section.exists()).toBe(true)
        // expect(row.exists()).toBe(true)
        // expect(column.exists()).toBe(true)
    })
})

