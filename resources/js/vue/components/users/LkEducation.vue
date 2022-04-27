<template>
    <div class="lk-info">
        <div class="lk-info__header">
            <div class="header-title">
                <h4>Образование</h4>
            </div>
            <div v-if="status === 'user' || status === 'admin'" class="lk-block-reduct" @click="educationReduct(true)">
                <img src="/images/icons/reduct-icon.svg" alt="">
            </div>
        </div>
        <template v-if="!lodash.isEmpty(educationArr)">
            <div v-for="(item, index) in educationArr" class="lk-education-block" :key="index">
                <h4 class="title">{{ item.type.name }}</h4>
                <template v-if="item.type.slug === 'advanced'">
                    <h5 class="institution" v-if="item.course_link">
                        <a :href="item.course_link" target="_blank">{{ item.course_organization }}{{ ( !lodash.isEmpty(item.end_at) ? ', ' + formatDate(item.end_at, 'YYYY') : '' ) }}</a>
                    </h5>
                    <h5 class="institution" v-else>{{ item.course_organization }}{{ ( !lodash.isEmpty(item.end_at) ? ', ' + formatDate(item.end_at, 'YYYY') : '' ) }}</h5>
                    <p class="profession">{{ item.course_name }}</p>
                </template>
                <template v-else>
                    <h5 class="institution">{{ ( !lodash.isEmpty(item.university) ? item.university.name : ( !lodash.isEmpty(item.raw_education) ? item.raw_education : '---') ) }}{{ ( !lodash.isEmpty(item.end_at) ? ', ' + formatDate(item.end_at, 'YYYY') : '' ) }}</h5>
                    <p class="profession">{{ item.position }}</p>
                </template>
            </div>
        </template>
        <template v-else>
            <div class="lk-education-block">
                <h4 class="title">Вы еще не добавили образование</h4>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    name: 'LkEducation',
    props: [
        'educationArr',
        'status'
    ],
    data () {
        return {
            reductEducationInfo: false,
            educations: [],
        }
    },
    methods: {
        educationReduct(reduct) {
            reduct == true ? this.reductEducationInfo = true : this.reductEducationInfo = false;
            
            this.$emit('educationReduct', {
                reductEducationInfo: this.reductEducationInfo
            })
        },
        formatDate(date, format)
        {
            return moment(date).format(format);
        }
    }
}
</script>