<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotivationAchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // Свой человек 1
        DB::table('motivation_achievements')->insert([
            'bage_id' => 1,
            'title' => 'Заполнить профиль на 80% и более и получить верификацию',
            'count' => 5,
            'type' => 'one',
            'image' => '/images/motivation/achivments/verification-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 1,
            'title' => 'Присоединиться к нам в социальных сетях',
            'count' => 5,
            'type' => 'one',
            'image' => '/images/motivation/achivments/social-media-achivment.svg'
        ]);

        // Цифровой доктор 2
        DB::table('motivation_achievements')->insert([
            'bage_id' => 2,
            'title' => 'Найти ошибку на сайте и сообщить о ней',
            'count' => 5,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/search-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 2,
            'title' => 'Предложить улучшение на сайте сообщества',
            'count' => 5,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/arrowup-achivment.svg'
        ]);

        // Амбасcадор 3
        DB::table('motivation_achievements')->insert([
            'bage_id' => 3,
            'title' => 'Пригласить участниика в сообщество',
            'count' => 20,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/members-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 3,
            'title' => 'Поделиться постом о сообществе в соц. сетях',
            'count' => 10,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/share-link-achivment.svg'
        ]);

        // Эксперт 4
        DB::table('motivation_achievements')->insert([
            'bage_id' => 4,
            'title' => 'Выступить в роли спикера на онлайн или офлайн мероприятии',
            'count' => 20,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/speaker-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 4,
            'title' => 'Принять участие в подкасте в роли спикера-гостя подкаста',
            'count' => 15,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/podcust-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 4,
            'title' => 'Принять участие в подготовке статьи (как автор)',
            'count' => 15,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/pencil-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 4,
            'title' => 'Предоставить материал для интервью',
            'count' => 10,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/document-folder-achivment.svg'
        ]);

        // Командный игрок 5
        DB::table('motivation_achievements')->insert([
            'bage_id' => 5,
            'title' => 'Принять участие в проектах сообщества в роли участника',
            'count' => 10,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/participant-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 5,
            'title' => 'Выступить в роли наставника в проекте',
            'count' => 15,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/mentor-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 5,
            'title' => 'Принять участие в проектах сообщества в роли лидера группы',
            'count' => 20,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/lead-achivment.svg'
        ]);

        // Генератор идей 6
        DB::table('motivation_achievements')->insert([
            'bage_id' => 6,
            'title' => 'Предложить в «Банке идей» инициативу, которую впоследствии реализуют',
            'count' => 20,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/idea-generator-achivment.svg'
        ]);

        // Сердцеед 7
        DB::table('motivation_achievements')->insert([
            'bage_id' => 7,
            'title' => 'Получить благодарность от участника сообщества',
            'count' => 5,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/thanks-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 7,
            'title' => 'Поставить "Спасибо" другому участнику (всего 5 лайков в неделю - не более 1 лайка 1 пользователю)',
            'count' => 2,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/thanks-action-achivment.svg'
        ]);

        // SuperStar 8
        DB::table('motivation_achievements')->insert([
            'bage_id' => 8,
            'title' => 'Принять участие в глубинном интервью (CustDev)',
            'count' => 20,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/custdev-achivment.svg'
        ]);

        DB::table('motivation_achievements')->insert([
            'bage_id' => 8,
            'title' => 'Иметь стаж в сообществе от 9 месяцев',
            'count' => 30,
            'type' => 'unlimited',
            'image' => '/images/motivation/achivments/work-experience-achivment.svg'
        ]);
    }
}
