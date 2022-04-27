<template>

    <div class="search__form">
        <div class="search-block">
            <span class="search-addon" id="search-addon">
                <img class="search-icon" src="/images/news/search_news.png" alt="search_icon">
            </span>
            <input @input="getTitle(txtInput)"
                v-model="txtInput"
                type="search"
                class="search"
                placeholder="Поиск"
                aria-label="Search"
                aria-describedby="search-addon" />
        </div>
        
        <template v-if="txtInput.length > 0">
            <div class="search-list">
                <div v-for="(item, index) in searchTitle" :key="index" class="search-list-item">
                    <a :href="route('news.show', item.id)">{{item.name}}</a>
                </div>
            </div>
        </template>
    </div>
</template>
<script>
import {Form} from "vform";

export default {
    name: 'Search',
    data: function(){
        return {
            txtInput: '',
            searchTitle: [
                {
                    id: '',
                    name: '',
                }
            ],
        }
    },
    computed: {

    },
    methods: {

        getTitle(txtInput) {
            let v = this;
            v.searchTitle = [];
            
            if( txtInput )
            {
                axios.get(route('api.news.search'), {
                    params:{
                        search: txtInput
                    }
                })
                .then(function (response) {
                    if( response.data.data.items )
                    {
                        response.data.data.items.forEach((item) => {
                            v.searchTitle.push({id: item['id'], name: item['title']})       
                        })
                    } else {
                        v.searchTitle.push({id: 0, name: 'По вашему запросу ничего не найдено'})
                    }
                })
            } 
        }
    },
}

</script>
<style>
    .search-list {
        position: absolute;
        top: 65px;
        left: 29px;
        z-index: 3;
        width: 89%;
    }

    .search-list-item {
        font-weight: 600;
        display: flex;
        flex: row;
        align-items: center;
        padding: 10px;
        font-size: 14px;
        border: 1px solid rgba(0,0,0,0.2);
        border-top: none;
        border-bottom: none;
        background-color: white;
    }
    .search-list-item:last-child {
        border-bottom: 1px solid rgba(0,0,0,0.2);
        border-radius: 4px;
    }
    .search-list-item:first-child {
        margin-top: 1px;
    }
    @media(max-width: 1024px )
    {
        iframe {
            width: 371px;
            height: 300px;
        }
    }
    @media(max-width: 768px )
    {
        .search-list{
            width: 80%;
        }
    } 
</style>
