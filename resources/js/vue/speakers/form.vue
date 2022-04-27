<template>
    <div>
        <input type="hidden" :value="tags.map(tag => tag.text)" name="operation">
        <div class="column m-0 vue-tag-font ">
            <!-- tags-aria -->
            <div @click="focusOnInput()" class="col-md-12  p-0">
    <!-- Инлайн стили применены по причине невозможности поменять стили -->
                <vue-tags-input
                    class="tags_beilec statspikerom__text-aria"
                    v-model="tag"
                    :placeholder="placeholder"
                    :tags="tags"
                    :autocomplete-items="filteredItems"
                    :add-on-key="[13, ':', ';', ',']"
                    @tags-changed="newTags => tags = newTags"
                    style="max-width: 100%; background-color: #fff; overflow: auto;"
                />
            </div>
            
            <!-- tags -->
            <div class="col-md-12 ti-tags-list beilec_tags">
                
                <label for="tag-open">Примеры тем для выступлений</label>
                <input type="checkbox" id="tag-open" class="tags-show">
                
                <span class="open-tag-icon"></span>
                <span class="open-tag-icon2"></span>
                
                
                <div class="tags-block">
                    <span>Вы можете выбрать тему из предложенных тегов ниже</span>
                    <div v-for="(item, index) in filteredItemsClickable" :key="index" @click="addTag(item.text)" class="small_circle_tag_spiker edittext">
                        {{ item.text }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import VueTagsInput, {createTag} from '@johmun/vue-tags-input';

export default {
    name: 'Form',
    components: {
        VueTagsInput,
    },
    data() {
        return {
            tag: '',
            placeholder: 'Укажите тему',
            tags: [],
            autocompleteItems: [ 
                {text: 'О своём проекте',},
                {text: 'Проектное и продуктовое управление',},
                {text: 'Стратегическое и аналитическое управление',}, 
                {text: 'Анализ данных, статистика',}, 
                {text: 'Управление качеством',},
                {text: 'Бизнес-процессы',},
                {text: 'Риск-менеджмент',}, 
                {text: 'HR, развитие персонала, мотивация',}, 
                {text: 'Управление изменениями',}, 
                {text: 'IT, цифровая трансформация, инновации',}, 
                {text: 'Управление коммуникациями',},
            ],
        };
    },
    computed: {
        filteredItems() {
            return this.autocompleteItems.filter(i => {
                return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
            });
        },

        filteredItemsClickable() {
            return this.autocompleteItems.filter(i => {
                return this.tags.map(tag => tag.text.toLowerCase()).indexOf(i.text.toLowerCase()) == -1;
            });
        }
    },
    methods: {
        focusOnInput()
        {
            document.querySelector('.tags_beilec input').focus()

        },
        addTag(text) {
            if (this.checkTagsLength()) {
                this.adding = false;
                let tag = createTag(text, this.tags);
                this.tags.push(tag);
                // console.log(tag)
                return null;
            }

        },
        checkTagsLength(){
            if(this.tags.length < 10)
            {
                this.placeholder = "Укажите тему";
                this.adding = false;
                return true;
            }
            if(this.tags.length == 10)
            {
                this.placeholder = "Вы не можете выбрать больше 10 тем";
                this.adding = true;
                return false;
            }
        },

    }
};
</script>