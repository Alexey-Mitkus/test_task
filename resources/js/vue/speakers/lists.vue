<template>

    <div class="statspikerom__spikers-cards">
        <div v-for="(spiker, index) in spikers" :key="index" class="statspikerom__card">
            <div class="statspikerom__info">
                <div class="statspikerom__info-item">
                    <div class="statspikerom__card-photo">
                        <img :src="spiker.photo" alt="">
                    </div>
                    <div class="statspikerom__info-name">
                        <h3>{{ spiker.spiker }}</h3>
                        <span>{{ spiker.profession }}</span>
                    </div>
                </div>
                <div class="statspikerom__info-content">
                    <h4>{{ spiker.type }}</h4>
                    <p>{{ spiker.theme }}</p>
                </div>
            </div>
            <a target="_blank" :href="spiker.link">Подробнее</a>
        </div>
    </div>

</template>
<script>
export default {
    name: 'Lists',
    data: () => ({
        spikers: [
            // new
            {
                spiker: 'Роман Бузунов',
                photo: '/storage/images/speakers/roman_buzunov.jpeg',
                profession: 'Врач-сомнолог',
                type: 'Подкаст',
                theme: '«Медикам не снилось: хронический стресс и бессонница у медицинских работников»',
                // link: 'https://community.mosgorzdrav.ru/news/1633025045415008713',
                link: route('news.show', '1633025045415008713'),
            },
            {
                spiker: 'Екатерина Сирина и Надежда Маленкова ',
                photo: '/storage/images/speakers/malinkova_nadezhda.jpeg',
                profession: 'Социолог и психолог',
                // profession: 'Социолог и медицинский психолог',
                type: 'Вебинар',
                theme: '«Системный подход к проблеме профессионального выгорания сотрудников: опыт ГКБ № 52»',
                // link: 'https://community.mosgorzdrav.ru/news/9060707919886951453',
                link: route('news.show', '9060707919886951453'),
            },
            {
                spiker: 'Раиса Покровская',
                photo: '/storage/images/speakers/pokrovskay_raisa.jpeg',
                profession: 'Основатель стартапа HealingHand Tech',
                type: 'Подкаст',
                theme: '«Геймификация в медицине»',
                // link: 'https://community.mosgorzdrav.ru/news/6256225903376796368',
                link: route('news.show', '6256225903376796368'),
            },
            {
                spiker: 'Максим Гущин',
                photo: '/storage/images/speakers/j5w2cCY8ZV726JgEbSrfy9zwGi7r9mmeahmiy4hz.jpeg',
                profession: 'Lean-эксперт, психолог',
                type: 'Вебинар',
                theme: '«Профилактика выгорания у сотрудников медицинских организаций»',
                // link: 'https://community.mosgorzdrav.ru/news/6029991385077328850',
                link: route('news.show', '6029991385077328850'),
            },
            {
                spiker: 'Николай Кондраков',
                photo: '/storage/images/speakers/kondrakov_nikolay.jpeg',
                profession: 'HR-специалист',
                type: 'Подкаст',
                theme: '«Токсичные сотрудники: особенности вида, среда обитания и способы приручения»',
                // link: 'https://community.mosgorzdrav.ru/news/5247335114442469517',
                link: route('news.show', '5247335114442469517'),
            },

            // old
            {
                spiker: 'Максим Гущин',
                photo: '/storage/images/speakers/j5w2cCY8ZV726JgEbSrfy9zwGi7r9mmeahmiy4hz.jpeg',
                profession: 'Lean-эксперт, психолог',
                type: 'Вебинар',
                theme: "«Практика тиражирования Lean-проектов на примере сети детских поликлиник города Москвы в условиях пандемии»",
                // link: 'https://community.mosgorzdrav.ru/news/23484643399572971',
                link: route('news.show', '23484643399572971'),
            },
            {
                spiker: 'Александр Кудряшов',
                photo: '/storage/images/speakers/VZiK9JF6Rr7irHsQ6NCyAPp4HuPHDeJOR703h1cj.jpeg',
                profession: 'Врач-психиатр',
                type: 'Вебинар',
                theme: '«Как реализовать соцальный проект в здравоохранении и получить разряд по здоровью»',
                // link: 'https://community.mosgorzdrav.ru/news/3428800304108771644',
                link: route('news.show', '3428800304108771644'),
            },
            {
                spiker: 'Александр Юмукян',
                photo: '/storage/images/speakers/un9UDMfNKVijg0MuYYQTUx2GuBjHeIDo5UjtX8OQ.jpeg',
                profession: 'Врач-методист',
                type: 'Вебинар',
                theme: '«Внедрение системы внутреннего контроля качества и безопасности медицинской деятельности»',
                // link: 'https://community.mosgorzdrav.ru/news/8577045381010321105',
                link: route('news.show', '8577045381010321105'),
            },
            {
                spiker: 'Сергей Тарасов',
                photo: '/storage/images/speakers/r1suzSdkiUvFNXpyT8poCkJ8c1N6RbkfI0wBONaK.jpeg',
                profession: 'Врач-методист',
                type: 'Вебинар',
                theme: '«Создание и внедрение системы оперативного учета и контроля за графиком работы персонала в “красной” зоне в условиях пандемии COVID-19»',
                // link: 'https://community.mosgorzdrav.ru/news/5280419720229932969',
                link: route('news.show', '5280419720229932969'),
            },
        ],
    }),
}

</script>