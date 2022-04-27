<template>
    <div class="pb-4 news__background">
        <!-- header -->
        <div class="news__header-block">
            <h1>Новости сообщества</h1> 
            <components-news-search />
        </div>
        
        <!-- tabs -->
        <div class="d-flex flex-wrap ml-4 mr-4 mt-4 tabs">
            <div @click="filtredNews()" :class="{labelActive: filterBy === 'all'}" class="tab text-uppercase">
                <div class="tab__title">Все публикации</div>
                <span :class="{tab__border: filterBy === 'all'}"></span>
            </div>
            <div v-for="(label, index) in labels" :key="index" @click="filtredNews(label)" :class="{labelActive: filterBy == label}" class="tab text-uppercase">
                <div class="tab__title">{{ label }}</div>
                <span :class="{tab__border: filterBy == label}"></span>
            </div>
        </div>

        <!-- news -->
        <div class="row justify-content-center ml-4 mr-4">
            <div v-for="item in news" :key="item.id" class="news__item">
                <!-- news image -->
                <a :href="route('news.show', item.id)" class="news__item-image p-0">
                    <img :src="item.images[0].url">
                </a>
                <!-- news title -->
                <div class="news__item-article">
                    <div class="d-flex flex-column ">

                        <div class=" d-flex justify-content-between">
                            <div class="news__item-label">
                                {{ item.labels[0] }}
                            </div>
                            <div class="news__item-date">
                                {{ item.published | formatDate }}
                            </div>
                        </div>

                        <div class="news__item-title">
                            <a :href="route('news.show', item.id)">
                                {{ item.title }}
                            </a>
                        </div>
                    </div>

                    <div class="news__item-description">
                        {{limitStr(item.content)}}
                    </div>
                    
                </div>

            </div>
        </div>
        
    </div>
</template>

<script>
import uniq from 'lodash/uniq';

export default {
    name: "News-index",
    props: [
        'newsitems'
    ],
    data() {
        return {
            news: null,
            labels: null,
            filterBy: 'all'
        };
    },
    methods: {
        filtredNews(value)
        {
            if( value )
            {

                this.filterBy = value;
                axios.get(route('api.news.index'), {
                    fetchImages: true,
                    maxResults: 70
                }).then(response => {
                    this.news = response.data.data.items.filter(item => item.labels[0] === value)
                });

                // this.news = newsitems.items.filter(item => item.labels[0] === value);

            } else {

                this.filterBy = 'all';
                axios.get(route('api.news.index'), {
                    fetchImages: true,
                    maxResults: 70
                }).then(response => {
                    this.news = response.data.data.items
                });
                // this.news = newsitems.items;
            }

            axios.get(route('api.news.index'), {
                    fetchImages: true,
                    maxResults: 70
                }).then(response => {
                this.labels = uniq(response.data.data.items.map(function (label) {
                    return label.labels[0];
                }))
            });

            // this.labels = uniq(newsitems.items.map(function (label) {
            //     return label.labels[0];
            // }));

        },
        limitStr(str) {
            str = $(str).text().trim();
            str = str[0].toUpperCase()+str.slice(1);
            return str.substr(0, 150) + '...';
        },
        getLocalStorage()
        {
            if( localStorage.getItem('news-categoty') )
            {
                this.filterBy = localStorage.getItem('news-categoty');
                this.filtredNews(this.filterBy);
                localStorage.removeItem('news-categoty');
            }
        },
    },
    mounted() {
        this.filtredNews();
        this.getLocalStorage();
    }
}
</script>
