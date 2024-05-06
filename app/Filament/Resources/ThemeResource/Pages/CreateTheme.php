<?php

namespace App\Filament\Resources\ThemeResource\Pages;

use App\Filament\Resources\ThemeResource;
use App\Models\Language;
use App\Models\Theme;
use App\Models\ThemeDescription;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTheme extends CreateRecord
{
    protected static string $resource = ThemeResource::class;

    public function create(bool $another = false): void
    {
        $data = $this->form->getState();
        $languages = $data['languages'];
        unset($data['languages']);
        $theme = Theme::query()->create($data);

        foreach ($languages as $langId => $language) {
            ThemeDescription::query()->create([
                'theme_id' => $theme->id,
                'language_id' => $langId,
                'name' => $language['name'],
            ]);
        }

        $this->redirect('/admin/themes');
    }
}
