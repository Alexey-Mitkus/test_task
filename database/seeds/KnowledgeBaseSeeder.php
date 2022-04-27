<?php

use Illuminate\Database\Seeder;

class KnowledgeBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class=KnowledgeBaseSeeder

        $Themes = [
            [
                'name' => 'Методологии',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => [
                    [
                        'name' => 'Управление проектом',
                        'user_id' => null,
                        'is_active' => 1,
                        'slug' => null
                    ],
                    [
                        'name' => 'Управление продуктом',
                        'user_id' => null,
                        'is_active' => 1,
                        'slug' => null
                    ],
                    [
                        'name' => 'Waterfall',
                        'user_id' => null,
                        'is_active' => 1,
                        'slug' => null
                    ],
                    [
                        'name' => 'PRINCE2',
                        'user_id' => null,
                        'is_active' => 1,
                        'slug' => null
                    ],
                    [
                        'name' => 'Agile',
                        'user_id' => null,
                        'is_active' => 1,
                        'slug' => null
                    ],
                    [
                        'name' => 'Scrum',
                        'user_id' => null,
                        'is_active' => 1,
                        'slug' => null
                    ],
                    [
                        'name' => 'Kanban',
                        'user_id' => null,
                        'is_active' => 1,
                        'slug' => null
                    ],
                    [
                        'name' => 'Lean',
                        'user_id' => null,
                        'is_active' => 1,
                        'slug' => null
                    ],
                    [
                        'name' => 'Six Sigma',
                        'user_id' => null,
                        'is_active' => 1,
                        'slug' => null
                    ]
                ]
            ],
            [
                'name' => 'Практики',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Инструменты',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Сертификации',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Другое',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ]
        ];

        $Formats = [
            [
                'name' => 'Статья',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Видео',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Подкаст',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Литература',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Документ',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Курс',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Сайт',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Инфографика',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ],
            [
                'name' => 'Другое',
                'parent_id' => null,
                'user_id' => null,
                'is_active' => 1,
                'slug' => null,
                'childrens' => []
            ]
        ];

        if( !empty($Themes) )
        {
            foreach($Themes as $key => $th):
                
                $theme = \App\Models\KnowledgeBase\KbTheme::create([
                    'name' => $th['name'],
                    'parent_id' => !empty($th['parent_id']) ? $th['parent_id'] : null,
                    'user_id' => $th['user_id'],
                    'is_active' => $th['is_active'],
                    'slug' => !empty($th['slug']) ? $th['slug'] : null,
                ]);

                if( !empty($th['childrens']) )
                {
                    $this->childrensThemes($theme, $th['childrens']);
                }

            endforeach;
        }

        if( !empty($Formats) )
        {
            foreach($Formats as $key => $frm):

                $format = \App\Models\KnowledgeBase\KbFormat::create([
                    'name' => $frm['name'],
                    'user_id' => $frm['user_id'],
                    'is_active' => $frm['is_active'],
                    'slug' => !empty($frm['slug']) ? $frm['slug'] : null
                ]);

                if( !empty($frm['childrens']) )
                {
                    $this->childrensFormats($format, $frm['childrens']);
                }

            endforeach;
        }
    }

    public function childrensThemes($parentTheme, $childrens)
    {
        if( empty($parentTheme) )
        {
            return null;
        }

        if( empty($childrens) )
        {
            return null;
        }

        if( !empty($childrens) )
        {
            foreach($childrens as $key => $childtheme):
                $theme = $parentTheme->childrens()->create([
                    'name' => $childtheme['name'],
                    'user_id' => $childtheme['user_id'],
                    'is_active' => $childtheme['is_active'],
                    'slug' => !empty($childtheme['slug']) ? $childtheme['slug'] : null,
                ]);

                if( !empty($childtheme['childrens']) )
                {
                    $this->childrensThemes($theme, $childtheme['childrens']);
                }

            endforeach;
        }

        return null;

    }

    public function childrensFormats($parentFormat, $childrens)
    {
        if( empty($parentFormat) )
        {
            return null;
        }

        if( empty($childrens) )
        {
            return null;
        }

        if( !empty($childrens) )
        {
            foreach($childrens as $key => $childformat):
                $format = $parentFormat->childrens()->create([
                    'name' => $childformat['name'],
                    'user_id' => $childformat['user_id'],
                    'is_active' => $childformat['is_active'],
                    'slug' => !empty($childformat['slug']) ? $childformat['slug'] : null
                ]);

                if( !empty($childformat['childrens']) )
                {
                    $this->childrensFormats($format, $childformat['childrens']);
                }

            endforeach;
        }

        return null;

    }
}
