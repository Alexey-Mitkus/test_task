<template>
    <div class="pb-4 news__background">
        <!-- header -->
        <div class="news__header-block">
            <h1>Проекты сообщества</h1>
            <components-projects-communities-search />
        </div>
        <!-- tabs -->
        <div class="d-flex flex-wrap ml-4 mr-4 mb-3 mt-4 tabs tabs__projects">
            <div @click="filtredNews()"  :class="{labelActive: filterBy == 'all'}" class="tab text-uppercase">
                <div class="tab__title">Все публикации</div>
                <span :class="{tab__border: filterBy == 'all'}"></span>
            </div>
            <div v-for="(label, index) in labels" :key="index" @click="filtredNews(label)" :class="{labelActive: filterBy == label}" class="tab text-uppercase">
                <div class="tab__title">{{ label }}</div>
                <span :class="{tab__border: filterBy == label}"></span>
            </div>
        </div>
        <!-- projects -->
        <div class="d-flex justify-content-start ml-4 mr-4 flex-wrap">
            <div v-for="item in news" :key="item.id" class="be_block d-flex border m-2 border-grey rounded p-0">
                <div class="d-flex align-items-center p-0">
                    <a :href="route('community-project.show', item.id)">
                        <img class="rounded" :src="item.images[0].url">
                    </a>
                </div>
                <div class="p-2">
                    <div class="d-flex flex-column ">
                        <div class=" d-flex justify-content-between">
                            <div class="labelArticle be_label">
                                {{ item.labels[0] }}
                            </div>
                            <div class="dataArticle be_date">
                                {{ item.published | formatDate }}
                            </div>
                        </div>
                        <div class="labelArticle be_title">
                            <a :href="route('community-project.show', item.id)">
                                {{ item.title }}
                            </a>
                        </div>
                        <div class="textArticle">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import uniq from 'lodash/uniq';

export default {
    name: "Projects-Communities-Index",
    data() {
        return {
            news: null,
            labels: null,
            filterBy: 'all',
        };
    },
    computed:{
    },
    methods: {
       
        filtredNews(value) {
            if(value) {
                this.filterBy = value;
                axios
                    .get(route('api.community-project.index'), {
                        params:{
                            fetchImages: true,
                            maxResults: 20,
                        }
                    })
                    .then(response => {
                        this.news = response.data.data.items.filter(item => item.labels[0] === value)
                    });
            } else {
                this.filterBy = 'all';
                //
                axios
                    .get(route('api.community-project.index'), {
                        params:{
                            fetchImages: true,
                            maxResults: 20,
                        }
                    })
                    .then(response => {
                        this.news = response.data.data.items
                    });
            }
            axios
                .get(route('api.community-project.index'), {
                    params:{
                        fetchImages: true,
                        maxResults: 20,
                    }
                })
                .then(response => {
                    this.labels = uniq(response.data.data.items.map(function (label) {
                        return label.labels[0];
                    }))
                });

        }
    },
    mounted() {
        this.filtredNews()
    }
}
</script>
<style scoped>

</style>
